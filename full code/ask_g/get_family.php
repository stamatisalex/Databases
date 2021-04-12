<?php
require_once 'establish_connection.php';

$query_body="SELECT SUM(CASE WHEN C.number_of_children=0 then 1 else 0 END) AS NO_CHILDREN,
SUM(CASE WHEN C.number_of_children>0 then 1 else 0 END) AS MORE_THAN_ONE
FROM Transaction as T,includes as i,Customer as C
WHERE i.transaction_id=T.transaction_id and C.card_id=T.card_id
";
$none=array();
$more=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$none[]=$row["NO_CHILDREN"];
$more[]=$row["MORE_THAN_ONE"];


$final_list[0]=$none;
$final_list[1]=$more;

}



$query_body2="SELECT
SUM(CASE WHEN table1.number_of_children=0 then table1.OCCURANCIES else 0 END) AS NO_CHILDREN_HEALTHY,
SUM(CASE WHEN table1.number_of_children>0 then table1.OCCURANCIES else 0 END) AS MORE_THAN_ONE_CHILDREN_HEALTHY
FROM (SELECT COUNT(P.category_id) AS OCCURANCIES,C.number_of_children
FROM Transaction as T,includes as i,Customer as C,Product as P
WHERE i.transaction_id=T.transaction_id and C.card_id=T.card_id and P.barcode=i.barcode and P.category_id=(Select category_id from Category where name='Fresh products')
group by C.number_of_children
)table1";

$healthy_none=array();
$healthy_more=array();

$result=$mysqli->query($query_body2);
while($row=$result->fetch_assoc()){
$healthy_none[]=$row["NO_CHILDREN_HEALTHY"];
$healthy_more[]=$row["MORE_THAN_ONE_CHILDREN_HEALTHY"];
$final_list[2]=$healthy_none;
$final_list[3]=$healthy_more;
}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
