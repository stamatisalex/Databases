<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$result = $mysqli->query("SELECT * FROM Product");
if($result){
  while($row = $result->fetch_assoc()) {
  $info[$row["barcode"]] = [$row["chain_tag"],$row["price"],$row["extra_points"],$row["barcode"],$row["brand"],$row["name"],$row["category_id"]];
  //$chain_tags[] = $row["chain_tag"];
  //$prices[] = $row["price"];
  //$extra_points[] = $row["extra_points"];
  $barcodes[] = $row["barcode"];
  /*$brands[] = $row["brand"];
  $names[] = $row["name"];
  $category_ids[] = $row["category_id"];*/
}

for($k=0;$k<sizeof($barcodes);$k++){

$result1 = $mysqli->query("SELECT * FROM Price_history WHERE barcode LIKE $barcodes[$k]");
if($result1){
  $history[$barcodes[$k]] =array();
  while($row = $result1->fetch_assoc()) {

      array_push($history[$barcodes[$k]],[$row["start_date"],$row["price"],$row["end_date"] ]);

  }
}else{
  echo "error";
}
}
for($k=0;$k<sizeof($barcodes);$k++){
    $result2 = $mysqli->query("SELECT * FROM sells WHERE barcode LIKE '$barcodes[$k]'");
    if($result2){
      $cur_array=[];
      while($row = $result2->fetch_assoc()) {
          $shop_list = $row["store_id"];
          //array_push($info[$barcodes[$k]],[$row["store_id"],$row["corridor"],$row["shelf"],$row["stock"] ]);
          array_push($cur_array,[$row["store_id"],$row["corridor"],$row["shelf"],$row["stock"] ]);

      }
      array_push($info[$barcodes[$k]],$cur_array);

    }else{
      echo "error";
    }
  }


}else{
  echo "error";
}
$final_list[0]=$info;
$final_list[1] =$barcodes;


$final_list = json_encode($final_list);

echo $final_list
 ?>
