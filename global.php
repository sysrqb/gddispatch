<?php

$log = FALSE;
$logfile = "/tmp/dispatch.log";
$die = TRUE;
$quiet = FALSE;

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
create prepared statements
*/
$prepare = array('ride' => "SELECT * FROM patron, ridetimes WHERE LEFT(ridetimes.ridecreated, 10) = ? AND status = ? AND ridetimes.tid = patron.pid", 
		 'location' => 'SELECT * FROM saferide.locations where lid = ?',
		 'totalcount' => "SELECT SUM(riders) as total FROM patron, ridetimes WHERE LEFT(ridetimes.ridecreated, 10) = ? AND status = ? AND ridetimes.tid = patron.pid",
		 'setassign' => "UPDATE patron, ridetimes SET patron.car=?, patron.status='assigned', ridetimes.rideassigned = ? WHERE ridetimes.pid = ? AND patron.pid = ?",
		 'setride' => "UPDATE patron SET car =? , status = 'riding' WHERE pid = ? AND UPDATE ridetimes SET timepickedup = ? WHERE pid = ?",
		 'getride' => "SELECT * FROM patron, ridetimes WHERE patron.pid = ? AND ridetimes.pid = patron.pid",
		 'splitduplicate' => "INSERT INTO patron (name,cell,riders,car,pickup,dropoff,notes,clothes,ridedate,status,timetaken,loc) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
		 'splitupdate' => "UPDATE rides SET car =? , riders =?, status = 'riding', timeassigned =? WHERE num=?",
		 'rideupdate' => "UPDATE rides SET car=?, name=?, cell=?, riders=?, pickup=?, fromloc=?, dropoff=?, loc=?, clothes=?, notes=? WHERE num=?",
		 //'rideadd' => "INSERT INTO rides (name,cell,riders,pickup,fromloc,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
		 'getlocationid' => "SELECT lid FROM locations WHERE value = ? OR name = ? OR code=?",
		 'addlocation' => 'INSERT INTO locations (name, value) VALUES (?, ?)',
		 'rideaddpatron' => "INSERT INTO patron (name,cell,riders,pickup,dropoff,clothes,notes,status) VALUES (?,?,?,?,?,?,?,?)",
		 'rideaddtime' => "INSERT INTO ridetimes (ridecreated, pid) VALUES (?, ?)",
		 'setridetocancel' => "UPDATE patron SET status = ? , timedone = ?, WHERE pid = ? AND UPDATE ridetimes SET timecancelled = ?", 
		 'rideundo' => "UPDATE patron SET status = ? where pid = ?",
		 'ridedone' => "UPDATE patron SET status = ?, WHERE pid = ? AND UPDATE ridetimes SET timecomplete = ? WHERE pid = ?",
		 'carupdate' => "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES (?,?,?,?)",
		);


