<?php
require_once(__DIR__.'/config.php');
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$id = array_keys($_POST);
	$limit = count($id);
	$sql_select = 'SELECT * FROM question WHERE ';
	for($i=0; $i<$limit; $i++){
		if($i==0){
			$flag='';
		}else{ 
			$flag = ' OR';
		}
		$sql_select.=$flag.' id='.(int)$id[$i];
	}
	$sql_select.=' LIMIT '.$limit; // получили сформированный sql-запрос к базе данных. LIMIT ставлю для того, чтобы как только требуемое количество вопросов выбрано, запрос прервался.
	$stm_select = $dbh -> prepare($sql_select);
	$stm_select -> execute();
	$answers = $stm_select -> fetchAll();
	$messages = array(); // массив для вывода сообщений на старницу
	$correct_answer = 0; // будем считать количество верных ответов
	$uncorrect_answer = 0;// будем считать количество не верных ответов
	/*
	* В цикле проверяем правильный ответ или нет. Если правильный, то пишем в переменную $message массив (1, текст вопроса), если не правильный, то пишем массив (0, текст вопроса, данный ответ, правильный ответ из БД), т.к. при неправильном ответе надо будет показать выданный ответ и правильный ответ.
	*/
	foreach($answers as $answer){
		if($answer['answer'] == strip_tags(trim($_POST[$answer['id']]))){
			$correct_answer++;
			$messages[] = array(0=>1, $answer['question']);
		}else{
			$uncorrect_answer++;
			$messages[] = array(0=>0, $answer['question'], strip_tags(trim($_POST[$answer['id']])), $answer['answer']);
		}
	}

	
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
			<h1>Результат теста "<?php echo $answers[0]['themes'];	?>"</h1>
			<div class="info alert alert-info">
				Правильных ответов: <?php echo $correct_answer; ?>.
				Неправильных ответов: <?php echo $uncorrect_answer; ?>.
			</div>
			
			<?php $i=1;
			foreach($messages as $message){
				if($message[0] == 0){
					echo "<div class='note'><p><b>".$i.".</b> ".$message[1]."</p><p class='wrong'>Неверно! <b>Ваш ответ:</b> ".$message[2].".<b> Правильный ответ:</b> ".$message[3].".</p></div>";
				}elseif($message[0] == 1){
					echo "<div class='note'><p><b>".$i.".</b> ".$message[1]."</p><p class='right'>Верно!</p></div>";
				}
				$i++;
			}
			?>
			<div class="info alert alert-info">
				<br>
			</div>			
		</div>

	</body>
</html>
	
	
<?php	
} catch (PDOException $e) {
	echo 'Проблема с одключением к базе данных: '. $e->getMessage();
} ?>



