<?php 
//
require('cred.php');
require('config.php');

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
	$prepare = array('ridecount' => "SELECT * FROM rides WHERE ridedate= ? AND status =?", 
		 'totalcount' => "SELECT SUM(riders) as total FROM rides WHERE ridedate = ? AND status = ?",
		 'setpreride' => "UPDATE rides SET precar = ?, status = 'waiting', timeassigned =? WHERE num=?",
		 'getpreride' => "SELECT precar FROM rides WHERE num=?",
		 'setride' => "UPDATE rides SET car =? , status = 'riding', timeassigned =? WHERE num=?",
		 'getride' => "SELECT * FROM rides WHERE num =?",
		 'splitduplicate' => "INSERT INTO rides (name,cell,riders,car,pickup,fromloc,dropoff,notes,clothes,ridedate,status,timetaken,loc) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)", 
		 'splitupdate' => "UPDATE rides SET car =? , riders =?, status = 'riding', timeassigned =? WHERE num=?",
		 'rideupdate' => "UPDATE rides SET car=?, name=?, cell=?, riders=?, pickup=?, fromloc=?, dropoff=?, loc=?, clothes=?, notes=? WHERE num=?",
		 'rideadd' => "INSERT INTO rides (name,cell,riders,pickup,fromloc,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
		 'setridetocancel' => "UPDATE rides SET status =? , timedone =?, WHERE num=?", 
		 'rideundo' => "UPDATE rides SET status=? where num=?",
		 'ridedone' => "UPDATE rides SET status=?, timedone=? WHERE num=?",
		 'carupdate' => "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES (?,?,?,?)",
		);
//	return $prepare;
}

