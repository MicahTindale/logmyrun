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

<div>
<?php
 $result = $conn->query("SELECT * FROM routines");
    while($row = $result->fetch_assoc()){
        echo "<h3 style='color: white; gradient: none;'>". $row['name']."</h3>";
        $arr = unserialize($row["routine"]);
        echo "<div style='border-bottom: 2px solid white; color: white; font-family: Trebuchet MS; font-weight: bold;'>";
        foreach($arr as $value){
          echo $value;
          echo "<br>";
        }
         echo "</div>";
        echo "<br>";
    }
?>

</div>


</body>
</html>