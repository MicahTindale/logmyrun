<?php
$toShow = "week";
$totalMiles = 0;

?>
<html>
<body style="background: -webkit-linear-gradient(var(--color-2), rgb(130,0,0));">
<style>
.dropbtn {
  background: -webkit-linear-gradient(var(--color-2), var(--color-2-dark));
  color: white;
  padding: 7px;
  font-size: 16px;
  border: none;
  cursor: pointer;
  float: right;
  margin: 0px;
  font-weight: bold;
  border-radius: 10px 2px 10px 2px;
  box-shadow: 0px 1px 4px black;
	transition:  0.6s;
	width: 200px;
	
	}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  top: 40px;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
	font-family: Arial;
	font-weight: bold;
	font-size: 15px;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1;}
.dropdown:hover .dropdown-content {display: block;}
.dropbtn:hover {background: -webkit-linear-gradient(var(--color-1), rgb(200,200,200)); color: var(--color-2);}


.img2 {
	
}
.img2:hover{
	content: url("red_x.png");
}
</style>
<?php

if(isset($_GET["usr"])){
	$usr = findUserByUsername($_GET["usr"], $conn);
	if($_SESSION['logged_in'] == true || $usr['public'] == 1){
	    
	}else{
	    header("Location: login.php?lg=4");
	    die();
	}
}else{
	header("Location: myruns.php?lg=2");
	die();
}
	$toShow = "week";
	if(isset($_GET["slct"])){
		if($_GET["slct"] == "alltime" || $_GET["slct"] == "month" || $_GET["slct"] == "year" || $_GET["slct"] == "day"){
			$toShow = $_GET["slct"];
		}
	}
?>
<div style="display: flex;">


<div style="background: -webkit-linear-gradient(white, rgb(200,200,200)); width: 30%; height: 80%; text-align: center; padding: 10px; border-right: 4px solid gray; border-radius: 30px 10px 30px 10px; box-shadow: 0px 5px 10px black;">
<?php

echo '<img style="margin-bottom: 10px;border-radius: 30px 10px 30px 10px; box-shadow: 0px 3px 4px black; width: 70%;"src="data:image/png;base64,'.base64_encode($usr["profile"]).'"/>';
echo "<h3>". "Name: " . $usr["firstname"] . " " . $usr["lastname"] . "</h3>";
echo "<h3>" . "Location: " . $usr["location"] . "</h3>";
?>
<h2 style="font-size: 18px;">

</h2>


<?php
if($_SESSION['logged_in'] == false){
?>
<br> 
<br>
<hr>
<div style='font-size: 15px; font-family: Trebuchet MS; '>
    Notice: This website is owned and maintained by Micah Tindale. If you would like to report a problem or are interested in using this website for your team, please email micahtindalebusiness@gmail.com. Since this is a small operation, I can offer a highly personalized experience for any team interested.  
    
</div>
<?php 
}
?>

</div>




<div style="flex: 1;background: -webkit-linear-gradient(white, rgb(200,200,200)); text-align: center; box-shadow: 0px 5px 10px black; border-radius: 30px 10px 30px 10px;">

<div style="display: flex; width: 30%;">
<div style="text-align: left; margin-left: 40px; margin-bottom: 10px; padding: 0px; border-bottom: 4px solid var(--color-2-dark); "> 
<h1 style="font-weight: bold;font-size: 35px; margin-top: 30px; margin-bottom: 20px; margin-bottom: 0px; margin-right: 0px;"> RECENT RUNS </h1>
</div>
<div id="recent_run_header"style="flex: 1; text-align: center;">



<form id="frm" style="margin-top: 45px;"action="other_pages.php" method="GET">
<select id="thing"style="width: 300px;margin-top: 5px; text-align: center;"class="input"name="slct" onchange="this.form.submit()">
<option <?php if($toShow == "week"){ echo "selected = 'selected'"; } ?> value="week">Last Week</option>
<option <?php if($toShow == "month"){ echo "selected = 'selected'"; } ?> value="month">Last Month</option>
<option <?php if($toShow == "year"){ echo "selected = 'selected'"; } ?> value="year"> Last Year </option>
<option <?php if($toShow == "alltime"){ echo "selected = 'selected'"; } ?> value="alltime">All Runs</option>
</select>
<input type="hidden" id="nm" name="usr"> 
</form>
    <select class='input' style='width: 300px; margin-top: 5px;'id = "toDisplay"onchange="update()">
     <option value="both">View All Activities</option>
    <option value="runs">View Runs</option>
    <option value="other">View Non-Running Activities</option>
    </select>

