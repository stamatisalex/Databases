<?php
$conn_error='Could not connect to database.';
$mysql_host='localhost';
$msql_user='root';
$mysql_password='mysql';
$mysql_database='super_market';

$mysqli = new mysqli($mysql_host,$msql_user,$mysql_password,$mysql_database);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>
