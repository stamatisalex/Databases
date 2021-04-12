var selected_criteria =[];
function load_shops(){
  var xmlhttp = new XMLHttpRequest();
  var shop_area = document.getElementById("shop_select");
  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
           //alert("uploaded")
           console.log(JSON.parse(xmlhttp.responseText));
           for(var j=0; j<JSON.parse(xmlhttp.responseText).length;j++){
             var el = document.createElement("option");
             el.value = JSON.parse(xmlhttp.responseText)[j][0]
             el.textContent = JSON.parse(xmlhttp.responseText)[j][1] +" , "+JSON.parse(xmlhttp.responseText)[j][2];
             shop_area.appendChild(el);
           }
       }else{
         //øalert("failed to upload");

       }
  }

  xmlhttp.open("POST","http://localhost/ask_a/quick_fetch_sho.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(null);
}

function loadDates(){

  var down_year = document.getElementById("down_year");
  var down_month = document.getElementById("down_month");
  var down_day = document.getElementById("down_day");
  var up_year = document.getElementById("up_year");
  var up_month = document.getElementById("up_month");
  var up_day = document.getElementById("up_day");

  var d = new Date();
  var n = d.getFullYear();
  var d = new Date();

  var m = d.getMonth() +1;
  var d = new Date();

  var cd = d.getDate();
  console.log(m);
  console.log(cd);
  console.log(n);


  for (var i=2015;i<=2020;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    el.value = String(i);
    down_year.appendChild(el);
    var el1 = document.createElement("option");
    el1.textContent = String(i);
    el1.value = String(i);
    up_year.appendChild(el1);
    if(i == n){
      el1.selected ="selected";
    }
  }


  for (var i=1;i<=12;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    var el1 = document.createElement("option");
    el1.textContent = String(i);
    if(i<10){
    el.value = '0'.concat(String(i));
    el1.value = '0'.concat(String(i));

  }else{
    el.value = String(i);
    el1.value = String(i);

  }
  if(i == m){
    el.selected ="selected";
  }
    down_month.appendChild(el1);
    up_month.appendChild(el);

  }
  for (var i=1;i<=31;i++){
    var el = document.createElement("option");
    el.textContent = String(i);
    var el1 = document.createElement("option");
    el1.textContent = String(i);
    if(i<10){
    el.value = '0'.concat(String(i));
    el1.value = '0'.concat(String(i));

  }else{
    el.value = String(i);
    el1.value = String(i);

  }
  if(i == cd){
    el.selected ="selected";
  }
    down_day.appendChild(el1);
    up_day.appendChild(el);

  }
}


function dis(checkboxElem) {
  if (document.getElementById(checkboxElem).checked) {
    document.getElementById(checkboxElem+"_box").style.display="block";
    selected_criteria.push(checkboxElem);
  } else {
    document.getElementById(checkboxElem+"_box").style.display="none";
    for(var i=0;i<selected_criteria.length;i++){
      if(selected_criteria[i] == checkboxElem){
        selected_criteria.splice(i,1);
        break;
      }
    }

  }
  console.log(selected_criteria);
}


function send(){

  var final = [];
  final[0]  =selected_criteria;
  final[3] = document.getElementById("shop_select").value;
  final[1] =[];
  final[2] =[];

  for(var i=0;i<selected_criteria.length;i++){
    if(selected_criteria[i]=="date_time"){
      final[1][i]= document.getElementById("down_year").value.concat("-").concat(document.getElementById("down_month").value).concat("-").concat(document.getElementById("down_day").value);
      final[2][i]= document.getElementById("up_year").value.concat("-").concat(document.getElementById("up_month").value).concat("-").concat(document.getElementById("up_day").value);

    }else if(selected_criteria[i] == "total_number_of_products"){
      if(document.getElementById("tot_num_down").value == ""){
        final[1][i] =0;
      }else{
        final[1][i] =document.getElementById("tot_num_down").value;
      }
      if(document.getElementById("tot_num_up").value == ""){
        final[2][i] =9999;
      }else{
        final[2][i] =document.getElementById("tot_num_up").value;
      }
    }else if(selected_criteria[i]=="total_cost"){
      if(document.getElementById("tot_cost_down").value == ""){
        final[1][i] =0;
      }else{
        final[1][i] =document.getElementById("tot_cost_down").value;
      }
      if(document.getElementById("tot_cost_up").value == ""){
        final[2][i] =999999;
      }else{
        final[2][i] =document.getElementById("tot_cost_up").value;
      }
    }else if(selected_criteria[i]=="payment_method"){
      final[1][i] = document.getElementById("payment").value;
      final[2][i] = "-";

    }else if(selected_criteria[i]=="category"){
      var categories =[];
      if(document.getElementById("fresh_products").checked){
        categories.push(document.getElementById("fresh_products").value);
      }
      if(document.getElementById("frozen_foods").checked){
        categories.push(document.getElementById("frozen_foods").value);
      }
      if(document.getElementById("household_goods").checked){
        categories.push(document.getElementById("household_goods").value);
      }
      if(document.getElementById("pet_foods_and_products").checked){
        categories.push(document.getElementById("pet_foods_and_products").value);
      }
      if(document.getElementById("toiletries").checked){
        categories.push(document.getElementById("toiletries").value);
      }
      if(document.getElementById("wine_goods").checked){
        categories.push(document.getElementById("wine_goods").value);
      }
      final[1][i] = categories;
      final[2][i] = "-";

    }

  }
  var jsonString = JSON.stringify(final);
  //console.log(jsonString);
  var xmlhttp = new XMLHttpRequest();
  var final_area = document.getElementById("return_table");
  xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
              var final_result = JSON.parse(xmlhttp.responseText);

              console.log(final_result);
              if(final_result[0]){
              var myhtml="<table>";
              myhtml +="<th>Payment Method</th><th>Date Time</th><th>transaction id</th><th>total number of products</th><th>total cost</th><th>card id</th><th>store id</th>";
              for(var j=0;j<final_result[0].length;j++){
                myhtml +="<tr><td>"+final_result[0][j]+"</td><td>"+final_result[1][j]+"</td><td>"+final_result[2][j]+"</td><td>"+final_result[3][j]+"</td><td>"+final_result[4][j]+"</td><td>"+final_result[5][j]+"</td><td>"+final_result[6][j]+"</td></tr>"
              }
              myhtml+="</table>";
              final_area.innerHTML =myhtml;
            }else{final_area.innerHTML ="No such items found";}
           }

       }


  xmlhttp.open("POST","http://localhost/ask_a/fetch_trans.php",true);
  xmlhttp.setRequestHeader("Content-type", "application/json");
  xmlhttp.send(jsonString);


  console.log(final);
}
