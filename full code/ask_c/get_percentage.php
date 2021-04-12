<?php
require_once 'establish_connection.php';

$query_body="select P.category_id,sum(table3.value_occurrence) as product_num
          from Product as P left join (select table2.barcode,table2.value_occurrence
          FROM Product as P join (SELECT i.barcode ,sum(i.quantity) as value_occurrence
                                  from includes as i
                                  group by i.barcode
                                  order by value_occurrence DESC
          ) table2
          on P.barcode=table2.barcode and P.chain_tag=1) table3
          on P.barcode=table3.barcode
          group by P.category_id";
$category_id=array();
$product_num=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$category_id[]=$row["category_id"];
$product_num[]=$row["product_num"];


$final_list[0]=$category_id;
$final_list[1]=$product_num;

}



$query_body2="select  P.category_id, sum(table2.value_occurrence) as product_num
          from Product as P left join (SELECT i.barcode ,sum(i.quantity) as value_occurrence
          from includes as i
          group by i.barcode
          order by value_occurrence DESC
          )table2
          on P.barcode=table2.barcode
          group by P.category_id";

$category_id=array();
$product_num=array();

$result=$mysqli->query($query_body2);
while($row=$result->fetch_assoc()){
$product_num[]=$row["product_num"];
$final_list[2]=$product_num;

}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
