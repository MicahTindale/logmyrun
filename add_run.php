<html>
<?php
include 'header.php';
    $tpe = "0";
    $toShow = "Run";
    if(isset($_GET["type"])){
        $toShow = $_GET["type"];
    }
    if($toShow == "aj"){
        $tpe = 1;
    }else if($toShow=="Biking"){
        $tpe = 2;
    }else if($toShow=="Core"){
        $tpe = 3;
    }else if ($toShow =="Other"){
        $tpe = 4;
    }
    
if(!isset($_SESSION["logged_in"])){
	header("Location: index.php");
	die();
}else if(isset($_POST["people_list"]) && isset($_POST["date"]) && isset($_POST["distance"]) && isset($_POST["location"]) && isset($_POST["description"])){
    $date = strtotime($_POST["date"]);
    if($_POST["update"] == "no"){
	$arr = explode(";", $_POST["people_list"]);
	$x = $conn->query("INSERT INTO runs (distance, location, description, date, companions, user_id, type) VALUES ('" .$_POST["distance"]."', '". mysqli_real_escape_string($conn, $_POST["location"])."', '". mysqli_real_escape_string($conn, $_POST["description"]) ."', '". $_POST['date'] . "', '" . mysqli_real_escape_string($conn, serialize($arr)) ."', '".$_SESSION["id"]."', '".$_POST["tpe"]."')");
	if(! $x){
		echo mysqli_error($conn);
	}else{
		header("Location: index.php");
		die();
	}
    }else{
        $arr = explode(";", $_POST["people_list"]);
	$x = $conn->query("UPDATE runs SET distance = '". $_POST['distance'] . "', location = '" . mysqli_real_escape_string($conn, $_POST['location']) ."', description = '" . mysqli_real_escape_string($conn, $_POST['description']) . "', date = '". $_POST['date'] ."', companions = '". mysqli_real_escape_string($conn, serialize($arr)) ."' WHERE id='". $_POST["idthing"] . "'");
	if(! $x){
		echo mysqli_error($conn);
	}else{
		header("Location: index.php");
		die();
	}
    }
}
    
    
?>
<body style="background: -webkit-linear-gradient(var(--color-2), var(--color-2-dark))">
<div style="text-align: center; margin: 0 auto;width: 80%; background: -webkit-linear-gradient(var(--color-1), rgb(200,200,200)); padding: 10px; box-shadow: 0px 5px 10px black; border-radius: 30px 10px 30px 10px;">
<h1 id="title">
ADD ACTIVITY
</h1>
   <form id="frm"action="add_run.php" method="GET">
<select class="input"id="type" name="type" onchange="this.form.submit()"> 
    <option <?php if($toShow == "Run"){ echo "selected = 'selected';"; } ?> value="Run">Run</option>
    <option <?php if($toShow == "aj"){ echo "selected = 'selected'"; } ?> value="aj">Aqua Jogging</option>
    <option <?php if($toShow == "Biking"){ echo "selected = 'selected'"; } ?> value="Biking">Biking</option>
    <option <?php if($toShow == "Core"){ echo "selected = 'selected'"; } ?> value="Core">Core</option>
    <option <?php if($toShow == "Other"){ echo "selected = 'selected'"; } ?> value="Other">Other</option>
</select>
</form>
<form onsubmit="updatePeople(); return confirm('Are you sure you want to add this run?');"id="my_profile"action="add_run.php" method="POST">
<input type="hidden"name="people_list" id="people_list">
<div style=" margin: auto; width: 50%;">

