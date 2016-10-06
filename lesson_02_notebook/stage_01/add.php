<?php
require_once(__DIR__.'/config.php');
$replacement = array('0'=>"\r\n", '1'=>"\n\n");
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$query_insert = 'INSERT INTO posts(title, anons, text, datetime) VALUES (:title, :anons, :text, :datetime)';
	if(!empty($_POST['title'])&& !empty($_POST['text'])){
		/**
		* Форматируем текст для записи в базу данных
		*/
		$text_article = '<p>'.str_replace($replacement, '</p><p>',trim($_POST['text'])).'</p>';
		/*
		* Формируем заголовок, анонс и тело статьи.
		*/
		$date['title'] = strip_tags(trim($_POST['title']));
		$date['anons'] = htmlspecialchars(mb_split('</p>', $text_article)[0].'</p>');
		$date['text'] = htmlspecialchars($text_article);
		$date['datetime'] = time();
		/*
		* Сформированную статью записываем в базу данных
		*/
		$sth_insert = $dbh->prepare($query_insert);
		$sth_insert->execute($date);
		header('Location: index.php', TRUE, 303);
		
		
	}
	
	
	
	
	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблема!<br>';
	echo $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Новая запись</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Новая запись</h1>

			<div>
				<form action="add.php" method="POST">
					<p><input class="form-control" name="title" placeholder="Название записи"></p>
					
					<p><textarea class="form-control" name="text" placeholder="Текст записи"></textarea></p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>


			