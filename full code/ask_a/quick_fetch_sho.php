<?php
$mysqli = new mysqli("localhost", "root", "mysql", "super_market");
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$_POST = json_decode(file_get_contents('php://input'));
$sql = "SELECT store_id,city,street FROM Store";
fwrite($myfile, $sql);
$result = $mysqli->query($sql);
if(!$result){
  echo "error";

}else{
    $id_list=[];
    while($row = $result->fetch_assoc()) {
      array_push($id_list,[$row["store_id"],$row["city"],$row["street"]]);
    }
}
$id_list = json_encode($id_list);
echo $id_list;
fclose($myfile);
?>
