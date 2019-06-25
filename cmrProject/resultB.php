<?php
	session_start();
	
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

	$sql = "SELECT distinct(market_name) FROM MARKET ";
	$result = mysqli_query($conn,$sql) or die("Error");

	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    echo "<h1>Choose Market</h1>";
		echo "<form action='B-buttons.php' method='post'>";
		echo '<select name="marketName">';
	    while($row = mysqli_fetch_array($result)) {
			echo "<option value='" . $row["market_name"] . "'>";
	        echo $row["market_name"];
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