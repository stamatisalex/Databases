<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$_POST = json_decode(file_get_contents('php://input'));


mysqli_query($mysqli,"START TRANSACTION");
$sql = "UPDATE Product
SET chain_tag = '$_POST[0]',
price = '$_POST[1]',
extra_points ='$_POST[2]',
barcode = '$_POST[3]',
brand = '$_POST[4]',
name = '$_POST[5]',
category_id = $_POST[6] WHERE barcode = $_POST[8]";
echo $sql;
$a1 = $mysqli->query($sql);
echo $a1;
$sql ="DELETE FROM sells WHERE barcode LIKE '$_POST[3]'";
$a2 = $mysqli->query($sql);
echo $sql;
echo "a2: ".$a2;
$cor = $_POST[7][0][1];
$shelf = $_POST[7][0][2];
$stock = $_POST[7][0][3];
$cur = $_POST[7][0][0];
$sql = "INSERT INTO sells(corridor,shelf,stock,store_id,barcode) VALUES('$cor','$shelf','$stock','$cur','$_POST[3]')";
for($i=1; $i<count($_POST[7]);$i++){
  $cor = $_POST[7][$i][1];
  $shelf = $_POST[7][$i][2];
  $stock = $_POST[7][$i][3];
  $cur = $_POST[7][$i][0];
  $sql = $sql.",('$cor','$shelf','$stock','$cur','$_POST[3]')";
}
$a3 = $mysqli->query($sql);
echo $sql;
echo "a3: ".$a3;

if ($a1 and $a2 and $a3 ) {
    echo "ok";
    mysqli_query($mysqli,"COMMIT");
} else {
    echo "error ".mysqli_error($mysqli);
    mysqli_query($mysqli,"ROLLBACK");
}



 ?>
