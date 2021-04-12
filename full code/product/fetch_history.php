<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");

$_POST = json_decode(file_get_contents('php://input'));
$full_list=[];
  $sql = "SELECT start_date,price,end_date FROM Price_history WHERE barcode LIKE '$_POST'";
  $result = $mysqli->query($sql);
  if($result){

  while($row = $result->fetch_assoc()) {
      if(!$row["end_date"]){
        array_push($full_list,[$row["start_date"],$row["price"],"now"]);

      }else{
      array_push($full_list,[$row["start_date"],$row["price"],$row["end_date"]]);

    }


  }
}


echo (json_encode($full_list));
?>
