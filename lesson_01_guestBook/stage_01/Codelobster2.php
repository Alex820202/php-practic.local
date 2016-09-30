
<html>
<head>
<?php
//$data = $_POST['data'];
if($data)	{
	echo $data['data'];
	
?>
<script type="text/javascript">
<!--
location.replace("Codelobster2.php");
//-->
</script>
<noscript>
<meta http-equiv="refresh" content="0; url=Codelobster2.php">
</noscript>
<?php
echo $data['data'];
}
?>
</head>
<body>
	<form action="Codelobster2.php" method="post">
		<input value="Данные" type="text" name="data" >
		<input type="submit" value="Ok">
	</form>
</body>
</html>