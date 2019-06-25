<?php

	session_start();

	$_SESSION['transferMarketName'] = $_POST['marketName'];

	$servername = "localhost";
	$username = "root";
	$password = "mysql";
	$dbname = "barkin_ozakay";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	// Product Button.
	echo "<form action='MarketSalesInformation.php' method='post'>";	
	echo "<input type='submit' value='Product' name='showProduct'>";
	echo "</form>";
	
	echo "<br>";
	
	// Salesman Button.
	echo "<form action='MarketSalesInformation.php' method='post'>";	
	echo '<input type="submit" value="Salesman" name="showSalesman">';
	echo "</form>";
	
	echo "<br>";

	// Choose Salesman Button.
	$sql = "SELECT distinct(salesman.salesman_name)
            FROM SALES
            LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
            LEFT JOIN product ON sales.product_id = product.product_id
            LEFT JOIN market ON cmr.market_id = market.market_id
            LEFT JOIN salesman ON sales.salesman_id = salesman.salesman_id
            ";
    $sql .= "WHERE market.market_name = '" . $_SESSION['transferMarketName'] . "'";
	$result = mysqli_query($conn,$sql) or die("Error");

	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    echo "<h1>Choose Salesman</h1>";
		echo "<form action='MarketSalesInformation.php' method='post'>";
		echo '<select name="chooseSalesman">';
	    while($row = mysqli_fetch_array($result)) {
			echo "<option value='" . $row["salesman_name"] . "'>";
	        echo $row["salesman_name"];
			echo "</option>";
	    }
		echo '</select>';
		echo '<input type="submit" value="Submit">';
		echo "</form>";
	} else {
	    echo "0 results";
	}

	
	// Invoice Button.
	$sql = "SELECT distinct(customer.customer_name)
            FROM SALES
            LEFT JOIN cmr ON sales.salesman_id = cmr.salesman_id
            LEFT JOIN product ON sales.product_id = product.product_id
            LEFT JOIN market ON cmr.market_id = market.market_id
            LEFT JOIN salesman ON sales.salesman_id = salesman.salesman_id
            LEFT JOIN customer ON sales.customer_id = customer.customer_id
            ";
    $sql .= "WHERE market.market_name = '" . $_SESSION['transferMarketName'] . "'";
	$result = mysqli_query($conn,$sql) or die("Error");

	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    echo "<h1>Choose Customer</h1>";
		echo "<form action='MarketSalesInformation.php' method='post'>";
		echo '<select name="chooseCustomer">';
	    while($row = mysqli_fetch_array($result)) {
			echo "<option value='" . $row["customer_name"] . "'>";
	        echo $row["customer_name"];
			echo "</option>";
	    }
		echo '</select>';
		echo '<input type="submit" value="Submit">';
		echo "</form>";
	} else {
	    echo "0 results";
	}
	

	mysqli_close($conn);
?>