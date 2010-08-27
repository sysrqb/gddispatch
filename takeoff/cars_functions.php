<?php
//include("classes.php"); 


function carBoxes(){
global $datecheck;
global $currtime;

$carsMaxBase = 12;
$cars = array();

// Looks up the highest car number used on the night
$cSql = "SELECT MAX(car) as max FROM rides WHERE ".$datecheck;
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['max']==NULL)
			$carsMaxCount = 1;
		else
			$carsMaxCount = $row['max'];}
			
$carsMax = max($carsMaxBase,$carsMaxCount);

// Generates an array of all the car numbers used
for ($x=1; $x <= $carsMax; $x ++){
        $cars[] = $x;
    }

// Generates a box for the car, and loops for each car
for ($i=0; $i<sizeof($cars); $i++){
$carsDoneTime=2;
$carsRidingTime=2;
$carsContactTime = 2;

// Looks up the last action of done (will detect a circuit car)
$cSql = "SELECT timedone, pickup FROM rides WHERE ".$datecheck." AND car = ".$cars[$i]." ORDER BY timedone";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['timedone']==NULL)
			$carsDoneTime = 1;
		else
			$carsDoneTime = $row['timedone'];
			$carsPickup = $row['pickup'];}

// Looks up the last action of done (will detect a circuit car)
$cSql = "SELECT timeassigned FROM rides WHERE ".$datecheck." AND car = ".$cars[$i]." ORDER BY timeassigned";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['timeassigned']==NULL)
			$carsRidingTime = 1;
		else
			$carsRidingTime = $row['timeassigned'];}


			
// Looks up the last action if executed by 
$cSql = "SELECT contacttime, reason FROM contacted WHERE contacttime > DATE_SUB(FROM_UNIXTIME(". date("U") ."), INTERVAL 8 HOUR) AND carnum = ".$cars[$i]." ORDER BY contacttime";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['contacttime']==NULL)
			$carsContactTime = 1;
		else
			$carsContactTime = $row['contacttime'];
			$carsContactReason = $row['reason'];}		
	
			
	$carsTime = max($carsRidingTime,$carsDoneTime);
	$ridetimeCont = substr($carsContactTime,8,2)*720+substr($carsContactTime,11,2)*60+substr($carsContactTime,14,2);
	$ridetime = substr($carsTime,8,2)*720+substr($carsTime,11,2)*60+substr($carsTime,14,2);
	$carsTimeDiff = $currtime - $ridetime;
	$carsContDiff = $currtime - $ridetimeCont;
	
	// Sets status for car Still Here
	$carsStatus = "car-here";
	$textLine1 = '<h2>Car '.$cars[$i].' is Not Out</h2>';
	$textLine2 = '';
	$textLine3 = '';
	$textLine4 = '';
	
	// Sets status for car on Circuit	
	if ($carsPickup=="ng" && $carsTime==$carsDoneTime && $carsTime > 3) {
			$carsStatus = "car-circuit";
			$textLine1 = '<h2>Car '.$cars[$i].' is on Circuit</h2>';
			$textLine2 = '<h3>It last dropped off</h3>';}
	// Sets status for normal car	
	elseif  ($carsTime > 3 && $carsTime==$carsRidingTime) {
			$carsStatus = "car-normal";			
			$textLine1 = '<h2>Car '.$cars[$i].' is on a Ride</h2>';
			$textLine2 = '<h3>It was assigned</h3>';}
	// Sets status for chilling
	elseif  ($carsPickup<>"ng" && $carsTime > 3 && $carsTime==$carsDoneTime){
			$carsStatus = "car-chill";			
			$textLine1 = '<h2>Car '.$cars[$i].' is Chilling</h2>';
			$textLine2 = '<h3>It was last done </h3>';}
			
	$carsStatusLast = $carsStatus;
			
	if ($carsStatus=="car-circuit" && $carsTimeDiff>90) {
			$carsStatus = "car-call";}
	elseif ($carsStatus=="car-normal" && $carsTimeDiff>60) {
			$carsStatus = "car-call";}
	elseif ($carsStatus=="car-chill" && $carsTimeDiff>60) {
			$carsStatus = "car-call";}
	
	if ($carsStatus<>"car-here"){
		$textLine3 = '<h3>'.$carsTimeDiff.' minutes ago</h3>';}
				
	if ($carsContDiff<$carsTimeDiff && $carsContactReason=='called') {
			$carsStatus = $carsStatusLast;
			$textLine4 = '<h3 style="font-weight:bold;">Spoke '.$carsContDiff.' mins ago</h3>';}
	elseif ($carsContDiff<$carsTimeDiff  && $carsContactReason=='home') {
			$carsStatus = "car-done";			
			$textLine1 = '<h2>Car '.$cars[$i].' is Back Home</h2>';
			$textLine2 = '';
			$textLine3 = '';}
			
	
	
	
		$carBoxText = '<div id="car'.$cars[$i].'" class="c car-box '.$carsStatus.'" onClick="'.
				"carsEdit('car".$cars[$i]."','".$cars[$i]."','cars_edit.php?carnum=".$cars[$i]."')".
				';">'.$textLine1.$textLine2.$textLine3.$textLine4.'</div>';
		
		echo $carBoxText;	
	}
}

?>