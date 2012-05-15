function checkPick(where)
{
  if (where == "pickup")
  {
    var dropdown = "fromloc";
    var field = "pickup";
  }
  else if (where == "dropoff")
  {
    var dropdown = "toloc";
    var field = "dropoff";
  }
  var loc = document.forms["theForm"][dropdown];  
  var textfield = document.forms["theForm"][field];
  if (loc.value == "Other")
  {
    textfield.disabled = false;
    textfield.focus();
  }
  else
  {
    if (textfield.disabled == false)
    {
      textfield.disabled = true;
      textfield.value = "";
    }
  }
}

function otherPlace(where)
{
  if (where == "pickup")
  {
    var dropdown = "fromloc";
    var field = "pickup";
  }
  else if (where == "dropoff")
  {
    var dropdown = "toloc";
    var field = "dropoff";
  }
  var loc = document.forms["theForm"][dropdown];  
  var textfield = document.forms["theForm"][field];
  loc.value = "Other";
  textfield.disabled = false;
}

function checkFilled(where)
{
  if (where == "pickup")
  {
    var dropdown = "fromloc";
    var field = "pickup";
  }
  else if (where == "dropoff")
  {
    var dropdown = "toloc";
    var field = "dropoff";
  }
  var loc = document.forms["theForm"][dropdown];  
  var textfield = document.forms["theForm"][field];
  if (textfield.value  == "")
  {
    textfield.disabled = true;
    loc.value = "Null";
  }
}
