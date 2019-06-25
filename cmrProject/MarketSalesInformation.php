<?php

session_start();

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


if($_POST['showProduct']){
    $sql = "SELECT product.product_name, COUNT(sale_id)
            FROM SALES
            LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
            LEFT JOIN product ON sales.product_id = product.product_id
            LEFT JOIN market ON cmr.market_id = market.market_id
            ";
    $sql .= "WHERE market.market_name = '" . $_SESSION['transferMarketName'] . "'";
    $sql .= "GROUP BY product.product_id";

    $result = mysqli_query($conn,$sql) or die("Error");

    $data_products = array();

    while($row = mysqli_fetch_array($result)){        
        $point = array("label" => $row['product_name'] , "y" => $row['COUNT(sale_id)']);
        array_push($data_products, $point);     
    }


    $title = $_SESSION['transferMarketName'];


    mysqli_close($conn);
}


if($_POST['showSalesman']){
    $sql = "SELECT salesman.salesman_name, COUNT(sale_id)
            FROM SALES
            LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
            LEFT JOIN product ON sales.product_id = product.product_id
            LEFT JOIN market ON cmr.market_id = market.market_id
            LEFT JOIN salesman ON sales.salesman_id = salesman.salesman_id
            ";
    $sql .= "WHERE market.market_name = '" . $_SESSION['transferMarketName'] . "'";
    $sql .= "GROUP BY product.product_id";

    $result = mysqli_query($conn,$sql) or die("Error");

    $data_salesman = array();

    while($row = mysqli_fetch_array($result)){        
        $point = array("label" => $row['salesman_name'] , "y" => $row['COUNT(sale_id)']);
        array_push($data_salesman, $point);     
    }


    $title = $_SESSION['transferMarketName'];


    mysqli_close($conn);
}

if($_POST['chooseSalesman']){
    $sql = "SELECT sale_id, salesman.salesman_name, product.product_name, customer.customer_name
            FROM SALES
            LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
            LEFT JOIN product ON sales.product_id = product.product_id
            LEFT JOIN market ON cmr.market_id = market.market_id
            LEFT JOIN salesman ON sales.salesman_id = salesman.salesman_id
            LEFT JOIN customer ON sales.customer_id = customer.customer_id
            ";
    $sql .= "WHERE market.market_name = '" . $_SESSION['transferMarketName'] . "' AND salesman.salesman_name = '" . $_POST['chooseSalesman'] . "'";
    //$sql .= "GROUP BY product.product_id";
    $result = mysqli_query($conn,$sql) or die("Error");

    $title = $_SESSION['transferMarketName'];

    
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "<table border='1'>";
        echo "<h1>" . $title . "</h1>";
        echo "<tr><td>Sale ID</td><td>Salesman Name</td><td>Product Name</td><td>Customer Name</td></tr>";
        while($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row["sale_id"]. "</td><td>" . $row["salesman_name"]. "</td><td>" . $row["product_name"]. "</td><td>" . $row["customer_name"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }


    mysqli_close($conn);
}

if($_POST['chooseCustomer']){
    $sql = "SELECT sale_id, customer.customer_name, product.product_name, salesman.salesman_name
            FROM SALES
            LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
            LEFT JOIN product ON sales.product_id = product.product_id
            LEFT JOIN market ON cmr.market_id = market.market_id
            LEFT JOIN salesman ON sales.salesman_id = salesman.salesman_id
            LEFT JOIN customer ON sales.customer_id = customer.customer_id
            ";
    $sql .= "WHERE market.market_name = '" . $_SESSION['transferMarketName'] . "' AND customer.customer_name = '" . $_POST['chooseCustomer'] . "'";
    //$sql .= "GROUP BY product.product_id";
    $result = mysqli_query($conn,$sql) or die("Error");

    $title = $_SESSION['transferMarketName'];

    
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "<table border='1'>";
        echo "<h1>" . $title . "</h1>";
        echo "<tr><td>Sale ID</td><td>Customer Name</td><td>Product Name</td><td>Salesman Name</td></tr>";
        while($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row["sale_id"]. "</td><td>" . $row["customer_name"]. "</td><td>" . $row["product_name"]. "</td><td>" . $row["salesman_name"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }


    mysqli_close($conn);
}

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
        var tempButton = <?php if($_POST['showProduct']) echo "1";
                               if($_POST['showSalesman']) echo "2";
                         ?>;
        
        if(tempButton == "1"){
            window.onload = function () {
            var newTitle = "<?php echo $title ?>"; 
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: newTitle   
                },
                axisX:{
                    labelFontSize:12
                },

                axisY:{
                    title: "Number of times Sold",
                    interval: 1
                },
                data: [              
                {
                    type: "bar",
                    legendText: "{label}",
                    toolTipContent: "{label}: <strong>{y}</strong>",
                    indexLabelFontSize: 16,
                    dataPoints: <?php echo json_encode($data_products, JSON_NUMERIC_CHECK); ?>
                }
            ]
            });
            chart.render();
            };
        }
        if(tempButton == "2"){
            window.onload = function () {
            var newTitle = "<?php echo $title ?>"; 
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: newTitle   
                },
                axisX:{
                    labelFontSize:12
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
                    dataPoints: <?php echo json_encode($data_salesman, JSON_NUMERIC_CHECK); ?>
                }
            ]
            });
            chart.render();
            };
        }
    </script>
</head>
<body>

    <div id="chartContainer" style="height: 700px; width: 100%;"></div>

</body>
</html>


