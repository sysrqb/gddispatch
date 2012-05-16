<!--Test to perfect layout. Will make echoing in conf.php easier once I know how to set everything up -->
<html><title>
Administrative Page
</title>
<body>
<h2><center> Car Administration </center></h2>
<br><div style="width:34%; float:left">
Current Number of Cars: &lt; To be filled &gt;
<br>
<br>
<form action='adminact.php?action=numcars' method='post'>
Thursday's Date (YYYY-MM-DD)
	<input type='text' name='week' maxlength=10 size=11 />
<br>Update the number of cars: 
	<input type='text' name='numcars' maxlength=3 size=5 />
	<input type='submit' value='Update' />
</form>
Add/Update Car Info for car number: 
<form action='adminact.php?action=info' method='post'>
	<select name='numcars'>
		<?php
		$num = 7;
		for($i = 1; $i<$num+1;$i++){
			echo "<option value='$i'>$i</option>";
		} ?>
	</select>
<br>	Make: <input type='text' name='make' />
<br>	Model: <input type='text' name='model' />
<br>	Year: <input type='text' name='year' />
<br>	Color: <input type='text' name='color' />
<br>	License Plate: <input type='text' name='lp' />
<br>	State: <input type='text' name='state' />
<p> <input type='submit' value='Add/Update' /></form></p>
<br><br></div>

<div style="width:33%; float:left">Add/Update Damage Report for Car: 
<form action='adminact.php?action=damage' method='post'>
	<select name='numcardamage'>
		<?php
		$num = 7;
		for($i = 1; $i<$num+1;$i++){
			echo "<option value='$i'>$i</option>";
		} ?>
	</select>
<br>	Date Discovered: <input type='text' name='datediscovered'/>(YYYY-MM-DD)
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
<div style="width=33%; float:right">
	<form action='adminact.php?action=authentication' method='post'>
Disable Local Admin Account: 
		<input type='checkbox' name='localadmin' value='FALSE' />
<br>		LDAP Server Address <input type='text' name='ldapserver' />
<br>		MySQL Server Address <input type='text' name='mysqlserver' />
<br>		MySQL Username <input type='text' name='mysqluser' />
<br>		MySQL Password <input type='password' name='mysqlpass' />
<br>		<input type='submit' value='Update' />
	</form>	
	<form action='adminact.php?action=init' method='post'>
		Initialize Database: <input type='submit' value='Initialize' />
	</form>
	<form action='adminact.php?action=checkcon' method='post' >
<br>		Check Connection to DB: <input type='button' value='Test' />
<br>		Check Connection to LDAP: <input type='button' value='Test' />
	</form>
		
</div></body></html>

