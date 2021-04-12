var stores_available =[];
var product_list =[];
var full_product_list=[];
var category_list=[];
var name_list=[];
var selected =-1;
function calcTime(a){
  var currentdate = new Date();
  var datetime = " (Now) "+currentdate.getDate() + "/"
                  + (currentdate.getMonth()+1)  + "/"
                  + currentdate.getFullYear() + "  "
                  + currentdate.getHours() + ":"
                  + currentdate.getMinutes() + ":"
                  + currentdate.getSeconds();

  if(a == "now"){
    return datetime;
  }else {
    return a;
  }
}
function clode(){

  document.getElementById("history_show").innerHTML="";
}
function load_history(){
  var xmlhttp = new XMLHttpRequest();
  var jsonString = JSON.stringify(selected);
  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
          console.log(JSON.parse(xmlhttp.responseText));
          console.log(JSON.parse(xmlhttp.responseText).length);
          var to_add;
          to_add = "<h3>Price History for product "+selected+"<button onclick='clode()'>Close</button></h3><table><tr><td><b>Start</b></td><td><b>Price</b></td><td><b>End</b></td></tr>";
          for(var i=0;i<JSON.parse(xmlhttp.responseText).length;i++){
          to_add += "<tr><td>"+JSON.parse(xmlhttp.responseText)[i][0]+"</td><td>"+JSON.parse(xmlhttp.responseText)[i][1]+"</td><td>"+calcTime(JSON.parse(xmlhttp.responseText)[i][2])+"</td></tr>"
        }
        to_add += "</table>";
        document.getElementById("history_show").innerHTML = to_add;

       }
  }

  xmlhttp.open("POST","http://localhost/product/fetch_history.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(jsonString);
}
function load_data(){
  var xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
           //alert("uploaded");
           console.log("here: ");
           console.log(JSON.parse(xmlhttp.responseText));
           full_product_list= JSON.parse(xmlhttp.responseText)[0];
           for(var i=0; i<JSON.parse(xmlhttp.responseText)[1].length;i++){
              product_list.push([JSON.parse(xmlhttp.responseText)[1][i],JSON.parse(xmlhttp.responseText)[0][JSON.parse(xmlhttp.responseText)[1][i]][4],JSON.parse(xmlhttp.responseText)[0][JSON.parse(xmlhttp.responseText)[1][i]][5]])
           }

           console.log(product_list);
           for(var i=0; i<product_list.length;i++){
             var el = document.createElement("option");
             el.value = product_list[i][0];
             el.textContent =product_list[i][2]+" from " + product_list[i][1];
             document.getElementById("choose_product").appendChild(el);
           }
       }
  }

  xmlhttp.open("POST","http://localhost/product/fetch_product.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(null);



}
function load_cat(){
  var xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
         console.log(JSON.parse(xmlhttp.responseText));
          category_list = JSON.parse(xmlhttp.responseText)[0];
          name_list = JSON.parse(xmlhttp.responseText)[1];

       }else{
         //øalert("failed to upload");

       }
  }

  xmlhttp.open("POST","http://localhost/product/quick_fetch_category.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(null);
}
function load_shops(){
  var xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
           //alert("uploaded")
           console.log(JSON.parse(xmlhttp.responseText));
           for(var j=0; j<JSON.parse(xmlhttp.responseText).length;j++){
             stores_available.push(JSON.parse(xmlhttp.responseText)[j][0]);
             document.getElementById("shop_choose").innerHTML +="<br><br>";
             document.getElementById("shop_choose").innerHTML +="<h3>Shop "+ JSON.parse(xmlhttp.responseText)[j][0]+" "+ JSON.parse(xmlhttp.responseText)[j][1]+" "+JSON.parse(xmlhttp.responseText)[j][2]+"</h3>";
              document.getElementById("shop_choose").innerHTML +=" corridor: <input type='text' id='"+JSON.parse(xmlhttp.responseText)[j][0]+" corridor'>"
              document.getElementById("shop_choose").innerHTML +=" shelf: <input type='text' id='"+JSON.parse(xmlhttp.responseText)[j][0]+" shelf'>"
              document.getElementById("shop_choose").innerHTML +=" stock: <input type='text' id='"+JSON.parse(xmlhttp.responseText)[j][0]+" stock'>"
              document.getElementById("shop_choose").innerHTML +="<br><br>";

           }
           console.log(stores_available);
       }else{
         //øalert("failed to upload");

       }
  }

  xmlhttp.open("POST","http://localhost/product/quick_fetch_sho.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(null);
}
function upload(){
  var to_upload = Array();
  to_upload[0] = document.getElementById("chain_select").value;
  to_upload[1] = document.getElementById("price").value;
  to_upload[2] = document.getElementById("extra_points").value;
  to_upload[3] = document.getElementById("barcode").value;
  to_upload[4] = document.getElementById("brand").value;
  to_upload[5] = document.getElementById("name").value;
  console.log(name_list);
  console.log(document.getElementById("category").value);
  to_upload[6] = name_list[document.getElementById("category").value];
  to_upload[8] =selected;
  to_upload[7] =[];
  for(var k=0; k<stores_available.length;k++){
    if(((document.getElementById(stores_available[k]+" corridor").value) != "") && ((document.getElementById(stores_available[k]+" shelf").value) != "") &&((document.getElementById(stores_available[k]+" stock").value) != "")) {
    to_upload[7].push([stores_available[k],document.getElementById(stores_available[k]+" corridor").value,document.getElementById(stores_available[k]+" shelf").value,document.getElementById(stores_available[k]+" stock").value])
}
  }
  console.log(to_upload);
  var jsonString = JSON.stringify(to_upload);
  var xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
          if(xmlhttp.responseText.includes("error")){
            alert(xmlhttp.responseText);
          }
           clear_menu();
           document.getElementById("delete_button").style.visibility="hidden";
       }else{

       }
  }
  console.log("selected "+ selected);
  if(selected>=0){
    console.log("here1");
  xmlhttp.open("POST","http://localhost/product/update_product.php",true);
}else{
  console.log("here2");

  xmlhttp.open("POST","http://localhost/product/upload_product.php",true);

}
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(jsonString);


}

