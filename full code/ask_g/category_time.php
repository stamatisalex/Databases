<?php
require_once 'establish_connection.php';

$query_body="SELECT table1.Category as Category,
                SUM(CASE WHEN table1.MONTH BETWEEN 3 AND 5 THEN table1.quantity ELSE 0 END) AS SPRING,
                SUM(CASE WHEN table1.MONTH BETWEEN 6 AND 8 THEN table1.quantity ELSE 0 END) AS SUMMER,
                SUM(CASE WHEN table1.MONTH BETWEEN 9 AND 11 THEN table1.quantity ELSE 0 END) AS AUTUMN,
                SUM(CASE WHEN table1.MONTH BETWEEN 1 AND 2  OR table1.MONTH=12 THEN table1.quantity ELSE 0 END) AS WINTER
FROM (select  EXTRACT(MONTH FROM T.date_time)as MONTH,P.barcode, sum(a.quantity) as quantity,P.category_id as Category
  from includes as a,Transaction as T,Product as P
  where a.transaction_id=T.transaction_id and a.barcode=P.barcode
group by MONTH,P.barcode
) table1
GROUP BY table1.Category";

$category_list=array();
$spring_list=array();
$summer_list=array();
$autumn_list=array();
$winter_list=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$category_list[]=$row["Category"];
$spring_list[]=$row["SPRING"];
$summer_list[]=$row["SUMMER"];
$autumn_list[]=$row["AUTUMN"];
$winter_list[]=$row["WINTER"];

$final_list[0]=$category_list;
$final_list[1]=$spring_list;
$final_list[2]=$summer_list;
$final_list[3]=$autumn_list;
$final_list[4]=$winter_list;


}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
