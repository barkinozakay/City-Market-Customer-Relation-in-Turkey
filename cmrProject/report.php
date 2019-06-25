<?php

$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "barkin_ozakay";

// Connecting to MySQL.
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql1 = "SELECT district.district_name, COUNT(sale_id)
		FROM SALES
		LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
		LEFT JOIN city ON cmr.city_id = city.city_id
		LEFT JOIN DISTRICT ON city.district_id = district.district_id
		GROUP BY district.district_name";

$result1 = mysqli_query($conn,$sql1) or die("Error");

$data_districts = array();

while($row = mysqli_fetch_array($result1)){        
    $point = array("label" => $row['district_name'] , "y" => $row['COUNT(sale_id)']);
    array_push($data_districts, $point);        
}

$sql2 = "SELECT market.market_name, COUNT(sale_id)
		FROM SALES
		LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
		LEFT JOIN market ON cmr.market_id = market.market_id
		GROUP BY market.market_name";

$result2 = mysqli_query($conn,$sql2) or die("Error");

$data_markets = array();
while($row = mysqli_fetch_array($result2)){        
    $point = array("label" => $row['market_name'] , "y" => $row['COUNT(sale_id)']);
    array_push($data_markets, $point);        
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
	<html lang="en"> 
	<meta charset="UTF-8">
    <title></title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script type="text/javascript">
        window.onload = function () {

                var chart = new CanvasJS.Chart("chartContainer1", {
	                animationEnabled: true,
	                title:{
	                    text: "Sales divided into Districts."              
	                },
	                data: [              
	                {
	                    // Change type to "doughnut", "line", "splineArea", etc.
	                    type: "pie",
	                    showInLegend: "true",
	                    legendText: "{label}",
	                    toolTipContent: "{label}: <strong>{y}</strong>",
	                    indexLabelFontSize: 16,
	                    indexLabel: "{label} - #percent%",
	                   	//yValueFormatString: "฿#,##0",
	                    dataPoints: <?php echo json_encode($data_districts, JSON_NUMERIC_CHECK); ?>
	                }
                ]
            	});
            	chart.render();
         
            	var chart = new CanvasJS.Chart("chartContainer2", {
	                animationEnabled: true,
	                title:{
	                    text: "Sales divided into Markets."              
	                },
	                data: [              
	                {
	                    // Change type to "doughnut", "line", "splineArea", etc.
	                    type: "pie",
	                    showInLegend: "true",
	                    legendText: "{label}",
	                    toolTipContent: "{label}: <strong>{y}</strong>",
	                    indexLabelFontSize: 16,
	                    indexLabel: "{label} - #percent%",
	                    //yValueFormatString: "฿#,##0",
	                    dataPoints: <?php echo json_encode($data_markets, JSON_NUMERIC_CHECK); ?>
	                }
                ]
            	});
            	chart.render();
            };

    </script>
</head>
<body>

    <div id="chartContainer1" style="float:left; width: 900px; height: 450px;"></div>
    <div id="chartContainer2" style="float:right; width: 900px; height: 450px;"></div>

</body>
</html>