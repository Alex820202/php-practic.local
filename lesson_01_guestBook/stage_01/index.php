<?php
require_once(__DIR__ . '/config.php');
try {
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	
	$query_insert = 'INSERT INTO posts(author, datetime, text) VALUES (:author_name, :datetime, :text_post)';
	$sth_insert = $dbh->prepare($query_insert);
	
	$query_select = 'SELECT * FROM posts ';
	$sth_select = $dbh->prepare($query_select);
	//if (headers_sent()){$flag = TRUE;}

	if (!empty($_POST['author']) && !empty($_POST['text'])){
		$data['author_name'] = trim($_POST['author']);
		$data['datetime'] = time();
		$data['text_post'] = htmlspecialchars(trim($_POST['text']));
		$sth_insert->execute($data);
		header( 'Location: index.php', true, 303 );
		//if (headers_sent()){$flag = TRUE;}
	}else{
		$sth_select -> execute();
		//$sth_select->setFetchMode(PDO::FETCH_ASSOC);
		$results = $sth_select->fetchAll(PDO::FETCH_ASSOC);
		//$flag = FALSE;
	}

// var_dump($_SERVER['HTTP_REFERER']);



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
			
			<?php


			foreach ($results as $result){
			?>
			<div class="note">
				<p>
					<span class="date"><?php echo date('H:i:s d.m.Y', $result['datetime']);
						?></span>
					<span class="name"><?php echo $result['author']; ?></span>

				</p>
				<p><?php echo $result['text']; ?></p>

			</div>
			<?php }
			
			$flag = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			
			if($_SERVER['HTTP_REFERER']== $flag){?> <div class="info alert alert-info">
				Запись успешно сохранена!
			</div>


			<?php }?>

			<div id="form">
				<form action="index.php" method="POST">
					<p><input class="form-control" placeholder="Ваше имя" name="author"></p>

					<p><textarea class="form-control" placeholder="Ваш отзыв" name="text"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>

<?php }
catch(PDOException $e) {
echo $e->getMessage();
}

