<?php
//var_dump($_GET);
session_start();
//var_dump($_SESSION);
require_once('../function.php');
if(autorizationStatus()==3 || $_GET['auth']==2){
	session_unset();
	}
try{
	$dbh = db_connect();
	autorization($dbh, $_POST);

?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Страница администратора</title>
		<link rel="stylesheet" href="../bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
		<?php 
			if (autorizationStatus()==0){
			
		?>
			<h1>Авторизация</h1>
		<?php 
			if($_GET['auth']==1){
				echo "<div class='info alert alert-danger'>Не правильная пара логин/пароль!</div>";
			} ?>
			<div id="form">
				<form action="index.php?auth=1" method="POST">
					<p><input class="form-control" placeholder="Ваше логин" name="login"></p>
					<p><input type="password" class="form-control" placeholder="Ваш пароль" name="password"></input></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Войти"></p>
					</form>
			</div>
		
		<?php }elseif(autorizationStatus()==1 ){ 
			if($_GET['delete']){
				$flag = deletePage($dbh);
				switch($flag){
					case 1:
						break;
					
					case 0:
						echo "<div class='info alert alert-danger'>Не правильный <b>URL</b> страницы!</div>";
						break;
				}
			}
		?>
			<h1>Админка</h1>
			<div class='nav-right'><a href='index.php?auth=2'>Выход</a></div>
			<?php if($_GET['flag'] == 1){ 	
			echo "<div class='info alert alert-success'>Запись успешно удалена!</div>";
			 } ?>
			<table class="table table-bordered">
				
				<?php 
				
				 tbody_table_admin($dbh);
				 
				?>
			</table>
			<div>
				<a href="new.php" class="btn btn-danger btn-block">Новая страница</a>
			</div>
		</div>

	</body>
</html>

<?php
	}elseif(autorizationStatus()==2){
		echo "<div class='info alert alert-danger'>Извините, <u>".$_SESSION['author']."</u>, у Вас не достаточно прав для просмотра страницы!</div>";
		echo "<div class='nav'><a href='../index.php'>На главную</a></div>";
		echo "<div class='nav-left'><a href='index.php?auth=2'>Выход</a></div>";
	}
	
} catch (PDOException $e) {
	echo 'Нет связи с базой данных: '. $e -> getMessage();
}

?>
		


			