<?php
require_once(__DIR__.'/function.php');
try{
	$dbh = db_connect();
	if(!empty($_GET['search'])){
		
		$content = search_text($dbh);
		
	}else{
		$content = contentPage($dbh);
	}
	

?>



<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title><?php echo $content['title']; ?></title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="menu">
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav">
					
					<?php
					
					gitMenu($dbh);
					
					?>
				  </ul>
				  
				  
				  <div class="nav navbar-nav navbar-right">
					<form class="navbar-form navbar-left" action="index.php" method="get" role="search">
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
			
			<h1><?php echo $content['h1'] ?></h1>
				<?php echo '<p>'.str_replace(array(0=>"\r", "\n"), '</p>', $content['text']); ?>
		</div>

	</body>
</html>

<?php
} catch (PDOException $e) {
	echo 'Нет связи с базой данных: '. $e -> getMessage();
}

?>
			