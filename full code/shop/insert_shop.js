var selected;
var ids =[];var cities =[];var streets =[];var numbers =[];var postal_codes =[];var opening_hours =[];var store_areas =[];var telephones =[];var categories=[];
var telfs=1;
function load_shops(){
  var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function() {
         if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
             //carthago delenda est
             if(xmlHttp.responseText == "error"){
               document.getElementById("the_bar").style.visibility = "hidden";
               document.getElementById("error_window").innerHTML ="Error while fetching available stores, please try again";
               document.getElementById("error_window").style.backgroundColor="red";
             }else{
               //document.getElementById("error_window").innerHTML ="";
               //document.getElementById("error_window").style.backgroundColor="white";
               ids = JSON.parse(xmlHttp.responseText)[0];
               cities =JSON.parse(xmlHttp.responseText)[1];
               streets = JSON.parse(xmlHttp.responseText)[2];
               numbers = JSON.parse(xmlHttp.responseText)[3];
               postal_codes = JSON.parse(xmlHttp.responseText)[4];
               opening_hours = JSON.parse(xmlHttp.responseText)[5];
               store_areas = JSON.parse(xmlHttp.responseText)[6];
               telephones = JSON.parse(xmlHttp.responseText)[7];
               categories = JSON.parse(xmlHttp.responseText)[8];

               console.log("nikos");
               console.log(JSON.parse(xmlHttp.responseText));
               for(var j =0; j<ids.length;j++){
                // console.log("here");
                 el1 = document.createElement("option");
                 el1.value=j;
                 el1.textContent =ids[j]+" "+cities[j]+" , "+streets[j];
                 document.getElementById("shop_list").appendChild(el1);
               }

             }
             //console.log(xmlHttp.responseText);
             clear();

         }else{
           //øalert("failed to upload");

         }
    }
    xmlHttp.open("POST","http://localhost/shop/fetch_selected.php",true);
    xmlHttp.setRequestHeader("Content-type", "application/json");
    xmlHttp.send(null);

}
function fetch_hours(){
  var first_hour = document.getElementById("hour_choose_first");
  var first_minute = document.getElementById("minute_choose_first");
  var second_hour = document.getElementById("hour_choose_second");
  var second_minute = document.getElementById("minute_choose_second");
  var el = document.createElement("option");
  el.value="None";
  el.textContent="None";
  first_hour.appendChild(el)
  var el1 = document.createElement("option");
  el1.value="None";
  el1.textContent="None";
  second_hour.appendChild(el1)
  var el2 = document.createElement("option");
  el2.value="None";
  el2.textContent="None";
  second_minute.appendChild(el2)
  var el3 = document.createElement("option");
  el3.value="None";
  el3.textContent="None";
  first_minute.appendChild(el3)
  for(var i=0; i<24;i++){
    if(i<10){
      var el = document.createElement("option");
      el.value="0".concat(i);
      el.textContent="0".concat(i);
      first_hour.appendChild(el)
      var el1 = document.createElement("option");
      el1.value="0".concat(i);
      el1.textContent="0".concat(i);
      second_hour.appendChild(el1)

    }else{
      var el = document.createElement("option");
      el.value=i
      el.textContent=i
      first_hour.appendChild(el)
      var el1 = document.createElement("option");
      el1.value=i;
      el1.textContent=i;
      second_hour.appendChild(el1)
    }
  }


  for(var i=0; i<60;i++){
    if(i<10){
      var el = document.createElement("option");
      el.value="0".concat(i);
      el.textContent="0".concat(i);
      first_minute.appendChild(el)
      var el1 = document.createElement("option");
      el1.value="0".concat(i);
      el1.textContent="0".concat(i);
      second_minute.appendChild(el1)

    }else{
      var el = document.createElement("option");
      el.value=i
      el.textContent=i
      first_minute.appendChild(el)
      var el1 = document.createElement("option");
      el1.value=i;
      el1.textContent=i;
      second_minute.appendChild(el1)
    }
  }
}


