<!DOCTYPE html>
<html>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
include "header.php";
echo "Working";

?>
<body style="background: url('background.jpg')">
<?php

if(isset($_POST["username"]) && isset($_POST["password"])){
	$user = findUser($_POST["username"], $_POST["password"], $conn);
	$_SESSION["logged_in"] = true;
	$_SESSION["id"] = $user["id"];
	$_SESSION["personal_records"] = unserialize($user["personal_records"]);
	$_SESSION["first_name"] = $user["firstname"];
	$_SESSION["last_name"] = $user["lastname"];
	$_SESSION["school"] = $user["location"];
	$_SESSION["profile"] = $user["profile"];
	header("Location: index.php");
	echo $user;
	die();
}


else if(isset($_SESSION["logged_in"])){
	header("Location: index.php?lg=1");
	die();
}else{
	
	?>
	<div style="margin: auto; background: -webkit-linear-gradient(white, rgb(200,200,200)); width: 30%; text-align: center; margin-top: 8%;">
		<div id="CENTER_2">
			<h1 >LOG IN</h1>
		<form method="POST" action="login.php"style="margin: 0px;">
			<input type="text"placeholder="USERNAME" name="username">
			<input type="password"placeholder="PASSWORD" name="password">
			<input type="submit" value="LOG IN"> 
			</form>
		</div>
	</div>
	
	
	
	<?php
}
?>
	</body>
	</html>