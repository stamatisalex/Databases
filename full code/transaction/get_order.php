<?php
    $conn = new mysqli("localhost", "root", "mysql", "super_market");

    $_POST = json_decode(file_get_contents('php://input'));
    $sql = ("INSERT INTO Transaction(card_id,store_id,date_time,payment_method) VALUES($_POST[4],$_POST[6],'$_POST[3]','$_POST[5]')");
    echo $sql;

    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "error " . $conn->error;
    }

    $visited =[];
    $prods = [];
    $meg = COUNT($_POST[0]);
    //print_r($_POST[0]);
    for ($i=0; $i<$meg;$i++){

        $cur_e =$_POST[0][$i];
        $ff=0;
        for($k=0; $k<count($visited);$k++){
          if($cur_e == $visited[$k]){
            $ff=1;
          }
        }
        if($ff){
          continue;
        }else{
          array_push($visited,$cur_e);
        }
        $freq = 1;
        for($j=$i+1;$j<$meg;$j++){
            if($_POST[0][$j] == $cur_e){
                $freq++;
            }
        }
        array_push($prods,[$cur_e,$freq]);

    }


      //$fid = "SELECT transaction_id FROM Transaction WHERE date_time = '".$_POST[3]."' AND card_id = $_POST[4]";
    $fid = $conn->insert_id;
    $quant = $prods[0][1];
    $id =$prods[0][0];
    $sql = "INSERT INTO includes VALUES ($quant,($fid),'$id')";

    for ($i=1; $i<count($prods);$i++){
        $quant = $prods[$i][1];
        $id =$prods[$i][0];
        $sql = $sql.",($quant,($fid),'$id')";


    }
    echo $sql;
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "error: " . $sql . "<br>" . $conn->error;
    }
?>
