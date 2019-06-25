<!DOCTYPE html>
<html>
<body>

<?php

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

$sql = "SELECT distinct(city_name) FROM CITY ";
$result = mysqli_query($conn,$sql) or die("Error");

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    echo "<h1>Choose City</h1>";
	echo "<form action='ShowCitySalesInformation.php' method='post'>";
	echo '<select name="cityName">';
    while($row = mysqli_fetch_array($result)) {
		echo "<option value='" . $row["city_name"] . "'>";
        echo $row["city_name"];
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

</body>
</html>