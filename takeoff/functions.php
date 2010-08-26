<?php 
// Display Functions

/*
Define two variables:
gtime defines the current time GMT, in Year-Month-Day Hour:Minutes:Seconds format
time defines the current time in the currect timezone, in Year-Month-Day Hour:Minutes:Seconds format
*/
$gtime = gmdate('Y-m-d H:i:s');
$time = time('Y-m-d H:i:s');

//Used to translate a time in GMT to EST+5
function gmttoest(){
	$gm = $gtime->format("H");//What hour is it GMT?
	if($gm -lt 05){//If it is before 05:00 then it is still yesterday EST and thus between 18:00...23:00
		$gm=24+$gm;//add 24 hours to GMT time to account for difference in day
		$diff = $gm-($time->format("H"));//subtract the hour in GMT and the hour in EST
		return new DateTime($time->format('Y-m-d') . ' '  . $diff . ':' . $time->format('i:s'));//return a type of DateTime with format Y-m-d H:i:s
	}
	else{
		$diff = $gmttime->diff($time);//if EST and GMT are still in the same day, calculate the difference between the two times
		$diff = $diff->format('%H');//save the difference between the two hours
		return new DateTime($time->format('Y-m-d') . ' '  . $diff . ':' . $time->format('i:s'));//return a type of DateTime with format Y-m-d H:i:s
	}
}

//Finds the minutes between the $inputtime and the current time
function minFromNow($inputtime) {
	$diff = $inputtime->diff(gmttoest());//Find diff between input time and current time GMT
	return (diff->format('%H:$I'));//return in form H:I
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
	if ($pickup=="ng") {
		$cellnum = '-';}
	else {
		$cellnum = '('.substr($cell,0,3).') '.substr($cell,3,3).'-'.substr($cell,6,4);}
	
	echo '<td>';
	echo $cellnum;
	echo '</td>'."\r";
	}

