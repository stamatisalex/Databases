<?php
require_once 'establish_connection.php';

$Card_id = $_GET['selected_customer_id'];

$query_body="select Store.store_id as store_id,Store.city as city,Store.street as street,Store.number as number,Store.postal_code as postal_code
            from Store
            where Store.store_id in (select Transaction.store_id
                        from Transaction
                        where Transaction.card_id='$Card_id');";

$store_id_list=array();
$city_list=array();
$street_list=array();
$number_list=array();
$postal_code_list=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$store_id_list[]=$row["store_id"];
$city_list[]=$row["city"];
$street_list[]=$row["street"];
$number_list[]=$row["number"];
$postal_code_list[]=$row["postal_code"];
$final_list[0]=$store_id_list;
$final_list[1]=$city_list;
$final_list[2]=$street_list;
$final_list[3]=$number_list;
$final_list[4]=$postal_code_list;
}

$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();   
          
?>