<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Опрос</title>
		<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/admin.css">
	</head>
	<body>
		
		<div id="wrapper">
			<h1>Опрос</h1>
			<div class="info alert alert-info">
				Укажите Ваш возраст:
			</div>
			<form action="check.php" method="POST">
				<div class="note">
					<p><input type="radio" name="radio" value="1"  id="r1"> <label for="r1">до 20 лет</label></p>
					<p><input type="radio" name="radio" value="2"  id="r2"> <label for="r2">от 20 до 30</label></p>
					<p><input type="radio" name="radio" value="3"  id="r3"> <label for="r3">более 30</label></p>
				</div>
				
				<p><input type="submit" class="btn btn-success btn-block" value="Ответить"></p>
					
			</form>
		</div>

	</body>
</html>


			