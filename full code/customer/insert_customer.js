var ids = [];
var cur_id=-1;
var points =[];var names =[];var birth_dates =[];var sexes =[];var emails =[];var ages =[];var marriage_statuses =[];var number_of_childrenes =[];var cities =[];var streets =[];var numbers =[];var postal_codes =[];var pets =[];
var telephones=[];
var telfs=1;
function loadGivenDates(){

  var select_year = document.getElementById("given_birth_date_year");
  var select_month = document.getElementById("given_birth_date_month");
  var select_day = document.getElementById("given_birth_date_day");
  var el = document.createElement("option");
  el.textContent = "None";
  el.value = "None";
  select_year.appendChild(el);
  var el1 = document.createElement("option");
  el1.textContent = "None";
  el1.value = "None";
  select_month.appendChild(el1);
  var el2 = document.createElement("option");
  el2.textContent = "None";
  el2.value = "None";
  select_day.appendChild(el2);

  for (var i=1930;i<=2002;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    el.value = String(i);
    select_year.appendChild(el);
  }
  for (var i=1;i<=12;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    if(i<10){
    el.value = '0'.concat(String(i));
  }else{
    el.value = String(i);
  }
    select_month.appendChild(el);
  }
  for (var i=1;i<=31;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    if(i<10){
    el.value = '0'.concat(String(i));
  }else{
    el.value = String(i);
  }
    select_day.appendChild(el);
  }
}
function loadDates(){

  var select_year = document.getElementById("birth_date_year");
  var select_month = document.getElementById("birth_date_month");
  var select_day = document.getElementById("birth_date_day");
  var el = document.createElement("option");
  el.textContent = "None";
  el.value = "None";
  select_year.appendChild(el);
  var el1 = document.createElement("option");
  el1.textContent = "None";
  el1.value = "None";
  select_month.appendChild(el1);
  var el2 = document.createElement("option");
  el2.textContent = "None";
  el2.value = "None";
  select_day.appendChild(el2);

  for (var i=1930;i<=2002;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    el.value = String(i);
    select_year.appendChild(el);
  }
  for (var i=1;i<=12;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    if(i<10){
    el.value = '0'.concat(String(i));
  }else{
    el.value = String(i);
  }
    select_month.appendChild(el);
  }
  for (var i=1;i<=31;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    if(i<10){
    el.value = '0'.concat(String(i));
  }else{
    el.value = String(i);
  }
    select_day.appendChild(el);
  }
}

function httpGetAsync()
{
var theUrl = "http://localhost/customer/fetch_customer.php";
var xmlHttp = new XMLHttpRequest();
xmlHttp.onreadystatechange = function() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
        if(xmlHttp.responseText =="0"){
          console.log("here1");
          document.getElementById("error_window").innerHTML ="Error while fetching customers, please try again";
          document.getElementById("error_window").style.backgroundColor="red";
        }else{
        if(document.getElementById("error_window").innerHTML =="Error while fetching customers, please try again" ){
        document.getElementById("error_window").innerHTML ="";
        document.getElementById("error_window").style.backgroundColor="white";
      }
        ids = JSON.parse(xmlHttp.responseText)[0];
        for(var i=0;i<ids.length;i++){
          points[ids[i]] = JSON.parse(xmlHttp.responseText)[1][i];
          names[ids[i]] = JSON.parse(xmlHttp.responseText)[2][i];
          birth_dates[ids[i]] = JSON.parse(xmlHttp.responseText)[3][i];
          sexes[ids[i]] = JSON.parse(xmlHttp.responseText)[4][i];
          emails[ids[i]] = JSON.parse(xmlHttp.responseText)[5][i];
          ages[ids[i]] = JSON.parse(xmlHttp.responseText)[6][i];
          marriage_statuses[ids[i]] = JSON.parse(xmlHttp.responseText)[7][i];
          number_of_childrenes[ids[i]] = JSON.parse(xmlHttp.responseText)[8][i];
          cities[ids[i]] = JSON.parse(xmlHttp.responseText)[9][i];
          streets[ids[i]] = JSON.parse(xmlHttp.responseText)[10][i];
          numbers[ids[i]] = JSON.parse(xmlHttp.responseText)[11][i];
          postal_codes[ids[i]] = JSON.parse(xmlHttp.responseText)[12][i];
          pets[ids[i]] = JSON.parse(xmlHttp.responseText)[13][i];
          telephones[ids[i]] = JSON.parse(xmlHttp.responseText)[14][ids[i]];
          console.log(ids[i]);

        }
      }
    }
}
xmlHttp.open("POST", theUrl, true); // true for asynchronous
xmlHttp.send(null);
console.log("test");
}


