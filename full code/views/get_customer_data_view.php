<?php
require_once 'establish_connection.php';

$Card_id = $_GET['selected_customer_id'];

$query_body="select * from customer_data where card_id='$Card_id';";

$name_list=array();
$card_list=array();
$birth_date_list=array();
$age_list=array();
$email_list=array();
$sex_list=array();
$marriage_status_list=array();
$number_of_children_list=array();
$pet_list=array();
$city_list=array();
$street_list=array();
$number_list=array();
$postal_code_list=array();
$phone_number_list=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
    $name_list[]=$row["name"];
    $card_list[]=$row["card_id"];
    $birth_date_list[]=$row["birth_date"];
    $age_list[]=$row["age"];
    $email_list[]=$row["email"];
    $sex_list[]=$row["sex"];
    $marriage_status_list[]=$row["marriage_status"];
    $number_of_children_list[]=$row["number_of_children"];
    $pet_list[]=$row["pet"];
    $city_list[]=$row["city"];
    $street_list[]=$row["street"];
    $number_list[]=$row["number"];
    $postal_code_list[]=$row["postal_code"];
    $phone_number_list[]=$row["phone_number"];
$final_list[0]=$name_list;
$final_list[1]=$card_list;
$final_list[2]=$birth_date_list;
$final_list[3]=$age_list;
$final_list[4]=$email_list;
$final_list[5]=$sex_list;
$final_list[6]=$marriage_status_list;
$final_list[7]=$number_of_children_list;
$final_list[8]=$pet_list;
$final_list[9]=$city_list;
$final_list[10]=$street_list;
$final_list[11]=$number_list;
$final_list[12]=$postal_code_list;
$final_list[13]=$phone_number_list;
}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();   
          
?>