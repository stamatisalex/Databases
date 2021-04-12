<?php
require_once 'establish_connection.php';

$Card_id = $_GET["selected_customer_id"];
$m=$_GET["month"];
//$Card_id =1;
//$m=5;

$query_body="select FORMAT(avg(T.num),2) as total from (
            select count(Transaction.transaction_id) as num
            from Transaction
            where Transaction.card_id='$Card_id' and EXTRACT(MONTH FROM Transaction.date_time)='$m' 
            group by EXTRACT(YEAR FROM Transaction.date_time)  
            ) as T;";

$number=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$number[]=$row["total"];
$final_list[0]=$number;
}
//$final_list[0]=$y;
$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>