function clear_menu(){
  console.log("here");
  //document.getElementById("delete_button").style.visibility="hidden";
  document.getElementById("price").value="";
  document.getElementById("extra_points").value ="";
  document.getElementById("barcode").value ="";
  document.getElementById("brand").value ="";
  document.getElementById("name").value ="";
  document.getElementById("category").value="None";
  document.getElementById("workbench").style.visibility ="hidden";
  //document.getElementById("category").innerHTML="";

}
function doo(){
  selected =-1;
  document.getElementById('price_button').style.visibility="hidden";
  document.getElementById('history_show').innerHTML="";
  console.log("here");
  document.getElementById("price").value="";
  document.getElementById("extra_points").value ="";
  document.getElementById("barcode").value ="";
  document.getElementById("brand").value ="";
  document.getElementById("name").value ="";
  document.getElementById("category").value="None";

}
function other(){
  document.getElementById('workbench').style.visibility='hidden';
  document.getElementById('selected_product').style.visibility='visible';
  clear_menu();

}
function fill(){
  selected = document.getElementById("choose_product").value;
  document.getElementById("price_button").style.visibility="visible";

  document.getElementById('selected_product').style.visibility='hidden';
  document.getElementById("delete_button").style.visibility="visible";
  var product_selected = document.getElementById("choose_product").value;
  console.log(full_product_list[product_selected][0]);
  document.getElementById("workbench").style.visibility="visible";
  document.getElementById("chain_select").value=full_product_list[product_selected][0];
  document.getElementById("price").value=full_product_list[product_selected][1];
  document.getElementById("extra_points").value =full_product_list[product_selected][2];
  document.getElementById("barcode").value =full_product_list[product_selected][3];
  document.getElementById("brand").value =full_product_list[product_selected][4];
  document.getElementById("name").value =full_product_list[product_selected][5];
  console.log(category_list[6]);
  console.log(full_product_list[product_selected][6]);
  document.getElementById("category").value=category_list[full_product_list[product_selected][6]];
  for(var i=0;i<full_product_list[product_selected][7].length;i++){
    var cur_it = full_product_list[product_selected][7][i][0];
    document.getElementById(cur_it+ " corridor").value=full_product_list[product_selected][7][i][1];
    document.getElementById(cur_it+ " shelf").value=full_product_list[product_selected][7][i][2];
    document.getElementById(cur_it+ " stock").value=full_product_list[product_selected][7][i][3];

  }
}
function delete_product(){
  var xmlhttp = new XMLHttpRequest();

  jsonString = JSON.stringify(selected);
  if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
    document.getElementById("price").value="";
    document.getElementById("extra_points").value ="";
    document.getElementById("barcode").value ="";
    document.getElementById("brand").value ="";
    document.getElementById("name").value ="";
    document.getElementById("category").value="None";
    document.getElementById("workbench").style.visibility ="hidden";



  }else{
    document.getElementById("price").value="";
    document.getElementById("extra_points").value ="";
    document.getElementById("barcode").value ="";
    document.getElementById("brand").value ="";
    document.getElementById("name").value ="";
    document.getElementById("category").value="None";
    document.getElementById("workbench").style.visibility ="hidden";
    document.getElementById("delete_button").remove();

  }


  xmlhttp.open("POST","http://localhost/product/delete_product.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(jsonString);
}
