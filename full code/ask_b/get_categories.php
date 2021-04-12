<?php
require_once 'establish_connection.php';

$query_body='select category_id, name
             from Category;';

$category_id_list=array();
$name_list=array();
$final_list=array();

$result=$mysqli->query($query_body);   
while($row=$result->fetch_assoc()){
$category_id_list[]=$row["category_id"];
$name_list[]=$row["name"];
$final_list[0]=$category_id_list;
$final_list[1]=$name_list;

}
$to_send=json_encode($final_list);
echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>
