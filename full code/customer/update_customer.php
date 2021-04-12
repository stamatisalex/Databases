<?php

$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$_POST = json_decode(file_get_contents('php://input'));


mysqli_query($mysqli,"START TRANSACTION");
$sql = "UPDATE Customer
SET card_id = $_POST[0],
points = $_POST[1],
name ='$_POST[2]',
email = '$_POST[3]',
number_of_children = $_POST[4],
city = '$_POST[5]',
street = '$_POST[6]',
postal_code = $_POST[7],
sex = '$_POST[8]',
marriage_status = '$_POST[9]',
pet = '$_POST[10]',
number = $_POST[11],
age = $_POST[13],
birth_date = $_POST[12]
WHERE card_id LIKE $_POST[14]";

$a1 = $mysqli->query($sql);
$selected = $_POST[15];
$sql = "DELETE FROM Customer_phone_number WHERE card_id LIKE $_POST[0]";
$a3 = $mysqli->query($sql);
$sql = "INSERT INTO  Customer_phone_number(phone_number,card_id) VALUES ($selected[0],$_POST[0])";

for($i=1;$i<count($selected);$i++){
  $sql = $sql.",($selected[$i],$_POST[0])";
}
$a2 = $mysqli->query($sql);
echo $sql;


if ($a1 and $a2 and $a3 ) {
    echo "ok";
    mysqli_query($mysqli,"COMMIT");
} else {
    echo "error ".mysqli_error($mysqli);
    mysqli_query($mysqli,"ROLLBACK");
}
 ?>
