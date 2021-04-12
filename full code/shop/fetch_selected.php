<?php
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

$mysqli = new mysqli("localhost", "root", "mysql", "super_market");

  $result = $mysqli->query("SELECT * FROM Store ");
  //$result1 = $mysqli->query("SELECT phone_number FROM Store_phone_number");
  $result2 = $mysqli->query("SELECT * FROM Category");



if($result){
while($row = $result->fetch_assoc()) {
    $shop_id_list[] = $row["store_id"];
    $city_list[] = $row["city"];
    $street_list[] = $row["street"];
    $number_list[] = $row["number"];
    $postal_code_list[] = $row["postal_code"];
    $opening_hours_list[] = $row["opening_hours"];
    $size_list[] = $row["size"];

}
}else{

$fail=1;
}


$fail = 0;
for($i=0;$i<count($shop_id_list);$i++){
$result1 = $mysqli->query("SELECT phone_number FROM Store_phone_number WHERE store_id LIKE $shop_id_list[$i]");

if($result1){
$telephone_list[$shop_id_list[$i]] = [];
   while($row = $result1->fetch_assoc()) {
       array_push($telephone_list[$shop_id_list[$i]], $row["phone_number"]);
       //$telephone_list[$user_card_list[$i]] = $row["phone_number"];


   }
 }else{
   $fail = 1;
 }
}
if($result2){
$category_dict=[];
while($row = $result2->fetch_assoc()) {
    $category_dict[$row["category_id"]] = $row["name"];

}
for($j=0;$j<count($shop_id_list);$j++){
$current = $shop_id_list[$j];
$sql = "SELECT category_id FROM sells_product_of WHERE store_id LIKE $current";
$result3 = $mysqli->query($sql);
  $cur =[];
  while($row = $result3->fetch_assoc()) {

      array_push($cur,[$row["category_id"],$category_dict[$row["category_id"]]]);
  }
  $category_list[$shop_id_list[$j]]= $cur;
}
}else{
  $fail=1;
}

if(!$fail){
$final_list[0]=$shop_id_list;
$final_list[1]=$city_list;
$final_list[2]=$street_list;
$final_list[3]=$number_list;
$final_list[4]=$postal_code_list;
$final_list[5]=$opening_hours_list;
$final_list[6]=$size_list;
$final_list[7] = $telephone_list;
$final_list[8] = $category_list  ;
$final_list = json_encode($final_list);
echo $final_list;

}else{
  echo "error";
}
 ?>
