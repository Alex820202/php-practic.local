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
		var_dump($result);
		$content['h1'] = 'По вашему запросу <b>'.$search.'</b> найдено:';
		if($result){
			$content['text'] = "1. <a href='?page=".$result['url']."'>".$result['title']."</a>";
		}else{
			$content['text'] = '0 страниц.';
			}
		return $content;
}
?>