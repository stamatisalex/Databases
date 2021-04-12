<?php
require_once 'establish_connection.php';

$query_body="     SELECT table1.store_id,table1.Hour,
                  SUM(CASE WHEN table1.age < 18 THEN 1 ELSE 0 END) AS 'Age_Under_18',
                  SUM(CASE WHEN table1.age BETWEEN 18 AND 24 THEN 1 ELSE 0 END) AS '18-24',
                  SUM(CASE WHEN table1.age BETWEEN 25 AND 34 THEN 1 ELSE 0 END) AS '25-34',
                  SUM(CASE WHEN table1.age BETWEEN 35 AND 42 THEN 1 ELSE 0 END) AS '35-42',
                  SUM(CASE WHEN table1.age BETWEEN 43 AND 50 THEN 1 ELSE 0 END) AS '43-50',
                  SUM(CASE WHEN table1.age BETWEEN 51 AND 64 THEN 1 ELSE 0 END) AS '51-64',
                  SUM(CASE WHEN table1.age > 65 THEN 1 ELSE 0 END) AS 'Age_Over_65'
           FROM (  SELECT c.age, Transaction.store_id,EXTRACT(HOUR FROM Transaction.date_time) as Hour
             from Customer as c,Transaction
             where c.card_id=Transaction.card_id
      ) table1
      group by table1.Hour,table1.store_id
      order by  table1.store_id asc,table1.Hour
";

$store_id=array();
$Hour=array();
$Age_Under_18=array();
$Age_18_24=array();
$Age_25_34=array();
$Age_35_42=array();
$Age_43_50=array();
$Age_51_64=array();
$Age_Over_65=array();
$final_list=array();

$result=$mysqli->query($query_body);
while($row=$result->fetch_assoc()){
$store_id[]=$row["store_id"];
$Hour[]=$row["Hour"];
$Age_Under_18[]=$row["Age_Under_18"];
$Age_18_24[]=$row["18-24"];
$Age_25_34[]=$row["25-34"];
$Age_35_42[]=$row["35-42"];
$Age_43_50[]=$row["43-50"];
$Age_51_64[]=$row["51-64"];
$Age_Over_65[]=$row["Age_Over_65"];

$final_list[0]=$store_id;
$final_list[1]=$Hour;
$final_list[2]=$Age_Under_18;
$final_list[3]=$Age_18_24;
$final_list[4]=$Age_25_34;
$final_list[5]=$Age_35_42;
$final_list[6]=$Age_43_50;
$final_list[7]=$Age_51_64;
$final_list[8]=$Age_Over_65;
}

$query_body2="SELECT  Transaction.store_id,EXTRACT(HOUR FROM Transaction.date_time) as Hour ,count(Transaction.card_id) as Sum
      from Customer as c,Transaction
      where c.card_id=Transaction.card_id
      group by EXTRACT(HOUR FROM Transaction.date_time),Transaction.store_id
      order by Transaction.store_id,EXTRACT(HOUR FROM Transaction.date_time)";

$store_id=array();
$Hour=array();
$Sum=array();

$result=$mysqli->query($query_body2);
while($row=$result->fetch_assoc()){
$store_id[]=$row["store_id"];
$Hour[]=$row["Hour"];
$Sum[]=$row["Sum"];
$final_list[9]=$store_id;
$final_list[10]=$Hour;
$final_list[11]=$Sum;
}



$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();

?>
