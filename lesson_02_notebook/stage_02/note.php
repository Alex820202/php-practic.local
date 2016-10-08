<?php
require_once(__DIR__.'/config.php');

try{
	$dbh_select = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh_select->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh_select->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$sql_select = 'SELECT title, text, datetime FROM posts WHERE id=:id';
	$sth_select = $dbh_select ->prepare($sql_select);
	$id = (int)$_GET['id'];
	$sth_select -> execute(array('id' => $id));
	$result = $sth_select ->fetch();
	?>
	
	<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title><?php echo $result['title'];?></title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1><?php echo $result['title'];?></h1>
			<div>
				<p class="nav right">
					<a href="index.php">на главную</a>
				</p>
				
				<p class="date"><?php echo $date['datetime'];?></p>
				<?php echo $result['text'];?>
			</div>
			<div>
				<a href="add.php" class="btn btn-danger btn-block">Добавить запись</a>
			</div>
		</div>

	</body>
</html>
<?php
} catch (PDOException $e) {
	echo $e->getMessage();
}
?>




			