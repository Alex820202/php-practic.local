<?php
require_once(__DIR__.'/config.php');

try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$query_select = 'SELECT title, text, datetime FROM posts WHERE id=:id';
	$sth_select = $dbh->prepare($query_select);
	$sth_select -> execute(array('id' => $_GET['id']));
	$result = $sth_select -> fetch();
	
	?>
	
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title><?php echo $result['title']; ?></title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1><?php echo htmlspecialchars_decode($result['title']); ?></h1>
			<div>
				<p class="date"><?php echo date('H:i:s d.m.Y', $result['datetime']); ?></p>
				<?php echo htmlspecialchars_decode($result['text']); ?>
				</div>
			<div>
				<a href="add.php" class="btn btn-danger btn-block">Добавить запись</a>
			</div>
		</div>

	</body>
</html>
	
<?php	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблема!<br />';
	echo $e->getMessage();
}
?>




			