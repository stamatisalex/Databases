<?php
$_POST = json_decode(file_get_contents('php://input'));

$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
//$_POST =["total_cost",""];
$first = $_POST[1];
$sec = $_POST[2];
if($_POST[0][0] == "date_time"){
$sql = "SELECT * FROM Transaction WHERE DATE(Transaction.date_time) BETWEEN '$first[0]' AND '$sec[0]' AND store_id = ".$_POST[3];
}else if($_POST[0][0] == "total_cost"){
  $sql = "SELECT * FROM Transaction WHERE total_cost BETWEEN ".$first[0]." AND ".$sec[0]." AND store_id = ".$_POST[3];
}else if($_POST[0][0] == "payment_method"){
  $sql = "SELECT * FROM Transaction WHERE payment_method = '$first[0]' AND store_id = ".$_POST[3];

}else if($_POST[0][0] == "total_number_of_products"){
  $sql = "SELECT * FROM Transaction WHERE total_number_of_products BETWEEN ".$first[0]." AND ".$sec[0]." AND store_id = ".$_POST[3];

}

for($i=1;$i<count($_POST[0]); $i++){

  if($_POST[0][$i] == "date_time"){
  $sql = "SELECT * FROM ($sql) AS T WHERE DATE(Transaction.date_time) BETWEEN  '$first[0]' AND '$sec[0]'";
}else if($_POST[0][$i] == "total_cost"){
    $sql = "SELECT * FROM ($sql) AS T WHERE total_cost BETWEEN ".$first[$i]." AND ".$sec[$i];
  }else if($_POST[0][$i] == "payment_method"){
    $sql = "SELECT * FROM ($sql) AS T WHERE payment_method = '$first[$i]'";

  }else if($_POST[0][$i]== "total_number_of_products"){
    $sql = "SELECT * FROM ($sql) AS T WHERE total_number_of_products BETWEEN ".$first[$i]." AND ".$sec[$i];

  }
}


$a2 = $mysqli->query($sql);
if ($a2 ) {
    $pos =-1;
    for($i =0; $i < count($_POST[0]); $i++){
      if ($_POST[0][$i] == "category"){
        $pos = $i;
      }
    }
    if($pos == -1){
      $sql = "SELECT * FROM Transaction WHERE transaction_id IN (SELECT DISTINCT i.transaction_id  FROM ($sql) AS T ,includes as i,  Product as p  WHERE p.barcode = i.barcode AND i.transaction_id IN (SELECT transaction_id FROM ($sql) AS T )) ";

}else{
  //  echo "old: ".$sql;
    $sql = "SELECT * FROM Transaction WHERE transaction_id IN (SELECT DISTINCT i.transaction_id  FROM ($sql) AS T ,includes as i,  Product as p  WHERE p.barcode = i.barcode AND i.transaction_id IN (SELECT transaction_id FROM ($sql) AS T  )AND ";

    $cur = $_POST[1][$pos];
    $small = "p.category_id = $cur[0]";

    for($i=1;$i<count($cur);$i++){
      $small = $small." OR p.category_id = $cur[$i]";
    }
    $sql = $sql."(".$small."))";
  }

  //echo "<br><br>".$sql;
  if($result = $mysqli->query($sql)){
    //echo "ok";



    while($row = $result->fetch_assoc()) {
        $payment_list[] = $row["payment_method"];
        $date_time_list[] = $row["date_time"];
        $transaction_id_list[] = $row["transaction_id"];
        $total_number_list[] = $row["total_number_of_products"];
        $total_cost_list[] = $row["total_cost"];
        $card_id_list[] = $row["card_id"];
        $store_id_list[] = $row["store_id"];



    }

    $final_list = [$payment_list,$date_time_list,$transaction_id_list,$total_number_list,$total_cost_list,$card_id_list,$store_id_list];
    $final_list = json_encode($final_list);
    echo $final_list;



} else {
    echo "error ".mysqli_error($mysqli);
}
}

 ?>
