<?php
require_once(__DIR__.'/config.php');
try{
	if(!empty($_POST)){
	
	$dbh_insert = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh_insert -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh_insert -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$query_insert = 'INSERT INTO  posts(title, anons, text, datetime) VALUES (:title, :anons, :text, :datetime)';
	$sth_insert = $dbh_insert -> prepare($query_insert);
	$data['title'] = strip_tags(trim($_POST['title']));
	/*
	*Форматируем с добавлением html-тегов абзаца текст, введенный в поле "Текст записи". 
	*/
	$text_note =  '<p>'.str_replace(array(0 =>"\r\n",1 =>"\n\n"),'</p><p>', trim($_POST['text'])).'</p>';
	$data['anons'] = mb_split('</p>', $text_note)[0].'</p>';
	$data['text'] = htmlspecialchars($text_note);
	$data['datetime'] = time();
	$sth_insert -> execute($data);
	header('Location: index.php', TRUE, 303);
	}
	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблема! <br />';
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
			<p class="nav">
				<a href="index.php">на главную</a>
			</p>
			<!-- 
			
				После сохранения перебрасывает 
				на список записей
				с помощью header location
			
			-->
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


			