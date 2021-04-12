<?php
require_once 'establish_connection.php';

$Card_id = $_GET['selected_customer_id'];

$query_body="select count(distinct store_id ) as count
             from Transaction
             where card_id='$Card_id';";

$count_list=array();
$result=$mysqli->query($query_body);   
$row=$result->fetch_assoc();
$count_list[]=$row["count"];

$to_send=json_encode($count_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>
