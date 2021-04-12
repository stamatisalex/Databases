<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$_POST = json_decode(file_get_contents('php://input'));

mysqli_query($mysqli,"START TRANSACTION");
$sql = "INSERT INTO  Product(chain_tag ,price ,extra_points,barcode,brand,name,category_id) VALUES
 ('$_POST[0]','$_POST[1]','$_POST[2]','$_POST[3]','$_POST[4]','$_POST[5]','$_POST[6]')";
  //fclose($myfile);
$a1 = $mysqli->query($sql);
$inserted_id = $mysqli->insert_id;

$cor = $_POST[7][0][1];
$shelf = $_POST[7][0][2];
$stock = $_POST[7][0][3];
$cur = $_POST[7][0][0];
$sql = "INSERT INTO  sells(corridor ,shelf ,stock,store_id,barcode) VALUES
 ('$cor' , '$shelf' , '$stock' , '$cur' , '$_POST[3]')";
 for($i=1;$i<count($_POST[7]);$i++){
   $cor = $_POST[7][$i][1];
   $shelf = $_POST[7][$i][2];
   $stock = $_POST[7][$i][3];
   $cur = $_POST[7][$i][0];
   $sql = $sql.",('$cor' , '$shelf' , '$stock' , '$cur' , '$_POST[3]')";
     //fclose($myfile);
 }
 $a2 = $mysqli->query($sql);


if ($a1 and $a2 ) {
    echo "ok";
    mysqli_query($mysqli,"COMMIT");
} else {
    if(1 === preg_match('~[0-9]~', mysqli_error($mysqli))){
      echo "error: product not sold in shop: ".mysqli_error($mysqli);
    }else{
      echo "error ".mysqli_error($mysqli);

    }
    mysqli_query($mysqli,"ROLLBACK");
}

 ?>
