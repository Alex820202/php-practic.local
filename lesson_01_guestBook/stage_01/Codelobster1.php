<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'test';

$link = mysqli_connect($host, $user, $password, $db_name) or die(mysqli_error($link));
$data = '';

if (!empty($_REQUEST['name']) && !empty($_REQUEST['text'])) { // adding new post
$author_name = htmlspecialchars(trim($_REQUEST['name']));
$post_text = htmlspecialchars(trim($_REQUEST['text']));
$created_on = time();
$query = "INSERT INTO visit_book (name, text, created_on) VALUES ('$author_name', '$post_text', '$created_on')";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
header( 'Location: index.php', true, 303 );
} else { // pull data from server
$query = "SELECT * FROM visit_book";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
if($result) {
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
$flag = count($data) ? 1 : 0; // check if database is empty

if((time() - $data[count($data)-1]['created_on']) < 20) { // checking if last post is new
$flag = 2;
}
}
}
$count = count($data);
$pageRange = 3;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Гостевая книга</title>
<link rel="stylesheet" href="bootstrap3/css/bootstrap.css">
<link rel="stylesheet" href="css/styles.css">
<!— <link rel="stylesheet" href="css/admin.css">-->
</head>
<body>

<div id="wrapper">
<h1>Гостевая книга</h1>

<?php include 'pagination.php'?>

<?php if ($flag == 1 ||$flag == 2) { // printing posts
$var = empty($_GET['page']) ? 1 : $_GET['page'];

for($i = ($var-1) * $pageRange; $i < $var * $pageRange; $i++ ) {
if(!empty($data[$i])) { ?>
<div class="note">
<p>
<span class="date"><?=date('H:i:s d.m.Y',$data[$i]['created_on'])?></span>
<span class="name"><?=$data[$i]['name']?></span>
</p>
<p><?=$data[$i]['text']?></p>
</div>
<? }}} ?>

<?php include 'pagination.php'?>

<?php if ($flag == 2) { ?>
<div class="info alert alert-info">
Запись успешно сохранена!
</div>
<? } ?>

<div id="form">
<form action="" method="POST">
<p><input class="form-control" name="name" placeholder="Ваше имя"></p>
<p><textarea class="form-control" name="text" placeholder="Ваш отзыв"></textarea></p>
<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
</form>
</div>
</div>
</body>
</html>