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
		 'location' => 'SELECT * FROM ' . $db . '.locations where lid = ?',
		 'totalcount' => "SELECT SUM(riders) as total FROM patron, ridetimes WHERE LEFT(ridetimes.ridecreated, 10) = ? AND status = ? AND ridetimes.tid = patron.pid",
		 'setassign' => "UPDATE patron, ridetimes SET patron.car=?, patron.status='assigned', ridetimes.rideassigned = ? WHERE ridetimes.pid = ? AND patron.pid = ?",
		 'setride' => "UPDATE patron SET car =? , status = 'riding' WHERE pid = ? AND UPDATE ridetimes SET timepickedup = ? WHERE pid = ?",
		 'getride' => "SELECT * FROM patron, ridetimes WHERE patron.pid = ? AND ridetimes.pid = patron.pid",
		 'splitduplicate1' => "INSERT INTO patron (name, cell, riders, car, pickup, dropoff, notes, clothes, status) VALUES (?,?,?,?,?,?,?,?,?)",
		 'splitduplicate2' => "INSERT INTO ridetimes (ridecreated, rideassigned, timepickedup, timecomplete, timecancelled, pid) VALUES(?,?,?,?,?,?)",
		 'splitupdate1' => "UPDATE patron SET car =? , riders =?, status = 'assigned' WHERE pid=?",
		 'splitupdate2' => "UPDATE ridetimes SET rideassigned =? WHERE pid=?",
		 'rideupdate' => "UPDATE patron SET car=?, name=?, cell=?, riders=?, pickup=?, dropoff=?, clothes=?, notes=? WHERE pid=?",
		 //'rideadd' => "INSERT INTO rides (name,cell,riders,pickup,fromloc,dropoff,loc,clothes,notes,status,ridedate,timetaken) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
		 'getlocationid' => "SELECT lid FROM locations WHERE value = ? OR name = ? OR code=?",
		 'addlocation' => 'INSERT INTO locations (name, value) VALUES (?, ?)',
		 'rideaddpatron' => "INSERT INTO patron (name,cell,riders,pickup,dropoff,clothes,notes,status) VALUES (?,?,?,?,?,?,?,?)",
		 'rideaddtime' => "INSERT INTO ridetimes (ridecreated, pid) VALUES (?, ?)",
		 'ridecanceltime' => "UPDATE ridetimes SET timecancelled = ? WHERE pid = ?", 
		 'changeridestatus' => "UPDATE patron SET status=? WHERE pid = ?",
		 'ridedonetime' => "UPDATE ridetimes SET timecomplete = ? WHERE pid = ?",
		 'rideridingtime' => "UPDATE ridetimes SET timepickedup = ? WHERE pid = ?",
		 'carupdate' => "INSERT INTO contacted (carnum,reason,ridedate,contacttime) VALUES (?,?,?,?)",
		);


