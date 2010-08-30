<?php 
//
require('cred.php');

/*
Define two variables:
gtime defines the current time GMT, in Year-Month-Day Hour:Minutes:Seconds format
time defines the current time in the currect timezone, in Year-Month-Day Hour:Minutes:Seconds format
*/
$gtime = gmdate('Y-m-d H:i:s');
$gdate = gmdate('Y-m-d');
$time = time('Y-m-d H:i:s');

/*
Define the array containing the prepared statement variables
*/
$prepare = array();

/*------------------------
Begin function definitions
------------------------*/


//create prepared statements
function prepare(){
	global $prepare; 
	$prepare = array('ridecount' => "SELECT * FROM rides WHERE DATE(ridedate) = ? AND status =?", 
		 'totalcount' => "SELECT SUM(riders) as total FROM rides WHERE ridedate = ? AND status = ?",
		 'setpreride' => "UPDATE rides SET precar = ?, status = 'waiting' WHERE num=?",
		 'getpreride' => "SELECT precar FROM rides WHERE num=?",
		 'setride' => "UPDATE rides SET car =? , status = 'riding', timeassigned =? WHERE num=?",
		 'getride' => "SELECT * FROM rides WHERE num =?",
		 'splitduplicate' => "INSERT INTO rides (name,cell,riders,pickup,dropoff,location,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)", 
		 'splitupdate' => "UPDATE rides SET car =? , riders =?, status = 'riding', timeassigned =? WHERE num=?",
		 'rideupdate' => "UPDATE rides SET car=?, name=?, cell=?, riders=?, pickup=?, dropoff=?, location=?, clothes=?, notes=? WHERE num=?",
		 'rideadd' => '"INSERT INTO rides (name,cell,riders,pickup,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)"',
		 'getridetocancel' => "UPDATE rides SET status =? , timedone =?, WHERE num=?", 
		 'rideundo' => "UPDATE rides SET status=? where num=?",
		 'ridedone' => "UPDATE rides SET status='done', timedone=? WHERE num=?",
		 'carupdate' => "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES (?,?,?,?)"
		);
	return $prepare;
}

