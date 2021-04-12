<?php
$_POST = json_decode(file_get_contents('php://input'));

$mysqli = new mysqli("localhost", "root", "mysql", "super_market");

mysqli_query($mysqli,"START TRANSACTION");

$sql = "UPDATE Product SET price = NULL WHERE barcode LIKE '$_POST'";
$a2 = $mysqli->query($sql);
if ($a2 ) {
    echo "ok";
    mysqli_query($mysqli,"COMMIT");
} else {
    echo "error ".mysqli_error($mysqli);
    mysqli_query($mysqli,"ROLLBACK");
}



 ?>
