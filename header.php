<html class="fadeIn">
<?php
session_start();


$servername = "localhost";
$username = "logmyrun_micah3";
$password = "123abc45612";

$conn = new mysqli($servername, $username, $password, "logmyrun_logmyrun");
if($conn->connect_error){
	die("Connection failed: " . connect_error);
}
function connected(){
	return isset($_SESSION["logged_in"]);
}
function date_sort($a, $b){
	return strtotime($a["date"]) - strtotime($b["date"]);
}
function getTeams($conn){
	$result = $conn->query("SELECT * FROM teams");
	return $result;
}
function getMyTeams($id, $conn){
	$usr = findUserById($id, $conn);
	$find_teams = $conn->query("SELECT * FROM team_directory WHERE username='". $usr["username"] . "'");
	$arr = [];
	while($row = $find_teams->fetch_assoc()){
		$team = $conn->query("SELECT * FROM teams WHERE code='". $row["team_code"] ."'");
		while($row = $team->fetch_assoc()){
		array_push($arr, $row);
		}
	}
	return $arr;
}
function create_account($conn, $username, $password, $profile_pic, $email, $firstname, $lastname, $location, $team_code){

	$query = $conn->query("INSERT INTO users (username, password, personal_records, profile, firstname, lastname, location) VALUES ('". $username. "', '". $password ."', 'a:0:{}', '". addslashes(file_get_contents($profile_pic)) ."', '" . $firstname . "', '" . $lastname ."', '". $location ."')") ;
	echo mysqli_error($conn);
	
	if($conn){
	if($team_code != ""){
		$query2 = $conn->query("INSERT INTO team_directory (username, team_code) VALUES ('". $username . "', '". $team_code . "')");
			echo mysqli_error($conn);

	}
	}
}
function getRun($id, $conn){
    $result = $conn->query("SELECT * FROM runs WHERE id='" . $id . "'");
    while($row = $result->fetch_assoc()){
        return $row;
        break;
    }
    return null;
}
function accExists($conn, $username){
   $account_exists = $conn->query("SELECT * FROM users WHERE username='". $username . "'");
   if(mysqli_num_rows($account_exists) > 0){
	   return true;
}else{
	return false;	
}
}
function findUser($us, $pw, $conn){
	$result = $conn->query("SELECT * FROM users WHERE username = '" . $us . "'");
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			if(password_verify($pw, $row["password"])){
				return $row;
			}else{
				header("Location: login.php?lg=3");
				die();
			}
			break;
		}
	}else{
			header("Location: login.php?lg=2");
			die();
	}
}
function findUserById($id, $conn){
	$result = $conn->query("SELECT * FROM users WHERE id = '" . $id . "'");
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
				return $row;
			break;
		}
	}else{
			header("Location: login.php?lg=2");
			die();
	}
}

function findUserByUsername($usr, $conn){
	$result = $conn->query("SELECT * FROM users WHERE username = '" . $usr . "'");
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
				return $row;
			break;
		}
	}else{
			header("Location: myruns.php?lg=2");
			die();
	}
}


if(isset($_POST["lgout"])){
	session_unset();
	header("Location: index.php");
	die();
}

function allDataLoaded(){
	return isset($_SESSION["logged_in"]) && isset($_SESSION["id"]) && isset($_SESSION["personal_records"]) && isset($_SESSION["first_name"]) && isset($_SESSION["last_name"]) && isset($_SESSION["school"]) && isset($_SESSION["profile"]) && isset($_SESSION["permission"]);
}
function loadAllData(){
	$user = findUser($_POST["username"], $_POST["password"], $conn);
	$_SESSION["logged_in"] = true;
	$_SESSION["id"] = $user["id"];


	$_SESSION["personal_records"] = unserialize($user["personal_records"]);
	$_SESSION["first_name"] = $user["firstname"];
	$_SESSION["last_name"] = $user["lastname"];
	$_SESSION["school"] = $user["location"];
	$_SESSION["profile"] = $user["profile"];
}

function updateSession($conn){
	$user = findUserById($_SESSION["id"], $conn);
	$_SESSION["logged_in"] = true;
	$_SESSION["id"] = $user["id"];
        $_SESSION["permission"] = $user["permission"];
	$_SESSION["personal_records"] = unserialize($user["personal_records"]);
	$_SESSION["first_name"] = $user["firstname"];
	$_SESSION["last_name"] = $user["lastname"];
        $_SESSION["permission"] = $user["permission"];
	$_SESSION["school"] = $user["location"];
	$_SESSION["profile"] = $user["profile"];
}
function getLeaderboard($team_code, $conn, $time){
	$arr = array();
	$find_members = $conn->query("SELECT * FROM team_directory WHERE team_code='".$team_code."'");
	while($row = $find_members->fetch_assoc()){
		$user = findUserByUsername($row["username"], $conn);
		$runsintime = $conn->query("SELECT * FROM runs WHERE user_id = '".$user['id']."' AND date >= DATE_SUB(curdate(), INTERVAL ". $time . " DAY) AND type=0");
		$totalMiles = 0;
		while($row2 = $runsintime->fetch_assoc()){
			$totalMiles += $row2["distance"];
		}
		$arr[$user['username']] = $totalMiles;
	}
	arsort($arr);
	return $arr;
}
function getSummerLeaderboard($team_code, $conn){
	$arr = array();
	$find_members = $conn->query("SELECT * FROM team_directory WHERE team_code='".$team_code."'");
	while($row = $find_members->fetch_assoc()){
		$user = findUserByUsername($row["username"], $conn);
		$runsintime = $conn->query("SELECT * FROM runs WHERE user_id = '".$user['id']."' AND date >= '2020-06-15' AND date <= '2020-08-23' AND type=0");
		$totalMiles = 0;
		while($row2 = $runsintime->fetch_assoc()){
			$totalMiles += $row2["distance"];
		}
		$arr[$user['username']] = $totalMiles;
	}
	arsort($arr);
	return $arr;
}

