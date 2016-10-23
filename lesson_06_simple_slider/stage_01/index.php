<?php
/*
 * Создание соединения с базой данных. На входе переменная, содержащая путь к файлу с настройками подключения, на выходе созданное подключение к базе данных
 */
function db_connect($config){
	require_once ($config);
	$dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $dbh;
}
/*
 * Функция выборки пунктов меню, на входе соединение с БД, на выходе массив (name, title) - (имя страницы, тайтл)
 */
function select_menu($dbh){
	$sql_menu = 'SELECT name, title FROM pages';
	$sth_menu = $dbh -> prepare($sql_menu);
	$sth_menu -> execute();
	$results_menu = $sth_menu -> fetchAll();
	return $results_menu;
}
/*
 * Функция получения содержимого страницы. На входе соединение с БД, имя страницы,  на выходе массив (id, name, title, h1, text).
 */
function select_page($dbh, $name){
	$sql_select = 'SELECT * FROM pages WHERE name=:name';
	$sth = $dbh -> prepare($sql_select);
	$sth -> execute(array('name'=>$name));
	$result = $sth -> fetch();
	return $result;
}
/*
 * Функция вывода меню. На входе массив (имена страниц, тайтлы) на выходе сформированные html-пункты меню.
 */
function gitMenu($results_menu, $name)
{
	foreach ($results_menu as $result_menu) {
		if ($result_menu['name'] == $name) {
			$class = "class='active'";
		} else {
			$class = '';
		}
		echo "<li " . $class . "><a href='?page=" . $result_menu['name'] . "'>" . $result_menu['title'] . "</a></li>";
	}
}
/*
 * Функция определения выводимой страницы. На входе имя, пришедшее get-параметром и массив имен страниц и тайтлов,
 * на выходе имя страницы для вывода.
 */
function namePage($name, $selectMenu){
	foreach($selectMenu as $result){
		$pages[] = $result['name'];
	}
	if(in_array($name, $pages)){
	    $page = $name;
	}else{
	    $page = 'index';}
	return $page;
}
/*
 * Функция поиска. На входе соединение с БД, переданное значение get-параметром 'search'.
 * На выходе - содержимое тега h1 и текст страницы.
 */
function search_text($dbh, $search){
    $options = array(0=>title, h1, text);
    for($i=0;$i<count($options);$i++){
        $option = $options[$i];
        $sql_search = "SELECT * FROM pages WHERE ".$option." LIKE '%".$search."%' LIMIT 1";
        $sth_search = $dbh -> prepare($sql_search);
        $sth_search -> execute();
        $result = $sth_search -> fetch();
        if($result) {
            break;
        }
    }

    $found ['h1'] = 'По запросу  <u>'.$search.'</u> найдено:';
    if($result){
        $found ['text'] = "1. <a href='?page=".$result['name']."'>".$result['title']."</a>";
    }else{
        $found ['text'] = '0 страниц';
    }
    return $found;
}
/*
 * Функция определения содержимого страницы, в зависимости от того какой get-запрос пришел,
 * выдает либо данные функции search_text, либо текст страницы из БД.
 * На входе массив(соединение с БД, имя страницы, текст запроса), на выходе массив (тег h1, текст страницы)
 */
function contentPage($dbh, $name, $search){
    if(!empty($search)){
        $result = search_text($dbh, $search);
    }else{
        $result = select_page($dbh, $name);
    }
    return $result;
}
$config = __DIR__.'/config.php';

$option = (string)strip_tags(trim($_GET['page']));
$search = strip_tags(trim($_GET['search']));



try{
	$dbh = db_connect($config);

	$results_menu = select_menu($dbh);

	$name = namePage($option, $results_menu);

	$result = contentPage($dbh, $name, $search);
?>
	<!DOCTYPE html>
	<html lang="ru">
	<head>
		<meta charset="utf-8">
		<title><?php echo $result['title'];	?></title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
	<div id="menu">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php gitMenu($results_menu, $name); ?>
						</ul>


					<div class="nav navbar-nav navbar-right">
						<form class="navbar-form navbar-left" action="index.php" method="GET" role="search">
							<div class="form-group">
								<input type="text" class="form-control" name="search" placeholder="Поиск">
							</div>
							<button type="submit" class="btn btn-default">Найти!</button>
						</form>
					</div>

				</div>
			</div>
		</nav>
	</div>
	<div id="wrapper">

		<h1><?php echo $result['h1']; ?></h1>
		<?php echo $result['text']; ?>
	</div>

	</body>
	</html>


	<?php
}catch (PDOException $e){
	echo 'Нет связи с базой данных: ' . $e->getMessage();
}


?>



			