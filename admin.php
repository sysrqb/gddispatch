<?php 
  require_once('adminfunctions.php');
  require_once('login-auth.php');
  if(!isset($_SESSION))
    exit();
  if(!isset($_SESSION['authenticated_admin']))
    exit();
  if($_SESSION['authenticated_admin'] != true)
    exit();
  if(isset($_SESSION) &&
      isset($_SESSION['data']) &&
      is_array($_SESSION['data']))
  {
    if(isset($_SESSION['data']['week'])) {
      $info = getCarInfo($_SESSION['data']['week']); 
      $info['week'] = $_SESSION['data']['week'];
    }
    else
      $info = getCarInfo(); 
    if(isset($_SESSION['data']['carinfoerror']))
      $info['carinfoerror'] = $_SESSION['data']['carinfoerror'];
  }
?><!--Test to perfect layout. Will make echoing in conf.php easier once I know how to set everything up -->
<html><head><title>
Administrative Page
</title>
<script type="text/javascript">
//<!--
function getFieldValues(type, week){
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      document.getElementById("carinfo_carnum").innerHTML=xmlhttp.responseText;
      document.getElementById("carinfo_carnum").disabled = false;
      if(type == 1){
        document.getElementById("carinfo_make").disabled = false;
        document.getElementById("carinfo_model").disabled = false;
        document.getElementById("carinfo_year").disabled = false;
        document.getElementById("carinfo_color").disabled = false;
        document.getElementById("carinfo_lp").disabled = false;
        document.getElementById("carinfo_state").disabled = false;
      }
        
    }
  }
  if(type === 0)
    xmlhttp.open("GET","adminact.php?action=carinfoupdate&week=" + week, true);
  else if(type === 1)
    xmlhttp.open("GET","adminact.php?action=carinfoadd&week=" + week, true);
  else
   return;
  xmlhttp.send();
}

function getFieldValuesDamage(type, week){
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      document.getElementById("damage_numcar").innerHTML=xmlhttp.responseText;
    }
  }
  if(type === 0)
    xmlhttp.open("GET","adminact.php?action=carinfoupdate&week=" + week, true);
  else if(type === 1)
    xmlhttp.open("GET","adminact.php?action=carinfoadd&week=" + week, true);
  else
   return;
  xmlhttp.send();
}

function adjustForCarOp_Week(){
  var re = /^\d{4}-\d{2}-\d{2}$/;
  var weekfield = document.getElementById("carinfoweek");
  if(!re.test(weekfield.value)){
    alert("Please make sure you enter the week as YYYY-MM-DD, thanks!");
    return;
  }
  var fields = document.getElementsByName("carinfoop");
  if(fields.length == 2){
    var oper = (fields[0].checked ? fields[0].value : fields[1].value);
    if(oper == "update"){
      /* XHR for available cars */
      getFieldValues(0, weekfield.value);
    }
    else if(oper == "add"){
      /* XHR for available cars */
      getFieldValues(1, weekfield.value);
    }
  }
}


function adjustForCarDamage_Week(){
  var re = /^\d{4}-\d{2}-\d{2}$/;
  var weekfield = document.getElementById("damage_week");
  if(!re.test(weekfield.value)){
    alert("Please make sure you enter the week as YYYY-MM-DD, thanks!");
    return;
  }
  var fields = document.getElementsByName("damage_op");
  if(fields.length == 2){
    var oper = (fields[0].checked ? fields[0].value : fields[1].value);
    if(oper == "update"){
      /* XHR for available cars */
      getFieldValues(0, weekfield.value);
    }
    else if(oper == "add"){
      /* XHR for available cars */
      getFieldValues(1, weekfield.value);
    }
  }
}

function fillFormIfUpdate(){
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      var response = xmlhttp.responseText;
      var jsonresp = eval("(" + response + ")");
      document.getElementById("carinfo_make").value = jsonresp.make;
      document.getElementById("carinfo_model").value = jsonresp.model;
      document.getElementById("carinfo_year").value = jsonresp.year;
      document.getElementById("carinfo_color").value = jsonresp.color;
      document.getElementById("carinfo_lp").value = jsonresp.licenseplate;
      document.getElementById("carinfo_state").value = jsonresp.state;
      document.getElementById("carinfo_cid").value = jsonresp.cid;


      document.getElementById("carinfo_make").disabled = false;
      document.getElementById("carinfo_model").disabled = false;
      document.getElementById("carinfo_year").disabled = false;
      document.getElementById("carinfo_color").disabled = false;
      document.getElementById("carinfo_lp").disabled = false;
      document.getElementById("carinfo_state").disabled = false;
    }
  }
  var week = document.getElementById("carinfoweek");
  var carnum = document.getElementById("carinfo_carnum");
  xmlhttp.open("GET","adminact.php?action=carinfofields&week=" +
               week.value + "&carnum=" + carnum.value, true);
  xmlhttp.send();
}

