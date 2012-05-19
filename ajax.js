function autotab1(abc){
if (abc.length == 3)
document.theForm.cell2.focus();
}
function autotab2(abc){
if (abc.length == 3)
document.theForm.cell3.focus();
}

function highlight(id) {
	document.getElementById(id).className="highlight";
}
function hovers(bgCol,id) {
	document.getElementById(id).className=bgCol;
}


function assignride(url,divid)
{
var xmlhttp
try {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest(); }
  catch (e){
    // Internet Explorer
    try {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
    catch (e) {
      try {
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
      catch (e) {
        alert("Your browser does not support AJAX!");
        return false; } } }
xmlHttp.onreadystatechange=function() {
      if(xmlHttp.readyState==4) {
        document.getElementById(divid).innerHTML=xmlHttp.responseText;} }
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}

function phonecheck(cellp)
{
  var xmlhttp
  try {
    // Firefox, Opera 8.0+, Safari, Chrome
    xmlHttp=new XMLHttpRequest(); }
  catch (e){
    // Internet Explorer, but I hope this isn't the case,
    try {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
    catch (e) {
      try {
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
      catch (e) {
	//Die gracefully
        return false;
      }
    }
  }
  xmlHttp.onreadystatechange=function()
  {
    if(xmlHttp.readyState==4)
    {
      //document.getElementById("phonecheck").innerHTML=xmlHttp.responseText;
      var response = xmlHttp.responseText;
      if (response != "")
        alert(response);
    }
  }
  xmlHttp.open("GET","phonecheck.php?"+cellp,true);
  xmlHttp.send(null);
}



function carsEdit(carid,num,url)
{
var xmlhttp
try {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest(); }
  catch (e){
    // Internet Explorer
    try {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
    catch (e) {
      try {
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
      catch (e) {
        alert("Your browser does not support AJAX!");
        return false; } } }
xmlHttp.onreadystatechange=function() {
      if(xmlHttp.readyState==4) {
      	document.getElementById(carid).className="car-box car-edit";
        document.getElementById(carid).innerHTML=xmlHttp.responseText;} }
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}
