<?php
require_once 'establish_connection.php';

$query_body="select store_id,max(Total_cost) as Total_cost,Hour
from(select Transaction.store_id,sum(ROUND(Transaction.total_cost,2)) as Total_cost,EXTRACT(HOUR FROM Transaction.date_time) as Hour
from Transaction
group by EXTRACT(HOUR FROM Transaction.date_time),store_id
order by Transaction.store_id asc,Total_cost Desc)T
group by store_id";

$store_id=array();
$Total_cost=array();
$Hour=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$store_id[]=$row["store_id"];
$Total_cost[]=$row["Total_cost"];
$Hour[]=$row["Hour"];

$final_list[0]=$store_id;
$final_list[1]=$Total_cost;
$final_list[2]=$Hour;

}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
