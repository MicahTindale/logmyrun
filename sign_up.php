<!DOCTYPE html>
<html>
<?php
include "header.php";
?>
<body style="background: url('background.jpg')">
<?php

if(isset($_POST["username"]) && isset($_POST["password"])){
    if(strpos($_POST["username"], '@') == true) {
     echo "<h1>You cannot have the @ symbol in your username</h1>";   
    }else{
	if(accExists($conn, $_POST["username"])){
		echo "Account already exists";
	}	else{
		$team_code = "";
		if(isset($_POST["team_codes"])){
			$team_code = $_POST["team_codes"];
		}
		create_account($conn, $_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT),$_FILES['image']['tmp_name'], $_POST["email"], $_POST["firstname"], $_POST["lastname"], $_POST["location"], $team_code);
		echo "Account created";
	}
    }
}else{
	
	?>
	<div style="margin: auto; background: -webkit-linear-gradient(white, rgb(200,200,200)); width: 30%; text-align: center; margin-top: 8%;">
		<div id="CENTER_2">
			<h1 >REGISTER</h1>
		<form method="POST" action="sign_up.php"style="margin: 0px;" enctype="multipart/form-data">
			<input type="text"placeholder="First Name" name="firstname" required>
			<input type="text"placeholder="Last Name" name="lastname" required>
			<input type="text"placeholder="Username" name="username" required>
			<input type="password"placeholder="Password" name="password" id="pass" required>
			<input type="password"placeholder="Confirm Password" name="confirm_password" id="confirm" required>
			<div id="message_box"> </div>
			<input type="text" placeholder="Location" name="location" required>
			<input type="text" placeholder="Email Address" name="email" id="email" required> 
			<input type="text" placeholder="Team Code (Leave blank if none)" name="team_codes"> 
			<div style="margin-top: 30px;">Select a profile image:</div>
			<input type="file" name="image" accept="image/*" required>
			<input type="submit" disabled="true"id="sub" value="Create An Account"> 
			
			</form>
		</div>
	</div>
	<script>
		document.getElementById("confirm").onchange = function(){
			var pass = document.getElementById("pass").value;
			var conf = document.getElementById("confirm").value;
			if(pass == conf){
				document.getElementById("sub").disabled = false;
				document.getElementById("message_box").innerHTML = "";
			}else{
				document.getElementById("sub").disabled = true;
				document.getElementById("message_box").innerHTML = "Your passwords do not match";
			}
		};
		document.getElementById("pass").onchange = function(){
			var pass = document.getElementById("pass").value;
			var conf = document.getElementById("confirm").value;
			if(pass == conf){
				document.getElementById("sub").disabled = false;
				document.getElementById("message_box").innerHTML = "";
			}else{
				document.getElementById("sub").disabled = true;
				document.getElementById("message_box").innerHTML = "Your passwords do not match";
			}
		};
	</script>
	
	
	<?php
}
?>
	</body>
	</html>