<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "barkin_ozakay";

set_time_limit(0);

// Connecting to MySQL.
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/*** PART A - Creating Tables ***/

// Creating a new database.
mysqli_query($conn, "DROP DATABASE IF EXISTS barkin_ozakay");
mysqli_query($conn, "CREATE DATABASE barkin_ozakay");


mysqli_query($conn, "SET NAMES utf8");
mysqli_query($conn, "SET CHARACTER SET utf8");
mysqli_query($conn, "SET COLLACTION_CONNECTION='utf8_turkish_ci");


// Creating tables for database.
$districtTable = "CREATE TABLE IF NOT EXISTS `DISTRICT` (
				  `district_id` int(11) NOT NULL AUTO_INCREMENT,
				  `district_name` varchar(50) NOT NULL,
				  PRIMARY KEY(`district_id`)
				  ) ENGINE=InnoDB;";

$cityTable = 	"CREATE TABLE IF NOT EXISTS `CITY` (
				  `city_id` int(11) NOT NULL AUTO_INCREMENT,
				  `city_name` varchar(50) NOT NULL,
				  `district_id` int(11) NOT NULL,
				  PRIMARY KEY(`city_id`),
				  FOREIGN KEY fk_city_district_id (`district_id`) REFERENCES  `DISTRICT` (`district_id`)
				) ENGINE=InnoDB;";

$marketTable =  "CREATE TABLE IF NOT EXISTS `MARKET` (
				  `market_id` int(11) NOT NULL AUTO_INCREMENT,
				  `market_name` varchar(50) NOT NULL,
				  PRIMARY KEY(`market_id`)
				) ENGINE=InnoDB;";

$salesmanTable = "CREATE TABLE IF NOT EXISTS `SALESMAN` (
				  `salesman_id` int(11) NOT NULL AUTO_INCREMENT,
				  `salesman_name` varchar(50) NOT NULL,
				  PRIMARY KEY(`salesman_id`)
				) ENGINE=InnoDB;";

$customerTable = "CREATE TABLE IF NOT EXISTS `CUSTOMER` (
				  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `customer_name` varchar(50) NOT NULL,
				  PRIMARY KEY(`customer_id`)
				) ENGINE=InnoDB;";

$productTable = "CREATE TABLE IF NOT EXISTS `PRODUCT` (
				  `product_id` int(11) NOT NULL AUTO_INCREMENT,
				  `product_name` varchar(50) NOT NULL,
				  `product_price` double DEFAULT 0,
				  PRIMARY KEY(`product_id`)
				) ENGINE=InnoDB;";

