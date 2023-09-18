
<?php 
include 'header.php';

?>
<html>

<body style="background: url('background.jpg'); background-repeat: no-repeat;
  background-size: cover; "> 
<?php
if(isset($_SESSION["logged_in"])){
	header("Location: myruns.php");
	die();
}
?>
<div style="text-align: center">
<input type="hidden">
<div id="CENTER">
<h1>LOG MY RUN</h1>
<form action="login.php">
<input type="submit" value="GET STARTED">
</form>
</div>
</div>
</body>

</html>