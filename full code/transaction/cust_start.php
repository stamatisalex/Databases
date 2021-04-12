<?php


    $store_id_list = array();
    $store_street_list = array();
    $store_city_list = array();

    $mysqli = new mysqli("localhost", "root", "mysql", "super_market");


    $result = $mysqli->query("SELECT store_id,street,city FROM Store");
       while($row = $result->fetch_assoc()) {
           $store_id_list[] = $row["store_id"];
           $store_street_list[] = $row["street"];
           $store_city_list[] = $row["city"];

       }

    $final_list[0] =$store_id_list;
    $final_list[1] =$store_street_list;
    $final_list[2] =$store_city_list;




    $final_list = json_encode($final_list);
    echo $final_list;
?>
