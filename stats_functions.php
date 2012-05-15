<?php
//include("functions.php");

function ridesbyhour() {
	global $gdate;

	$con = connect();
	$totdone = 0;
	$totcancel = 0;
	$totmissed = 0;

	$timeArray = array(00,01,02,03,07,08);
	for ($i=0; $i<6; $i++){

		$hTime = $timeArray[$i];

	// Count done rides	
		if(!($stmt=mysqli_stmt_init($con))){
			die('Failed Init ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_prepare($stmt, "SELECT SUM(riders) as sum FROM rides WHERE ridedate = ? AND status = 'done' AND HOUR(timedone) = ?")){
			die('Prep1 Failed: . ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_param($stmt, 'si', $gdate, $hTime)){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
		}
		/*$cSql = "SELECT SUM(riders) as max FROM rides WHERE ridedate = ".$gdate." AND status = 'done' AND HOUR(timedone) = ".$hTime;
			$cQry = mysql_query($cSql);
			while ($row = mysql_fetch_array($cQry)) {
			if ($row['max']==NULL)
				$hourdone = 0.0;
			else
				$hourdone = $row['max'];}*/
		if(!mysqli_stmt_execute($stmt)){
			die('Exec Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_result($stmt, $donesum)){
			die('Bind Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while(mysqli_stmt_fetch($stmt)){
			$hourdone = $donesum;
		}


		if(!($stmt=mysqli_stmt_init($con))){
			die('Failed Init ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_prepare($stmt, "SELECT SUM(riders) as max FROM rides WHERE ridedate = ? AND status = 'cancelled' AND timedone = ?")){
			die('Prep1 Failed: . ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_param($stmt, 'si', $gdate, $hTime)){
			die('Bind Failed: ' . mysqli_stmt_error($stmt));
		}
		
		/*$cSql = "SELECT SUM(riders) as max FROM rides WHERE ridedate = ".$dateofride." AND status = 'cancelled' AND HOUR(timedone) = ".$hTime;
			$cQry = mysql_query($cSql);
			while ($row = mysql_fetch_array($cQry)) {
			if ($row['max']==NULL)
				$hourcan = 0.0;
			else
				$hourcan = $row['max'];}*/
		if(!mysqli_stmt_execute($stmt)){
			die('Exec Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_result($stmt, $cancsum)){
			die('Bind Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while(mysqli_stmt_fetch($stmt)){
			$hourcan = $cancsum;
		}

		if(!($stmt=mysqli_stmt_init($con))){
			die('Failed Init ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_prepare($stmt, "SELECT SUM(riders) as max FROM rides WHERE ridedate = ? AND status = 'missed' AND timedone = ?")){
			die('Prep1 Failed: . ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_param($stmt, 'si', $gdate, $hTime)){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
		}

		/*$cSql = "SELECT SUM(riders) as max FROM rides WHERE ridedate = ".$dateofride." AND status = 'cpmissed' AND HOUR(timedone) = ".$hTime;
			$cQry = mysql_query($cSql);
			while ($row = mysql_fetch_array($cQry)) {
			if ($row['max']==NULL)
				$hourmissed = 0.0;
			else
				$hourmissed = $row['max'];}*/
		if(!mysqli_stmt_execute($stmt)){
			die('Exec Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!mysqli_stmt_bind_result($stmt, $misssum)){
			die('Bind Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while(mysqli_stmt_fetch($stmt)){
			$hourmissed = $misssum;
		}
	}

	$totcancel = $totcancel.','.$hourcan;
	$totmissed = $totmissed.','.$hourmissed;
	$totdone = $totdone.','.$hourdone;

	$ridesbyhour = 'http://chart.apis.google.com/chart?cht=lc&chd=t:'.$totdone.'|'.$totcancel.'|'.$totmissed.'&chxt=x,y&chxl=0:||11pm|12am|1am|2am|3am|4am|1:|1|50|100&chs=300x180&chco=00ff00cc,0000ffcc,ff0000cc&chtt=Rides%20by%20Hour&chts=333333,18&chdl=Done|Cancelled|Missed';

	echo $ridesbyhour;
}

function ridesByCar() {
global $dateofride;

$carMaxBase = 12;
$cars = array();

// Looks up the highest car number used on the night
$cSql = "SELECT MAX(car) as max FROM rides WHERE ridedate = ".$dateofride;
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['max']==NULL)
			$carMaxCount = 1;
		else
			$carMaxCount = $row['max'];}
			
$carMax = max($carMaxBase,$carMaxCount);

// Generates an array of all the car numbers used
for ($x=1; $x <= $carMax; $x ++){
        $cars[] = $x;
    }


$normalCountText = '0';
$ngCountText = '0';
$allCountText = 't0,444444,1,0,13,1';

	
	$sizeFactor = Round(sizeof($cars)*(-21.5/18)+44);

 

// Generates a box for the car, and loops for each car
for ($i=0; $i<sizeof($cars); $i++){
$ngCount = '0.0';
$allCount = '0.0';
$normalCount = '0.0';


// Count done NG rides
$cSql = "SELECT SUM(riders) as max FROM rides WHERE ridedate = ".$dateofride." AND status = 'done' AND pickup = 'ng' AND car = ".$cars[$i];
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['max']==NULL)
			$ngCount = 0.0;
		else
			$ngCount = $row['max'];}

$cSql = "SELECT SUM(riders) as max FROM rides WHERE ridedate = ".$dateofride." AND status = 'done' AND  car = ".$cars[$i];
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['max']==NULL)
			$allCount = 0.0;
		else
			$allCount = $row['max'];}	
$j=$i+1;
$normalCount = $allCount - $ngCount;

$normalCountText = $normalCountText.','.$normalCount;
$ngCountText = $ngCountText.','.$ngCount;
$allCountText = $allCountText.'|t'.$allCount.',444444,0,'.$j.',13,1';
}

$ridesByCarText = 'http://chart.apis.google.com/chart?cht=bvs&chd=t:'.$normalCountText.'|'.$ngCountText.'&chs=640x200&chtt=Rides%20by%20Type%20for%20each%20Car&chts=333333,18&chco=cc33ffcc,66cccccc&chxt=x,y&chxl=1:|0|20|40&chds=0,40&chbh='.$sizeFactor.'&chdl=Calls|North Gate&chm='.$allCountText;

echo $ridesByCarText;
}

/// Counts done, missed, and cancelled
function rideCounter(){
global $dateofride;
$doneRides=0;
$missedRides=0;
$cancelledRides=0;

$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND status = 'done'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['total']==NULL)
			$doneRides = 0.0;
		else
			$doneRides = $row['total'];}	
		
$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND status = 'cpmissed'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['total']==NULL)
			$missedRides = 0.0;
		else
			$missedRides = $row['total'];}

$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND status = 'cancelled'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['total']==NULL)
			$cancelledRides = 0.0;
		else
			$cancelledRides = $row['total'];}	
		
$totalRides = $doneRides + $missedRides + $cancelledRides;
$gInt=round($totalRides/4);
$gInt2 = $gInt*2;
$gInt3 = $gInt*3;
$gInt4 = $gInt*4;
		
$rideCounterText = 'http://chart.apis.google.com/chart?cht=bvg&chd=t:'.$doneRides.'|'.$cancelledRides.'|'.$missedRides.'&chs=200x170&chco=00ff00cc,0000ffcc,ff0000cc&chts=00ff00,16&chdl=Done|Cancelled|Missed&chxt=y&chxl=0:|0|'.$gInt.'|'.$gInt2.'|'.$gInt3.'|'.$gInt4.'&chds=0,'.$gInt4.'&chbh=25&chm=t'.$doneRides.',444444,0,0,13,1|t'.$cancelledRides.',444444,1,0,13,1|t'.$missedRides.',444444,2,0,13,1';

echo $rideCounterText;
}

