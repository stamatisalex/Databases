<?php
require_once 'establish_connection.php';

$Card_id = $_GET["selected_customer_id"];
$y=$_GET["year"];
$m=$_GET["month"];
//$Card_id =1;
//$y=2020;
//$m=5;

$query_body="select FORMAT(avg(T.total_cost),2) as total
            from 
            (select Transaction.total_cost as total_cost, EXTRACT(YEAR FROM Transaction.date_time) as year, EXTRACT(MONTH FROM Transaction.date_time) as month
                from Transaction
                where Transaction.card_id='$Card_id' and (EXTRACT(YEAR FROM Transaction.date_time))='$y' and (EXTRACT(MONTH FROM Transaction.date_time))='$m' ) as T

            group by T.month,T.year;";

$cost=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$cost[]=$row["total"];
$final_list[0]=$cost;
}
//$final_list[0]=$y;
$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>