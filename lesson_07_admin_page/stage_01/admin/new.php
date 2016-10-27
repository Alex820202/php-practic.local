<?php

session_start();
try{
	require_once('../function.php');
	$status = autorizationStatus();
	switch($status){
		case 2:
			echo "<div class='info alert alert-danger'>Извините, <u>".$_SESSION['author']."</u>, у Вас не достаточно прав для просмотра страницы!</div>";
			echo "<div class='nav'><a href='../index.php'>На главную</a></div>";
			echo "<div class='nav-left'><a href='index.php?auth=2'>Выход</a></div>";
			break;
			
		case 1:
					
			break;
			
		default:
			session_unset();
			header("Location: index.php", TRUE, 303);
			break;
	}
	if(!empty($_POST)){
		$flag = newPage();
		switch($flag){
			case 1:
				header("Location: index.php", TRUE, 303);
				break;
			
			case 0:
				header("Location: new.php?flag=1", TRUE, 303);
				break;
		}
		
	}else{
		
?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Новая страница</title>
		<link rel="stylesheet" href="../bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/admin.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Новая страница</h1>
			<p class="nav">
				<a href="index.php">на главную админки</a>
			</p>
			<?php 
			if($_GET['flag']==1){
				echo "<div class='info alert alert-danger'>В базе существует страница с такими <b>URL/TITLE!</b></div>";
			} ?>
			<!-- 
			
				После сохранения перебрасывает 
				на список записей
				с помощью header location
			
			-->
			<div>
				<form action="" method="POST">
					<p><input class="form-control" placeholder="Url страницы" name="url" required></p>
					<p><input class="form-control" placeholder="тайтл страницы" name="title" required></p>
					<p><input class="form-control" placeholder="Название страницы" name="h1" required></p>
					<p><textarea class="form-control" placeholder="Текст страницы" name="text" required></textarea></p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>
<?php }	
} catch (PDOException $e) {
	echo 'Нет связи с базой данных: '.$e->getMessage();
}
?>

			