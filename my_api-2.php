<?php
header("Access-Control-Allow-Origin: *");
#using mysqli_connect to connect php file to mysql database and takes four parameter
$dbHost = 'localhost';
$dbName = 'db2226148';
$dbUname = 'root';
$dbPassword = '';

$mysqli = mysqli_connect($dbHost, $dbUname, $dbPassword);
if ($mysqli -> connect_errno){
    echo"Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}

#Creating the database if not exist
$result = $mysqli -> query("CREATE DATABASE IF NOT EXISTS `db2226148`");


$result = $mysqli -> query("USE `db2226148`");

$result = $mysqli -> query("CREATE TABLE IF NOT EXISTS `weather` (
    `weather_description` varchar(100) DEFAULT NULL,
    `weather_temperature` int(11) NOT NULL,
    `weather_wind` int(11) NOT NULL,
    `weather_when` datetime NOT NULL,
    `weather_humidity` int(5) NOT NULL,
    `weather_pressure` float NOT NULL,
    `wind_speed` float NOT NULL,
    `wind_direction` int(11) NOT NULL,
    `city` varchar(20) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

#including php
include('fetch.php');

#query
$sql = "SELECT *
FROM weather
WHERE city = '{$_GET['city']}'
AND weather_when >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
ORDER BY weather_when DESC limit 1";

$result = $mysqli -> query($sql);

// Get data, convert to JSON and print
$row = $result -> fetch_assoc();
print json_encode($row);

// Free result set and close connection
$result -> free_result();
$mysqli -> close();
?>