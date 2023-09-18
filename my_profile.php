<html>
<body style="background: -webkit-linear-gradient(var(--color-2), var(--color-2-dark))">


<?php
include 'header.php';
if(!isset($_SESSION["logged_in"])){
	header("Location: index.php");
	die();
}else if(isset($_FILES["image"]["tmp_name"]) && isset($_POST["shouldChange"])){
	echo "updating image";
		$image_contents = addslashes(file_get_contents($_FILES['image']['tmp_name']));
		$conn->query("UPDATE users SET profile = '". $image_contents . "' WHERE id='". $_SESSION["id"] ."'");
		echo mysqli_error($conn);
		updateSession($conn);
		header("Location: my_profile.php");
		die();
}else if(isset($_POST["firstname"])){
	$conn->query("UPDATE users SET firstname = '".$_POST["firstname"] . "', lastname='". $_POST["lastname"] . "' WHERE id='".$_SESSION["id"] . "'");
	updateSession($conn);
	header("Location: myruns.php");
		die();
}else{
	$user = findUserById($_SESSION["id"], $conn);
	?>
	<div style="margin: auto; background: -webkit-linear-gradient(white, rgb(200,200,200)); width: 30%; text-align: center; margin-top: 8%;">
		<div id="CENTER_2">
	<?php 
	echo '<img style="margin-top: 10px;margin-bottom: 10px;border-radius: 30px 10px 30px 10px; box-shadow: 0px 3px 4px black; width: 70%;"src="data:image/png;base64,'.base64_encode($_SESSION["profile"]).'"/>';
	?>
	<form method="POST" action="my_profile.php">
	First Name<input type="text" placeholder="First Name" name="firstname" value="<?php echo $user["firstname"]; ?>">
	Last Name <input type="text" placeholder="Last Name" name="lastname" value="<?php echo $user["lastname"]; ?>">
	<input type="submit" value="Save Changes">
	</form>
	<form action="my_profile.php"method="POST"enctype="multipart/form-data">
		Change Profile Image <input type="file" name="image" accept="image/*"onchange="submit();">
		<input type="hidden" name="shouldChange" value="hasValue">
	</form>
	
	</div>
	</div>
<?php
	}
?>

</body>
</html>