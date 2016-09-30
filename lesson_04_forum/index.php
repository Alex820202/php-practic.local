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
			
			<div class="note">
				<p class="topic">
					<a href="topic.php?topic=3&page=1">Помогите решить проблему с HTML</a>
				</p>
				<p>
					<span class="subheader">Создана:</span> 
					18.04.2014 23:59:59.
					<span class="subheader">Автор:</span> 
					Дмитрий.
					<br>
					<span class="subheader">Количество ответов:</span> 
					7
				</p>
				
				
			</div>	
			<div class="note">
				<p class="topic">
					<a href="topic.php?topic=2&page=1">Помогите решить проблему с CSS</a>
				</p>
				<p>
					<span class="subheader">Создана:</span> 
					19.04.2014 14:59:59.
					<span class="subheader">Автор:</span> 
					Петр.
					<br>
					<span class="subheader">Количество ответов:</span> 
					5
				</p>
				
				
			</div>		
			<div class="note">
				<p class="topic">
					<a href="topic.php?topic=1&page=1">Ничего не работает! Что делать?!</a>
				</p>
				<p>
					<span class="subheader">Создана:</span> 
					19.04.2014 14:59:59.
					<span class="subheader">Автор:</span> 
					Иммануил.
					<br>
					<span class="subheader">Количество ответов:</span> 
					53
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
			
			<h2>Создать тему</h2>
			
			<div class="info alert alert-info">
				Тема успешно создана!
			</div>
			<div class="info alert alert-danger">
				Тема с таким назанием уже существует!
			</div>
			<!--
			
				Обеспечьте удаление концевых пробелов
				и тегов при сохранении.
				
				Обеспечьте редирект с помощью
				header location чтобы не было 
				двойного сохранения при обновлении страницы.
			
			-->
			
			<div id="form">
				<form action="#form" method="POST">
					<p><input class="form-control" placeholder="Ваше имя"></p>
					<p><input class="form-control" placeholder="Название темы"></p>
					
					<p><textarea class="form-control" placeholder="Описание темы"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
			
		</div>

	</body>
</html>


			