<?php
echo "<div style='text-align: left; margin-left: 15%;'>";
echo "<h2 id='d2'style='font-size: 15px; color: black; font-weight: bold;'>Distance (miles)</h2>";
echo "</div>";
echo "<input id='distance'name='distance' placeholder='Miles'type='number' step='0.01' value='' required><br>";
echo "<div style='text-align: left; margin-left: 15%;'>";
echo "<h2 style='font-size: 15px; color: black; font-weight: bold;'>Location</h2>";
echo "</div>";
echo "<input id='location'name='location' type='text' placeholder='Location' required><br>";
echo "<div style='text-align: left; margin-left: 15%;'>";
echo "<h2 style='font-size: 15px; color: black; font-weight: bold;' required>Description</h2>";
echo "</div>";
echo "<textarea id='desc'rows='4' cols='40' name='description'></textarea><br>";
echo "<div style='text-align: left; margin-left: 15%;'>";
echo "<h2 style='font-size: 15px; color: black; font-weight: bold;'>Date</h2>";
echo "</div>";
echo "<input id='date'type='date' name='date'>";
echo "<div style='text-align: left; margin-left: 15%;'>";
echo "<h2 style='font-size: 15px; color: black; font-weight: bold;'>People";
echo "</div>";
?>
<input type="button" onclick="remove_last();"style="width: 80px; height: 20px; font-size: 12px; font-weight: bold; float: right; margin-top: 0px; margin-right: 155;"value="REMOVE">
<input type="button" onclick="add_person();"style="width: 80px; height: 20px; font-size: 12px; font-weight: bold; float: right; margin-top: 0px; margin-right: 0px;"value="ADD"> <br>
<input type="hidden" name="update" id="update" value="no">
<input type="hidden" name="idthing"id="idthing" value="x">
<input type="hidden" name="tpe" value="<?php echo $tpe ?>">    
    <br>
<?php
echo "</h2></div>";
?>
<div id="container">

</div>
    
<?php
if($toShow != "Run"){
    ?>
    <script>
    document.getElementById("distance").value = 0;
    document.getElementById("distance").style.display="none";
        document.getElementById("d2").style.display="none";
    </script>
    <?php
} 
?>

    
   
<script>
function updatePeople(){
	var hidden = document.getElementById("people_list");
	var div = document.getElementById("container");
	hidden.value = "";
	for(var i = 0; i < div.childNodes.length; i++){
		if(div.childNodes[i].childNodes[0] != undefined){
		hidden.value += div.childNodes[i].childNodes[0].value;
		if(i < div.childNodes.length -1){
			hidden.value += ";";
		}
		}
	}
	console.log(hidden.value);
}
add_person();
function add_person(){
	var div = document.getElementById("container");
	var toAdd = document.createElement("div");
	toAdd.className = "person";
	var toAddtoAdd = document.createElement("input");
	toAddtoAdd.type = "text";
	toAddtoAdd.required = true;
	toAddtoAdd.placeholder = "PERSON" + (div.children.length + 1);
	toAdd.appendChild(toAddtoAdd);
	div.appendChild(toAdd);
	updatePeople();

}
function remove_last(){
		var div = document.getElementById("container");
div.removeChild(div.lastChild);
updatePeople();
}


</script>
    
 <?php
     if(isset($_GET["id"])){
         
        $run = getRun($_GET["id"], $conn);
         if($_SESSION['id'] != $run["user_id"]){
             header("Location: index.php");
             die();
         }
          $arr3 = unserialize($run["companions"]);
        ?>
        <script>
            document.getElementById("idthing").value = "<?php echo $run["id"] ?>";
            document.getElementById("update").value = "yes";
            document.getElementById("title").innerHTML = "Update Run";
            document.getElementById("distance").value = "<?php echo $run["distance"] ?>";
            document.getElementById("location").value = "<?php echo $run["location"] ?>";
            document.getElementById("desc").value = "<?php echo $run["description"] ?>";
            document.getElementById("date").value = "<?php echo $run["date"] ?>";
            var div = document.getElementById("container");
            div.innerHTML = "";

            <?php
            foreach($arr3 as $value){
                ?>
                var toAdd = document.createElement("div");
	           toAdd.className = "person";
	           var toAddtoAdd = document.createElement("input");
                toAddtoAdd.type = "text";
                    toAddtoAdd.required = true;
                toAddtoAdd.value = "<?php echo $value ?>";
	           toAddtoAdd.placeholder = "PERSON" + (div.children.length + 1);
	           toAdd.appendChild(toAddtoAdd);
                div.appendChild(toAdd);
	           updatePeople();
            <?php
            }
            ?>
            
        </script>
        <?php
         
    }
?>

<input id='subber'type="submit" value="Add Run"> 
    <script>
    <?php if(isset($_GET["id"])){ ?>
    document.getElementById("subber").value = "Update Activity";    
    <?php } ?>
    </script>
</div>
</form>

</div>

</body>
</html>