function upload(){
  var xmlhttp = new XMLHttpRequest();
  var to_upload = [];
  var year = new Date().getFullYear();
  var categories_to_upload =[];
  if(document.getElementById(1).checked){

    categories_to_upload.push(1);
  }
  if(document.getElementById(2).checked){
    categories_to_upload.push(2);
  }
  if(document.getElementById(3).checked){
    categories_to_upload.push(3);
  }
  if(document.getElementById(4).checked){
    categories_to_upload.push(4);
  }
  if(document.getElementById(5).checked){
    categories_to_upload.push(5);
  }
  if(document.getElementById(6).checked){
    categories_to_upload.push(6);
  }

  to_upload[0] = document.getElementById("city").value;
  to_upload[1] = document.getElementById("street").value;
  to_upload[2] = document.getElementById("number").value;
  to_upload[3] = document.getElementById("postal_code").value;
  to_upload[4] = document.getElementById("hour_choose_first").value.concat(":").concat((document.getElementById("minute_choose_first").value)).concat("-").concat((document.getElementById("hour_choose_second").value)).concat(":").concat(document.getElementById("minute_choose_second").value);
  to_upload[5] = document.getElementById("size").value;
  to_upload[6] = cur_id;

  var total_tel=[];
  for(var j=0; j<telfs;j++){
    if(document.getElementById("telephone-input"+j)){
      total_tel.push((document.getElementById("telephone-input"+j)).value);
    }
  }
  to_upload[7] = total_tel;
  to_upload[8] = categories_to_upload;

  console.log(to_upload);
  var jsonString = JSON.stringify(to_upload);

  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
           if(xmlhttp.responseText.includes("ok")){
             document.getElementById("error_window").innerHTML =" Successfull upload";
             document.getElementById("error_window").style.backgroundColor="green";

           }else{
             document.getElementById("error_window").innerHTML =" Failed upload " +xmlhttp.responseText;
             document.getElementById("error_window").style.backgroundColor="red";
           }
           document.getElementById("workspace").style.visibility="hidden";
           document.getElementById("delete_button").style.visibility="hidden";

       }
       load_shops();
  }
  var url;
  if(cur_id>0){
    url = "http://localhost/shop/update_store.php";
  }else{
    url = "http://localhost/shop/insert_shop.php"
  }
  console.log(url);
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(jsonString);
  clear();

}
function clear(){
  console.log("clear called");
  document.getElementById("city").value="None";
  document.getElementById("street").value="";
  document.getElementById("postal_code").value="";
  document.getElementById("hour_choose_first").value="None";
  document.getElementById("minute_choose_first").value="None";
  document.getElementById("hour_choose_second").value="None";
  document.getElementById("minute_choose_second").value="None";

  document.getElementById("number").value="";
  document.getElementById("size").value="";
  for(var j=0; j<telfs;j++){
    if(document.getElementById("telephone-input"+j)){
      document.getElementById("telephone-input"+j).value="";
    }
  }

}
function doo(){
  cur_id = -1;
  console.log("clear called");
  document.getElementById("the_bar").style.visibility = "hidden";
  document.getElementById("workspace").style.visibility = "visible";
  document.getElementById("insert_button").textContent="insert";
  if(document.getElementById("delete_button")){
  document.getElementById("delete_button").style.visibility="hidden";
}
  document.getElementById("city").value="None";
  document.getElementById("street").value="";
  document.getElementById("postal_code").value="";
  document.getElementById("hour_choose_first").value="None";
  document.getElementById("minute_choose_first").value="None";
  document.getElementById("hour_choose_second").value="None";
  document.getElementById("minute_choose_second").value="None";
  document.getElementById(1).checked = false;
  document.getElementById(2).checked = false;
  document.getElementById(3).checked = false;
  document.getElementById(4).checked = false;
  document.getElementById(5).checked = false;
  document.getElementById(6).checked = false;

  document.getElementById("number").value="";
  document.getElementById("size").value="";
  //document.getElementById("phone_number").value="";
  for(var j=0; j<telfs;j++){
    if(document.getElementById("telephone-input"+j)){
      document.getElementById("telephone-input"+j).value="";
    }
  }

}
function redoo(){
  document.getElementById("the_bar").style.visibility = "visible";
  document.getElementById("workspace").style.visibility = "hidden";

}


