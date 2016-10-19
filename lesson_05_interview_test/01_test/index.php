<?php  
require_once(__DIR__.'/config.php');
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$sql_select = "SELECT * FROM question WHERE themes=:themes ORDER BY RAND() LIMIT :count"; //делаем выборку count количества вопросов из базы данных по заданной теме themes
	$stm_select = $dbh -> prepare($sql_select);
	$themes = 'Знание html тегов'; // тема вопросов
	$count = 3; // количество вопросов
	$stm_select -> bindValue(themes, $themes, PDO::PARAM_STR);
	$stm_select -> bindValue(count, $count, PDO::PARAM_INT);
	$stm_select -> execute();
	$results = $stm_select -> fetchAll();
	?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Тесты</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Тест "<?php echo $themes; ?>"</h1>
			<div class="info alert alert-info">
				Теги следует записывать без уголков.
			</div>
			<form action="check.php" method="POST">
			<?php foreach($results as $result){
				echo "<div class='note'><p><b>1.</b>".$result['question']."</p><p><input class='form-control' name='".$result['id']."' placeholder=''></p></div>";
			} 
			?>
			<p><input type="submit" class="btn btn-success btn-block" value="Проверить ответы"></p>
					
			</form>
		</div>

	</body>
</html>
				
	
	<?php
} catch (PDOException $e) {
	echo 'Поблема с подключение к базе данных: '. $e->getMessage();
}



?>
