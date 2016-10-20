<?php
require_once(__DIR__.'/config.php');

try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh -> beginTransaction();
	/*
	* Выбираем из базы данных информацию о вопросе и ответах на него.
	*/
	$sql_select = 'SELECT * FROM Questions WHERE id=:id LIMIT 1';
	$stm_select = $dbh -> prepare($sql_select);
	$data['id'] = (int)$_GET['id'];
	$stm_select -> execute($data);
	$result = $stm_select -> fetch();
	/*
	* Обрабатываем пришедший ответ. 
	*/
	if(!empty($_POST)){
		$sql_update = 'UPDATE Questions SET total_answer=:total_answer, int_answer_1=:int_answer_1, pct_answer_1=:pct_answer_1, int_answer_2=:int_answer_2, pct_answer_2=:pct_answer_2, int_answer_3=:int_answer_3, pct_answer_3=:pct_answer_3 WHERE id=:id';
		$stm_update = $dbh -> prepare($sql_update);
		$data['total_answer'] = $result['total_answer'] +1; //общее количество опрошенных
		$stm_update -> bindValue(total_answer, $data['total_answer'], PDO::PARAM_INT);
		/*
		* Определяем количество ответивших на вопрос одним из трех вариантов ответа в людях и процентах.
		*/
		for($i=1; $i<=3; $i++){
			$key_1 = 'int_answer_'.$i; 
			$key_2 = 'pct_answer_'.$i;
			if($i != (int)$_POST['radio']){
				$data[$key_1] = $result[$key_1];
				$data[$key_2] = round($data[$key_1]*100/$data['total_answer']);
				$stm_update -> bindValue($key_1, $data[$key_1], PDO::PARAM_INT);
				$stm_update -> bindValue($key_2, $data[$key_2], PDO::PARAM_INT);
			}else{
				$data[$key_1] = $result[$key_1]+1;
				$data[$key_2] = round($data[$key_1]*100/$data['total_answer']);
				$stm_update -> bindValue($key_1, $data[$key_1], PDO::PARAM_INT);
				$stm_update -> bindValue($key_2, $data[$key_2], PDO::PARAM_INT);
			}
		}
		$stm_update -> bindValue(id, (int)$_GET['id'], PDO::PARAM_INT);
		$stm_update ->execute();
	}
	$dbh -> commit();
	
	?>
	
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Результат опроса</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Результат опроса</h1>
			<div class="info alert alert-info">
				Общее количество опрошенных: <b><?php echo $data['total_answer']; ?></b>.
				<?php 
				for($i=1; $i<=3; $i++){
					$key_1 = 'answer_'.$i;
					$key_2 = 'int_answer_'.$i;
					$key_3 = 'pct_answer_'.$i;
				echo "<br><b>1.</b>Ответили \"".$result[$key_1]."\": <b>".$data[$key_2]."</b> человек, <b> ".$data[$key_3]."%</b> опрошенных.<br>";
				}
				?>
			</div>		
			
			<div class="note">
			<?php
			for($i=1; $i<=3; $i++){
					$key_1 = 'answer_'.$i;
					$key_2 = 'pct_answer_'.$i;
					echo "<p class='answer'>Ответ \"".$result[$key_1]."\":</p><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width:".$data[$key_2]."%;'>".$data[$key_2]."%</div></div>";
				}
				?>
			</div>
			
			<div class="info alert alert-info">
				<br>
			</div>			
		</div>

	</body>
</html>

<?php	
} catch (PDOException $e) {
	echo 'Ошибка базы данных: '.$e->getMessage();
}

?>
			