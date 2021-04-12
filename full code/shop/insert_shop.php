  <?php
  $mysqli = new mysqli("localhost", "root", "mysql", "super_market");
  $_POST = json_decode(file_get_contents('php://input'));


  mysqli_query($mysqli,"START TRANSACTION");
  $sql = "INSERT INTO  Store(city,street,number,postal_code,opening_hours,size) VALUES
   ('$_POST[0]','$_POST[1]',$_POST[2],$_POST[3],'$_POST[4]',$_POST[5])";
  $a1 = mysqli_query($mysqli,$sql);
  echo "a1: ".$a1;
   $inserted_id = $mysqli->insert_id;
   $to_insert = $_POST[7];
  $sql = "INSERT INTO  Store_phone_number(phone_number,store_id) VALUES ($to_insert[0],$inserted_id)";
  for($i=1;$i<count($to_insert);$i++){
    $sql = $sql.",($to_insert[$i],$inserted_id)";
  }
  echo $sql;
  $a2 = $mysqli->query($sql);
  if(count($_POST[8])>0){
    $to_insert = $_POST[8][0];
    $sql3 = "INSERT INTO sells_product_of VALUES ($inserted_id,$to_insert)";
  }

    for($i=1; $i<count($_POST[8]);$i++){
      $to_insert = $_POST[8][$i];
      $sql3 = $sql3.",($inserted_id,$to_insert)";

    }
  $a3 = mysqli_query($mysqli,$sql3);

  if ($a1 and $a2 and $a3) {
      echo "ok";
      mysqli_query($mysqli,"COMMIT");
  } else {
      echo "error ".mysqli_error($mysqli);
      mysqli_query($mysqli,"ROLLBACK");
  }



  ?>
