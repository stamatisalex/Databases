<?php
require_once 'establish_connection.php';

$query_body='select store_id,category_name, total_quantity
             from sales_per_category;';

$category_name_list=array();
$sales_list=array();
$stores_list=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
    $stores_list[]=$row["store_id"];
    $category_name_list[]=$row["category_name"];
    $sales_list[]=$row["total_quantity"];
    $final_list[0]=$stores_list;
    $final_list[1]=$category_name_list;
    $final_list[2]=$sales_list;

}
$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>
