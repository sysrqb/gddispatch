<?php
/*
$gmttime = new DateTime(NULL,new DateTimeZone('UTC'));
echo (($gm=$gmttime->format('H') ). "\n");
$time = new DateTime();
echo (($est=$time->format('H')) . "\n");
echo ((24+$gm)-$est . "\n");
$diff = $gmttime->diff($time);
echo ($diff->format('%R%H'."\n"));


$time = new DateTime();
$past = new DateTime("2010-08-24 18:57:00");
$diff = $time->diff($past);
echo ($diff->format("%H:%I\n"));

$tinter = new DateInterval("PT30M");
echo $tinter->format('%H:%I:%S'."\n");

if(($tinter->format('%H')) < ($diff->format('%H'))){
	echo "true\n";
	if($tinter->format('%I') < $diff->format('%I')){
		echo "true\n";
	}
	else{
		echo "false\n";
	}
}
else {
	echo "false\n";
}

for($bench = 0; $bench < 3; $bench++)
{
    $start = microtime(true);
        $a = 1;
	    for($i = 0; $i < 100000000; $i++)
	      {
	              if($a != 'hello') $b++;
		          }
			      $end = microtime(true);
			          echo "Used time: " . ($end-$start) . "\n";
				  }

*/
	$prepare['ridecount'] = 'mysqli_stmt_prepare($con, "SELECT * FROM rides WHERE DATE(ridedate) = ? AND status =?")'; 
/*		 'totalcount' => "mysqli_stmt_prepare($con, "SELECT SUM(riders) as total FROM rides WHERE ridedate = ? AND status = ?")",
		 'setpreride' => "mysqli_stmt_prepare($con, "UPDATE rides SET precar = ?, status = 'waiting' WHERE num=?")",
		 'getpreride' => "mysqli_stmt_prepare($con, "SELECT precar FROM rides WHERE num=?")",
		 'setride' => "mysqli_stmt_prepare($con, "UPDATE rides SET car =? , status = 'riding', timeassigned =? WHERE num=?")",
		 'getride' => "mysqli_stmt_prepare($con, "SELECT * FROM rides WHERE num =?")",
		 'splitduplicate' => "mysqli_stmt_prepare($con, "INSERT INTO rides (name,cell,riders,pickup,dropoff,location,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?)")", 
		 'splitupdate' = "mysqli_stmt_prepare($con, "UPDATE rides SET car =? , riders =?, status = 'riding', timeassigned =? WHERE num=?")",
		 'rideupdate' => "mysqli_stmt_prepare($con, "UPDATE rides SET car=?, name=?, cell=?, riders=?, pickup=?, dropoff=?, location=?, clothes=?, notes=? WHERE num=?")",
		 'rideadd' => "mysqli_stmt_prepare($con, "INSERT INTO rides (name,cell,riders,pickup,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?, 'waiting', ?,?)")",
		 'getridetocancel' => "mysqli_stmt_prepare($con, "UPDATE rides SET status =? , timedone =?, WHERE num=?")", 
		 'rideundo' => "mysqli_stmt_prepare($con, "UPDATE rides SET status=? where num=?")",
		 'ridedone' => "mysqli_stmt_prepare($con, "UPDATE rides SET status='done', timedone=? WHERE num=?")",
		 'carupdate' => "mysqli_stmt_prepare($con, "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES (?,?,?,?)")",
		);*/
	print_r($prepare);
?>
