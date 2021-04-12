var bought = [];
var cost = 0;
var points = 0;
var timestamp="";
var time = [];
var selectedPerson;
var itemsdata={};
function sendRequest(){
    timestamp =(document.getElementById("year").value).concat("-").concat(document.getElementById("month").value).concat("-").concat(document.getElementById("day").value).concat(" ").concat(document.getElementById("hour").value).concat(":").concat(document.getElementById("minute").value).concat(":").concat(document.getElementById("second").value);

    var strUser = String(document.getElementById("cust_name").value);
    var a = document.getElementById("payment");
    var strpay = a.options[a.selectedIndex].value;
    var b = document.getElementById("selectPlace");
    var strpl = b.options[b.selectedIndex].value;

    var hold = [];
    hold[0] = bought;
    hold[1] = cost;
    hold[2] = points;
    hold[3] = timestamp;
    hold[4] =strUser;
    hold[5] =strpay;
    hold[6] = strpl;
    console.log(hold);

    var jsonString = JSON.stringify(hold);
    //console.log(jsonString);
    var xmlhttp = new XMLHttpRequest();
    if(bought.length>0){

    xmlhttp.onreadystatechange = function() {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200){

             console.log(xmlhttp.responseText)
             if(xmlhttp.responseText.includes("error")){
               alert(xmlhttp.responseText.substring(0, xmlhttp.responseText.indexOf('<br>')));
               error_area.innerHTML =xmlhttp.responseText.substring(0, xmlhttp.responseText.indexOf('<br>'));
               error_area.style.color = "red";
             }else{
               alert("successfully inserted");
               error_area.innerHTML ="successfully inserted";
               error_area.style.color = "green";


             }
             cancelInput();
             getStart();
         }else{
           //øalert("failed to upload");

         }
    }

    xmlhttp.open("POST","http://localhost/transaction/get_order.php",true);
    xmlhttp.setRequestHeader("Content-type", "application/json");
    xmlhttp.send(jsonString);

  }else{
    alert("customer has bought none products!");
  }
}
function rem(index){

  console.log("cost: "+cost);
  console.log("points: "+points);

    var found = 0;
    for(var i=0; i<bought.length;i++){
      if(bought[i] == itemsdata[4][index]){
        found = 1;
      }
    }
    if(!found){
      alert("cannot remove anymore");
      return;
    }
    cost -=itemsdata[0][index];
    points -=itemsdata[2][index];
    console.log("cost: "+cost);
    console.log("points: "+points);

    if(cost<0){
        cost =0;

    }
    if(points<0){
        points =0;
    }
    if(parseInt(document.getElementById(itemsdata[4][index]).innerHTML)>0){
    document.getElementById(itemsdata[4][index]).innerHTML = parseInt(document.getElementById(itemsdata[4][index]).innerHTML)-1;
    }
    document.getElementById("pay").innerHTML = "<h3>Pay,Customer!</h3><h2>Cost: "+ cost+" points: "+points+"</h2><br /><button onclick=sendRequest()>Send order!</button>";
    for (var i=0;i<bought.length;i++){

        if(bought[i] == itemsdata[4][index]){
            console.log("this");
            if(i != bought.length-1){
                console.log("i: "+i);
                bought.splice(i,1);
                console.log("bought: "+bought);
                break;
            }else{
                    bought.pop();
                    console.log("case2");
                    break;

                }
            //console.log(bought);
        }
    }
    console.log(bought);console.log(cost);console.log(points);

}

function test(index){

    console.log("nikos: "+itemsdata[4][index]);
    bought.push(itemsdata[4][index]);
    cost +=Number(itemsdata[0][index]);
    points +=Number(itemsdata[2][index]);
    console.log(bought);console.log(cost);console.log(points);
    document.getElementById(itemsdata[4][index]).innerHTML = parseInt(document.getElementById(itemsdata[4][index]).innerHTML)+1;
    console.log("cost: "+cost);
    console.log("points: "+points);

    document.getElementById("pay").innerHTML = "<h3>Pay,Customer!</h3><h2>Cost: "+ cost+" points: "+points+"</h2><br /><button onclick=sendRequest()>Send order!</button>";

}
function httpGetAsync()
{
selectedPerson = document.getElementById("selectPlace").value;
console.log(selectedPerson);
var psend = JSON.stringify(selectedPerson);
var theUrl = "http://localhost/transaction/cust.php";
var xmlHttp = new XMLHttpRequest();
itemsdata={};
xmlHttp.onreadystatechange = function() {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
        console.log(xmlHttp.responseText);
        if(xmlHttp.responseText.includes("error")){
          error_area.innerHTML =xmlHttp.responseText.substring(0, xmlHttp.responseText.indexOf('<br />'))
          error_area.style.color = "red";
        }else{
          cost =0;
          points = 0;
          document.getElementById("pay").innerHTML = "";

          error_area.innerHTML ="";
          product_store.innerHTML="";

          var myhtml='<table>';
          myhtml+='<th>Item</th><th>Price</th><th>actions</th>'
          itemsdata = JSON.parse(xmlHttp.responseText);

        for(var i=0; i<itemsdata[1].length;i++ ){
          myhtml+= '<tr><td>'+itemsdata[1][i]+' by '+itemsdata[3][i]+'</td><td>'+itemsdata[0][i]+'</td><td><button onclick=test('+i+')>buy</button> - <button onclick=rem('+i+')>remove</button><label id='+itemsdata[4][i]+'>0</label></td></tr>';
        }
        document.getElementById("product_store").innerHTML = myhtml+'</table>';
      }
    }
}
xmlHttp.open("POST", theUrl, true); // true for asynchronous
xmlHttp.setRequestHeader("Content-type", "application/json");
xmlHttp.send(psend);
}

function getStart(){
  var theUrl = "http://localhost/transaction/cust_start.php";
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function() {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
          var select = document.getElementById("selectPlace");
          var opt = "None";
          var vopt = "None";
          var el = document.createElement("option");
          el.textContent = opt;
          el.value = vopt;
          select.appendChild(el);
          var id =0;
          for(var i = 0; i < JSON.parse(xmlHttp.responseText)[0].length; i++) {


              var opt = JSON.parse(xmlHttp.responseText)[1][i]+" , "+ JSON.parse(xmlHttp.responseText)[2][i];
              var vopt = JSON.parse(xmlHttp.responseText)[0][i];
              var el = document.createElement("option");
              el.textContent = opt;
              el.value = vopt;
              select.appendChild(el);
          }
      }
  }
  xmlHttp.open("GET", theUrl, true); // true for asynchronous
  xmlHttp.send(null);
}

function cancelInput(){
  document.getElementById("input").style.visibility = "visible";
  /*for(var t=0;t<bought.length;t++){
    document.getElementById(bought[t]).innerHTML = 0;

  }*/
  document.getElementById("cust_name").value= "";
  document.getElementById("product_store").innerHTML ="";
  bought = [];
  cost = 0;
  points = 0;
  timestamp="";
  time = [];
  selectedPerson=-1;
  document.getElementById("pay").innerHTML = "";


}
