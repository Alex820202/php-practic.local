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
			<!--
				При удалении следует сделать так,
				чтобы мы попадали на ту же страницу пагинации.
			-->
			<!--
			<div class="info alert alert-success">
				Запись успешно удалена!
			</div>
			-->
			<!--
			<div class="info alert alert-danger">
				Ошибка удаления записи!
			</div>
			-->
			<div class="note">
				<p>
					<span class="date">15.04.2014</span>
					<a href="note.php?id=3">Моя заметка номер 5</a>
				</p>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla efficitur elementum lorem id venenatis. Nullam id sagittis urna, eu ultrices risus. Duis ante lorem, semper nec fringilla eu, commodo vel mauris. Nunc tristique odio lectus, eget condimentum nunc consectetur eu. Nullam non varius nisl, aliquet fringilla lectus. Aliquam erat volutpat. Ut vel mi et lectus hendrerit ornare vel ut neque. Quisque venenatis nisl eu mi...
				</p>
				<p class="nav">
					<a href="index.php?page=1&del=3">удалить</a> |
					<a href="edit.php?edit=3">редактировать</a>
				</p>
			</div>	
			<div class="note">
				<p>
					<span class="date">13.04.2014</span>
					<a href="note.php?id=2">Запись о предстоящих делах</a>
				</p>
				<p>
					Ut varius commodo fringilla. Nullam id pulvinar odio. Pellentesque gravida aliquam ipsum, et malesuada neque molestie eget. Vestibulum sagittis finibus efficitur. Donec sit amet aliquet dolor, vitae ornare tortor. Etiam eget augue nec diam vehicula bibendum. Nulla quis erat lacus. Vestibulum quis mattis augue...
				</p>
				<p class="nav">
					<a href="index.php?page=1&del=2">удалить</a> |
					<a href="edit.php?edit=2">редактировать</a>
				</p>
			</div>
			<div class="note">
				<p>
					<span class="date">12.04.2014</span>
					<a href="note.php?id=1">Список моих дел на завтра</a>
				</p>
				<p>
					Etiam nisl ipsum, accumsan nec lacinia quis, gravida et neque. Morbi enim sem, sagittis id varius mattis, consectetur a ligula. Suspendisse molestie vulputate erat eu dapibus. Integer mattis elit in ipsum facilisis maximus. Vivamus eu urna velit. Integer sed lorem est. Nunc malesuada erat sit amet leo mattis, vitae egestas lacus sagittis...
				</p>
				<p class="nav">
					<a href="index.php?page=1&del=1">удалить</a> |
					<a href="edit.php?edit=1">редактировать</a>
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
			<div>
				<a href="add.php" class="btn btn-danger btn-block">Добавить запись</a>
			</div>
		</div>

	</body>
</html>


			