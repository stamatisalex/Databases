<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$_POST = json_decode(file_get_contents('php://input'));
$sql = "SELECT category_id,name FROM Category";
fwrite($myfile, $sql);
$result = $mysqli->query($sql);
if(!$result){
  echo "error";

}else{
    $id_list=[];
    $name_list=[];
    while($row = $result->fetch_assoc()) {
      $id_list[$row["category_id"]] = $row["name"];
      $name_list[$row["name"]] = $row["category_id"];

    }
}
$final_list = [$id_list,$name_list];
$final_list = json_encode($final_list);
fclose($myfile);
echo $final_list;
 ?>