function fetchRuns($conn, $toShow){
	$toAdd = "";
	$interval = 0;
	if($toShow != "alltime"){
	if($toShow == "week"){
		$toAdd = "AND date >= DATE_SUB(curdate(), INTERVAL 6 DAY)";
		$interval = 6;
	}else if ($toShow == "year"){
		$toAdd = "AND date >= DATE_SUB(curdate(), INTERVAL 365 DAY)";
		$interval = 365;
	}else if ($toShow == "month"){
				$toAdd = "AND date >= DATE_SUB(curdate(), INTERVAL 31 DAY)";
				$interval = 30;

	}
	$qu = "SELECT * FROM runs WHERE user_id = '" . $_SESSION["id"] . "' AND date >= DATE_SUB(curdate(), INTERVAL ". $interval . " DAY) ORDER BY date DESC";
	$result = $conn->query($qu);
	return $result;
	}else{
	    	$qu = "SELECT * FROM runs WHERE user_id = '" . $_SESSION["id"] . "' ORDER BY date DESC";
	$result = $conn->query($qu);
	return $result;
	}
}
function fetchRunsByPerson($conn, $toShow, $id){
	$toAdd = "";
	if($toShow == "week"){
		$toAdd = "AND date >= DATE_SUB(curdate(), INTERVAL 6 DAY)";
	}else if ($toShow == "year"){
		$toAdd = "AND date >= DATE_SUB(curdate(), INTERVAL 365 DAY)";
	}else if ($toShow == "month"){
				$toAdd = "AND date >= DATE_SUB(curdate(), INTERVAL 31 DAY)";
	}
	$qu = "SELECT * FROM runs WHERE user_id = '" . $id . "' " . $toAdd . " ORDER BY date DESC";
	$result = $conn->query($qu);
	return $result;
}
function fetchRunsByUsername($conn, $firstDate, $lastDate, $username){
	$user = findUserByUsername($username, $conn);
	$qu = "SELECT * FROM runs WHERE user_id = '" . $user['id'] . "' AND date >= '".$firstDate."' AND date <= '".$lastDate."' ORDER BY date ASC";
	$result = $conn->query($qu);
	return $result;
}
function fetchRunsByIDUpdated($conn, $firstDate, $lastDate, $username){

	$qu = "SELECT * FROM runs WHERE user_id = '" . $username . "' AND date >= '".$firstDate."' AND date <= '".$lastDate."' ORDER BY date ASC";
	$result = $conn->query($qu);
	return $result;
}

function checkEmpty($arr){
	$toReturn = true;
	foreach($arr as $value){
		if($value == ""){
			
		}else{
		$toReturn = false;
		}
	}
	
	return $toReturn;
}
?>


<link rel="stylesheet" type="text/css" href="style.css">
	<div id="head">
		<a href="index.php"> Home </a>
		
		<?php
		if(!isset($_SESSION["logged_in"])){
			?> <div style="float:right;"><a href="login.php">Log In</a> </div>
				<div style="float:right;"><a href="sign_up.php">Sign Up</a> </div><?php
						
		}else{
			?> <form action = "header.php" method="POST"style="display:none;" id="frm"> <input type="hidden" name="lgout"></form>
				<a href="other_pages.php">View Other Logs</a>
				<a href="routines.php">Exercise Routines</a>
				<div style="float: right; ">
				<a href="join_team.php"> Join A Team</a>
				<a href="my_profile.php"> My Profile </a>
				<a onclick = "logout();" >Log Out</a>
				</div>
				<script>
				function logout(){
					document.getElementById("frm").submit();
					console.log("logging_out");
				}
				</script>
			<?php
		}
		//CHECKING FOR ERR MESSAGE
		if(isset($_GET["lg"])){
		if($_GET["lg"] == 1){
		echo "<div id='err' class = 'fadeOut'>";
		echo "You are already logged in <br>";
		echo "</div>";
		}else if($_GET["lg"] == 2){
		echo "<div id='err' class = 'fadeOut'>";
		echo "ERROR: USER CANNOT BE FOUND<br>";
		echo "</div>";
		}else if($_GET["lg"] == 3){
		echo "<div id='err' class = 'fadeOut'>";
		echo "ERROR: INCORRECT PASSWORD<br>";
		echo "</div>";
		}else if($_GET["lg"] == 4){
		echo "<div id='err' class = 'fadeOut'>";
		echo "You must log in to see this training log<br>";
		echo "</div>";
		}
		}
		?>
	</div>