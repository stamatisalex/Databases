<?php
$_POST = json_decode(file_get_contents('php://input'));

$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$result = $mysqli->query("DELETE  FROM Customer WHERE card_id LIKE $_POST");
if($result){
  echo 0;
}else{
  echo 1;
}


 ?>
