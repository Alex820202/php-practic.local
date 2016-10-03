<?php
require_once(__DIR__.'/config.php');

$post_per_page = 5; // количество записей на странице
$number_visible_pages = 5;

try{
	$dsn = "mysql:host=$host;dbname=$dbname";
	$dbh = new PDO($dsn, $user, $password);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	$query_total= 'SELECT COUNT(*) FROM posts';
	$query_select = 'SELECT * FROM posts LIMIT start = :start, $number = :number';
	$query_insert = 'INSERT INTO posts(author, datetime, text) VALUES (:author_name, :datetime, :text_post)';
	
	$sth_total = $dbh->prepare($query_total);
	$sth_select = $dbh->prepare($query_select);
	$sth_insert = $dbh->prepare($query_insert);
	
	$sth_total->execute();
	$result_total = $sth_total->fetch();
	$total = $result_total['COUNT(*)']; // общее количество постов в гостевой книге
	$total_page = ceil($total/$post_per_page);//максимально возможное количество страниц
	/*
	* Определяем текущую страницу вывода сообщений.
	*/
	if(!$_GET['page'] && !$_POST){
		$current_page = 1;
	}else{
		$current_page = $_GET['page'];
	}
	/*
	* Определяем массив выводимых в пагинации страниц
	*/
	if($current_page-2<0 && $current_page+2>=$total_page){
		$start_page = 1;
		$end_page = $total_page;
	}elseif($current_page-2>=0 && $current_page+2>=$total_page){
		$start_page = $current_page-2;
		$end_page = $total_page;
	}elseif($current_page-2>=0 && $current_page+2<$total_page){
		$start_page = $current_page-2;
		$end_page = $current_page+2;
	}else{
		$start_page = 1;
		$end_page = $current_page+2;
	}
	
	for($i=$start_page; $i<$end_page+1; $i++){
		$page_pagination[] = $i;
	}
		/*
	* Определяем начальный пост и число постов на странице. 
	*/
	if(!$_GET['page'] || $_GET['page'] == 1){//первая страница
		$start = 1; // с какого поста начинаем вывод
		$number = $total; // сколько постов показываем
	}elseif($total_page == $_GET['page']){//последняя страница
		$start = $post_per_page*$_GET['page']-$post_per_page;
		$number = $total - $start;
	}else{//любая другая страница
		$start = $_GET['page']*$post_per_page - $post_per_page;
		$number = $post_per_page;}
	/*
	* 
	*/
	
}catch(PDOException $e){
	echo 'Хьюстон - у нас проблема!!!'.'<br>';
	echo $e->getMessage();
}
?>






<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Гостевая книга</h1>
			
			<div>
				<nav>
				  <ul class="pagination">
					<li class="disabled">
					  <a href="?page=1"  aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					  </a>
					</li>
					<li class="active"><a href="?page=1">1</a></li>
					<li><a href="?page=2">2</a></li>
					<li><a href="?page=3">3</a></li>
					<li><a href="?page=4">4</a></li>
					<li><a href="?page=5">5</a></li>
					<li>
					  <a href="?page=5" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					  </a>
					</li>
				  </ul>
				</nav>
				
			</div>
			
			
			<div class="note">
				<p>
					<span class="date">18.04.2014 23:59:59</span>
					<span class="name">Дмитрий</span>
					
				</p>
				<p>
					Lorem ipsum dolor sit amet, 
					consectetur adipiscing elit. 
					Nulla efficitur elementum lorem id venenatis. 
					Nullam id sagittis urna, eu ultrices risus. 
					Duis ante lorem, semper nec fringilla eu,
					commodo vel mauris. Nunc tristique odio lectus, eget condimentum nunc consectetur eu. Nullam non varius nisl, aliquet fringilla lectus. Aliquam erat volutpat. Ut vel mi et lectus hendrerit ornare vel ut neque. Quisque venenatis nisl eu mi
				</p>
				
			</div>	
			<div class="note">
				<p>
					<span class="date">16.04.2014 14:59:59</span>
					<span class="name">Николай</span>
					
				</p>
				<p>
					Ut varius commodo fringilla. Nullam id pulvinar odio. Pellentesque gravida aliquam ipsum, et malesuada neque molestie eget. Vestibulum sagittis finibus efficitur. Donec sit amet aliquet dolor, vitae ornare tortor. Etiam eget augue nec diam vehicula bibendum. Nulla quis erat lacus. Vestibulum quis mattis augue. Praesent dignissim, justo non aliquam feugiat, lorem metus egestas leo, quis eleifend odio quam in ex. Aenean diam est, scelerisque ac ultricies sit amet, vulputate in tortor. Etiam ac mi enim. Sed pellentesque elementum erat eu eleifend. Integer imperdiet sem eu magna feugiat, sed efficitur velit convallis. 
				</p>
				
			</div>	
			<div class="note">
				<p>
					<span class="date">15.04.2014 12:59:59</span>
					<span class="name">Петр</span>
					
				</p>
				<p>
					Phasellus gravida fermentum pellentesque. Aenean non neque mollis nisl dapibus eleifend. Sed interdum dui nec dictum elementum. Proin eget semper dolor, ut commodo nibh. 
					Quisque vitae pharetra ligula. Sed dictum, sem sed pellentesque aliquam, tellus sapien dapibus magna, eu suscipit lacus augue sed velit. Ut vehicula sagittis nulla, et aliquet elit. Quisque tincidunt sem nibh, finibus dictum nisl vulputate quis. In vitae nisl et lacus pulvinar ornare id ac libero. Morbi pharetra fringilla erat ut lacinia. 
					
				</p>
				
			</div>	
			
			<div>
				<nav>
				  <ul class="pagination">
					<li class="disabled">
					  <a href="?page=1"  aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					  </a>
					</li>
					<li class="active"><a href="?page=1">1</a></li>
					<li><a href="?page=2">2</a></li>
					<li><a href="?page=3">3</a></li>
					<li><a href="?page=4">4</a></li>
					<li><a href="?page=5">5</a></li>
					<li>
					  <a href="?page=5" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					  </a>
					</li>
				  </ul>
				</nav>
				
			</div>
			
			
			<div class="info alert alert-info">
				Запись успешно сохранена!
			</div>
			
			<!--
			
				Обеспечьте удаление концевых пробелов
				и тегов при сохранении.
				
				Обеспечьте редирект с помощью
				header location чтобы не было 
				двойного сохранения при обновлении страницы.
			
			-->
			
			<div id="form">
				<form action="#form" method="POST">
					<p><input class="form-control" placeholder="Ваше имя"></p>
					
					<p><textarea class="form-control" placeholder="Ваш отзыв"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>


			