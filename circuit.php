<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php include("classes.php"); ?>

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
<title>Add a circuit ride</title>
<link rel="stylesheet" href="style.css">
</head>
<body id="circuit">
<div class="container">
	<?php include("header.php"); ?>
	<div class="main-content">
		<div class="rideform">
<form class="main" method="post" action="actions.php?action=circuit">
<fieldset><legend>&nbsp;Circuit Short Form&nbsp;</legend>
<br />
<p><label class="left">Car:</label>
<input class="number" name="car"></p>
<p><label class="left">What is the name:</label>
<input class="field" name="name"></p>
<p><label class="left">How many:</label>
<input class="number" name="riders"></p>
<p><label class="left">Dropoff:</label>
<input class="field" name="dropoff"></p>
<p><label class="left">Bryan or CS?:</label>
     		<select name="location" class="combo">
                	<option value="CS"> College Station </option>
                     	<option value="B"> Bryan </option>
                     	<option value="Other"> Other </option></select></p>
    <center><input name="submit" type="submit" class="assign" value="Add Ride" style="font-size:150%;" /></center> </center> 
</fieldset>
</form>
</div>
<!--
<div class="instructions">
		<fieldset><legend>&nbsp;Instructions&nbsp;</legend>
		<p>When doing incoming line, here are a few guidelines to follow:</p>
		<p>Answer the phone "Howdy, CARPOOL" and begin asking the questions once the patron has asked for a ride.</p>
		<p>If it gets too loud to hear in the phone room, tell the execs to BE QUIET!</p>
		<p>If someone calls asking to be taken home to Traditions (apts by NG), then ask them what building they live in, who their RA is and explain that we don't take people to bars if you suspect they just want a ride to NG.</p>
		<p>If someone calls from NG and the Herschels haven't left yet (10pm-11:30pm), please take their information.</p>
		<p>If someone calls from NG when the Herschels are there, then instruct them to go see the people in Lime Green Shirts.</p>
		<p>If you have ANY questions, please ask an exec or the DIC.</p>
		<br />
		<p>HAVE FUN!</p>
		</fieldset>
		</div> -->
</div>
<?php include("footer.php"); ?>
</div>
</body>
</html>