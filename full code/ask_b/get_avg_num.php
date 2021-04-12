<?php
require_once 'establish_connection.php';

$Card_id = $_GET["selected_customer_id"];
$m=$_GET["month"];
//$Card_id =13;
//$m=1;

$query_body="select FORMAT(avg(T.num),2) as number, T.week_of_month  from (
            select count(Transaction.transaction_id) as num, 1 + FLOOR((EXTRACT(DAY FROM Transaction.date_time) - 1) / 7) as week_of_month
            from Transaction
            where Transaction.card_id='$Card_id' and EXTRACT(MONTH FROM Transaction.date_time)='$m' 
            group by (1 + FLOOR((EXTRACT(DAY FROM Transaction.date_time) - 1) / 7)),EXTRACT(YEAR FROM Transaction.date_time)
            ) as T
            group by T.week_of_month
            order by T.week_of_month;";

$num=array();
$week=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$num[]=$row["number"];
$week[]=$row["week_of_month"];
$final_list[0]=$num;
$final_list[1]=$week;
}
//$final_list[0]=$y;
$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>