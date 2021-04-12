<?php
require_once 'establish_connection.php';

$query_body="select sells.store_id,sells.corridor,sells.shelf,max(table2.value_occurrence),table2.name,table2.brand,table2.barcode
                from sells inner join (SELECT i.barcode, S.store_id ,count(i.barcode) as value_occurrence, P.name, P.brand
                                      from includes as i,Store as S,Transaction as T,Product as P
                                      where S.store_id=T.store_id and i.transaction_id=T.transaction_id and P.barcode=i.barcode
                                      group by i.barcode,T.store_id
                                      order by value_occurrence DESC) table2
                on sells.barcode=table2.barcode and sells.store_id=table2.store_id
                where table2.barcode is not null
                group by sells.store_id
                order by sells.store_id";

$store_id=array();
$corridor=array();
$self=array();
$product1_list=array();
$product1_1_list=array();
$product1_2_list=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$store_id[]=$row["store_id"];
$corridor[]=$row["corridor"];
$self[]=$row["shelf"];
$product1_list[]=$row["barcode"];
$product1_1_list[]=$row["name"];
$product1_2_list[]=$row["brand"];



$final_list[0]=$store_id;
$final_list[1]=$corridor;
$final_list[2]=$self;
$final_list[3]=$product1_list;
$final_list[4]=$product1_1_list;
$final_list[5]=$product1_2_list;

}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
