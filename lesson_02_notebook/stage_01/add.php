<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Новая запись</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Новая запись</h1>
			<!-- 
			
				После сохранения перебрасывает 
				на список записей
				с помощью header location
			
			-->
			<div>
				<form action="" method="POST">
					<p><input class="form-control" placeholder="Название записи"></p>
					
					<p><textarea class="form-control" placeholder="Текст записи"></textarea></p>
					<p><input type="submit" class="btn btn-danger btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>


			