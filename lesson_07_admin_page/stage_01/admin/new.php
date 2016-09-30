<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Новая страница</title>
		<link rel="stylesheet" href="../bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/admin.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Новая страница</h1>
			<p class="nav">
				<a href="index.php">на главную админки</a>
			</p>
			<!-- 
			
				После сохранения перебрасывает 
				на список записей
				с помощью header location
			
			-->
			<div>
				<form action="" method="POST">
					<p><input class="form-control" placeholder="Url страницы"></p>
					<p><input class="form-control" placeholder="Название страницы"></p>
					
					<p><textarea class="form-control" placeholder="Текст страницы"></textarea></p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>


			