function upload(){
  var xmlhttp = new XMLHttpRequest();
  var to_upload = [];
  var year = new Date().getFullYear()
  to_upload[14] = cur_id;
  to_upload[0] = document.getElementById("card_id").value;
  to_upload[1] = document.getElementById("points").value;
  to_upload[2] = document.getElementById("name").value;
  to_upload[3] = document.getElementById("email").value;
  to_upload[4] = document.getElementById("number_of_children").value;
  to_upload[5] = document.getElementById("city").value;
  to_upload[6] = document.getElementById("street").value;
  to_upload[7] = document.getElementById("postal_code").value;
  to_upload[8] = document.getElementById("sex").value;
  to_upload[9] = document.getElementById("marriage_status").value;
  to_upload[10] = document.getElementById("pet").value;
  to_upload[11] = document.getElementById("number").value;
  to_upload[12] = document.getElementById("birth_date_year").value.concat(document.getElementById("birth_date_month").value).concat(document.getElementById("birth_date_day").value);
  to_upload[13] = year - parseInt(document.getElementById("birth_date_year").value);

  var total_tel=[];
  for(var j=0; j<telfs;j++){
    if(document.getElementById("telephone-input"+j)){
      total_tel.push((document.getElementById("telephone-input"+j)).value);
    }
  }
  to_upload[15] = total_tel;

  console.log(total_tel);
  var jsonString = JSON.stringify(to_upload);

  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
           if(xmlhttp.responseText.includes("error")){
             document.getElementById("error_window").innerHTML =xmlhttp.responseText;
             document.getElementById("error_window").style.backgroundColor="red";

          }else{
            document.getElementById("error_window").innerHTML =" Successfull upload";
            document.getElementById("error_window").style.backgroundColor="green";
           }
       }else{
         //øalert("failed to upload");

       }
  }
  var url;
  if(cur_id>0){
    url = "http://localhost/customer/update_customer.php";
  }else{
    url = "http://localhost/customer/insert_customer.php"
  }
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(jsonString);
  clear();
  httpGetAsync();

}
function clear(){
  console.log("clear called");
  document.getElementById("card_id").value = "";
  document.getElementById("points").value="";
  document.getElementById("name").value="";
  document.getElementById("email").value="";
  document.getElementById("number_of_children").value="";
  document.getElementById("city").value="";
  document.getElementById("street").value="";
  document.getElementById("postal_code").value="";
  document.getElementById("sex").value="None";
  document.getElementById("marriage_status").value="None";
  document.getElementById("pet").value="None";
  document.getElementById("number").value="";
  document.getElementById("birth_date_year").value="None";
  document.getElementById("birth_date_month").value="None";
  document.getElementById("birth_date_day").value="None";
  //document.getElementById("telephone").value="";
  for(var j=0; j<telfs;j++){
    if(document.getElementById("telephone-input"+j)){
      document.getElementById("telephone-input"+j).value="";
    }
  }
  //document.getElementById("birth_date_year").value.concat(document.getElementById("birth_date_month").value).concat(document.getElementById("birth_date_day").value);
}
function doo(){
  cur_id = -1;
  console.log("clear called");
  document.getElementById("the_bar").style.visibility = "hidden";
  document.getElementById("workspace").style.visibility = "visible";
  if(document.getElementById("delete_button")){
  document.getElementById("delete_button").remove();
}
  document.getElementById("card_id").value = "";
  document.getElementById("points").value="";
  document.getElementById("name").value="";
  document.getElementById("email").value="";
  document.getElementById("number_of_children").value="";
  document.getElementById("city").value="";
  document.getElementById("street").value="";
  document.getElementById("postal_code").value="";
  document.getElementById("sex").value="None";
  document.getElementById("marriage_status").value="None";
  document.getElementById("pet").value="None";
  document.getElementById("number").value="";
  //document.getElementById("telephone").value="";
  for(var j=0; j<telfs;j++){
    if(document.getElementById("telephone-input"+j)){
      document.getElementById("telephone-input"+j).value="";
    }
  }
  document.getElementById("birth_date_year").value="None";
  document.getElementById("birth_date_month").value="None";
  document.getElementById("birth_date_day").value="None";
}
function redoo(){
  document.getElementById("the_bar").style.visibility = "visible";
  document.getElementById("workspace").style.visibility = "hidden";


}
function search(){

  var found =0;
  console.log("search");
  var given_id = document.getElementById("given_card_id").value;
  var given_email = document.getElementById("given_email").value;
  var selected_year = document.getElementById("given_birth_date_year").value;
  var selected_month = document.getElementById("given_birth_date_month").value;
  var selected_day = document.getElementById("given_birth_date_day").value;
  var given_date = selected_year.concat("-").concat(selected_month).concat("-").concat(selected_day);
  console.log(given_id);
  if(given_id !=""){
    for(var i=0;i<ids.length;i++){
      if(given_id==ids[i]){
        console.log(ids);
        console.log(i);
        load_elems(i,ids[i]);
        found =1;
        break;
      }
    }
  }else {
    console.log(emails[ids[0]]);

    if((given_email!="")&&given_date!=""){
      for(var i=0;i<ids.length;i++){
        console.log(emails[ids[i]]);
        console.log(birth_dates[ids[i]]);
        console.log(given_email);
        console.log(given_date);
        if ((emails[ids[i]] ==given_email) &&(birth_dates[ids[i]]== given_date)){
          load_elems(i,ids[i]);
          found =1;
          console.log("found");
          break;
        }
      }
  }else{
    alert("not search data given")
  }
}

if(!found){
alert("not found");}
}