function fillFormIfUpdateDamage(){
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      var response = xmlhttp.responseText;
      var jsonresp = eval("(" + response + ")");
      document.getElementById("carinfo_make").value = jsonresp.make;
      document.getElementById("carinfo_model").value = jsonresp.model;
      document.getElementById("carinfo_year").value = jsonresp.year;
      document.getElementById("carinfo_color").value = jsonresp.color;
      document.getElementById("carinfo_lp").value = jsonresp.licenseplate;
      document.getElementById("carinfo_state").value = jsonresp.state;
      document.getElementById("carinfo_cid").value = jsonresp.cid;


      document.getElementById("carinfo_make").disabled = false;
      document.getElementById("carinfo_model").disabled = false;
      document.getElementById("carinfo_year").disabled = false;
      document.getElementById("carinfo_color").disabled = false;
      document.getElementById("carinfo_lp").disabled = false;
      document.getElementById("carinfo_state").disabled = false;
    }
  }
  var week = document.getElementById("carinfoweek");
  var carnum = document.getElementById("carinfo_carnum");
  xmlhttp.open("GET","adminact.php?action=carinfofields&week=" +
               week.value + "&carnum=" + carnum.value, true);
  xmlhttp.send();
}
//-->
</script>
</head>
<body style='background-color:#00B000'>
<h2><center> Car Administration </center></h2>
<br><div style="width:24%; float:left; border-style: double; border-width: medium; background-color: #22D000; padding: 1em 0 0 1em;">
Current Number of Cars for 
    <?php 
      if(isset($info['week']))
        echo $info['week']; 
      else
        echo 'this week';
    ?>
: <?php
    if(isset($info['carnum']))
      echo $info['carnum'];
    else
      echo '0';
  ?>
<br>
<br>
<form action='adminact.php?action=numcars' method='post'>
<?php
  if(isset($info['carnumerror'])){
?>
  <p><span class="error">
<?php
  echo $info['carnumerror'];
?>
  </span></p>
<?php
  }
?>


Thursday's Date (YYYY-MM-DD)
	<input type='text' name='week' maxlength=10 size=11 value='<?php if(isset($info['date'])) echo $info['date']; ?>'/>
<br>Update the number of cars: 
	<input type='text' name='numcars' maxlength=3 size=5 />
	<input type='submit' value='Update' />
</form>
<br />
Add/Update Car Info for car number: 
<p><span class="error">
  <?php if(isset($info['carinfoerror']))
      echo $info['carinfoerror']; ?>
  </span></p>
<form action='adminact.php?action=carinfo' method='post'>
  <input type="radio" name="carinfoop" value="update">Update</input>
  <input type="radio" name="carinfoop" value="add" checked>Add</input>
  <br />
  <br />
  <label for="carinfoweek">Week: <input type="text" name="week" id="carinfoweek" onBlur="adjustForCarOp_Week()"></label>
  <br />

        <input type="hidden" name="cid" id="carinfo_cid" value="0"/>
	<label for="carnum">Car: <select disabled name='carnum' id="carinfo_carnum" onChange="fillFormIfUpdate()">
	</select></label>
<br>	Make: <input disabled type='text' name='make' id="carinfo_make" value='<?php if(isset($info['model'])) echo $row['model']; ?>'/>
<br>	Model: <input disabled type='text' name='model' id="carinfo_model" />
<br>	Year: <input disabled type='text' name='year' id="carinfo_year" />
<br>	Color: <input disabled type='text' name='color' id="carinfo_color"/>
<br>	License Plate: <input disabled type='text' name='lp' id="carinfo_lp"/>
<br>	State: <input disabled type='text' name='state' id="carinfo_state"/>
<p> <input type='submit' value='Add/Update' /></form></p>
<br><br></div>

<div style="width:34%; float:left; border-style: double; border-width: medium; background-color: #22D000; padding: 1em 0 0 1em;">
<form action='adminact.php?action=damage' method='post'>
  <input type="radio" name="damage_op" value="update">Update</input>
  <input type="radio" name="damage_op" value="add" checked>Add</input>
  <br />
  <label for="damage_week">Week: <input type="text" name="week" id="damage_week" onBlur="adjustForCarDamage_Week()"></label>
  <br />

  <label for="damage_numcar">Add/Update Damage Report for Car: <select name='damage_numcar' onChange="fillFormIfUpdateDamage()"></select>
<br>	Date Occurred/Discovered (YYYY-MM-DD): <input type='text' name='datediscovered'/>
<br>	Type:
		<select name='damagetype'>
			<option value='collision'>Collision</option>
			<option value='scrape'>Scrape</option>
			<option value='paint'>Paint Damage</option>
			<option value='bodydamage'>Body Damage</option>
			<option value='other'>Other (Please Specify Below)</option>
		</select>
<br>	Other: <input type='text' name='other' />
<br>	Description: 
<br>	<textarea name='descript' cols=30 rows=10 ></textarea>
<br>
<br>	<input type='submit' value='Add/Update' />
</form></div>
<div style="width:24%; float: left; border-style: double; border-width: medium; background-color: #22D000; padding: 1em 0 0 1em;">
	<form action='adminact.php?action=authentication' method='post'>
Disable Local Admin Account: 
		<input type='checkbox' name='localadmin' value='FALSE' />
<br>		LDAP Server Address <input type='text' name='ldapserver' />
<br>		MySQL Server Address <input type='text' name='mysqlserver' />
<br>		MySQL Username <input type='text' name='mysqluser' />
<br>		MySQL Password <input type='password' name='mysqlpass' />
<br>		<input type='submit' value='Update' />
	</form>	
	<form action='adminact.php?action=dbinit' method='post'>
		Initialize Database: <input type='submit' value='Initialize' />
	</form>
	<form action='adminact.php?action=checkcon' method='post' >
<br>		Check Connection to DB: <input type='button' value='Test' />
<br>		Check Connection to LDAP: <input type='button' value='Test' />
	</form>
		
</div>
<div style="width:12%; float: left; border-style: double; border-width: medium; background-color: #22D000; padding-left: 1em;">
<p><a href="incoming.php">Logout</a></p>
<p><a href="grantaccess.php">Grant Users Access</a></p>
<p><a href="authedusers.php">Current Active Authenticated Users</a></p>
</div>


</body></html>
