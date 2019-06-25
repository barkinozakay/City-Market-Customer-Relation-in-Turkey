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

$sql = "SELECT market.market_name, COUNT(product.product_id)
		FROM SALES
		LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
		LEFT JOIN product ON sales.product_id = product.product_id
		LEFT JOIN market ON cmr.market_id = market.market_id
		LEFT JOIN city ON cmr.city_id = city.city_id
		";
$sql .= "WHERE city.city_name = '" . $_POST['cityName'] . "'";
$sql .= "GROUP BY cmr.market_id";

$result = mysqli_query($conn,$sql) or die("Error");


$data_markets = array();

while($row = mysqli_fetch_array($result)){        
    $point = array("label" => $row['market_name'] , "y" => $row['COUNT(product.product_id)']);
    array_push($data_markets, $point);        
}

$title = $_POST['cityName'];

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
            var newTitle = "<?php echo $title ?>"; 
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: newTitle   
                },
                axisY:{
                    title: "Number of Products Sold",
                    interval: 1
                },
                data: [              
                {
                    type: "bar",
                    legendText: "{label}",
                    toolTipContent: "{label}: <strong>{y}</strong>",
                    indexLabelFontSize: 16,
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

    <div id="chartContainer" style="width: 100%; height: 700px;"></div>

</body>
</html>