//establish connection to mysql database. credentials and db name are found in cred.php
function connect(){
	global $host, $username, $password, $db,$prepare;
	prepare();
	//echo $prepare["setpreride"];
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
	$gmt = date_parse($gtime);
	$called = date_parse($intime);
	$hourdiff = $gmt['hour'] - $called['hour'];
	$mindiff = $gmt['minute'] - $called['minute'];
	if($hourdiff>0){
		if($mindiff<0){
			$hour = ($gmt['hour'] - 1) - $called['hour'];
			$min = (60 + $gmt['minute']) - $called['minute'];
		}
		else{
			$hour = $hourdiff;
			$min = $mindiff;
		}
	}
	else{
		$hour = $hourdiff;
		$min = $mindiff;
	}
		
	//$diff = date_diff($gtime, $intime);//get difference between $intime and now
	//$time = $diff->format('%I');//format difference in hours:minutes format
	//$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
	//$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison

	if($hour > 0){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif($min < 30){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif($min < 50){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
		$tclass = 'med';//time = med
	}
	else{
		$tclass = 'long';//otherwise it's just been way too long
	}
	echo '<td><span class="'.$tclass.'">';
	if($hour==1){
		echo "$hour hour $min min </span></td>\r";
	}
	elseif($hour>1){
		echo "$hour hours $min min </span></td>\r";
	}
	else{
		echo "$min min</span></td>\r";
	}
}
	

//returns the total wait time. determines response time is considered short, medium, or long. returns difference.
function tblTimeWait($called,$assigned,$done,$status,$pickup) {
	if ($status=="missed" || $status=="cancelled"){
		$calledin = date_parse($called);//get difference between the time the patron called in and the time the ride was cancelled 
		$donezed = date_parse($done);
		$hourdiff = $donezed['hour'] - $calledin['hour'];
		$mindiff = $donezed['minute'] - $calledin['minute'];
		if($hourdiff>0){
			if($mindiff<0){
				$hour = ($donezed['hour'] - 1) - $calledin['hour'];
				$min = (60 + $donezed['minute']) - $calledin['minute'];
			}
			else{
				$hour = $hourdiff;
				$min = $mindiff;
			}
		}
		else{
			$hour = $hourdiff;
			$min = $mindiff;
		}
	}
	else{
		$calledin = date_parse($called);//get difference between the time the patron called in and the time the ride was cancelled 
		$assIgned = date_parse($assigned);
		$hourdiff = $assIgned['hour'] - $calledin['hour'];
		$mindiff = $assIgned['minute'] - $calledin['minute'];
		if($hourdiff>0){
			if($mindiff<0){
				$hour = ($assIgned['hour'] - 1) - $calledin['hour'];
				$min = (60 + $assIgned['minute']) - $calledin['minute'];
			}
			else{
				$hour = $hourdiff;
				$min = $mindiff;
			}
		}
		else{
			$hour = $hourdiff;
			$min = $mindiff;
		}
	}
	if($hour > 0){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif($min < 30){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif($min < 50){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
		$tclass = 'med';//time = med
	}
	else{
		$tclass = 'long';//otherwise it's just been way too long
	}
	echo '<td><span class="'.$tclass.'">';
	if($hour==1){
		echo "$hour hour $min min </span></td>\r";
	}
	elseif($hour>1){
		echo "Too Long";
	}
	else{
		echo "$min min</span></td>\r";
	}
}


//returns the total wait time. determines response time is considered short, medium, or long. returns difference.
function tblTimeRode($done,$assigned,$status,$pickup) {
	if ($status=="missed" || $status=="cancelled"){
		$tclass = '0';//well they never rode, did they?
		$diff = '0';
	}
	else{
		$assIgned = date_parse($assigned);//get difference between the time the patron called in and the time the ride was cancelled 
		$donezed = date_parse($done);
		$hourdiff = $donezed['hour'] - $assIgned['hour'];
		$mindiff = $donezed['minute'] - $assIgned['minute'];
		if($hourdiff>0){
			if($mindiff<0){
				$hour = ($donezed['hour'] - 1) - $assIgned['hour'];
				$min = (60 + $donezed['minute']) - $assIgned['minute'];
			}
			else{
				$hour = $hourdiff;
				$min = $mindiff;
			}
		}
		else{
			$hour = $hourdiff;
			$min = $mindiff;
		}
	}
	if($hour > 0){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif($min < 30){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif($min < 50){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
		$tclass = 'med';//time = med
	}
	else{
		$tclass = 'long';//otherwise it's just been way too long
	}
	echo '<td><span class="'.$tclass.'">';
	if($hour==1){
		echo "$hour hour $min min </span></td>\r";
	}
	elseif($hour>1){
		echo "Too Long";
	}
	elseif($diff=='0'){
		echo "Cancelled </span></td>\r";
	}
	else{
		echo "$min min</span></td>\r";
	}
}

//prints whether or not the patron has arrived home or if they are still intransit. if they have arrived, it states what time they were dropped off.
function tblHome($tdone,$tstatus) {
	if ($tstatus=="done") {//if the ride is done 
		$done = date_parse($tdone);
		if($done[hour]<5){
			$athome = ($done['hour']+19) . ':' . $done['minute'];
		}
		else{
			$athome = ($done['hour']-4) .':'.$done['minute'];
		}
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
	global $gdate,$prepare;
	$con = connect();
	if(!($stmt=mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt, $prepare['ridecount'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	mysqli_stmt_bind_param($stmt, 'ss', $gdate, $status);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
	return mysqli_stmt_num_rows($stmt);
}

//counts the total number of lives saved
function checkCount($type) {
	global $gdate,$prepare;
	$con = connect();
	if(!($stmt=mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt,$prepare['totalcount'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	mysqli_stmt_bind_param($stmt, 'ss', $gdate, $type);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	while(mysqli_stmt_fetch($stmt)){
		return $total;
	}
}

/// Action Functions


//sets the preride car
function prerideAssign($num,$precar) {
	//global $prepare;
	global $gtime,$prepare;
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init failed: ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_prepare($stmt, $prepare['setpreride'])){
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
	if(!mysqli_stmt_prepare($stmt, $prepare['getpreride'])){
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

	if(!mysqli_stmt_prepare($stmt, $prepare['setride'])){
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
	global $gtime,$prepare;
	$con = connect();
	if(!($stmt=mysqli_stmt_init($con))){
		die('Init failed: ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_prepare($stmt, $prepare['getride'])){
		die('Prep failed: ' .mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_bind_param($stmt,'i', $num)){
		die('Bind failed ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_execute($stmt)){
		die('UPDATE failed: ' . mysqli_stmt_error($stmt));
	}
	//this opens the original ride
	if(!mysqli_stmt_bind_result($stmt, 
				$row['num'], 
				$row['name'], 
				$row['cell'], 
				$row['requested'], 
				$row['riders'], 
				$row['precar'], 
				$row['car'], 
				$row['pickup'], 
				$row['fromloc'], 
				$row['dropoff'], 
				$row['notes'], 
				$row['clothes'], 
				$row['ridedate'], 
				$row['status'], 
				$row['timetaken'], 
				$row['timeassigned'], 
				$row['timedone'],
				$row['loc'])){
		die('Res Bind Failed: ' . mysqli_stmt_error($stmt));
	}
	while(mysqli_stmt_fetch($stmt)){
    	
    		$ridersLeft = ($row['riders'] - $riders);
	}		
	mysqli_close($con);
	
	print_r($row);

	// this creates the duplicate ride
	$con1 = connect();
	if(!($stmt1 = mysqli_stmt_init($con1))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt1, $prepare['splitduplicate'])){
		die('Prep Failed1: ' . mysqli_stmt_error($stmt1));
	}
	if(!mysqli_stmt_bind_param($stmt1, 'ssiisssssssss',$row['name'],$row['cell'],$ridersLeft,$row['car'],$row['pickup'],$row['fromloc'],$row['dropoff'],$row['notes'],$row['clothes'],$row['ridedate'],$row['status'],$row['timetaken'],$row['loc'])){
		die('Bind Failed: '. 'name: ' . $row['name'] . ' cell: ' . $row['cell']  . ' riders: ' . $ridersLeft . ' car: ' . $row['car'] . ' pickup: ' . $row['pickup'] . ' dropoff: ' . $row['dropoff'] . ' notes: ' . $row['notes'] . ' clothes: ' . $row['clothes'] . ' ridedate: ' . $row['ridedate'] . ' status: ' . $row['status'] . ' timetaken: ' . $row['timetaken'] . ' loc: ' . $row['loc'] . mysqli_stmt_error($stmt1));
	}	
	if(!mysqli_stmt_execute($stmt1)){
		die('INSERT failed: ' .  print_r($row) . ' ' . $ridersLeft . ' '  . mysqli_stmt_error($stmt1));
	}
	mysqli_close($con1);
	// this assigns the intended ride
	$con2 = connect();
	if(!($stmt2 = mysqli_stmt_init($con2))){
		die('Init Failed: ' . mysqli_stmt_error($stmt2));
	}
	if(!mysqli_stmt_prepare($stmt2,$prepare['splitupdate'])){
		die('Prep Failed2: ' . mysqli_stmt_error($stmt2));
	}
	mysqli_stmt_bind_param($stmt2, 'iisi', $car, $riders, $gtime, $num);
	if(!mysqli_stmt_execute($stmt2)){
		die('UPDATE failed: ' . mysqli_stmt_error($stmt2));
	}
	mysqli_close($con2);
}

//Edit the ride information
function rideEdit($num) {
	global $prepare;
	$con = connect();
	$cellnumber = $_POST["cellOne"].$_POST["cellTwo"].$_POST["cellThree"];
	if(!($stmt=mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt,$prepare['rideupdate'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	mysqli_stmt_bind_param($stmt, 'ississssssi', $_POST['car'],
								     $_POST["name"],
								     $cellnumber,
								     $_POST['riders'],
								     $_POST['pickup'],
								     $_POST['fromloc'],
								     $_POST['dropoff'],
								     $_POST['loc'],
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
	if(!mysqli_stmt_execute($stmt)){
		die('UPDATE failed: ' . $_POST['name'] . mysqli_stmt_error($stmt));
	}
}


//Add ride to DB
function rideAdd() {
	global $prepare, $gtime, $gdate;
	$con= connect();
	
	if(!($stmt = mysqli_stmt_init($con))){
		die('Initialization failed: ' . isset($con));
	}
	if(!mysqli_stmt_prepare($stmt, $prepare['rideadd'])){
		die('Preparing the statement failed ' .  isset($stmt) . mysqli_stmt_errno($stmt) . ' ' . mysqli_stmt_error($stmt));
	}
	//echo "before prep\n";
	 	//'rideadd' => "INSERT INTO rides (name,cell,riders,pickup,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
	$cellnumber = $_POST["cell1"].$_POST["cell2"].$_POST["cell3"]; 
	$waiting = 'waiting';
	if(!mysqli_stmt_bind_param($stmt, 'ssisssssssss',$_POST["name"], 
							  $cellnumber,
							  $_POST["riders"],
							  $_POST["pickup"],
							  $_POST["fromloc"],
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
function rideCancel($num) {
	global $gtime,$prepare;
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt,$prepare['ridedone'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	$status = 'cancelled';
	if(!mysqli_stmt_bind_param($stmt, 'ssi', $status, $gtime, $num)){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_execute($stmt)){
		die('INSERT failed: ' . mysqli_stmt_errno($stmt) . ' ' . mysqli_stmt_error($stmt) );
	}
}


//undo the last status change
function rideUndo($num) {
	global $prepare;
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt, $prepare['getride'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}

	//this opens the original ride
	if(!mysqli_stmt_bind_param($stmt, 'i', $num)){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_execute($stmt)){
		die('Exec Failed: ' . mysqli_stmt_error($stmt));
	}
	$rows = array();
	mysqli_stmt_bind_result($stmt,
				$rows['car']);

	while(mysqli_stmt_fetch($stmt)){
		//$getundo  =  new  Ride($rows); 
   		
		/*if ($getundo->getAtt('car')==0){
			$stat="waiting";
		}
		else {
			$stat="riding";
		}*/

		if($rows['car']==0){
			$stat='waiting';
		}
		else{
			$stat='riding';
		}
	}
	mysqli_close($con);


	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}

	if(!mysqli_stmt_prepare($stmt, $prepare['rideundo'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_bind_param($stmt, 'si', $stat, $num)){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
	}
   	//$qry = "UPDATE rides SET status = '" . $stat . "' WHERE num='" . $num . "'";
	if(!mysqli_stmt_execute($stmt)){
		die('INSERT failed: ' . mysqli_stmt_error($stmt));
	}

	header("location: ./" . $stat . ".php?num=".$_POST["num"]);
	mysqli_close($con);

}
    
//move ride from 'riding' to 'done'
function rideDone($num) {
	global $gtime,$prepare;
	$con = connect();
	if(!($stmt=mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt, $prepare['ridedone'])){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	$done = 'done';
	if(!mysqli_stmt_bind_param($stmt, 'ssi', $done, $gtime, $num)){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
	}
	//$qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
	if(!mysqli_stmt_execute($stmt)){
		die('INSERT failed: ' . mysqli_stmt_error($stmt));
	}
	mysqli_close($stmt);
}

//update when the car was last contacted
function carUpdate() {
	global $gtime,$gdate,$prepare;
	$called = 'called';
	$con = connect();
	if(!($stmt=mysqli_stmt_init($con))){
		die('Failed Init ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt,$prepare['carupdate'])){
		die('Failed Prep: ' . mysqli_stmt_error($stmt));
	}
	mysqli_stmt_bind_param($stmt, 'isss', $_POST["carnum"], 
						  $called, 
						  $gdate, 
						  $gtime);
	
/*    $qry = "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES 
    ('".
    $_POST["carnum"]."',
    'called','".
    $dateofride."',
    '".date("YmdHis")."')";
*/
	if(!mysqli_stmt_execute($stmt)){
		die('INSERT failed: ' . mysqli_stmt_error($stmt));
	}
}
    
//update when the car has returned back to the office
function carHome() {
	global $gdate, $gtime,$prepare;
	$home = 'home';
	$con = connect();
	if(!($stmt=mysqli_stmt_init($con))){
		die('Failed Init ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt,$prepare['carupdate'])){
		die('Failed Prep: ' . mysqli_stmt_error($stmt));
	}
	mysqli_stmt_bind_param($stmt, 'isss', $_POST["carnum"], 
						  $home, 
						  $gdate, 
						  $gtime);

/*    $qry = "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES 
    ('".
    $_POST["carnum"]."',
    'home','".
    $dateofride."',
    '".date("YmdHis")."')";
*/    
	if(!mysqli_stmt_execute($stmt)){
		die('INSERT failed: ' . $_POST["carnum"] . ' ' . mysqli_stmt_error($stmt));
	}
}

function carBoxes(){
	global $gdate,$gtime,$prepare;

	$carsMaxBase = 8;
	$cars = array();
	$carsMax = $carsMaxBase;
	$con = connect();

// Generates a box for the car, and loops for each car
	for ($i=1; $i<=$carsMax; $i++){
		$cars[] = $i;
		$carsDoneTime=2;
		$carsRidingTime=2;
		$carsContactTime = 2;

// Looks up the last action of done (will detect a circuit car)
		if(!($stmt=mysqli_stmt_init($con))){
			die('Init1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_prepare($stmt, "SELECT timedone, pickup FROM rides WHERE ridedate=? AND car =? ORDER BY timedone")){
			die('Prep1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_param($stmt, 'si', $gdate, $i)){
			die('Bind1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_execute($stmt)){
			die('Exec1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_result($stmt, $row['timedone'], $row['pickup'])){
			die('Bind1 Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while(mysqli_stmt_fetch($stmt)){
			$carsDoneTime = date_parse($row['timedone']);
		//	echo "\n Done Year Fetch: " . $carDoneTime['year']." \n";
			$carsPickup = date_parse($row['pickup']);
		//	echo "\n Pickup Year Fetch: ". $carsPickup['year'] ."\n";
		}
/*$cSql = "SELECT timedone, pickup FROM rides WHERE ".$datecheck." AND car = ".$cars[$i]." ORDER BY timedone";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['timedone']==NULL)
			$carsDoneTime = 1;
		else
			$carsDoneTime = $row['timedone'];
			$carsPickup = $row['pickup'];}*/

// Looks up the last action of done (will detect a circuit car)
		if(!($stmt=mysqli_stmt_init($con))){
			die('Init2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_prepare($stmt, "SELECT timeassigned FROM rides WHERE ridedate=? AND car =? ORDER BY timeassigned")){
			die('Prep2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_param($stmt, 'si', $gdate, $i)){
			die('Bind2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_execute($stmt)){
			die('Exec2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_result($stmt, $row['timeassigned'])){
			die('Bind2 Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while(mysqli_stmt_fetch($stmt)){
			$carsRidingTime = date_parse($row['timeassigned']);
		//	echo "\n Ride Year Fetch: ". $carRidingTime['year']." \n";
		}
/*$cSql = "SELECT timeassigned FROM rides WHERE ".$datecheck." AND car = ".$cars[$i]." ORDER BY timeassigned";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['timeassigned']==NULL)
			$carsRidingTime = 1;
		else
			$carsRidingTime = $row['timeassigned'];}*/

	// Looks up the last action if executed by 
		if(!($stmt=mysqli_stmt_init($con))){
			die('Init3 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_prepare($stmt, "SELECT contacttime,reason FROM contacted WHERE ridedate=? AND carnum =? ORDER BY contacttime asc")){
			die('Prep3 Failed: . ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_param($stmt, 'si', $gdate, $i)){
			die('Bind3 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_execute($stmt)){
			die('Exec3 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_result($stmt, $row['contacttime'],$row['reason'])){
			die('Bind3 Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while(mysqli_stmt_fetch($stmt)){
			$carsContactTime = $row['contacttime'];
			$carsContactTime = date_parse($carsContactTime);
			$carsContactReason = $row['reason'];
		}
		if($row['contacttime']==NULL){
			$carsContactTime = 0;
		}
		//	echo "\n Contact time = " . $carsContactTime['minute'] . "\n";
/*$cSql = "SELECT contacttime, reason FROM contacted WHERE contacttime > DATE_SUB(FROM_UNIXTIME(". date("U") ."), INTERVAL 8 HOUR) AND carnum = ".$cars[$i]." ORDER BY contacttime";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['contacttime']==NULL)
			$carsContactTime = 1;
		else
			$carsContactTime = $row['contacttime'];
			$carsContactReason = $row['reason'];}		*/
//ridingtime and carsdonetime does not change for each car
		if($carsRidingTime['year']==0){//If car is not being used and has never given a ride
			if($carsDoneTime['year']==0){
				$carsTime=0;
			}
			else{
				$carsTime=$carsDoneTime;
				echo "\n carsTime1 \n";
			}
		}
		else{
			if($carsDoneTime['year']>0){
				$carsTime=$carsDoneTime;
			}
			else{
				$carsTime=$carsRidingTime;
			}
			//echo "\n carsTime2 = " . $carsDoneTime['minute'] . "\n";
		}
		//echo "\nRide Year: " . $carRidingTime['year']  . "Done Year: " . $carDoneTime['year'] . "\n";
		//print_r($carTime);
		//echo "\n carsTime: " . $carsTime['year'] . "\n";
		
		$gmt = date_parse($gtime);
		$hourdiff = $gmt['hour'] - $carsTime['hour'];
		$mindiff = $gmt['minute'] - $carsTime['minute'];
	if($carsContactTime!=0){
		if($hourdiff>0){
			if($mindiff<0){
				$hour = ($gmt['hour'] - 1) - $carsTime['hour'];
				$min = (60 + $gmt['minute']) - $carsTime['minute'];
			}
			else{
				$hour = $hourdiff;
				$min = $mindiff;
			}
		}
		else{
			$hour = $hourdiff;
			$min = $mindiff;
		}	
	}
		$hourdiff = $gmt['hour'] - $carsContactTime['hour'];
		$mindiff = $gmt['minute'] - $carsContactTime['minute'];
	if($hourdiff>0){
		if($mindiff<0){
			$conthour = ($gmt['hour'] - 1) - $carsContactTime['hour'];
			$contmin = (60 + $gmt['minute']) - $carsContactTime['minute'];
		}
		else{
			$conthour = $hourdiff;
			$contmin = $mindiff;
		}
	}
	else{
		$conthour = $hourdiff;
		$contmin = $mindiff;
	}	
	if($carsContactTime!=0){
		$ridetimeCont = $carsContactTime['hour'].':'.$carsContactTime['minute'];
	}
	else{
		$ridetimeCont = $carsContactTime['minute'];
	}
	if($carsTime['hour']>0){
		$carTime = $carsTime['hour'].':'.$carsTime['minute'];
	}
	else{
		$carTime = $carsTime['minute'];
	}
	//$carsTime = max($carsRidingTime,$carsDoneTime);
	//$ridetimeCont = substr($carsContactTime,8,2)*720+substr($carsContactTime,11,2)*60+substr($carsContactTime,14,2);
	if($conthour>0){
		$carsContDiff = $conthour.':'.$contmin;
	}
	else{
		$carsContDiff = $contmin;
	}
	//$ridetime = substr($carsTime,8,2)*720+substr($carsTime,11,2)*60+substr($carsTime,14,2);
	if($carsContactTime!=0){
		if($hour>0){
			$carsTimeDiff = $hour.':'.$min;
		}
		else{
			$carsTimeDiff = $min;
		}
	//	echo "\n" . $carsTimeDiff . "\n"; 
	}
	//$carsTimeDiff = $currtime - $ridetime;
	//$carsContDiff = $currtime - $ridetimeCont;
	
	// Sets status for car Still Here
	$carsStatus = "car-here";
	$textLine1 = '<h2>Car '.$i.' is Not Out</h2>';
	$textLine2 = '';
	$textLine3 = '';
	$textLine4 = '';
//UNCOMMENT WHEN DONE
	//echo "\n carsTime: " . $carsTime['year'] . " ==Riding: " . $carsTime==$carsRidingTime;
	//echo "\n carsTime: " . $carsTime['year'] . " ==Done: " . $carsTime==$carsDoneTime;

	// Sets status for normal car	
	if  ($carsTime['year']>0 && $carsTime==$carsRidingTime) {
			$carsStatus = "car-normal";			
			$textLine1 = '<h2>Car '.$i.' is on a Ride</h2>';
			$textLine2 = '<h3>It was assigned</h3>';
			$textLine3 = '<h3>'.$carsTimeDiff.' minutes ago</h3>';
	}
	// Sets status for chilling
	elseif  ($carsTime['year']>0 && $carsTime==$carsDoneTime){
			$carsStatus = "car-chill";			
			$textLine1 = '<h2>Car '.$i.' is Chilling</h2>';
			$textLine2 = '<h3>It was last done </h3>';
	}
			
	$carsStatusLast = $carsStatus;
	if($carsContactTime!=0){
		if ($carsStatus=="car-normal" && $carsTimeDiff>60) {
				$carsStatus = "car-call";}
		elseif ($carsStatus=="car-chill" && $carsTimeDiff>60) {
				$carsStatus = "car-call";}
		
		if ($carsStatus<>"car-here"){
			$textLine3 = '<h3>'.$carsTimeDiff.' minutes ago</h3>';}
	}
	if($carsContactTime!=0){		
		if ($carsContDiff<$carsTimeDiff && $carsContactReason=='called') {
				$carsStatus = $carsStatusLast;
				$textLine4 = '<h3 style="font-weight:bold;">Spoke '.$carsContDiff.' mins ago</h3>';
		}
		elseif ($carsContDiff<$carsTimeDiff && $carsContactReason=='home') {
				$carsStatus = "car-done";			
				$textLine1 = '<h2>Car '.$i.' is Back Home</h2>';
				$textLine2 = '';
				$textLine3 = '';
		}
	}
		$carBoxText = '<div id="car'.$i.'" class="c car-box '.$carsStatus.'" onClick="'.
				"carsEdit('car".$i."','".$i."','cars_edit.php?carnum=".$i."')".
				';">'.$textLine1.$textLine2.$textLine3.$textLine4.'</div>';
		
		echo $carBoxText;	
	}
	mysqli_close($con);
}
   

function loginLdap($username, $password){
	global $ldapserver,$localadmin;
	global $auth;
	if($username=='admin' && $password=='1234' && $localadmin==TRUE){
		 config(TRUE);
	}
	/*else{
		$conserver = ldap_connect($ldapserver['host']); //$ldapserver is defined in creds.php
		$ldapbind = ldap_bind($conserveri,$username,$password)
			or die('Could not establish connection with server: ' . $ldapserver['name'] . "\n");
		echo "Bind Successful \n";
		$auth=$TRUE;
		header("location: ./config.php");
		return 'location ./index.php';
	}*/
	else{
		return 'location: ./index.php';
	}
}

function Test(){
	global $auth;
	echo intval($auth) . "\n";
	return $auth;
}
?>
