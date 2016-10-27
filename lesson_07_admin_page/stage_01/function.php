<?php
/*
* Инициализируем подключение к базе данных.
*/
function db_connect(){
	require_once(__DIR__.'/config.php');
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $dbh;
}
/*
* Формируем содержимое запрошенной страницы
*/
function contentPage($dbh, $get_page){
	if(!empty($get_page)){
		$url = strip_tags(trim($get_page));
	}else{
		$url = 'index';
	}
	$sql_content = 'SELECT * FROM pages WHERE url=:url';
	$sth = $dbh -> prepare($sql_content);
	$data['url'] = $url;
	$sth -> execute($data);
	$content = $sth -> fetch();
	if(!$content){ // если запрошенной страницы нет в БД, то выводим главную
		$url = 'index';
		$data['url'] = $url;
		$sth -> execute($data);
		$content = $sth -> fetch();
		}
		return $content;
}
/*
* Функция добавления новой страницы в базу.
* возвращает 1 - добавление прошло успешно,
* возвращает 0 - в базе есть страница с таким url/title, страница не добавлена.
*/
function newPage(){
	$dbh = db_connect();
	
	$url = strip_tags(trim($_POST['url']));
	$title = strip_tags(trim($_POST['title']));
	$h1 = strip_tags(trim($_POST['h1']));
	$text = strip_tags(trim($_POST['text']));
	$author = $_SESSION['author'];
	$sql_select = 'SELECT * FROM pages WHERE url=:url OR title=:title'; // проверяем нет ли в базе страниц с таким же урлом и тайтлом.
	$sth = $dbh -> prepare($sql_select);
	$data_select['url'] = $url;
	$data_select['title'] = $title;
	$sth -> execute($data_select);
	$result = $sth -> fetch();
	if(!$result){
		$sql_insert = 'INSERT INTO pages SET url=:url, title=:title, h1=:h1, text=:text, author=:author';
		$sth_insert = $dbh -> prepare($sql_insert);
		
		$data_insert['url'] = $url;
		$data_insert['title'] = $title;
		$data_insert['h1'] = $h1;
		$data_insert['text'] = $text;
		$data_insert['author'] = $author;
		
		$sth_insert -> execute($data_insert);
		
		return 1;
		 
	}else{
		return 0;
	}
	
	
}
/*
* Функция перезаписи содержимого страницы, 
* в случае успеха возвращает массив (1, новый урл),
* в случае неудачи возвращает массив (2, старый урл).
*/
function saveContentPage($dbh){
		$sql_save = 'UPDATE pages SET url=:url, title=:title, h1=:h1, text=:text, author=:author WHERE url=:url_start';
		$sth_save = $dbh -> prepare($sql_save);
		$data['url'] = strip_tags(trim($_POST['url']));
		$data['title'] = strip_tags(trim($_POST['title']));
		$data['h1'] = strip_tags(trim($_POST['h1']));
		$data['text'] = strip_tags(trim($_POST['text']));
		$data['author'] = $_SESSION['author'];
		$data['url_start'] = strip_tags(trim($_POST['hidden']));
		$result = $sth_save -> execute($data);
		if($result){
			return array(0=>1, $data['url']);
		}else{
			return array(0=>2, $data['url_start']);
		}
		
}
/*
* Выводим на экран меню.
*/
function gitMenu($dbh, $get_page){
	$get_page = strip_tags(trim($get_page));
	
	$sql_menu = 'SELECT url, title FROM pages';
	$sth_menu = $dbh -> prepare($sql_menu);
	$sth_menu -> execute();
	$menu = $sth_menu -> fetchAll();
	
	for($i=0;$i<count($menu);$i++){
		$page_menu[$menu[$i]['url']] = $menu[$i]['title']; //для удобства работы формируем массив url=>title
		}
	/*
	* Если запрошенная через get-параметр страница есть, то выводится она, если нет, то главная. 
	*/	
	if(!empty($get_page) && array_key_exists($get_page, $page_menu)){
			$url = $get_page;
		}else{
			$url = 'index';
		}
	/*
	* Выволим на экран пункты меню
	*/	
	foreach($page_menu as $key => $value){
		if($key == $url){
							$class = "class='active'";
						}else{
							$class = '';
						}
						echo "<li ".$class."><a href='?page=".$key."'>".$value."</a></li>";
	}
						 
}
/*
* Функция поиска. На входе подключение к БД, искомое выражение. На выходе массив содержимого страницы h1 и text.
*/
function search_text($dbh, $search){
	$tags = array(0=>'title', 'h1', 'text');
		for($i=0;$i<count($tags);$i++){
			$url = $tags[$i];
			$search = strip_tags(trim($search));
			$sql_search = "SELECT * FROM pages WHERE ".$url." LIKE '%".$search."%' LIMIT 1";
			$sth_search = $dbh -> prepare($sql_search);
			$sth_search -> execute();
			$result = $sth_search -> fetch();
			if($result){
				break;
			}
		}
		$content['h1'] = 'По вашему запросу <b>'.$search.'</b> найдено:';
		if($result){
			$content['text'] = "1. <a href='?page=".$result['url']."'>".$result['title']."</a>";
		}else{
			$content['text'] = '0 страниц.';
			}
		return $content;
}
/*
* Функция авторизации для входа в административную часть, если пользователь не был авторизован, до этого
*/
function autorization($dbh, $post_array){
	if(!empty($_POST)){
		$login = strip_tags(trim($_POST['login']));
		$password = strip_tags(trim($_POST['password']));
		$password_md5 = md5($password);
		$sql_authorization = 'SELECT * FROM users WHERE login=:login AND password=:password';
		$sth_authorization = $dbh -> prepare($sql_authorization);
		$data['login'] = $login;
		$data['password'] = $password_md5;
		$sth_authorization -> execute($data);
		$result = $sth_authorization ->fetch();
		if($result){
			$_SESSION['true'] = 1;
			$_SESSION['id'] = $result['id'];
			$_SESSION['author'] = $result['author'];
			$_SESSION['status'] = $result['status'];
			$_SESSION['lastactivity'] = time();
			
			
		}else{
			header("Location: index.php?auth=1", TRUE, 303);
		}
	}
}
/*
* Функция проверки авторизации пользователя, 
* возвращает '0', если пользователь не авторизован,
* возвращает '1', если пользователь авторизован и является администратором,
* возвращает '2', если пользователь авторизован, но не является администратором,
* возвращает '3', если пользователь авторизован, но не проявлял активность $timeOutSession секунд.
*/
function autorizationStatus(){
	$t = time();
	$timeOutSession = 300;
	if(empty($_SESSION)){
		return 0;
	}elseif($_SESSION['status']==1 && $t-$_SESSION['lastactivity']<$timeOutSession){
		$_SESSION['lastactivity'] = $t;
		return 1;
	}elseif($_SESSION['status']!=1 && $t-$_SESSION['lastactivity']<$timeOutSession){
		return 2;
	}else{
		return 3;
	}
	
}
/*
* Формирует тело таблицы администрирования содержимого сайта.
*/
function tbody_table_admin($dbh){
	echo "<tr><th>Страница</th><th>Редактирование</th><th>Удаление</th></tr>";
	$sql_administration = 'SELECT * FROM pages';
				 $sth_administration = $dbh -> prepare($sql_administration);
				 $sth_administration -> execute();
				 $results_administration =  $sth_administration -> fetchAll();
				 foreach ($results_administration as $result_administration){
				 	echo '<tr>';
				 	echo "<td><a href='../index.php?page=".$result_administration['url']."'>".$result_administration['title']."</a></td>";
				 	echo "<td><a href='edit.php?page=".$result_administration['url']."'>редактировать</a></td>";
				 	echo "<td><a href='index.php?delete=".$result_administration['url']."'>удалить</a></td>";
				 	echo '</tr>';
				 }
}
/*
* Функция удаления страницы.
* Возвращает 0, если страницы нет в базе (пытались подделать GET['delete']). (По сути не нужно, но для тренировки вставил :)  ).
* Возвращает 1, если удаление прошло успешно.
*/
function deletePage($dbh){
	$url = strip_tags(trim($_GET['delete']));
	$data['url'] = $url;
	
	$dbh -> beginTransaction();
	$sql_inspection = 'SELECT * FROM pages WHERE url=:url';
	$sth_inspection = $dbh -> prepare($sql_inspection);
	$sth_inspection -> execute($data);
	$result = $sth_inspection -> fetchAll();
	if ($result){
		
		$sql_delete = 'DELETE FROM pages WHERE url=:url';
		$sth_delete = $dbh -> prepare($sql_delete);
		$sth_delete -> execute($data);
		
		$dbh -> commit();
		return 1;
	}else{
		$dbh -> rollBack();
		return 0;
	}
}
?>