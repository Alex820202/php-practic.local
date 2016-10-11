<?php
require_once(__DIR__.'/config.php');



try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	/*
	* Если передана команда на удаление поста
	*/
	if($_GET['del'] && $_GET['page']){
		$id = (int)$_GET['del'];
		$sql_delete = 'DELETE FROM posts WHERE id = :id';
		$sth_delete = $dbh->prepare($sql_delete);
		$option = $sth_delete->execute(array('id'=>$id));
	} 
	/*
	* Формируем страницу выдачи
	*/
	$sql_total = "SELECT COUNT(*) FROM posts";
	$sth_total = $dbh->prepare($sql_total);
	$sth_total->execute();
	$total_posts = $sth_total->fetch()['COUNT(*)'];
	$post_to_page = 5;// количество записей на странице
	$total_page = ceil($total_posts/$post_to_page); // количество страниц пагинации
	/*
	* Определяем текущую страницу
	*/
	if($_GET['page']){
		$current_page = $_GET['page'];
	}else{
		$current_page = $total_page;
	}
	/*
	* Определяем массив страниц пагинации
	*/
	if($current_page-2<=0 && $current_page+2>=$total_page){
		$start_page = 1;
		$end_page = $total_page;
	}elseif($current_page-2>0 && $current_page+2>=$total_page){
		$start_page = $current_page-2;
		$end_page = $total_page;
	}elseif($current_page-2>0 && $current_page+2<$total_page){
		$start_page = $current_page-2;
		$end_page = $current_page+2;
	}else{
		$start_page = 1;
		$end_page = $current_page+2;
	}
	
	for($i=$start_page; $i<$end_page+1; $i++){
		$page_pagination[] = $i;
	} 
	
	 /*
	 * Извлекаем данные из базы данных для формирования текущей страницы
	 */
	$sql_select = "SELECT * FROM posts WHERE id BETWEEN :id_start AND :id_end";	
	$sth_select= $dbh->prepare($sql_select);
	$data['id_start'] = $current_page*$post_to_page-$post_to_page+1;
	$data['id_end'] = $current_page*$post_to_page;
	$sth_select->execute($data);
	$results = $sth_select->fetchAll();
	
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Список записей</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Список записей</h1>
			<?php if(!empty($option) && $option ==TRUE){
				echo "<div class='info alert alert-success'>Запись успешно удалена!</div>";
			}elseif(!empty($option) && $option ==FALSE){
				echo "<div class='info alert alert-danger'>Ошибка удаления записи!</div>";
			}
			foreach($results as $result){?>
				<div class="note">
				<p>
					<span class="date"><?php echo date("d.m.Y", $result['datetime']);?></span>
					<a href="note.php?id=<?php echo $result['id']; ?>"><?php echo $result['title']; ?></a>
				</p>
				<?php echo $result['Anons']; ?>
				<p class="nav">
					<a href="index.php?page=<?php echo $current_page; ?>&del=<?php echo $result['id']; ?>">удалить</a> |
					<a href="edit.php?edit=<?php echo $result['id']; ?>">редактировать</a>
				</p>
			</div>
			
			<?php }?>
			
			<div>
				<nav>
				  <ul class="pagination">
				  <?php if($current_page != 1){ echo "<li><a href='?page=1'  aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";}
				  for($i=0;$i<count($page_pagination);$i++){
				  	if($current_page == $page_pagination[$i]){
						$disable = " class='active'";
					}else{
						$disable='';
					}
				  	echo "<li".$disable."><a href='?page=".$page_pagination[$i]."'>".$page_pagination[$i]."</a></li>";
				  }
				  if($current_page != $total_page){
				  	echo "<li><a href='?page=".$total_page."' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
				  }
				  ?>
					<!--<li class="disabled">
					  <a href="?page=1"  aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					  </a>
					</li>
					<li class="active"><a href="?page=1">1</a></li>
					<li><a href="?page=2">2</a></li>
					<li><a href="?page=3">3</a></li>
					<li><a href="?page=4">4</a></li>
					<li><a href="?page=5">5</a></li>
					<li>
					  <a href="?page=5" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					  </a>
					</li>-->
				  </ul>
				</nav>
				
			</div>
			<div>
				<a href="add.php" class="btn btn-danger btn-block">Добавить запись</a>
			</div>
		</div>

	</body>
</html>


<?php	
	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблема!<br />';
	echo $e -> getMessage();
}
?>



			