function load_elems(){
  document.getElementById(1).checked = false;
  document.getElementById(2).checked = false;
  document.getElementById(3).checked = false;
  document.getElementById(4).checked = false;
  document.getElementById(5).checked = false;
  document.getElementById(6).checked = false;
  var selected = document.getElementById("shop_list").value;
  var to_send = ids[selected];
  cur_id = to_send;
  var xmlhttp = new XMLHttpRequest();
  jsonString =JSON.stringify(to_send);


         document.getElementById("insert_button").textContent="update";
         document.getElementById("the_bar").style.visibility = "hidden";
         document.getElementById("city").value=cities[selected];
         document.getElementById("street").value=streets[selected];
         document.getElementById("number").value=numbers[selected];
         document.getElementById("postal_code").value=postal_codes[selected];
          var time_date=opening_hours[selected];
          console.log(time_date);
          document.getElementById("hour_choose_first").value = time_date.slice(0,2);
          document.getElementById("minute_choose_first").value = time_date.slice(3,5);
          document.getElementById("hour_choose_second").value = time_date.slice(6,8);
          document.getElementById("minute_choose_second").value=time_date.slice(9,11);

         document.getElementById("size").value=store_areas[selected];
         //document.getElementById("phone_number").value=telephones[selected];
         console.log(telephones);
         console.log(ids[selected]);
         document.getElementById("telephone-input0").value=telephones[ids[selected]][0];
         for(var k=1; k<telephones[ids[selected]].length;k++){
           console.log(telephones[ids[selected]][k]);
           add_tel(telephones[ids[selected]][k]);
         }
         console.log("here");
         console.log(to_send);
         console.log(categories);

         for(var k=0; k<categories[to_send].length;k++){
          console.log(categories[to_send][k][0]);
          console.log(document.getElementById(categories[to_send][k][0]));
         document.getElementById(categories[to_send][k][0]).checked = true;
       }

  document.getElementById("workspace").style.visibility = "visible";
  var work = document.getElementById("workspace");
  if(!document.getElementById("delete_button")){
  var el2 = document.createElement("button");
  el2.id = "delete_button";
  el2.onclick = function(){
    console.log("here");
    var xmlhttp = new XMLHttpRequest();
    jsonString =JSON.stringify(to_send)
    xmlhttp.onreadystatechange = function() {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
             //carthago delenda est
             if(!xmlhttp.responseText.includes("ok")){
               document.getElementById("error_window").innerHTML =" Failed Delete " +xmlhttp.responseText;
               document.getElementById("error_window").style.backgroundColor="red";
             }else{
               document.getElementById("error_window").innerHTML =" Successfull delete";
               document.getElementById("error_window").style.backgroundColor="green";
             }
             clear();
             document.getElementById("workspace").style.visibility = "hidden";
             document.getElementById("the_bar").style.visibility = "hidden";
         }else{
           //øalert("failed to upload");

         }
    }
    xmlhttp.open("POST","http://localhost/shop/delete_shop.php",true);
    xmlhttp.setRequestHeader("Content-type", "application/json");
    xmlhttp.send(jsonString);


  }
  el2.textContent = "delete";
  work.appendChild(el2);
}else{
  document.getElementById("delete_button").style.visibility="visible";

}
}
function rem_telf(c_id){
  console.log(c_id);
  document.getElementById("telephone-label"+c_id).remove();
  document.getElementById("telephone-input"+c_id).remove();
  document.getElementById("telephone-rem"+c_id).remove();

}
function add_tel(val){
  var menu = document.getElementById("menu");
  var elx  =document.createElement("div");
  elx.id = "area"+telfs;
  console.log("value: "+val);
  menu.appendChild(elx);
  var el  =document.createElement("label");
  el.textContent = "Telephone ";
  el.id = "telephone-label"+telfs;
  elx.appendChild(el);

  elx.innerHTML += "<input type ='text' id= 'telephone-input"+telfs+"' value="+val+">";
  var el2 = document.createElement("button");
  el2.textContent = "remove";
  el2.id = "telephone-rem"+telfs;
  console.log(el2.id);

  var tidir = "telephone-rem"+telfs;

  elx.innerHTML += "<button id ='"+tidir+"'onclick='rem_telf("+telfs+");'>Remove</button>";
  elx.innerHTML +="<br />";
  telfs++;

}
