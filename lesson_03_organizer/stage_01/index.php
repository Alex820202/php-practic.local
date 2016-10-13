<?php
require_once(__DIR__.'/config.php');

$week = array(1, 2, 3, 4, 5, 6, 7); // массив дней недели для проверки передаваемого параметра $_GET['date'].
/*
* Определяем видимый в органайзере день недели
*/
if(in_array($_GET['date'], $week)){
	$visible_day = $_GET['date'];
}else{
	if(date('w')!=0){
		$visible_day = date('w');
		}else{
			$visible_day = 7;
	}
}
/*
* Определяем текущий день недели
*/
if(date('w')!=0){
	$current_day = date('w');
}else{
	$current_day = 7;
}

try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	/*
	* Обрабатываем данные из формы
	*/
	if(!empty($_POST)){
		$option['text'] = htmlspecialchars($_POST['text']);
		$option['day_date'] = strtotime("today") + ($visible_day-$current_day)*24*60*60;
		
		if($_GET['flag']=='1'){
			$sql_update = 'UPDATE posts SET text=:text WHERE day_date=:day_date';
			$stm_update = $dbh -> prepare($sql_update);
			$stm_update -> execute($option);
		}
		if($_GET['flag']=='2'){
			$sql_insert = 'INSERT INTO posts(day_date, text) VALUES (:day_date, :text)';
			$stm_insert = $dbh -> prepare($sql_insert);
			$stm_insert ->execute($option);
		}
	}
	/*
	* Запрашиваем из базы данных заметку органайзера на отображаемый в органайзере день
	*/
	$sql_select = 'SELECT * FROM posts WHERE day_date=:day_date';
	$sth_select = $dbh->prepare($sql_select);
	$data['day_date'] = strtotime("today") + ($visible_day-$current_day)*24*60*60;
	$sth_select -> execute($data);
	$result= $sth_select -> fetch();
	if($result == TRUE){
		$flag = 1;
		
	}else{
		$result = array('text'=>'');
		$flag = 2;
	}

	?>
<!DOCTYPE html>
<html lang="ruEn">
	<head>
		<meta charset="utf-8">  
		<title>Органайзер</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Органайзер</h1>
			
			<div>
				<nav>
				  <ul class="pagination">
				  <?php 
				  for($i=1;$i<8;$i++){
				  	if($i==$visible_day){
						$class = "class='active'";
					}else{
						$class='';
					}
					$days_week = array(1=>'Понедельник', 2=>'Вторник', 3 =>'Среда', 4=>'Четверг', 5=>'Пятница', 6=>'Суббота', 7=>'Воскресенье');
				  	echo "<li ".$class."><a href='?date=".$i."'>".$days_week[$i]."</a></li>";
				  }
				  ?>
				  </ul>
				</nav>
				<p class="date"><span>Сегодня:</span> 
				<?php $today = explode('.',date("d.m.Y"));
					$month = array(1=>'января', 2=>'февраля', 3=>'марта', 4=>'апреля', 5=>'мая', 6=>'июня', 7=>'июля', 8=>'августа', 9=>'сентября', 10=>'октября', 11=>'ноября', 12=>'декабря');
					echo $today[0].' '.$month[$today[1]].' '.$today[2].' года';?>
					</p>
			</div>
			<div class="note">
				
				<p>
					</p>
				
			</div>	
			
			<div id="form">
				<form action="index.php?date=<?php echo $visible_day;?>&flag=<?php echo $flag;?>" method="POST">
					<p>
						<textarea class="form-control" name="text" placeholder="Ваш отзыв">	
						<?php echo htmlspecialchars_decode($result['text']);?>
						</textarea>
					</p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>
						
<?php	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблема с базой данных!';
	echo $e->getMessage();
}

?>

