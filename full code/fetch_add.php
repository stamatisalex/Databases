<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");

$sql = "SELECT city,street FROM Store ";
if($result = $mysqli->query($sql)){
  $list =[];
  while($row = $result->fetch_assoc()){
    $city_list[] = $row["city"];
    $street_list[] = $row["street"];
    array_push($list,$row["street"].",".$row["city"].",Greece");

  }
}

echo json_encode($list);
?>
