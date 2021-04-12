<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$user_card_list= array();
$user_points_list= array();
$user_name_list= array();
$user_birth_date_list= array();
$user_sex_list= array();
$user_email_list= array();
$user_age_list= array();
$user_marriage_status_list= array();
$user_number_of_children_list= array();
$user_city_list= array();
$user_street_list= array();
$user_number_list= array();
$user_postal_code_list= array();
$user_pet_list= array();
$telephone_list= array();

$result = $mysqli->query("SELECT * FROM Customer");
if($result){
   while($row = $result->fetch_assoc()) {
       $user_card_list[] = $row["card_id"];
       $user_points_list[] = $row["points"];
       $user_name_list[] = $row["name"];
       $user_birth_date_list[] = $row["birth_date"];
       $user_sex_list[] = $row["sex"];
       $user_email_list[] = $row["email"];
       $user_age_list[] = $row["age"];
       $user_marriage_status_list[] = $row["marriage_status"];
       $user_number_of_children_list[] = $row["number_of_children"];
       $user_city_list[] = $row["city"];
       $user_street_list[] = $row["street"];
       $user_number_list[] = $row["number"];
       $user_postal_code_list[] = $row["postal_code"];
       $user_pet_list[] = $row["pet"];

   }
   $fail = 0;
   for($i=0;$i<count($user_card_list);$i++){
   $result1 = $mysqli->query("SELECT phone_number FROM Customer_phone_number WHERE card_id LIKE $user_card_list[$i]");

 if($result1){
   $telephone_list[$user_card_list[$i]] = [];
      while($row = $result1->fetch_assoc()) {
          array_push($telephone_list[$user_card_list[$i]], $row["phone_number"]);
          //$telephone_list[$user_card_list[$i]] = $row["phone_number"];


      }
    }else{
      $fail = 1;
    }
  }
    if(!$fail){
   $final_list[0]=$user_card_list;
   $final_list[1] =$user_points_list;
   $final_list[2] =$user_name_list;
   $final_list[3] =$user_birth_date_list;
   $final_list[4] =$user_sex_list;
   $final_list[5] =$user_email_list;
   $final_list[6] =$user_age_list;
   $final_list[7] =$user_marriage_status_list;
   $final_list[8] =$user_number_of_children_list;
   $final_list[9] =$user_city_list;
   $final_list[10] =$user_street_list;
   $final_list[11] =$user_number_list;
   $final_list[12] =$user_postal_code_list;
   $final_list[13] =$user_pet_list;
   $final_list[14] =$telephone_list;

   $final_list = json_encode($final_list);
   echo $final_list;

 }else {
   echo 0;
 }
}else{
  echo 0;
}
?>
