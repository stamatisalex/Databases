<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$all_done = 1;
$_POST = json_decode(file_get_contents('php://input'));
/*
$result = $mysqli->query($sql);
if (!$result) {
  $all_done =0;
  for ($i=1; $i<=4;$i++){
    if(strpos(mysqli_error($mysqli), 'store_chk_'.($i))){
      echo $i;
      break;
    }

  }
}
*/


  mysqli_query($mysqli,"START TRANSACTION");

  $sql = "DELETE FROM Store_phone_number WHERE store_id LIKE $_POST[6]";
  $a0 = $mysqli->query($sql);
  $selected = $_POST[7];
  $sql = "INSERT INTO  Store_phone_number(phone_number,store_id) VALUES ($selected[0],$_POST[6])";

  for($i=1;$i<count($selected);$i++){
    $sql = $sql.",($selected[$i],$_POST[6])";
  }
  $a1 = $mysqli->query($sql);
  echo $sql;
  $a2 = mysqli_query($mysqli,"UPDATE Store
  SET
  city = '$_POST[0]',
  street ='$_POST[1]',
  number = $_POST[2],
  postal_code = $_POST[3],
  opening_hours = '$_POST[4]',
  size = $_POST[5]
  WHERE store_id LIKE $_POST[6]");
  $sql = "DELETE FROM sells_product_of WHERE store_id LIKE $_POST[6]";
  echo $sql;
  $a3 = mysqli_query($mysqli,$sql);
  echo $a3;
  for($i=0; $i<count($_POST[8]);$i++){
  $to_insert = $_POST[8][$i];
  $sql = "INSERT INTO sells_product_of VALUES ($_POST[6],$to_insert)";
  echo $sql;
  $a4 = mysqli_query($mysqli,$sql);
  echo $a4;
}

  if ($a1 and $a2 and $a3 and $a4) {
      echo "ok";
      mysqli_query($mysqli,"COMMIT");
  } else {
      echo "error ".mysqli_error($mysqli);
      mysqli_query($mysqli,"ROLLBACK");
  }
?>
