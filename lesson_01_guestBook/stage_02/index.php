<?php
require_once(__DIR__.'/config.php');

$post_per_page = 5; // количество записей на странице
$number_visible_pages = 5;

try{
	$dsn = "mysql:host=$host;dbname=$dbname";
	$dbh = new PDO($dsn, $user, $password);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	/*
	*Блок запросов, которые нам нужны в работе.
	*/
	$query_total= 'SELECT COUNT(*) FROM posts';
	$query_select = 'SELECT * FROM posts LIMIT :start, :number';
	$query_insert = 'INSERT INTO posts(author, datetime, text) VALUES (:author_name, :datetime, :text_post)';
	/*
	*Определяем общее количество постов в базе и соответственно максимальное количество страниц.
	*/
	$sth_total = $dbh->prepare($query_total);
	$sth_total->execute();
	$result_total = $sth_total->fetch();
	$total = $result_total['COUNT(*)']; // общее количество постов в гостевой книге
	if($total < $post_per_page){
		$total_page = 1;
	}else{$total_page = ceil($total/$post_per_page);}//максимально возможное количество страниц
	
	/*
	* Определяем текущую страницу вывода сообщений.
	*/
	
	if(!$_GET['page']){
		$current_page = $total_page;
	}else{
		$current_page = $_GET['page'];
	}
	/*
	* Определяем массив выводимых в пагинации страниц
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
	* Определяем начальный пост и число постов на странице. 
	*/
	if($_GET['page'] == 1){//первая страница
		$start = 0; // с какого поста начинаем вывод
		$number = $post_per_page; // сколько постов показываем
	}elseif($total_page == $_GET['page']){//последняя страница
		$start = $post_per_page*$_GET['page']-$post_per_page;
		$number = $total - $start;
	}elseif(!$_GET){
		$start = $post_per_page*$total_page - $post_per_page;
		$number = $post_per_page;
	}else{//любая другая страница
		$start = $_GET['page']*$post_per_page - $post_per_page;
		$number = $post_per_page;}
	
	/*
	*Обрабатываем сохраненное сообщение
	*/
	if(!empty($_POST['author']) && !empty($_POST['text'])){
		$data_insert['author_name'] = trim($_POST['author']);
		$data_insert['datetime'] = time();
		$data_insert['text_post'] = htmlspecialchars(trim($_POST['text']));
		$sth_insert = $dbh->prepare($query_insert);
		$sth_insert->execute($data_insert);
		header('Location: index.php?page='.$total_page, true, 303);
	}else{
		$sth_select = $dbh->prepare($query_select);
		$sth_select->bindValue(start, $start, PDO::PARAM_INT);
		$sth_select->bindValue(number, $number, PDO::PARAM_INT);
		
		$sth_select->execute();
		$results = $sth_select->fetchAll();
	}
	echo $current_page;
	?>






<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Гостевая книга</h1>
<!-----------------------Начало вывода верхнего блока пагинации----------------------------------------->			
			<div>
				<nav>
				  <ul class="pagination">
					<?php
					if(1 != $current_page){
					?>	
					<li>
					  <a href="?page=1"  aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					  </a>
					 </li>
					 <?php } 
					 foreach($page_pagination as $page_pag){
					 	if($current_page == $page_pag){
					 		$class_start = "class='active'";
					 		}else{
							$class_start = '';
						}
					 	echo "<li ".$class_start." ><a href=".$_SERVER['PHP_SELF']."?page=".$page_pag.">".$page_pag."</a></li>";
					 }
					 ?>
					
					<li>
					 <?php if($total_page != $current_page){ ?><a href="?page=<?php echo $total_page; ?>" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					  </a>
					</li><?php } ?>
				  </ul>
				</nav>
				
			</div>
<!---------------------Окончание вывода верхнего блока пагинации----------------------------------------->

<!-----------------------Блок с записями гостевой книги----------------------------------------->
			
			<?php
			foreach($results as $result){
				?>
				<div class="note">
				<p>
					<span class="date"><?php  echo date('H:i:s d.m.Y', $result['datetime']); 
					?></span>
					<span class="name"><?php echo $result['author'];
					?></span>
				</p>
				<p>
				<?php echo $result['text'];
				?>
				</p>
				
			</div>
			<?php } ?>
<!---------------------Окончание блока с записями гостевой книги----------------------------------------->
			
			
	<!----------------Выводим пагинацию страниц внизу списка постов----------------------------->
			<div>
				<nav>
				  <ul class="pagination">
					<?php
					if(1 != $current_page){
					?>	
					<li>
					  <a href="?page=1"  aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					  </a>
					 </li>
					 <?php } 
					  foreach($page_pagination as $page_pag){
					 	if($current_page == $page_pag){
					 		$class_start = "class='active'";
					 		}else{
							$class_start = '';
						}
					 	echo "<li ".$class_start." ><a href=".$_SERVER['PHP_SELF']."?page=".$page_pag.">".$page_pag."</a></li>";
					 }
					 ?>
					
					 <?php if($total_page != $current_page){ ?>
					 <li>
					<a href="?page=<?php echo $total_page; ?>" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					  </a>
					</li><?php } ?>
				  </ul>
				</nav>
				
			</div>
			
<!-----------------------Закончили вывод пагинации---------------------------------------------->

<!------------В случае добавления записи выводим уведомление "Запись успешно сохранена!"---------------->
			
			<?php
			$flag = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			
			if($_SERVER['HTTP_REFERER']== $flag){?> <div class="info alert alert-info">
				Запись успешно сохранена!
			</div>


			<?php }?>
<!-----------------------Окончание вывода---------------------------------------------->
<!-----------------------Форма добавления записи---------------------------------------------->
			
			<div id="form">
				<form action="index.php" method="POST">
					<p><input class="form-control" placeholder="Ваше имя" name="author"></p>
					
					<p><textarea class="form-control" placeholder="Ваш отзыв"name="text"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>
		


<?php	
}catch(PDOException $e){
	echo 'Хьюстон - у нас проблема!!!'.'<br>';
	echo $e->getMessage();
}
?>

			