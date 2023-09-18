<html>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
	var dat = [['date', 'distance']];
    var dat2 = [['date', 'distance']];
            var dat3 = [['week', 'distance']];

	</script>
<?php include "header.php"; 

if(isset($_GET["usr"])){
	
	echo "<div style='text-align: left;'>";
	echo "<div style='text-align: center;'>";
	$user = findUserByUsername($_GET['usr'], $conn);
	echo "<div style='text-align: center;'>";
	echo "<div style='width: 300px; margin: 0 auto;'>";
	echo '<img width= "100px" style="width: 100px;margin-bottom: 10px;border-radius: 30px 10px 30px 10px; box-shadow: 0px 3px 4px black; width: 70%;"src="data:image/png;base64,'.base64_encode($user["profile"]).'"/>';
	echo "</div>";
		echo "</div>";

	echo "<br>";
	echo "<span style='font-family: Trebuchet MS;font-weight: bold; font-size: 30px; color: rgb(255, 55, 0); '>";
	echo "Name: " . $user['firstname'] . " " . $user['lastname'] . "<br>";
	echo "Location: " . $user['location'] . "<br><br>";
	echo "</span>";
	echo "</div>";
	$runs = fetchRunsByUsername($conn, "2020-6-15", "2020-08-23", $_GET["usr"]);
	$arr = array();
	echo "<span style='color: red; font-weight: bold;'> </span>";
	$totalMileage = 0;
	while($row = $runs->fetch_assoc()){
		$totalMileage += $row['distance'];
		if(!isset($arr[$row['date']])){
		$arr[$row['date']] = $row;
		}else{
		$arr[$row['date']]['distance'] += $row['distance'];	
		}
	}
    $highestWeek = 0;
    $highestDay = 0;
	foreach($arr as $value){
        $week = getWithinWeek($arr, $value['date']);
 		echo "<script>";
		echo "var thing = ['" . $value['date'] . "'," . $value['distance'] . "];";
		echo "dat.push(thing);";
		echo "var thing2 = ['" . $value['date'] . "'," . getWithinWeekLast($arr, $value['date']) . "];";
        echo "dat2.push(thing2);";
        
		echo "</script>";
		if($week > $highestWeek){
            $highestWeek = $week;
        }
        if($value['distance'] > $highestDay){
            $highestDay = $value['distance'];
        }
        
        
        
	}
          
       echo "<br>";
    echo "<div style='font-size: 20px; text-align: center;'>";
    echo "Summer Mileage - 6/15/2020 through 8/23/2020 <br>";
    echo "Highest Mileage In 7 Days: <span style='font-weight:bold; color: blue;'>" . $highestWeek . " Miles</span><br>";
    echo "Highest Mileage Day: <span style='font-weight:bold; color: blue;'>" . $highestDay . " Miles</span><br>";
	echo "Total Summer Mileage: <span style='font-weight:bold; color: blue;'> " . $totalMileage . "</span>";
    echo "</div>";
	
	?>
	</div>
	
		<script>
		window.onload = function(){
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart(){
		  var data = google.visualization.arrayToDataTable(dat);
        var data2 = google.visualization.arrayToDataTable(dat2);
        var data3 = google.visualization.arrayToDataTable(dat3);

		 var options = {
          title: 'Summer Mileage (Daily)',
          curveType: 'function',
          legend: { position: 'bottom' }		  
        };
        var options2 = {
          title: 'Summer Mileage (Last 7 Days Continuous)',
          curveType: 'function',
          legend: { position: 'bottom' }		  
        };
              var options3 = {
          title: 'Summer Mileage (Weekly)',
          curveType: 'function',
          legend: { position: 'bottom' }		  
        };
        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
			chart.draw(data, options);
        var chart2 = new google.visualization.LineChart(document.getElementById('curve_chart2'));
			chart2.draw(data2, options2);
		
        var chart3 = new google.visualization.LineChart(document.getElementById('curve_chart3'));
			chart3.draw(data3, options3);
		}
}
	</script>
	<?php
	
}

    function getWithinWeek($arr, $date){
        $mileage = 0;
        foreach($arr as $value){
            if(check_in_range($date, $value['date'])){
                $mileage += $value["distance"];
            }
        }
        return $mileage;
    }
     function getWithinWeekLast($arr, $date){
        $mileage = 0;
        foreach($arr as $value){
            if(check_in_range2($date, $value['date'])){
                $mileage += $value["distance"];
            }
        }
        return $mileage;
    }
    function check_in_range($start_date, $date_from_user)
{
  // Convert to timestamp
    
    
  $start_ts = strtotime($start_date);
  $end_ts = strtotime(date("Y-m-d", strtotime($start_date. ' + 6 days')));
  $user_ts = strtotime($date_from_user);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}
    function check_in_range2($start_date, $date_from_user)
{
  // Convert to timestamp
    
    
  $end_ts = strtotime($start_date);
  $start_ts = strtotime(date("Y-m-d", strtotime($start_date. ' - 6 days')));
  $user_ts = strtotime($date_from_user);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}
    
?>

<body>
<div id="curve_chart" style="width: 100%; height: 500px;"> </div>
    <div id="curve_chart2" style="width: 100%; height: 500px;"> </div>
    <div id="curve_chart3" style="width: 100%; height: 500px;"> </div>

</body>
</html>