/// Pie Chart for B/CS
function locationPie() {
global $dateofride;
$csRides=0.0;
$bryanRides=0.0;

$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND location = 'CS'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['total']==NULL)
			$csRides = 0.0;
		else
			$csRides = $row['total'];}	
		
$cSql = "SELECT SUM(riders) as total FROM rides WHERE ridedate = ".$dateofride." AND location = 'B'";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		if ($row['total']==NULL)
			$bryanRides = 0.0;
		else
			$bryanRides = $row['total'];}
				
$locationPieText = 'http://chart.apis.google.com/chart?cht=p&chd=t:'.$bryanRides.','.$csRides.'&chs=200x100&chco=cccccccc,aa4444ee&chdl=Bryan|College%20Station';

echo $locationPieText;
}

function rideMeter() {
global $dateofride;

$timeStart=0;
$timeDone=0;
$i=0;
$j=0;

$cSql = "SELECT SUM(timetaken) as total, count(timetaken) as count FROM rides WHERE ridedate = ".$dateofride." AND status = 'done' AND NOT pickup = 'ng' ";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		$j=$row['count'];
		$timeStart = $row['total'];}	
		
$cSql = "SELECT SUM(timeassigned) as total, count(timeassigned) as count FROM rides WHERE ridedate = ".$dateofride." AND status = 'done' AND NOT pickup = 'ng' ";
		$cQry = mysql_query($cSql);
		while ($row = mysql_fetch_array($cQry)) {
		$i=$row['count'];
		$timeDone = $row['total'];}

$timeDoneAvg = Round($timeDone/$i,-2);
$timeStartAvg = Round($timeStart/$j,-2);

$timeSpent=Round((($timeDoneAvg-$timeStartAvg)/100),0)+5;
$rideMeterText = 'http://chart.apis.google.com/chart?cht=gom&chd=t:'.$timeSpent.'&chs=220x110&chco=00ff00,ffff00,ff0000&chtt=Average%20Wait&chts=333333,18&chl='.$timeSpent.'%20mins';

echo $rideMeterText;
}


?>