//establish connection to mysql database. credentials and db name are found in cred.php
function connect(){
	global $host, $username, $password, $db;
	prepare();
	$con = mysqli_connect($host,$username,$password);
	if(!$con){
		die("Connection Error host = " . $host . " \n username= ". $username . " \n password= ". $password . " \n db = ". $db . '(' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	}
	mysqli_select_db($con, $db);
	return $con;
}


//Used to translate a time in GMT to EST+5
function gmttoest(){
	global $gtime;
	$gm = date_format($gtime, "H");//What hour is it GMT?
	if($gm < 05){//If it is before 05:00 then it is still yesterday EST and thus between 18:00...23:00
		$gm=24+$gm;//add 24 hours to GMT time to account for difference in day
		$diff = $gm-(date_format($time, "H"));//subtract the hour in GMT and the hour in EST
		return date_create(date_format($time, 'Y-m-d') . ' '  . $diff . ':' . date_format($time, 'i:s'));//return a type of DateTime with format Y-m-d H:i:s
	}
	else{
		$diff = date_diff($gtime, $time);//if EST and GMT are still in the same day, calculate the difference between the two times
		$diff = date_format($diff, '%H');//save the difference between the two hours
		return date_create(date_format($time, 'Y-m-d') . ' '  . $diff . ':' . date_format($time, 'i:s'));//return a type of DateTime with format Y-m-d H:i:s
	}
}

//Finds the minutes between the $inputtime and the current time
function minFromNow($inputtime) {
	$diff = date_diff($inputtime, gmttoest());//Find diff between input time and current time GMT
	return (date_format($diff, '%H:$I'));//return in form H:I
}

//Creates the preassing button
function tblBtnPreAssign($ride) {
	echo '<td class="btn">';
	echo '<button class="assign" onClick="highlight(\'row'.$ride.'\');assignride(\'preassign.php?num='.$ride.'\',\'assign'.$ride.'\')">PreAssign</button>';
	echo '</td>'."\r";
}
//Creates the assign button
function tblBtnAssign($ride) {
	echo '<td class="btn">';
	echo '<button class="assign" onClick="highlight(\'row'.$ride.'\');assignride(\'assign.php?num='.$ride.'\',\'assign'.$ride.'\')">Assign</button>';
	echo '</td>'."\r";
}

//Creates the Split button
function tblBtnSplit($ride) {
	echo '<td class="btn">';
	echo '<button class="split" onClick="highlight(\'row'.$ride.'\');assignride(\'split.php?num='.$ride.'\',\'assign'.$ride.'\')">Split</button>';
	echo '</td>'."\r";
}

//Creates the Edit button
function tblBtnEdit($ride,$pg) {
	echo '<td class="btn">';
	echo '<button class="edit" onClick="window.location=\'edit.php?pg='.$pg.'&num='.$ride.'\'">Edit</button>';
	echo '</td>'."\r";
}

//Creates the Done button
function tblBtnDone($ride) {
	echo '<td class="btn">';
	echo '<button class="done" onClick="window.location=\'actions.php?num='.$ride.'&action=done\'">Done</button>';
	echo '</td>'."\r";
}

//Creates the Cancel button
function tblBtnCancel($ride) {
	echo '<td class="btn">';
	echo '<button class="cancel" onClick="window.location=\'actions.php?num='.$ride.'&action=cancel\'">Cancel</button>';
	echo '</td>'."\r";
}

//Creates the Undo button
function tblBtnUndo($ride) {
	echo '<td class="btn">';
	echo '<button class="undo" onClick="window.location=\'actions.php?num='.$ride.'&action=undo\'">Undo</button>';
	echo '</td>'."\r";
}

//Prints the ride info
function tblRideInfo($info) {
	echo '<td>';
	echo $info;
	echo '</td>'."\r";
}

//Marks care an finished??
function tblDoneCar($car) {
	echo '<td>';
	echo '<a href="done.php?car='.$car.'">'.$car.'</a>';
	echo '</td>'."\r";
}

//
function tblCell($cell,$ps) {//$ps stands for pickup/status, the two input types for this function
	if ($ps=="ng") {
		$cellnum = '-';}
	else {
		$cellnum = '('.substr($cell,0,3).') '.substr($cell,3,3).'-'.substr($cell,6,4);}
	
	echo '<td>';
	echo $cellnum;
	echo '</td>'."\r";
}

//returns the amount of time between now and $intime. determines response time is considered short, medium, or long. returns difference.
function tblCalledIn($intime){
	global $gtime;

	$diff = date_diff($gtime, $intime);//get difference between $intime and now
	$diff = date_format($diff, "%H:%I");//format difference in hours:minutes format
	$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
	$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison
	if(date_format($diff, '%H') > $interval30->format('%H')){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif(date_format($diff, '%I') < $interval30->format('%I')){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif(date_format($diff, '%I') < $interval50->format('%I')){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
		$tclass = 'med';//time = med
	}
	else{
		$tclass = 'long';//otherwise it's just been way too long
	}
	echo '<td><span class="'.$tclass.'">';
	echo $diff;
	echo ' min</span></td>'."\r";
}
	

//returns the total wait time. determines response time is considered short, medium, or long. returns difference.
function tblTimeWait($called,$assigned,$done,$status,$pickup) {
	if ($status=="missed" || $status=="cancelled"){
		$diff = date_diff($called, $done);//get difference between the time the patron called in and the time the ride was cancelled 
	}
	else{
		$diff = date_diff($called, $assigned);//get difference between the time the patron called in and the time the ride was assigned
	}
	$diff = date_format($diff, "%H:%I");//format difference in hours:minutes format
	$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
	$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison
	if(date_format($diff, '%H') > $interval30->format('%H')){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif(date_format($diff, '%I') < $interval30->format('%I')){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif(date_format($diff, '%I') < $interval50->format('%I')){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
		$tclass = 'med';//time = med
	}
	else{
		$tclass = 'long';//otherwise it's just been way too long
	}
	echo '<td><span class="'.$tclass.'">';
	echo $diff;
	echo ' min</span></td>'."\r";
}


//returns the total wait time. determines response time is considered short, medium, or long. returns difference.
function tblTimeRode($done,$assigned,$status,$pickup) {
	if ($status=="missed" || $status=="cancelled"){
		$tclass = '0';//well they never rode, did they?
		$diff = '0';
	}
	else{
		$diff = date_diff($done, $assigned);//get difference between the time the patron called in and the time the ride was assigned
		$diff = date_format($diff, "%H:%I");//format difference in hours:minutes format
		$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
		$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison
		if(date_format($diff, '%H') > $interval30->format('%H')){//compare the hours. if $diff has hours greater than 0, time long
			$tclass = 'long';//time = long
		}
		elseif(date_format($diff, '%I') < $interval30->format('%I')){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
			$tclass = 'short';//time = short
		}
		elseif(date_format($diff, '%I') < $interval50->format('%I')){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
			$tclass = 'med';//time = med
		}
		else{
			$tclass = 'long';//otherwise it's just been way too long
		}
	}
	echo '<td><span class="'.$tclass.'">';
	echo $diff;
	echo ' min</span></td>'."\r";
}

//prints whether or not the patron has arrived home or if they are still intransit. if they have arrived, it states what time they were dropped off.
function tblHome($tdone,$tstatus) {
	if ($tstatus=="done") {//if the ride is done 
		$athome = date_format($tdone, 'H:i');//set the time it finished
	}
	else {//if not
		$athome = '-';//null for now
	}
	
	echo '<td>';
	echo $athome;
	echo '</td>'."\r";
	
	}

//color the rows?
function rowColor($i) {
	if (fmod($i,2) == 0) {//white if the row is even
		$bgCol = 'white';
	}
	else {//grey if the row isn't even
		$bgCol = 'grey';
	}
	
	return $bgCol;
}

/// Header functions

//counts the number of rides pending for the given status
function checkRideCount($status) {
	global $prepare, $gtime;

	mysqli_stmt_bind_param($prepare['ridecount'], 'ss', date_format($gtime, 'Y-m-d'), $status);
	mysqli_stmt_execute($prepare['ridecount']);
	mysqli_stmt_bind_result($prepare['ridecount'], $count);
	while(mysqli_stmt_fetch($prepare['ridecount'])){
		return $count;//return the number of current rides for appropiate status
	}
}

//counts the total number of lives saved
function checkCount($type) {
	global $prepare, $gtime;

	mysqli_stmt_bind_param($prepare['totalcount'], 'ss', date_format($gtime, 'Y-m-d'), $status);
	mysqli_stmt_execute($prepare['totalcount']);
	mysqli_stmt_bind_result($prepare['totalcount'], $total);
	while(mysqli_stmt_fetch($prepare['totalcount'])){
		return $total;
	}
}

/// Action Functions


//sets the preride car
function prerideAssign($num,$precar) {
	//global $prepare;
	global $gtime;
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init failed: ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_prepare($stmt, "UPDATE rides SET precar =? , status = 'waiting', timeassigned =? WHERE num=?")){
		die('Prep failed: ' .mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_bind_param($stmt,'isi',$precar,$gtime,$num)){
		die($num . 'Bind failed ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_execute($stmt)){
		die('UPDATE failed: ' . mysqli_stmt_error());
	}
}


//retrieves set preride car
function assignedPreride($num){
	global $prepare;
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Initialization failed: ' . mysqli_stmt_error($stmt) . isset($con));
	}
	if(!mysqli_stmt_prepare($stmt, "SELECT precar FROM rides WHERE num=?")){
		die('Prepare failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_bind_param($stmt,'i',$num)){
		die("Bind Failed: $num " . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_execute($stmt)){
		die ('SELECT failed: ' . mysql_error());
	}
	mysqli_stmt_bind_result($stmt, $car);
	while(mysqli_stmt_fetch($stmt)){
		return $car;
	}
}


//assigns ride to a car
function rideAssign($num,$car) {
	global $prepare, $gtime;
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init failed: ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_prepare($stmt, "UPDATE rides SET car =? , status = 'riding', timeassigned =? WHERE num=?")){
		die('Prep failed: ' .mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_bind_param($stmt,'isi', $car, $gtime, $num)){
		die('Bind failed ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_execute($stmt)){
		die('UPDATE failed: ' . mysqli_stmt_error($stmt));
	}
}


//splits a single ride into multiple cars
function rideSplit($num,$car,$riders) {
	global $prepare;

	//this opens the original ride
	mysqli_stmt_bind_param($prepare['getride'], 'i', $num);
	mysqli_stmt_execute($prepare['getride']);
	mysqli_stmt_bind_result($prepare['getride'], $rows['num'], 
						     $rows['name'], 
						     $rows['cell'], 
						     $rows['requested'], 
						     $rows['riders'], 
						     $rows['precar'], 
						     $rows['car'], 
						     $row['dropoff'], 
						     $row['notes'], 
						     $rows['clothes'], 
						     $rows['ridedate'], 
						     $rows['status'], 
						     $rows['timetaken'], 
						     $rows['timeassigned'], 
						     $rows['timedone'], 
						     $rows['loc']);

	while(mysqli_stmt_fetch($prepare['getride'])){
		$getsplit = new Ride($rows);
    	
		$ridersTotal = $getsplit->getAtt('riders');
    		$ridersLeft = $ridersTotal - $riders;
	}		

	// this creates the duplicate ride
	mysqli_stmt_bind_params($prepare['splitride'], 's,s,i,s,s,s,s,s,s,s,s', $getsplit->getAtt('name'), 
									  $getsplit->getAtt('cell'), 
									  $ridersLeft, 
									  $getsplit->getAtt('pickup'),  
									  $getsplit->getAtt('dropoff'), 
									  $getsplit->getAtt('location'), 
									  $getsplit->getAtt('clothes'), 
									  $getsplit->getAtt('notes'), 
									  $getsplit->getAtt('status'), 
									  $getsplit->getAtt('ridedate'), 
									  $getsplit->getAtt('timetaken')
									  );
	

	if(!mysqli_stmt_execute($prepare['splitduplicate'])){
		die('INSERT failed: ' . mysqli_stmt_error($prepare['splitduplicate']));
	}
	
	// this assigns the intended ride
	mysqli_stmt_bind_params($prepare['splitupdate'], 'iisi', $car, $riders, date_format($gdate, 'Y-m-d H:i:s'), $num);
	if(!mysqli_stmt_execute($prepare['splitupdate'])){
		die('UPDATE failed: ' . mysqli_stmt_error($prepare['splitupdate']));
	}
}

//Edit the ride information
function rideEdit($num) {
	global $prepare;
	$cellnumber = $_POST["cellOne"].$_POST["cellTwo"].$_POST["cellThree"];

	mysqli_stmt_bind_param($prepare['rideupdate'], 'iisisssssi', $_POST['car'],
								     $_POST['name'],
								     $cellnumber,
								     $_POST['riders'],
								     $_POST['pickup'],
								     $_POST['dropoff'],
								     $_POST['location'],
								     $_POST['clothes'],
								     $_POST['notes'],
								     $num);
/*	$qry = "UPDATE rides SET 
	car = '" . $_POST["car"] . "', 
	name = '" . $_POST["name"] . "',
	cell = '" . $_POST["cellOne"].$_POST["cellTwo"].$_POST["cellThree"]. "',
	riders = '" . $_POST["riders"] . "',
	pickup = '" . $_POST["pickup"] . "',
	dropoff = '" . $_POST["dropoff"] . "',
	location = '" . $_POST["location"] . "',
	clothes = '" . $_POST["clothes"] . "',
	notes = '" . $_POST["notes"] . "' WHERE num = '" . $num . "'";*/
	if(!mysqli_stmt_execute($prepare['rideupdate'])){
		die('UPDATE failed: ' . mysqli_stmt_error($prepare['rideupdate']));
	}
}


//Add ride to DB
function rideAdd() {
	global $prepare, $gtime, $gdate;
	$con= connect();
	
	if(!($stmt = mysqli_stmt_init($con))){
		die('Initialization failed: ' . isset($con));
	}
	//echo "after init \n";
	$prep = '"' . $prepare['rideadd'] . '"';
	//echo "Prepare: \n" . $prep . "\n";?> <br><?php
	//echo '"INSERT INTO rides (name,cell,riders,pickup,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)"';
	if(!mysqli_stmt_prepare($stmt, "INSERT INTO rides (name,cell,riders,pickup,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)")){
		die('Preparing the statement failed ' .  isset($stmt) . mysqli_stmt_errno($stmt) . ' ' . mysqli_stmt_error($stmt));
	}
	//echo "before prep\n";
	 	//'rideadd' => "INSERT INTO rides (name,cell,riders,pickup,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
	$cellnumber = $_POST["cell1"].$_POST["cell2"].$_POST["cell3"]; 
	$waiting = 'waiting';
	if(!mysqli_stmt_bind_param($stmt, 'ssissssssss', $_POST["name"], 
								  $cellnumber,
								  $_POST["pickup"],
								  $_POST["riders"],
								  $_POST["dropoff"], 
								  $_POST["loc"], 
								  $_POST["clothes"], 
								  $_POST["notes"], 
								  $waiting,
								  $gdate, 
								  $gtime)){
		die('Binding the parameters failed ' .  mysqli_stmt_errno($stmt) . mysqli_stmt_error($stmt));
	}
	echo "before exec";

/*    $qry = "INSERT INTO rides (name,cell,riders,pickup,dropoff,aploc,dormloc,clothes,notes,status,ridedate,timetaken) VALUES 
    ('".
    $_POST["name"]."','".
    $_POST["cell1"].$_POST["cell2"].$_POST["cell3"]."','".
    $_POST["riders"]."','".
    $_POST["pickup"]."','".
    $_POST["dropoff"]."','".
    $_POST["loc"]."','".
    $_POST["clothes"]."','".
    $_POST["notes"]."',
    'waiting','".
    $dateofride."','".
    date("YmdHis")."')";
*/
	if(!mysqli_stmt_execute($stmt)){
		die('INSERT failed: ' . mysqli_stmt_errno($stmt) . ' ' . mysqli_stmt_error($stmt) );
	}
	echo "end";
}


//cancel the ride?
function rideCancel($num, $status) {
	global $prepare;

	mysqli_stmt_bind_param($prepare['ridecancel'], 'sssi', $status, date_format($gtime, 'Y-m-d H:i:s'), $num);
	mysqli_stmt_execute($prepare['ridecancel']);
}


//undo the last status change
function rideUndo($num) {
	global $prepare;

	//this opens the original ride
	mysqli_stmt_bind_param($prepare['getride'], 'i', $num);
	mysqli_stmt_execute($prepate['getride']);

	mysqli_stmt_bind_result($prepare['getride'], $rows['num'], 
						     $rows['name'], 
						     $rows['cell'], 
						     $rows['requested'], 
						     $rows['riders'], 
						     $rows['precar'], 
						     $rows['car'], 
						     $row['dropoff'], 
						     $row['notes'], 
						     $rows['clothes'], 
						     $rows['ridedate'], 
						     $rows['status'], 
						     $rows['timetaken'], 
						     $rows['timeassigned'], 
						     $rows['timedone'], 
						     $rows['loc']);

	while(mysqli_stmt_fetch($prepare['getride'])){
		$getundo  =  new  Ride($rows); 
   		
		if ($getundo->getAtt('car')==0){
			$stat="waiting";
		}
		else {
			$stat="riding";
		}
	}

	mysqli_stmt_bind_param($prepare['rideundo'], 'si', $stat, $num);
   	//$qry = "UPDATE rides SET status = '" . $stat . "' WHERE num='" . $num . "'";
	if(mysqli_stmt_execute($prepare['rideundo'])){
		die('INSERT failed: ' . mysqli_stmt_error($prepare['rideundo']));
	}

	header("location: ./" . $stat . ".php?num=".$_POST["num"]);

}
    
//move ride from 'riding' to 'done'
function rideDone($num) {
	global $prepare, $gtime;
	mysqli_stmt_bind_param($prepare['ridedone'], 'si', date_format('Y-m-d H:i:s'), $num);
	//$qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
	if(!mysqli_stmt_execute($prepare['ridedone'])){
		die('INSERT failed: ' . mysqli_stmt_error($prepare['ridedone']));
	}
}

//update when the car was last contacted
function carUpdate() {
	global $prepare, $gtime;
	$called = 'called';
	mysqli_stmt_bind_param($prepare['careupdate'], 'i,s,s,s', $_POST["carnum"], 
								  $called, 
								  date_format($gtime, 'Y-m-d H:i:s'), 
								  date_format($gtime, 'Y-m-d H:i:s'));
	
/*    $qry = "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES 
    ('".
    $_POST["carnum"]."',
    'called','".
    $dateofride."',
    '".date("YmdHis")."')";
*/
	if(!mysqli_stmt_execute($prepare['carupdate'])){
		die('INSERT failed: ' . mysqli_stmt_error($prepare['carupdate']));
	}
}
    
//update when the car has returned back to the office
function carHome() {
	global $prepare, $gtime;
	$home = 'home';
	mysqli_stmt_bind_param($prepare['careupdate'], 'i,s,s,s', $_POST["carnum"], 
								  $home, 
								  date_format($gtime, 'Y-m-d H:i:s'), 
								  date_format($gtime, 'Y-m-d H:i:s'));

/*    $qry = "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES 
    ('".
    $_POST["carnum"]."',
    'home','".
    $dateofride."',
    '".date("YmdHis")."')";
*/    
	if(!mysqli_stmt_execute($prepare['carupdate'])){
		die('INSERT failed: ' . mysqli_stmt_error($prepare['carupdate']));
	}
}
    

?>
