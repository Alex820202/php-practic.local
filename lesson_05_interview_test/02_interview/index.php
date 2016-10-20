<?php
require_once(__DIR__.'/config.php');
$id = 1; //номер вопроса из таблицы questions базы данных, в БД одна таблица, поэтому предполагаетя наличие до трех ответов на все вопросы.
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$sql = 'SELECT * FROM Questions WHERE id=:id LIMIT 1';
	$stm = $dbh -> prepare($sql);
	$stm -> execute(array('id'=>$id));
	$result = $stm -> fetch();
?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Опрос</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Опрос</h1>
			<div class="info alert alert-info">
				<?php echo $result['question']; ?>:
			</div>
			<form action="check.php?id=<?php echo $id; ?>" method="POST">
				<div class="note">
					<p><input type="radio" name="radio" value="1"  id="r1"> <label for="r1"><?php echo $result['answer_1']; ?></label></p>
					<p><input type="radio" name="radio" value="2"  id="r2"> <label for="r2"><?php echo $result['answer_2']; ?></label></p>
					<p><input type="radio" name="radio" value="3"  id="r3"> <label for="r3"><?php echo $result['answer_3']; ?></label></p>
				</div>
				
				<p><input type="submit" class="btn btn-success btn-block" value="Ответить"></p>
					
			</form>
		</div>

	</body>
</html>

<?php	
} catch (PDOException $e) {
	echo 'Ошибка чтения из базы данных: '. $e->getMessage(); 
}
?>

			