//returns the amount of time between now and $intime. determines response time is considered short, medium, or long. returns difference.
function tblCalledIn($intime){
	$diff = $gmtime->diff($intime);//get difference between $intime and now
	$diff = $diff->format("%H:%I");//format difference in hours:minutes format
	$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
	$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison
	if($diff->format('%H') > $interval30->format('%H'){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif($diff->format('%I') < $interval30->format('%I'){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif($diff->format('%I') < $interval50->format('%I'){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
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
		$diff = $called->diff($done);//get difference between the time the patron called in and the time the ride was cancelled 
	}
	else{
		$diff = $called->diff($assigned);//get difference between the time the patron called in and the time the ride was assigned
	}
	$diff = $diff->format("%H:%I");//format difference in hours:minutes format
	$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
	$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison
	if($diff->format('%H') > $interval30->format('%H'){//compare the hours. if $diff has hours greater than 0, time long
		$tclass = 'long';//time = long
	}
	elseif($diff->format('%I') < $interval30->format('%I'){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
		$tclass = 'short';//time = short
	}
	elseif($diff->format('%I') < $interval50->format('%I'){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
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
		$diff = $done->diff($assigned);//get difference between the time the patron called in and the time the ride was assigned
		$diff = $diff->format("%H:%I");//format difference in hours:minutes format
		$interval30 = new DateInterval('PT30M');//create a time interval of 30 minutes, used for comparison
		$interval50 = new DateInterval('PT50M');//creates a time interval of 50 minutes, used for comparison
		if($diff->format('%H') > $interval30->format('%H'){//compare the hours. if $diff has hours greater than 0, time long
			$tclass = 'long';//time = long
		}
		elseif($diff->format('%I') < $interval30->format('%I'){//if hours aren't larger than 0 (which I hope they aren't), compare minutes. if diff is less than 30, time is short
			$tclass = 'short';//time = short
		}
		elseif($diff->format('%I') < $interval50->format('%I'){//if time is not less than 30 minutes, maybe it's less than 50 minutes. if so, time is medium
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
		$athome = $tdone->format('H:i');//set the time it finished
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

function checkRideCount($status) {
	global $dateofride;
	$ridecount->bind_param('bs',,$status);
	return mysql_num_rows(mysql_query($cSql)); //return the number of current rides for appropiate status
	}

function checkCount($type) {
global $dateofride;

$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND status = '".$type."'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		return $row['total'];}
		}

/// Action Functions

function prerideAssign($num,$precar) {
    $qry = "UPDATE rides SET precar = '" . $precar . "', status = 'waiting' WHERE num='" . $num . "'";
    mysql_query($qry) or die('UPDATE failed: ' . mysql_error() . " \n " . ' $num = ' . $num . " \n " . ' $precar = ' . $precar . " \n " . ' $qry = ' . $qry);}

function assignedPreride($num){
    $qry = "SELECT precar FROM rides WHERE num='" . $num . "'";
    $print = mysql_query($qry) or die('UPDATE failed: ' . mysql_error() . " \n " . ' $num = ' . $num . " \n " . ' $qry = ' . $qry);
    return mysql_result($print,0);
}


function rideAssign($num,$car) {
    $qry = "UPDATE rides SET car = '" . $car . "', status = 'riding', timeassigned = '".date("YmdHis")."' WHERE num='" . $num . "'";
    mysql_query($qry) or die('UPDATE failed: ' . mysql_error());}


    
function rideSplit($num,$car,$riders) {
	
	//this opens the original ride
	$sql="SELECT * FROM rides WHERE num = '" . $num . "'";
	$qry1 = mysql_query($sql);
	while($row = mysql_fetch_array($qry1)) {
    	$getsplit  =  new  Ride($row); 
    	
    	$ridersTotal = $getsplit->getAtt('riders');
    	$ridersLeft = $ridersTotal - $riders;
		
		$sName = $getsplit->getAtt('name');
		$sCell = $getsplit->getAtt('cell');
		$sPickup = $getsplit->getAtt('pickup');
		$sDropoff = $getsplit->getAtt('dropoff');
		$sLocation = $getsplit->getAtt('location');
		$sClothes = $getsplit->getAtt('clothes');
		$sNotes = $getsplit->getAtt('notes');
		$sStatus = $getsplit->getAtt('status');
		$sDate = $getsplit->getAtt('ridedate');
		$sTime = $getsplit->getAtt('timetaken');}
	
	// this creates the duplicate ride
	$qry2 = "INSERT INTO rides (name,cell,riders,pickup,dropoff,location,clothes,notes,status,ridedate,timetaken) VALUES 
    ('".
    $sName."','".
    $sCell."','".
    $ridersLeft."','".
    $sPickup."','".
    $sDropoff."','".
    $sLocation."','".
    $sClothes."','".
    $sNotes."','".
    $sStatus."','".
    $sDate."','".
    $sTime."')";
    mysql_query($qry2) or die('INSERT failed: ' . mysql_error());
	
	
	// this assigns the intended ride
	$qry3 = "UPDATE rides SET car = '" . $car . "', riders = '" . $riders . "', status = 'riding', timeassigned = '".date("YmdHis")."' WHERE num='" . $num . "'";
    mysql_query($qry3) or die('UPDATE failed: ' . mysql_error());}
    
function rideEdit($num) {
	$qry = "UPDATE rides SET 
	car = '" . $_POST["car"] . "', 
	name = '" . $_POST["name"] . "',
	cell = '" . $_POST["cellOne"].$_POST["cellTwo"].$_POST["cellThree"]. "',
	riders = '" . $_POST["riders"] . "',
	pickup = '" . $_POST["pickup"] . "',
	dropoff = '" . $_POST["dropoff"] . "',
	location = '" . $_POST["location"] . "',
	clothes = '" . $_POST["clothes"] . "',
	notes = '" . $_POST["notes"] . "' WHERE num = '" . $num . "'";
	mysql_query($qry) or die('UPDATE failed: ' . mysql_error());}

function rideAdd() {
global $dateofride;

    $qry = "INSERT INTO rides (name,cell,riders,pickup,dropoff,aploc,dormloc,clothes,notes,status,ridedate,timetaken) VALUES 
    ('".
    $_POST["name"]."','".
    $_POST["cell1"].$_POST["cell2"].$_POST["cell3"]."','".
    $_POST["riders"]."','".
    $_POST["pickup"]."','".
    $_POST["dropoff"]."','".
    $_POST["aploc"]."','".
    $_POST["dormloc"]."','".
    $_POST["clothes"]."','".
    $_POST["notes"]."',
    'waiting','".
    $dateofride."','".
    date("YmdHis")."')";
    
    mysql_query($qry) or die('INSERT failed: ' . mysql_error());}
    
function rideCircuit() {
global $dateofride;

    $qry = "INSERT INTO rides (car,name,riders,pickup,dropoff,location,notes,status,ridedate,timetaken,timeassigned,timedone) VALUES 
    ('".
    $_POST["car"]."','".
    $_POST["name"]."','".
    $_POST["riders"]."',
    'ng','".
    $_POST["dropoff"]."','".
    $_POST["location"]."',
    'circuit',
    'done','".
    $dateofride."','".
    date("YmdHis")."','".
    date("YmdHis")."','".
    date("YmdHis")."')";
    
    mysql_query($qry) or die('INSERT failed: ' . mysql_error());}
  
function rideCancel($num) {
    $tQry = mysql_query("SELECT timetaken FROM rides WHERE num='" . $num . "'");
    $tRide = mysql_fetch_array($tQry);
    $tDone = date("U");
    $tAssigned = strtotime($tRide['timetaken']);
    $tDiff = $tDone - $tAssigned;
    if ($tDiff > 1800)
    {$cStatus = 'cpmissed';}
    else 
    {$cStatus = 'cancelled';}
    
    $qry = "UPDATE rides SET status = '".$cStatus."', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
    mysql_query($qry) or die('INSERT failed: ' . mysql_error());}  
    
function rideUndo($num) {

	//this opens the original ride
	$sql="SELECT * FROM rides WHERE num = '" . $num . "'";
	$qry1 = mysql_query($sql);
	while($row = mysql_fetch_array($qry1)) {
    	$getundo  =  new  Ride($row); 
   		$uCar = $getundo->getAtt('car');}
   		
   	if ($uCar==0){
   		$stat="waiting";}
   	else {
   		$stat="riding";}

   	$qry = "UPDATE rides SET status = '" . $stat . "' WHERE num='" . $num . "'";
	mysql_query($qry) or die('INSERT failed: ' . mysql_error());
    header("location: ./" . $stat . ".php?num=".$_POST["num"]);}
    
function rideDone($num) {
    $qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
    mysql_query($qry) or die('INSERT failed: ' . mysql_error());}
    
function carUpdate() {
global $dateofride;

    $qry = "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES 
    ('".
    $_POST["carnum"]."',
    'called','".
    $dateofride."',
    '".date("YmdHis")."')";
    
    mysql_query($qry) or die('INSERT failed: ' . mysql_error());}
    
function carHome() {
global $dateofride;

    $qry = "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES 
    ('".
    $_POST["carnum"]."',
    'home','".
    $dateofride."',
    '".date("YmdHis")."')";
    
    mysql_query($qry) or die('INSERT failed: ' . mysql_error());}
    

?>