</div>

</div>
<script>
function update(){
    var cont = document.getElementById("big_container");
    if(document.getElementById("toDisplay").value == "runs"){
    for(var i = 0; i < cont.childNodes.length; i++){
        if(cont.childNodes[i].nodeName == "DIV"){
           if(cont.childNodes[i].childNodes[0].value == 0){
              cont.childNodes[i].style.display = "block";
            }else{
                cont.childNodes[i].style.display = "none";
            }
        }
    }
    }
    if(document.getElementById("toDisplay").value == "other"){
    for(var i = 0; i < cont.childNodes.length; i++){
        if(cont.childNodes[i].nodeName == "DIV"){
           if(cont.childNodes[i].childNodes[0].value == 0){
              cont.childNodes[i].style.display = "none";
            }else{
                cont.childNodes[i].style.display = "block";
            }
        }
    }
    }
    if(document.getElementById("toDisplay").value == "both"){
    for(var i = 0; i < cont.childNodes.length; i++){
        if(cont.childNodes[i].nodeName == "DIV"){
          cont.childNodes[i].style.display = "block";
        }
    }
    }
}    
    </script>
<div id="big_container">
<?php
$result = fetchRunsByPerson($conn, $toShow, $usr["id"]);
while($row = $result->fetch_assoc()){
	echo "<div style='border-radius: 0px 10px 0px 10px; box-shadow: 0px 5px 15px black; margin: 10px; border: 3px solid rgb(200,200,200)'>";
echo "<input type='hidden' value= '" . $row['type'] . "' >"; 
    $txt = $row['distance'] . " Miles";
     if($row["type"] == 3){
        $txt = "Core";
    }else if($row["type"] == 1){
        $txt = "Aqua Jogging";
    }else if($row["type"]== 2){
        $txt = "Biking";
    }else if($row["type"] == 4){
        $txt = "Other";
    }
    echo "<div id='recent_run' style='display: flex; position: relative;'>";
	echo "<div style='display: flex;width: 97%;'>";
	echo "<div style='box-shadow: 0px 2px 5px black;width: 300px; margin-right: 40px; background: -webkit-linear-gradient(var(--color-1), rgb(200,200,200));'>";
	echo "<h2 style='font-size:19px;'>";
	echo "<span style='font-size: 18px;font-weight: bold;float: right;padding: 10px;color: var(--color-2); background: -webkit-linear-gradient(var(--color-1), rgb(200,200,200));'>" . $txt  . "</span><span style='font-size: 18px;float: left;flex: 1;color: white; background: -webkit-linear-gradient(var(--color-2), var(--color-2-dark)); padding: 10px;'>" . date("F j, Y", strtotime($row["date"])) . "</span>";
	echo "</h2>";
	echo "</div>";
	echo "<div style='flex:1; text-align: center;'>";
	echo "<h2 style='font-size: 25px;'>" . $row["location"] . "</h2>";
	echo "</div>";
	$totalMiles += $row["distance"];
?> 
	

	<?php
	echo "</div>";
	
	echo "</div>";
	?>
	<div id="desc">
	<?php echo "<h2>" . $row["description"] . "</h2>"; 
		if(!checkEmpty(unserialize($row["companions"]))){
	echo "<h2> People: ";
	$num = 0;
	foreach(unserialize($row["companions"]) as $value){
		$num++;
		if($num < sizeOf(unserialize($row["companions"]))){
		echo $value . ", ";
		}else{
			echo $value;
		}
	}
	echo "</h2>";
	}
	?>
	</div>
	</div>
	<?php
	}

?>

<script>
document.getElementById("nm").value = "<?php echo $_GET["usr"] ?>";
window.onload = function(){
var thing = document.getElementById("thing");
 for ( var i = 0, len = thing.options.length; i < len; i++ ) {
            opt = thing.options[i];
            if ( opt.selected === true ) {
				opt.innerHTML += " (<?php echo $totalMiles ?> miles)";
                break;
            }
        }
}
</script>
</div>
</body>
</html>