<?php
session_start();
require_once('../function.php');
try{
	if(autorizationStatus()==3 OR autorizationStatus()==0){
	session_unset();
	header('Location: index.php', TRUE, 303);
}elseif(autorizationStatus()==2){
		echo "<div class='info alert alert-danger'>Извините, <u>".$_SESSION['author']."</u>, у Вас не достаточно прав для просмотра страницы!</div>";
		echo "<div class='nav'><a href='../index.php'>На главную</a></div>";
		echo "<div class='nav-left'><a href='index.php?auth=2'>Выход</a></div>";
	}elseif(autorizationStatus()==1){ 
		$dbh = db_connect();
		$option = 'save';
		if(!empty($_POST) && $_GET['option']==$option){
			$parametr = saveContentPage($dbh);
			header("Location: edit.php?page=".$parametr[1]."&flag=".$parametr[0], TRUE, 303);
		}
		$content = contentPage($dbh, $_GET['page']);
		
	?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Редактировать страницу</title>
		<link rel="stylesheet" href="../bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/admin.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Редактировать страницу</h1>
			<p class="nav">
				<a href="index.php">на главную</a>
			</p>
			<?php
			if($_GET['flag']==1){
				echo "<div class='info alert alert-success'>Запись успешно сохранена!</div>";
			}elseif($_GET['flag']==2){
				echo "<div class='info alert alert-danger'>Ошибка сохранения записи!</div>";
			} 
			?>
			<div>
				<form action="edit.php?option=save" method="POST">
					<p><input class="hidden" value="<?php echo $content['url']; ?>" name="hidden" required></p>
					<p><input class="form-control" value="<?php echo $content['url']; ?>" name="url" placeholder="Url страницы" required></p>
					<p><input class="form-control" value="<?php echo $content['title']; ?>" name="title" placeholder="Тайтл страницы" required></p>
					<p><input class="form-control" value="<?php echo $content['h1']; ?>" name="h1" placeholder="Название страницы" required></p>
					<p>
						<textarea class="form-control" name="text">
						<?php echo $content['text']; ?>
						</textarea>
					</p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>
	
	<?php	
	}
} catch (PDOException $e) {
	echo 'Нет связи с базой данных : <br/>'. $e->getMessage();
}


?>

<!--<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Редактировать страницу</title>
		<link rel="stylesheet" href="../bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/admin.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Редактировать страницу</h1>
			<p class="nav">
				<a href="index.php">на главную</a>
			</p>-->
			<!-- 
			
				После сохранения выдает сообщение
				об успехе.
			
			-->
			<!--
			<div class="info alert alert-success">
				Запись успешно сохранена!
			</div>-->
			
			<!--
			<div class="info alert alert-danger">
				Ошибка сохранения записи!
			</div>
			-->
			<!--<div>
				<form action="" method="POST">
					<p><input class="form-control" value="index" placeholder="Url страницы"></p>
					<p><input class="form-control" value="Главная" placeholder="Название страницы"></p>
					<p>
					<textarea class="form-control">
<p>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla efficitur elementum lorem id venenatis. Nullam id sagittis urna, eu ultrices risus. Duis ante lorem, semper nec fringilla eu, commodo vel mauris. Nunc tristique odio lectus, eget condimentum nunc consectetur eu. Nullam non varius nisl, aliquet fringilla lectus. Aliquam erat volutpat. Ut vel mi et lectus hendrerit ornare vel ut neque. Quisque venenatis nisl eu mi
</p>
<p>
	Ut varius commodo fringilla. Nullam id pulvinar odio. Pellentesque gravida aliquam ipsum, et malesuada neque molestie eget. Vestibulum sagittis finibus efficitur. Donec sit amet aliquet dolor, vitae ornare tortor. Etiam eget augue nec diam vehicula bibendum. Nulla quis erat lacus. Vestibulum quis mattis augue. Praesent dignissim, justo non aliquam feugiat, lorem metus egestas leo, quis eleifend odio quam in ex. Aenean diam est, scelerisque ac ultricies sit amet, vulputate in tortor. Etiam ac mi enim. Sed pellentesque elementum erat eu eleifend. Integer imperdiet sem eu magna feugiat, sed efficitur velit convallis. 
</p>
<p>
	Phasellus gravida fermentum pellentesque. Aenean non neque mollis nisl dapibus eleifend. Sed interdum dui nec dictum elementum. Proin eget semper dolor, ut commodo nibh. Quisque vitae pharetra ligula. Sed dictum, sem sed pellentesque aliquam, tellus sapien dapibus magna, eu suscipit lacus augue sed velit. Ut vehicula sagittis nulla, et aliquet elit. Quisque tincidunt sem nibh, finibus dictum nisl vulputate quis. In vitae nisl et lacus pulvinar ornare id ac libero. Morbi pharetra fringilla erat ut lacinia. Curabitur eget augue at felis maximus condimentum id id sem. Ut dapibus, nisl rutrum scelerisque commodo, velit purus aliquam libero, faucibus iaculis odio augue sit amet mi. Donec interdum, ante volutpat mattis lacinia, nulla enim aliquet nulla, quis congue leo ante at justo. Mauris pellentesque risus a arcu bibendum, nec fringilla nisi tristique. Ut laoreet turpis non tincidunt fringilla. 
</p>
					</textarea>
					</p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>

-->
			