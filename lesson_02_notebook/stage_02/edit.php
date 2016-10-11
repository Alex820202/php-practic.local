
<?php
require_once(__DIR__.'/config.php');
$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
try{
if(!empty($_GET['edit']) && empty($_POST)){
	$sql_select = 'SELECT title, text, datetime FROM posts WHERE id='.(int)$_GET['edit'];
	$stm_select = $dbh -> prepare($sql_select);
	$stm_select -> execute();
	$result = $stm_select -> fetch();
	/*
	* В случае, если запись не найдена в БД, то ставим флаг для выдачи предупреждения "Такой записи не существует!"
	*/
	if($result == FALSE){
		$flag = 1;
	}
}elseif(!empty($_POST) && !empty($_GET['edit'])){
	$sql_update = 'UPDATE posts SET title=:title, anons=:anons, text=:text, datetime=:datetime WHERE id=:id';
	$stm_update = $dbh -> prepare($sql_update);
	$result['title'] = $_POST['title'];
	$result['anons'] = mb_split('</p>', $_POST['text'])[0].'</p>';
	$result['text'] = $_POST['text'];
	/*
	*приводим дату к формату даты базы данных BIGINT
	*/
	$time_array = explode('.', $_POST['datetime']);
	$date_of_base = mktime(0, 0, 0, $time_array[1], $time_array[0], $time_array[2]);
	$result['datetime'] = $date_of_base;
	$result['id'] = (int)$_GET['edit'];
	/*
	* Определяем значение флага для выдачи предупреждения о записи/неудачи записи в БД
	*/
	$option = $stm_update -> execute($result);
	if($option == TRUE){
		$flag = 2; //запись в базу данных удалась
	}elseif($option == FALSE){
		$flag = 3; // запись в базу данных не удалась
	}
	
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
			<?php 
			if($flag == 1){
				echo "<div class='info alert alert-danger'> Такой записи не существует! </div>";
			}elseif($flag == 2){
				echo "<div class='info alert alert-success'>Запись успешно сохранена!</div>";
			}elseif($flag == 3){
				echo "<div class='info alert alert-danger'>Ошибка сохранения записи!</div>";
			}
			?>
			<div>
				<form action="edit.php?edit=<?php echo (int)$_GET['edit'];?>" method="POST">
					<p><input class="form-control" name="datetime" value="<?php echo date('d.m.Y', $result['datetime'])?>"></p>
					<p><input class="form-control" name="title" value="<?php echo $result['title']?>"></p>
					<p>
					<textarea name="text" class="form-control">
					<?php echo htmlspecialchars_decode($result['text']);?>
					</textarea>
					</p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>


	
<?php 

} catch (PDOException $e) {
	echo "Хьюстон у нас проблема! <br />";
	echo $e -> getMessage();
}
?>
