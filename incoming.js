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

function verifyIncoming()
{
  var error = "";
  var str = "";
  if(document.forms["theForm"]["name"].value == "")
    str += "[Name] ";
  if(document.forms["theForm"]["cell1"].value == "")
    str += "[Area Code] ";
  if(document.forms["theForm"]["cell2"].value == "")
    str += "[Beginning of Phone Number] ";
  if(document.forms["theForm"]["cell3"].value == "")
    str += "[End of Phone Number] ";
  if(document.forms["theForm"]["riders"].value == "")
    str += "[Number of passangers] ";
  if(document.forms["theForm"]["fromloc"].value == "Null")
  {
    str += "[Pickup] ";
  }
  else  
  {
    if(document.forms["theForm"]["fromloc"].value == "Other")
    {
      if(document.forms["theForm"]["pickup"].value == "") 
      {
        str += "[Pickup Location] ";
      }
    }
    else
    {
      if(document.forms["theForm"]["pickup"].value != "") 
      {
        error += "[Pickup has invalid value!] ";
      }
    }
  }
  if(document.forms["theForm"]["toloc"].value == "Null")
  {
    str += "[Dropoff] ";
  }
  else  
  {
    if(document.forms["theForm"]["toloc"].value == "Other")
    {
      if(document.forms["theForm"]["dropoff"].value == "") 
      {
        str += "[Dropoff Location] ";
      }
    }
    else
    {
      if(document.forms["theForm"]["dropoff"].value != "") 
      {
        error += "[Dropoff has invalid value!] ";
      }
    }
  }
  if(error != "")
    str = str + "\nErrors: " + error;
  if(str != "")
    alert("Please fill in the Following Field: " + str);
  else
  {
    document.theForm.submit();
  }

}
