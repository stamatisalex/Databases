<?php
require_once 'establish_connection.php';

$query_body="select T1.Product1,T1.Name1,T1.Brand1,T1.Name2, T1.Brand2 ,T1.Product2,T1.Occurance from
 (select a.barcode as Product1,P1.name as Name1,P1.brand as Brand1,P2.name as Name2, P2.brand as Brand2 ,b.barcode as Product2,Count(*) as Occurance
                from includes as a, includes as b, Product as P1, Product as P2
                where b.barcode=P2.barcode and a.barcode=P1.barcode and a.transaction_id=b.transaction_id and a.barcode<>b.barcode
                group by Product1,Product2
                order by Occurance desc) as T1
                left join (select a.barcode as Product1,P1.name as Name1,P1.brand as Brand1,P2.name as Name2, P2.brand as Brand2 ,b.barcode as Product2,Count(*) as Occurance
                                from includes as a, includes as b, Product as P1, Product as P2
                                where b.barcode=P2.barcode and a.barcode=P1.barcode and a.transaction_id=b.transaction_id and a.barcode<>b.barcode
                                group by Product1,Product2
                                order by Occurance desc) as T2
                on (T1.Occurance<T2.Occurance)
                group by T1.Product1,T1.Product2
                HAVING COUNT(*)<=1
                ORDER by T1.Occurance DESC";

$product1_list=array();
$product1_1_list=array();
$product1_2_list=array();
$product2_list=array();
$product2_1_list=array();
$product2_2_list=array();
$occurance_list=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$product1_list[]=$row["Product1"];
$product1_1_list[]=$row["Name1"];
$product1_2_list[]=$row["Brand1"];
$product2_list[]=$row["Product2"];
$product2_1_list[]=$row["Name2"];
$product2_2_list[]=$row["Brand2"];
$occurance_list[]=$row["Occurance"];

$final_list[0]=$product1_list;
$final_list[1]=$product1_1_list;
$final_list[2]=$product1_2_list;
$final_list[3]=$product2_list;
$final_list[4]=$product2_1_list;
$final_list[5]=$product2_1_list;
$final_list[6]=$occurance_list;

}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
