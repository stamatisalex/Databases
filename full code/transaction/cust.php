<?php
$_POST = json_decode(file_get_contents('php://input'));
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$store = json_decode($_POST);

$category_list= array();
$sql = "SELECT category_id FROM sells_product_of WHERE store_id = $store";
$result = $mysqli->query($sql);
if(!$result){
  echo "error: not valid store, please try again";
}else{
  if(mysqli_num_rows($result)>0){
while($row = $result->fetch_assoc()) {
    $category_list[] = $row["category_id"];
}
}else{
  echo "error:shop has no products";
  }
}
    $price_list= array();
    $brand_list= array();
    $name_list = array();
    $points_list = array();
    $barcode_list = array();
    $final_list = array();
    $sql = "SELECT * FROM Product WHERE category_id = $category_list[0] and price IS NOT NULL";
    for($i=1; $i<count($category_list);$i++){
      $sql.=" OR category_id = $category_list[$i] and price IS NOT NULL";
    }
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
        $price_list[] = $row["price"];
        $name_list[] = $row["name"];
        $points_list[] = $row["extra_points"];
        $brand_list[] = $row["brand"];
        $barcode_list[] = $row["barcode"];

    }

    $final_list[0]=$price_list;
    $final_list[1]=$name_list;
    $final_list[2]=$points_list;
    $final_list[3]=$brand_list;
    $final_list[4]=$barcode_list;

    $final_list = json_encode($final_list);
    echo $final_list;
?>
