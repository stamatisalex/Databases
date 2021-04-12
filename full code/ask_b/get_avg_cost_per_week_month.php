<?php
require_once 'establish_connection.php';

$Card_id = $_GET["selected_customer_id"];
$y=$_GET["year"];
$m=$_GET["month"];
//$Card_id =1;
//$y=2020;
//$m=5;

$query_body="select FORMAT(avg(T.total_cost),2) as total , T.week_of_month as week_of_month
            from 
            (select Transaction.total_cost as total_cost, EXTRACT(YEAR FROM Transaction.date_time) as year, EXTRACT(MONTH FROM Transaction.date_time) as month, EXTRACT(WEEK FROM Transaction.date_time) as week, 1 + FLOOR((EXTRACT(DAY FROM Transaction.date_time) - 1) / 7) as week_of_month
                from Transaction
                where Transaction.card_id='$Card_id' and (EXTRACT(YEAR FROM Transaction.date_time))='$y' and (EXTRACT(MONTH FROM Transaction.date_time))='$m' ) as T

            group by T.week_of_month,T.month,T.year
            order by T.week_of_month";

$cost=array();
$week=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$cost[]=$row["total"];
$week[]=$row["week_of_month"];
$final_list[0]=$cost;
$final_list[1]=$week;
}
//$final_list[0]=$y;
$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>