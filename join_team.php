<html>
<body style="background: -webkit-linear-gradient(var(--color-2), var(--color-2-dark))">


<?php
include 'header.php';
if(!isset($_SESSION["logged_in"])){
	header("Location: index.php");
	die();
}
else if(isset($_POST["code"])){
	$team_exists = $conn->query("SELECT * FROM teams WHERE code='". $_POST["code"] ."'");
	if(mysqli_num_rows($team_exists) > 0){
		$user = findUserById($_SESSION["id"], $conn);
		$already_in_team = $conn->query("SELECT * FROM team_directory WHERE username='". $user["username"] . "' AND team_code='". $_POST["code"] ."'");
		if(mysqli_num_rows($already_in_team) == 0){
		$conn->query("INSERT INTO team_directory (username, team_code) VALUES ('". $user["username"] . "', '". $_POST["code"] . "')");
		}else{
			echo "<div style='text-align: center;'> <h1 style='color: white;'>Already part of this team</h1></div>";
		}
	}else{
		echo "<div style='text-align: center;'> <h1 style='color: white;'>Team does not exist</h1></div>";
	}
}else{
	?>
	<div style="margin: auto; background: -webkit-linear-gradient(white, rgb(200,200,200)); width: 30%; text-align: center; margin-top: 8%;">
		<div id="CENTER_2">
	<form action="join_team.php" method="POST">
	<h1>Join A Team </h1>
	<input type="text"name="code" placeholder="Team Code">
	<input type="submit" value="Join"> 
	</form>
	</div>
	</div>
	<?php
}
?>

</body>
</html>