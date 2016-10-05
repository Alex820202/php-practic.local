<?php

require_once(__DIR__.'/config.php');
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$query_select = 'SELECT id, title, anons, datetime FROM posts ORDER BY id DESC';
	$sth_select = $dbh->prepare($query_select);
	$sth_select->execute();
	$results = $sth_select->fetchAll();
	
	?>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Список записей</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Список записей</h1>
			<?php
			foreach($results as $result){
				?>
				<div class="note">
				<p>
					<span class="date"><?php echo date('H:i:s d.m.Y', $result['datetime']);?></span>
					<a href="note.php?id=<?php echo $result['id']; ?>"><?php echo $result['title']; ?></a>
				</p>
				<p>
				<?php echo $result['anons'];?> 
				</p>
			</div>	
			<?php }?>
			<a href="add.php" class="btn btn-danger btn-block">Добавить запись</a>
			</div>
		</div>

	</body>
</html>
			
				
	
<?php	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблемы!!!<br>';
	echo $e->getMessage();
}



?>



			