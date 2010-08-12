<?php 

// sets the time zone and establishes the "day" as 12 hours to prevent midnight problem
date_default_timezone_set("america/chicago");
$today = date("U")-60*60*12;
$dateofride = date("Ymd", mktime(date("H")-12, date("i"), date("s"), date("m"), date("d"), date("Y")))."000000";

$datecheck = "timetaken > DATE_SUB(FROM_UNIXTIME(". date("U") ."), INTERVAL 10 HOUR)";
$currtime = date("d")*720+date("H")*60+date("i");
$timenow = date("U");


/// Display Functions

function minFromNow($inputtime) {

	$mindiff = date("U") - strtotime($inputtime);
	$mindiff = round($mindiff/60,0);
	return $mindiff;
}
function tblBtnPreAssign($ride) {
	echo '<td class="btn">';
	echo '<button class="assign" onClick="highlight(\'row'.$ride.'\');assignride(\'preassign.php?num='.$ride.'\',\'assign'.$ride.'\')">PreAssign</button>';
	echo '</td>'."\r";
	}

function tblBtnAssign($ride) {
	echo '<td class="btn">';
	echo '<button class="assign" onClick="highlight(\'row'.$ride.'\');assignride(\'assign.php?num='.$ride.'\',\'assign'.$ride.'\')">Assign</button>';
	echo '</td>'."\r";
	}
function tblBtnSplit($ride) {
	echo '<td class="btn">';
	echo '<button class="split" onClick="highlight(\'row'.$ride.'\');assignride(\'split.php?num='.$ride.'\',\'assign'.$ride.'\')">Split</button>';
	echo '</td>'."\r";
	}
function tblBtnEdit($ride,$pg) {
	echo '<td class="btn">';
	echo '<button class="edit" onClick="window.location=\'edit.php?pg='.$pg.'&num='.$ride.'\'">Edit</button>';
	echo '</td>'."\r";
	}
function tblBtnDone($ride) {
	echo '<td class="btn">';
	echo '<button class="done" onClick="window.location=\'actions.php?num='.$ride.'&action=done\'">Done</button>';
	echo '</td>'."\r";
	}
function tblBtnCancel($ride) {
	echo '<td class="btn">';
	echo '<button class="cancel" onClick="window.location=\'actions.php?num='.$ride.'&action=cancel\'">Cancel</button>';
	echo '</td>'."\r";
	}
function tblBtnUndo($ride) {
	echo '<td class="btn">';
	echo '<button class="undo" onClick="window.location=\'actions.php?num='.$ride.'&action=undo\'">Undo</button>';
	echo '</td>'."\r";
	}

function tblRideInfo($info) {
	echo '<td>';
	echo $info;
	echo '</td>'."\r";
	}
function tblDoneCar($car) {
	echo '<td>';
	echo '<a href="done.php?car='.$car.'">'.$car.'</a>';
	echo '</td>'."\r";
	}
function tblCell($cell,$ng) {
	if ($ng=="ng") {
		$cellnum = '-';}
	else {
		$cellnum = '('.substr($cell,0,3).') '.substr($cell,3,3).'-'.substr($cell,6,4);}
	
	echo '<td>';
	echo $cellnum;
	echo '</td>'."\r";
	}
function tblCalledIn($intime) {
	$mindiff = date("U") - strtotime($intime);
	$mindiff = round($mindiff/60,0);
	
	if ($mindiff<30) 
		$tclass = 'short';
	elseif ($mindiff<50) 
		$tclass = 'med';
	else 
		$tclass = 'long';
	
	echo '<td><span class="'.$tclass.'">';
	echo $mindiff;
	echo ' min</span></td>'."\r";
	}

function tblTimeWait($called,$assigned,$done,$status,$ng) {
	if ($status=="cpmissed" || $status=="cancelled") {
		$mindiff = strtotime($done) - strtotime($called);}
	else {
		$mindiff = strtotime($assigned) - strtotime($called);}
	
	$mindiff = round($mindiff/60,0);
	
	if ($mindiff<30) 
		$tclass = 'short';
	elseif ($mindiff<50) 
		$tclass = 'med';
	else 
		$tclass = 'long';
		
	if ($ng=="ng") {
		$mindiff = '-';
		$tclass = 'circuit';}
		
	if ($mindiff<>"-"){
		$mindiff = $mindiff.' min';}
	
	echo '<td><span class="'.$tclass.'">';
	echo $mindiff;	
	echo '</span></td>'."\r";
	}

function tblTimeRode($done,$assigned,$status,$ng) {
	if ($status=="cpmissed" || $status=="cancelled") {
		$tclass = '';}
	else {
		$mindiff = strtotime($done) - strtotime($assigned);}
	
	$mindiff = round($mindiff/60,0);
	
	if ($mindiff<>"0") {
		if ($mindiff<30) 
			$tclass = 'short';
		elseif ($mindiff<60) 
			$tclass = 'med';
		else 
			$tclass = 'long';
	}
	else {$mindiff = '-';}
	
	if ($ng=="ng") {
		$mindiff = '-';
		$tclass = 'circuit';}
		
	if ($mindiff<>"-"){
		$mindiff = $mindiff.' min';}
	
	echo '<td><span class="'.$tclass.'">';
	echo $mindiff;
	echo '</span></td>'."\r";
	}

function tblHome($tdone,$tstatus) {
	if ($tstatus=="done") {
		$athome = date("h:i",strtotime($tdone));}
	else {
		$athome = '-';}
	
	echo '<td>';
	echo $athome;
	echo '</td>'."\r";
	
	}


function rowColor($i) {
	if (fmod($i,2) == 0) {
		$bgCol = 'white';}
	else {
		$bgCol = 'grey';}
	
	return $bgCol;
	}

/// Header functions

function checkCount($type) {
global $dateofride;

$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND status = '".$type."'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		return $row['total'];}
		}

/// Action Functions

function prerideAssign($num,$car) {
    $qry = "UPDATE rides SET car = '" . $car . "', status = 'waiting' 'WHERE num='" . $num . "'";
    mysql_query($qry) or die('UPDATE failed: ' . mysql_error());}



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

    $qry = "INSERT INTO rides (name,cell,riders,pickup,dropoff,location,clothes,notes,status,ridedate,timetaken) VALUES 
    ('".
    $_POST["name"]."','".
    $_POST["cell1"].$_POST["cell2"].$_POST["cell3"]."','".
    $_POST["riders"]."','".
    $_POST["pickup"]."','".
    $_POST["dropoff"]."','".
    $_POST["location"]."','".
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
