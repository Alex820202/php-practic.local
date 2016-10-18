<?php
require_once(__DIR__.'/config.php');
$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$id = (int)$_GET['topic'];
try{
	if(!empty($_POST)){
		$dbh -> beginTransaction();
		$sql_select_number_post = 'SELECT number_replies FROM themes WHERE id=:id';
		$stm_select_number_post = $dbh -> prepare($sql_select_number_post);
		$stm_select_number_post -> execute(array('id'=>$id));
		$result_number_replies = $stm_select_number_post -> fetch();
		$number_replies = (int)$result_number_replies['number_replies'];

		$sql_update_themes = 'UPDATE themes SET number_replies=:number_replies WHERE id=:id';
		$stm_update_themes = $dbh -> prepare($sql_update_themes);
		$opt = $stm_update_themes -> execute(array('number_replies'=>$number_replies+1, 'id'=>$id));
		
		$sql_insert_post = 'INSERT INTO posts(themes_number, data_post, author, text) VALUES (:themes_number, :data_post, :author, :text)';
		$stm_insert_post = $dbh -> prepare($sql_insert_post);
		$data['themes_number'] = $id;
		$data['data_post'] = time();
		$data['author'] = strip_tags(trim($_POST['author']));
		$data['text'] = strip_tags(trim($_POST['text']));
		$stm_insert_post -> execute($data); 
		$dbh -> commit();
	}
} catch (PDOException $f) {
	$dbh -> rollBack();
	echo 'Ошибка записи в базу данных: '.$f->getMessage();
}
try{
	
	if(!empty($_GET['page'])){
		$current_page = (int)$_GET['page'];
		}else{
			$current_page = 1;
			}
	$post_to_page = 5; // количество ответов на страницу
	$dbh -> beginTransaction();
	/*
	* Вычисляем общее количество ответов в теме для задания пагинации
	*/
	$sql_select_post_total = 'SELECT COUNT(*) FROM posts WHERE themes_number=:id_theme';
	$stm_select_post_total = $dbh -> prepare($sql_select_post_total);
	$stm_select_post_total -> execute(array('id_theme'=>$id));
	$result_total = $stm_select_post_total -> fetch();
	if($result_total['COUNT(*)'] !=0){
			$total_post = $result_total['COUNT(*)'];
		}else{
			$total_post = 1;
		}
	$total_page_post = ceil($total_post/$post_to_page);
	/*
	 * Формируем массив страниц пагинации
	 */
	 if($current_page-2>=0 && $current_page+2<=$total_page_post){
	 	for($i=$current_page-2;$i<=$current_page+2;$i++){
			$page_pagination_post[]=$i;
		}
	 }elseif($current_page-2<0 && $current_page+2<=$total_page_post){
	 	for($i=0;$i<=$current_page+2;$i++){
			$page_pagination_post[]=$i;
		}
	 }elseif($current_page-2>=0 && $current_page+2>$total_page_post){
	 	for($i=$current_page-2;$i<=$total_page_post;$i++){
			$page_pagination_post[]=$i;
		}
	 }elseif($current_page-2<0 && $current_page+2>$total_page_post){
	 	for($i=1;$i<=$total_page_post;$i++){
			$page_pagination_post[]=$i;
		}
	 }
	/*
	 * Извлекаем название и текст темы.
	 */
	$sql_select_themes = 'SELECT * FROM themes WHERE id=:id';
	$stm_select_themes = $dbh ->prepare($sql_select_themes);
	$stm_select_themes -> execute(array('id' => $id));
	$result_themes = $stm_select_themes -> fetch();
	/*
	 * Извлекаем ответы в теме
	 */
	$sql_select_post = 'SELECT * FROM posts WHERE themes_number=:id_theme LIMIT :id_start, :id_total';
	$stm_select_post = $dbh ->prepare($sql_select_post);
	$stm_select_post->bindValue(id_theme, $id, PDO::PARAM_STR);
	$start = $current_page*$post_to_page - $post_to_page;
	$stm_select_post->bindValue(id_start, $start, PDO::PARAM_INT);
	$stm_select_post->bindValue(id_total, $post_to_page, PDO::PARAM_INT);
	$stm_select_post -> execute();
	$results_post = $stm_select_post -> fetchAll();

	$dbh -> commit();
	 /*
	 * Формируем страницу выдачи
	 */
	?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Тема №<?php echo $result_themes['id']; ?></title>
	<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div id="wrapper">
	<h1>Тема №<?php echo $result_themes['id']; ?></h1>
	<p>
		<span class="subheader">Создана:</span>
		<?php echo date("d.m.Y H:i:s", $result_themes['data_creation']);?>
		<span class="subheader">Автор:</span>
		<?php echo $result_themes['author']; ?>
		<br />
		<span class="subheader">Количество ответов:</span>
		<?php echo $result_themes['number_replies']; ?>
		<a href="index.php">Перейти на список тем.</a>
	</p>
	<p class="title"> <?php echo $result_themes['themes_title']; ?></p>

	<div class="desc">

		<?php echo $result_themes['themes_text']; ?>
	</div>

	<h2>Ответы</h2>
 	<?php
	foreach ($results_post as $result){
		echo "<div class='note'><p><span class='date'>".date("d.m.Y H:i:s",$result['data_post'])."</span><span class='name'>".$result['author']."</span></p><p>".$result['text']."</p></div>";
	}
	?>
	<div>
		<nav>
			<ul class="pagination">
			<?php
			if($current_page != 1){
					echo "<li class='disabled'><a href='?page=1'  aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
				} 
			foreach($page_pagination_post as $page_pagination){
				if($page_pagination == $current_page){
					$flag = "class='active'";
				}else{
					$flag='';
				}
				echo "<li ".$flag."><a href='?page=".$page_pagination."'>".$page_pagination."</a></li>"; 
			}
			echo "<li><a href='?page=".$total_page_post."' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
			?>
				
			</ul>
		</nav>

	</div>
	<?php
	if(!empty($_GET['flag']) && (int)$_GET['flag'] == 1){
		echo "<div class='info alert alert-info'>Запись успешно сохранена!</div>";
	}
	?>
	<div id="form">
				<form action="topic.php?topic=<?php echo $id; ?>&flag=1" method="POST">
					<p><input class="form-control" name="author" placeholder="Ваше имя"></p>
					
					<p><textarea class="form-control" name="text" placeholder="Ваше сообщение"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>


	<?php
}catch(PDOException $e){
	$dbh -> rollBack();
	echo 'Ошибка базы данных: '.$e->getMessage();
}
?>
<!--<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Тема №1</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Тема №1</h1>
			<p>
				<span class="subheader">Создана:</span> 
				19.04.2014 14:59:59.
				<span class="subheader">Автор:</span> 
				Иммануил.
				<br>
				<span class="subheader">Количество ответов:</span> 
				53.
				<a href="index.php">Перейти на список тем.</a>
			</p>
			<p class="title">Помогите решить проблему с CSS</p>
			
			<div class="desc">
				
				<p>
					Проблема такая: Lorem ipsum dolor sit amet, 
					consectetur adipiscing elit. 
					Nulla efficitur elementum lorem id venenatis. 
					Nullam id sagittis urna, eu ultrices risus. 
					Duis ante lorem, semper nec fringilla eu,
					commodo vel mauris. Nunc tristique odio lectus, eget condimentum nunc consectetur eu. Nullam non varius nisl, aliquet fringilla lectus. Aliquam erat volutpat. Ut vel mi et lectus hendrerit ornare vel ut neque. Quisque venenatis nisl eu mi.
					Помогите решить!
				</p>
				
			</div>	
			
			<h2>Ответы</h2>
			
			<div class="note">
				<p>
					<span class="date">18.04.2014 23:59:59</span>
					<span class="name">Дмитрий</span>
					
				</p>
				<p>
					Lorem ipsum dolor sit amet, 
					consectetur adipiscing elit. 
					Nulla efficitur elementum lorem id venenatis. 
					Nullam id sagittis urna, eu ultrices risus. 
					Duis ante lorem, semper nec fringilla eu,
					commodo vel mauris. Nunc tristique odio lectus, eget condimentum nunc consectetur eu. Nullam non varius nisl, aliquet fringilla lectus. Aliquam erat volutpat. Ut vel mi et lectus hendrerit ornare vel ut neque. Quisque venenatis nisl eu mi
				</p>
				
			</div>	
			<div class="note">
				<p>
					<span class="date">16.04.2014 14:59:59</span>
					<span class="name">Николай</span>
					
				</p>
				<p>
					Ut varius commodo fringilla. Nullam id pulvinar odio. Pellentesque gravida aliquam ipsum, et malesuada neque molestie eget. Vestibulum sagittis finibus efficitur. Donec sit amet aliquet dolor, vitae ornare tortor. Etiam eget augue nec diam vehicula bibendum. Nulla quis erat lacus. Vestibulum quis mattis augue. Praesent dignissim, justo non aliquam feugiat, lorem metus egestas leo, quis eleifend odio quam in ex. Aenean diam est, scelerisque ac ultricies sit amet, vulputate in tortor. Etiam ac mi enim. Sed pellentesque elementum erat eu eleifend. Integer imperdiet sem eu magna feugiat, sed efficitur velit convallis. 
				</p>
				
			</div>	
			<div class="note">
				<p>
					<span class="date">15.04.2014 12:59:59</span>
					<span class="name">Петр</span>
					
				</p>
				<p>
					Phasellus gravida fermentum pellentesque. Aenean non neque mollis nisl dapibus eleifend. Sed interdum dui nec dictum elementum. Proin eget semper dolor, ut commodo nibh. 
					Quisque vitae pharetra ligula. Sed dictum, sem sed pellentesque aliquam, tellus sapien dapibus magna, eu suscipit lacus augue sed velit. Ut vehicula sagittis nulla, et aliquet elit. Quisque tincidunt sem nibh, finibus dictum nisl vulputate quis. In vitae nisl et lacus pulvinar ornare id ac libero. Morbi pharetra fringilla erat ut lacinia. 
					
				</p>
				
			</div>	
			
			<div>
				<nav>
				  <ul class="pagination">
					<li class="disabled">
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
					</li>
				  </ul>
				</nav>
				
			</div>
			
			
			<div class="info alert alert-info">
				Запись успешно сохранена!
			</div>
			
			<!--
			
				Обеспечьте удаление концевых пробелов
				и тегов при сохранении.
				
				Обеспечьте редирект с помощью
				header location чтобы не было 
				двойного сохранения при обновлении страницы.
			
			-->
			
			<!--<div id="form">
				<form action="#form" method="POST">
					<p><input class="form-control" placeholder="Ваше имя"></p>
					
					<p><textarea class="form-control" placeholder="Ваше сообщение"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>
-->

			