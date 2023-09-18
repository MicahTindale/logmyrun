<?php
include "header.php";
?>
<html>
<body style="background: -webkit-linear-gradient(var(--color-2), rgb(130,0,0));">
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<div style="margin: 0 auto; text-align: center; width: 30%;">
<!-- <form id="search"method="GET" action="other_pages.php">
<input name="usr"style="float: center;color: black; text-align: center; width: 250px; height: 30px;"placeholder="USERNAME"type="text">
<input style="float: center;color: black; text-align: center; width: 250px; height: 30px;" type="submit" value="SEARCH"> 
--></form>
</div>
<div style="background: -webkit-linear-gradient(var(--color-1), rgb(200,200,200)); box-shadow: 0px 5px 5px black; border-radius: 10px 30px 10px 30px; padding: 20px;">

<?php
if(isset($_GET["usr"])){
include "view_page.php";	
}else{
    ?>

	<?php
	$teamQuery = $conn->query("SELECT * FROM teams");
	$localuser = findUserById($_SESSION["id"], $conn);
	while($value = $teamQuery->fetch_assoc()){
	    ?>
	    <?php
		$inTeamQuery = $conn->query("SELECT * FROM team_directory WHERE team_code='". $value["code"] . "' AND username='". $localuser["username"] ."'");
		if(mysqli_num_rows($inTeamQuery) != 0){
		echo "<h2 id='h22' style='color: black;'>" . $value["name"] . ": Team Code - " . $value["code"] . "</h2>";

		?> 
		
		
			<table style="width: 100%;">
			<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th> Role </th>
			<th>View</th>
			</tr>
			
		<?php
		$findMembers = $conn->query("SELECT * FROM team_directory WHERE team_code='". $value["code"] ."'");
		echo mysqli_error($conn);
		while($row = $findMembers->fetch_assoc()){
		$user = findUserByUsername($row["username"], $conn);
        if($user["show_on_page"] == 0){
		echo "<tr>";
 		echo "<th>" . $user["firstname"] ."</th>";
		echo "<th>" . $user["lastname"] ."</th>";
		echo "<th>" . $user["username"] . "</th>";
		if($user["permission"] == 0){
			echo "<th>Athlete</th>";
		}
		if($user["permission"] == 1){
			echo "<th style='font-weight: bold; color: red;'>Coach</th>";
		}
		echo "<th> <a href='other_pages.php?usr=".$user['username'] . "'>View Log</a></th>";
		echo "</tr>";
		}
        }
		echo "</table>";
		echo "<br>";
        

	}
	?>
	<?php
	}
}
?>
	</div>

</body>
</html>