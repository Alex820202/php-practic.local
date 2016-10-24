<?php
require_once('../function.php');
try{
	$dbh = db_connect();
	session_start();
	if(!empty($_SESSION['true'])){
		
	}
	var_dump($_SESSION);
	
	

?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Главная страница</title>
		<link rel="stylesheet" href="../bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Админка</h1>
			<div class="info alert alert-success">
				Запись успешно удалена!
			</div>
			<table class="table table-bordered">
				<tr>
					<th>Страница</th>
					<th>Редактирование</th>
					<th>Удаление</th>
				</tr>
				<tr>
					<td><a href="../index.php?page=index">Главная</a></td>
					<td><a href="edit.php?page=index">редактировать</a></td>
					<td><a href="admin.php?page=index">удалить</a></td>
					
				</tr>
				<tr>
					<td><a href="../index.php?page=about">О компании</a></td>
					<td><a href="edit.php?page=about">редактировать</a></td>
					<td><a href="admin.php?page=about">удалить</a></td>
					
				</tr>
				<tr>
					<td><a href="../index.php?page=info">Инормация</a></td>
					<td><a href="edit.php?page=info">редактировать</a></td>
					<td><a href="admin.php?page=info">удалить</a></td>
					
				</tr>
				<tr>
					<td><a href="../index.php?page=price">Наши цены</a></td>
					<td><a href="edit.php?page=price">редактировать</a></td>
					<td><a href="admin.php?page=price">удалить</a></td>
					
				</tr>
			</table>
			<div>
				<a href="new.php" class="btn btn-danger btn-block">Новая страница</a>
			</div>
		</div>

	</body>
</html>

<?php
} catch (PDOException $e) {
	echo 'Нет связи с базой данных: '. $e -> getMessage();
}

?>
		


			