function load_elems(n1,n){
  cur_id=ids[n1];
  console.log("n:"+n);
  console.log("id:"+ids[n]);
  var to_delete = ids[n1];
  document.getElementById("workspace").style.visibility = "visible";
  var work = document.getElementById("workspace");
  var el2 = document.createElement("button");
  el2.id = "delete_button"
  el2.onclick = function(){
    console.log("here");
    var xmlhttp = new XMLHttpRequest();
    jsonString =JSON.stringify(to_delete)
    xmlhttp.onreadystatechange = function() {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
             //carthago delenda est
             clear();
             httpGetAsync();
             if(xmlhttp.responseText.includes("0")){
               document.getElementById("error_window").innerHTML ="Customer deleted successfully";
               document.getElementById("error_window").style.backgroundColor="green";
             }else{
               document.getElementById("error_window").innerHTML ="Couldn't delete Customer";
               document.getElementById("error_window").style.backgroundColor="red";
             }
             document.getElementById("workspace").style.visibility = "hidden";
             document.getElementById("the_bar").style.visibility = "hidden";

    }

  }
    xmlhttp.open("POST","http://localhost/customer/delete_customer.php",true);
    xmlhttp.setRequestHeader("Content-type", "application/json");
    xmlhttp.send(jsonString);


  }
  el2.textContent = "delete";
  work.appendChild(el2);
  document.getElementById("insert_button").textContent="update";
  document.getElementById("the_bar").style.visibility = "hidden";
  document.getElementById("card_id").value = ids[n1];
  document.getElementById("points").value=points[n];
  document.getElementById("name").value=names[n];
  document.getElementById("email").value=emails[n];
  document.getElementById("number_of_children").value=number_of_childrenes[n];
  document.getElementById("city").value=cities[n];
  document.getElementById("street").value=streets[n];
  document.getElementById("postal_code").value=postal_codes[n];
  document.getElementById("sex").value=sexes[n];
  document.getElementById("telephone-input0").value=telephones[ids[n1]][0];
  for(var k=1; k<telephones[ids[n1]].length;k++){
    console.log(telephones[ids[n1]][k]);
    add_tel(telephones[ids[n1]][k]);
  }
  document.getElementById("marriage_status").value=marriage_statuses[n];
  if(pets[n] != ""){
  document.getElementById("pet").value=pets[n];
}else{
  document.getElementById("pet").value="None";

}
  document.getElementById("number").value=numbers[n];
  document.getElementById("birth_date_year").value=String(birth_dates[n].slice(0,4));
  document.getElementById("birth_date_month").value=String(birth_dates[n].slice(5,7));
  document.getElementById("birth_date_day").value=String(birth_dates[n].slice(8,10));
}
function rem_telf(c_id){
  console.log(c_id);
  document.getElementById("telephone-label"+c_id).remove();
  document.getElementById("telephone-input"+c_id).remove();
  document.getElementById(c_id).remove();

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


  elx.innerHTML += "<button id ='"+telfs+"'onclick='rem_telf("+telfs+");'>Remove</button>";
  elx.innerHTML +="<br />";
  telfs++;

}
