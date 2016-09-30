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
					<li><a href="?date=1">Понедельник</a></li>
					<li class="active"><a href="?date=2">Вторник</a></li>
					<li><a href="?date=3">Среда</a></li>
					<li><a href="?date=4">Четверг</a></li>
					<li><a href="?date=5">Пятница</a></li>
					<li><a href="?date=6">Суббота</a></li>
					<li><a href="?date=7">Воскресенье</a></li>
					
				  </ul>
				</nav>
				<p class="date"><span>Сегодня:</span>  12 марта 2015 года</p>
			</div>
			
			
			<div class="note">
				
				<p>
					</p>
				
			</div>	
			
			<div id="form">
				<form action="#form" method="POST">
					<p>
						<textarea class="form-control" placeholder="Ваш отзыв">
1. Поесть.
2. Поспать.
3. Поработать.
4. Погулять.
5. Посмотреть телевизор.
</textarea>
					</p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>

	</body>
</html>


			