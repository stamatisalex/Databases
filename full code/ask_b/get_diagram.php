<?php
require_once 'establish_connection.php';

$Card_id = $_GET["selected_customer_id"];
$s=$_GET["store"];
//$Card_id=1;
//$s=4;

$query_body="select count(Transaction.transaction_id) as count,EXTRACT(HOUR FROM Transaction.date_time) as time
            from Transaction
            where Transaction.card_id='$Card_id' and Transaction.store_id='$s'
            group by EXTRACT(HOUR FROM Transaction.date_time)
            order by time ASC;";

$querry_body2="select Store.opening_hours as open 
               from Store
               where Store.store_id='$s';";

$count_list=array();
$time_list=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$count_list[]=$row["count"];
$time_list[]=$row["time"];
$final_list[0]=$count_list;
$final_list[1]=$time_list;
}
$result2=$mysqli->query($querry_body2);
while($row=$result2->fetch_assoc()){
$hours_list[]=$row["open"];
$final_list[2]=$hours_list;
}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>