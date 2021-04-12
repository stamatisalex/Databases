<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$_POST = json_decode(file_get_contents('php://input'));

mysqli_query($mysqli,"START TRANSACTION");
$sql = "INSERT INTO  Customer(card_id,points,name,email,number_of_children,city,street,postal_code,sex,marriage_status,pet,number,birth_date) VALUES
 ($_POST[0],$_POST[1],'$_POST[2]','$_POST[3]',$_POST[4],'$_POST[5]','$_POST[6]',$_POST[7],'$_POST[8]','$_POST[9]','$_POST[10]',$_POST[11],$_POST[12])";
$a1 = $mysqli->query($sql);
$inserted_id = $mysqli->insert_id;
echo $sql;
echo $a1;
$to_insert = $_POST[15];
$sql = "INSERT INTO  Customer_phone_number(phone_number,card_id) VALUES ($to_insert[0],$inserted_id)";
for($i=1;$i<count($to_insert);$i++){
  $sql = $sql.",($to_insert[$i],$inserted_id)";
}
echo $sql;
$a2 = $mysqli->query($sql);

if ($a1 and $a2 ) {
    echo "ok";
    mysqli_query($mysqli,"COMMIT");
} else {
    echo "error ".mysqli_error($mysqli);
    mysqli_query($mysqli,"ROLLBACK");
}

?>
