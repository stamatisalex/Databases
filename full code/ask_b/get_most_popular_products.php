<?php
require_once 'establish_connection.php';

$Card_id = $_GET['selected_customer_id'];

$query_body="select T1.name, T1.brand, T1.barcode, T1.total from
(select Product.name as name, Product.brand as brand, includes.barcode as barcode,sum(includes.quantity) as total
                    from includes,Product
                    where (includes.transaction_id in (select Transaction.transaction_id
                                                from Transaction
                                                where Transaction.card_id='$Card_id'
                                                )
                        and product.barcode=includes.barcode)
                    group by includes.barcode) as T1
left join (select Product.name as name, Product.brand as brand, includes.barcode as barcode,sum(includes.quantity) as total
                    from includes,Product
                    where (includes.transaction_id in (select Transaction.transaction_id
                                                from Transaction
                                                where Transaction.card_id='$Card_id'
                                                )
                        and product.barcode=includes.barcode)
                    group by includes.barcode) as T2 
on (T1.total<T2.total)
GROUP by (T1.barcode)
HAVING COUNT(*)<=10
ORDER by T1.total DESC";

$name_list=array();
$brand_list=array();
$barcode_list=array();
$total_list=array();
$final_total=array();

$result=$mysqli->query($query_body); 

while($row=$result->fetch_assoc()){
    $name_list[]=$row["name"];
    $brand_list[]=$row["brand"];
    $barcode_list[]=$row["barcode"];
    $total_list[]=$row["total"];
}
$count=count(($total_list));
//echo $count;
if ($count>9){
    for ($x=0; $x<=9; $x++){
        $final_total[$x]=$total_list[$x];
    }
    $c=9;
    while($c+1<$count && $total_list[$c]==$total_list[$c+1]){
        $final_total[$c+1]=$total_list[$c+1];
        $c=$c+1;
    }
    for($x=0; $x<$c+1; $x++){
        $final_total[]=$total_list;
        $final_list[0][$x]=$name_list[$x];
        $final_list[1][$x]=$brand_list[$x];
        $final_list[2][$x]=$barcode_list[$x];
        $final_list[3][$x]=$t=$final_total[$x];

    }
//echo $final_list;
}
else {
    $final_total=$total_list;
    $final_list[0]=$name_list;
    $final_list[1]=$brand_list;
    $final_list[2]=$barcode_list;
    $final_list[3]=$t=$final_total;
}

$to_send=json_encode($final_list);
echo $to_send;
//echo $to_send;
$result-> free_result();
$mysqli -> close();             
?>
