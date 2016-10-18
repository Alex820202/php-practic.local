<?php
require_once(__DIR__.'/config.php');
try{
	$dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$dbh -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh -> setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	/*
	* Обрабатываем создание новой темы на форуме.
	*/
	if(!empty($_POST)){
		$sql_inspection = 'SELECT * FROM themes WHERE themes_title=:themes_title';
		$stm_inspection = $dbh -> prepare($sql_inspection);
		$data_inspection['themes_title'] = strip_tags(trim($_POST['theme_title']));
		$stm_inspection ->execute($data_inspection);
		$opt0 = $stm_inspection -> fetch();
		if(!empty($opt0)){
			header("Location: index.php?page=".(int)$_GET['page']."&flag=1", TRUE, 303);
		}else {

			$sql_insert_themes = 'INSERT INTO themes(themes_title, themes_text, data_creation, author, number_replies) VALUES (:themes_title, :themes_text, :data_creation, :author, :number_replies)';
			$stm_insert_themes = $dbh->prepare($sql_insert_themes);
			$data_theme['themes_title'] = strip_tags(trim($_POST['theme_title']));
			$data_theme['themes_text'] = strip_tags(trim($_POST['text']));
			$data_theme['data_creation'] = time();
			$data_theme['author'] = strip_tags(trim($_POST['author']));
			$data_theme['number_replies'] = 0;
			$stm_insert_themes->execute($data_theme);
			header("Location: index.php?page=" . (int)$_GET['page'] . "&flag=2", TRUE, 303);
		}

	}
	/*
	* Определяем количество тем в базе и соответственно общее количество страниц
	*/
	$flag = $_GET['flag'];
	$sql_total_themes = 'SELECT COUNT(*) FROM themes';
	$sth_total_themes = $dbh -> prepare($sql_total_themes);
	$sth_total_themes ->execute();
	$result_themes = $sth_total_themes -> fetch();
	$total_themes = $result_themes['COUNT(*)'];// количество тем в базе
	$themes_per_page = 5;// количество отображаемых тем на странице
	$total_page_themes = ceil($total_themes/$themes_per_page); // общее количество страниц с темами
	if(!empty($_GET['page'])){
		$current_page_themes = (int)$_GET['page'];
	}else{
		$current_page_themes = $total_page_themes;
	}
	/*
	* Определяем страницы в пагинации
	*/
	if($current_page_themes-2>=0 && $current_page_themes+2<=$total_page_themes){
		for($i=$current_page_themes-2;$i<$current_page_themes+3;$i++){
			$page_pagination_themes[]=$i;
		}
	}elseif($current_page_themes-2<0 && $current_page_themes+2>$total_page_themes){
		for($i=1;$i<=$total_page_themes;$i++){
			$page_pagination_themes[]=$i;
		}
	}elseif($current_page_themes-2<0 && $current_page_themes+2<=$total_page_themes){
		for($i=1;$i<=$current_page_themes+2;$i++){
			$page_pagination_themes[]=$i;
		}
	}elseif($current_page_themes-2>=0 && $current_page_themes+2>$total_page_themes){
		for($i=$current_page_themes-2;$i<=$total_page_themes;$i++){
			$page_pagination_themes[]=$i;
		}
	}
	/*
	* Выбираем из базы темы, которые будут отображаться на странице, учитываем, что сперва идут самые свежие.
	*/
	$sql_select_themes = 'SELECT * FROM themes ORDER BY id DESC LIMIT :id_start, :id_count';
	$sth_select_themes = $dbh -> prepare($sql_select_themes);
	$start_themes = $current_page_themes*$themes_per_page-$themes_per_page;
	$sth_select_themes -> bindValue(id_start, $start_themes, PDO::PARAM_INT);
	$sth_select_themes -> bindValue(id_count, $themes_per_page, PDO::PARAM_INT);
	$sth_select_themes->execute();
	$results = $sth_select_themes ->fetchAll();
	/*
	* Формируем страницу выдачи
	*/
	?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Форум</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Наш форум</h1>
			
			
			<div class="note">
				Наш супер крутой форум посвящен phasellus gravida fermentum pellentesque. Aenean non neque mollis nisl dapibus eleifend. Sed interdum dui nec dictum elementum. Proin eget semper dolor, ut commodo nibh. 
				Quisque vitae pharetra ligula. Sed dictum, sem sed pellentesque aliquam, tellus sapien dapibus magna, eu suscipit lacus augue sed velit. Ut vehicula sagittis nulla, et aliquet elit. Quisque tincidunt sem nibh, finibus dictum nisl vulputate quis. In vitae nisl et lacus pulvinar ornare id ac libero. Morbi pharetra fringilla erat ut lacinia. 
			</div>	
			<h2>Темы форума</h2>
			<?php 
			foreach($results as $result){
				
			
			?>
			<div class="note">
				<p class="topic">
					<a href="topic.php?topic=<?php echo $result['id'];?>&page=1"><?php echo $result['themes_title'];?></a>
				</p>
				<p>
					<span class="subheader">Создана:</span>
					<?php echo date("d.m.Y H:i:s", $result['data_creation']);?>
					<span class="subheader">Автор:</span>
					<?php echo $result['author'];?>
					<br>
					<span class="subheader">Количество ответов:</span>
					<?php echo $result['number_replies'];?>
				</p>
				<?php } ?>
				
			<div>
				<nav>
				  <ul class="pagination">
				  <?php 
				  if($current_page_themes != 1){
				  	echo "<li><a href='?page=1'  aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
				  	} 
				  foreach($page_pagination_themes as $page){
				  	if($page == $current_page_themes){
				  		$class = "class='active'";
				  }else{
				  	$class = '';
				  }
				  echo "<li ".$class."><a href='?page=".$page."'>".$page."</a></li>";
				}
				if($current_page_themes != $total_page_themes){
					echo "<li><a href='?page=".$total_page_themes."' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
				} ?>
				  </ul>
				</nav>
				
			</div>
			
			<h2>Создать тему</h2>
			<?php 
			if($flag == 2){
				echo "<div class='info alert alert-info'>Тема успешно создана!</div>";
			}elseif($flag == 1){
				echo "<div class='info alert alert-danger'>Тема с таким назанием уже существует!</div>";
			}
			?>
			
			<div id="form">
				<form action="index.php?page=<?php echo $current_page_themes; ?>" method="POST">
					<p><input class="form-control" name="author" placeholder="Ваше имя"></p>
					<p><input class="form-control" name="theme_title" placeholder="Название темы"></p>
					
					<p><textarea class="form-control" name="text" placeholder="Описание темы"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>
				
	
<?php	
} catch (PDOException $e) {
	echo 'Хьюстон, у нас проблема! <br />';
	echo $e -> getMessage();
}

?>


			