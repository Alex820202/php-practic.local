<?php
require_once(__DIR__.'/config.php');
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if(!empty($_GET['edit']) && empty($_GET['flag']) && empty($_POST)){
	$sql_select = 'SELECT title, text, datetime FROM posts WHERE id='.(int)$_GET['edit'];
	$stm_select = $dbh -> prepare($sql_select);
	$stm_select -> execute();
	$result = $stm_select -> fetch();
	?>
	<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Редактировать запись</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Редактировать запись</h1>
			<p class="nav">
				<a href="index.php">на главную</a>
			</p>
			<div>
				<form action="edit.php" method="POST">
					<p><input class="form-control" name="datetime" value="<?php echo $result['datetime']?>"></p>
					<p><input class="form-control" value="<?php echo $result['title']?>"></p>
					<p>
					<textarea class="form-control">
					<?php echo $result['text'];?>
					</textarea>
					</p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>

	
	
}
} catch (PDOException $e) {
	echo $e -> getMessage();
}

?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Редактировать запись</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Редактировать запись</h1>
			<p class="nav">
				<a href="index.php">на главную</a>
			</p>
			<!-- 
			
				После сохранения выдает сообщение
				об успехе.
			
			-->
			<!--
			<div class="info alert alert-success">
				Запись успешно сохранена!
			</div>
			-->
			<!--
			<div class="info alert alert-danger">
				Ошибка сохранения записи!
			</div>
			-->
			<div>
				<form action="" method="POST">
					<p><input class="form-control" value="15.04.2014"></p>
					<p><input class="form-control" value="Моя заметка номер 5"></p>
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


			