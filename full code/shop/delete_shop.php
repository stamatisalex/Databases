<?php
$_POST = json_decode(file_get_contents('php://input'));
$store_list=[];
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$sql = "DELETE FROM Store WHERE store_id LIKE $_POST";
$result = $mysqli->query($sql);
if($result){
  echo "ok";
}
//when a shop is deleted, every element that references to it as a foreign key is deleted as well
 ?>