$salesTable = "CREATE TABLE IF NOT EXISTS `SALES` (
			  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_id` int(11) NOT NULL,
			  `salesman_id` int(11) NOT NULL,
			  `customer_id` int(11) NOT NULL,
			  PRIMARY KEY(`sale_id`),
			  FOREIGN KEY fk_sales_product_id (`product_id`) REFERENCES `PRODUCT` (`product_id`),
			  FOREIGN KEY fk_sales_salesman_id (`salesman_id`) REFERENCES `SALESMAN` (`salesman_id`),
			  FOREIGN KEY fk_sales_customer_id (`customer_id`) REFERENCES `CUSTOMER` (`customer_id`)
			) ENGINE=InnoDB;";


// Connecting to the new database and creating tables.
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn, $districtTable);
mysqli_query($conn, $cityTable);
mysqli_query($conn, $marketTable);
mysqli_query($conn, $salesmanTable);
mysqli_query($conn, $customerTable);
mysqli_query($conn, $productTable);
mysqli_query($conn, $salesTable);




/*** PART B - Inserting Tables ***/

// Inserting Districts... 
$row = 0;
$filename = "./districts.csv";


if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle)) !== FALSE)
	{
		$item = mysqli_real_escape_string($conn, $row[0]);
		$sql = "INSERT INTO `DISTRICT` (`district_name`) VALUES ('$item');";
		mysqli_query($conn, $sql);
	}
	fclose($handle);
}

// Inserting Cities...
$row = 0;
$filename = "./cities.csv";

if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		if(!$header)
				$header = $row;
		else{
			$item1 = mysqli_real_escape_string($conn, $row[0]);
			$item2 = mysqli_real_escape_string($conn, $row[1]);
			$sql = "INSERT INTO `CITY` (`city_name`, `district_id`) VALUES ('$item1', '$item2');";
			mysqli_query($conn, $sql);
		}
	}
	fclose($handle);
}

// Inserting Markets...
$row = 0;
$filename = "./markets.csv";

if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle)) !== FALSE)
	{
		$item = mysqli_real_escape_string($conn, $row[0]);
		$sql = "INSERT INTO `MARKET` (`market_name`) VALUES ('$item');";
		mysqli_query($conn, $sql);
	}
	fclose($handle);
}

// Inserting Products...
$row = 0;
$filename = "./products.csv";

if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	while (($row = fgetcsv($handle)) !== FALSE)
	{
		$item = mysqli_real_escape_string($conn, $row[0]);
		$sql = "INSERT INTO `PRODUCT` (`product_name`) VALUES ('$item');";
		mysqli_query($conn, $sql);
	}
	fclose($handle);
}


// Names
$nameArray = [];
$row = 0;
$filename = "./names.csv";

if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	//echo '<table border=1>';
	//echo '<tr><td>Name</td><tr/>';
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		//echo '<tr><td>'.$row[0].'</td><tr/>';
		array_push($nameArray,"$row[0]");

	}
	echo '</table>';
	fclose($handle);
}

// Surnames
$surnameArray = [];
$row = 0;
$filename = "./surnames.csv";

if(!file_exists($filename) || !is_readable($filename))
	return FALSE;

$header = NULL;
if (($handle = fopen($filename, 'r')) !== FALSE)
{
	//echo '<table border=1>';
	//echo '<tr><td>Surname</td><tr/>';
	while (($row = fgetcsv($handle, 1000, ';')) !== FALSE)
	{
		//echo '<tr><td>'.$row[0].'</td><tr/>';
		array_push($surnameArray,"$row[0]");
	}
	echo '</table>';
	fclose($handle);
}


$number = 3000;
for($i=0; $i<$number; $i++){
	$random_fname_index = array_rand($nameArray);
	$random_lname_index = array_rand($surnameArray);

	shuffle($nameArray);
	shuffle($surnameArray);

	$first_name = $nameArray[$random_fname_index];
	$last_name = $surnameArray[$random_lname_index];

	$fullName[$i] = $first_name . ' ' . $last_name;
}

array_unique($fullName);


// Inserting Customers & Salesmans ...
$size=sizeof($fullName);
for($i=0;$i<$size;$i++){
	if($i<1620){
		$sql = "INSERT INTO `CUSTOMER` (`customer_name`) VALUES ('$fullName[$i]');";
		mysqli_query($conn, $sql);
	}
	else if(1620<=$i && $i<2835){
		$sql = "INSERT INTO `SALESMAN` (`salesman_name`) VALUES ('$fullName[$i]');";
		mysqli_query($conn, $sql);
	}
	else{
		break;
	}
}

/* City-Market-Salesman Relation Table */
$cmrTable = "CREATE TABLE IF NOT EXISTS `CMR` (
				  `city_id` int(11) NOT NULL,
				  `market_id` int(11) NOT NULL,
				  `salesman_id`int(11) NOT NULL,
				  FOREIGN KEY fk_cmr_city_id (`city_id`) REFERENCES  `CITY` (`city_id`),
				  FOREIGN KEY fk_cmr_market_id (`market_id`) REFERENCES  `MARKET` (`market_id`),
				  FOREIGN KEY fk_cmr_salesman_id(`salesman_id`) REFERENCES `SALESMAN`(`salesman_id`)
				) ENGINE=InnoDB;";

mysqli_query($conn, $cmrTable);

$marketArray = [];
foreach (range(1, 10) as $number) {		
    array_push($marketArray, $number);
}

$salesmanArray = [];
foreach (range(1, 1215) as $number) {
    array_push($salesmanArray, $number);
}

shuffle($salesmanArray);

$counter = 0;
for($i=1; $i<=81; $i++){			// ERROR -> 405 MARKET MUST BE UPLOADED!!!	/* Every city should have 5 different markets randomly and every market should have exact 3 salesman. */
	$random_markets = array_rand($marketArray, 5);	
	for($j=0; $j<5; $j++){
		for($k=0; $k<3; $k++){
			$sql = "INSERT INTO `CMR` (`city_id`,`market_id`,`salesman_id`) VALUES ('$i','$random_markets[$j]','$salesmanArray[$counter]');";
			mysqli_query($conn, $sql);
			$counter++;
			if($counter == 1215){
				$counter = mt_rand(1,1215);
			}
		}
	}
}


// Inserting Sales... 
$sql = "SELECT salesman_id FROM cmr;";		/* Getting salesman data from CMR table to insert them into the SALES table. */
$result = mysqli_query($conn, $sql) or die();
$cmrSalesmans = [];
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
        array_push($cmrSalesmans, $row["salesman_id"]);
    }
} else {
    echo "0 results";
}

shuffle($cmrSalesmans);
$counter = 0;
for($i=1; $i<=1620; $i++){
	for($j=0; $j<mt_rand(1,5); $j++){
		$randomProduct = mt_rand(1, 200);
		$sql = "INSERT INTO `sales` (`product_id`, `customer_id`, `salesman_id`) 
				VALUES('$randomProduct','$i',$cmrSalesmans[$counter]);";
		mysqli_query($conn, $sql);
		$counter++;
		if($counter == 1215)
			$counter = 200;
	}
}


mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
	<head>
		<script>
			function partA(){
				window.location = "resultA.php";
			}

			function partB(){
				window.location = "resultB.php";
			}

			function partC(){
				window.location = "report.php";
			}
		</script>
	</head>
	<body>
		<button onclick="partA()">PART A</button>
		<button onclick="partB()">PART B</button>
		<button onclick="partC()">REPORT</button>
	</body>
</html>
