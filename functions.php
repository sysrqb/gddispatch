<?php 
require_once('credfile.php');
require_once('config.php');
require_once('global.php');
require_once('connection.php');
require_once('uifunctions.php');
require_once('logger.php');

/*------------------------
Begin function definitions
------------------------*/

//establish connection to mysql database. credentials and db name are found in cred.php
/*
function connect(){
	global $host, $username, $password, $db,$prepare;
	$con = mysqli_connect($host,$username,$password);
	if(!$con){
		die("Connection Error host = " . $host . " \n username= ". $username . " \n password= ". $password . " \n db = ". $db . '(' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	}
	mysqli_select_db($con, $db);
	return $con;
}
*/

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

/* Finds the minutes between the $inputtime and the current time */
function minFromNow($inputtime) {
	$diff = date_diff($inputtime, gmttoest());
	return (date_format($diff, '%H:%I'));
}

/// Header functions

/* Returns Date in DateTime */
function getRideDate($datetime)
{
  $pos = stripos($datetime, " ");
  $date = str_split($datetime, $pos)[0];
  return $date;
}

/**********
 * Returns Count of rides with given status
 *********/
function checkRideCount($status)
{
  global $gdate,$prepare;
  $con = connect();
  if(!($stmt = $con->prepare($prepare['ride']))){
    $error = 'checkRideCount: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0; 
  }
  if(!$stmt->bind_param('ss', $gdate, $status))
  {
    $error = 'checkRideCount: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  } 
  if(!$stmt->execute())
  {
    $error = 'checkRideCount: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  } 
  if(!$stmt->store_result())
  {
    $error = 'checkRideCount: Store Result Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  } 
  $affected_rows = $stmt->affected_rows;
  $stmt->close();
  $con->close();
  return $affected_rows;
}

/* Counts the total number of lives saved */
function checkCount($type)
{
  global $gdate,$prepare;
  $con = connect();
  if(!($stmt = $con->prepare($prepare['totalcount']))){
    $error = 'checkCount: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('ss', $gdate, $type))
  {
    $error = 'checkCount: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'checkCount: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $total = 0;
  if(!$stmt->bind_result($total))
  {
    $error = 'checkCount: Bind Result Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $stmt->fetch();
  $stmt->close();
  $con->close();
  return $total;
}

/// Action Functions

//assigns ride to a car
function rideAssign($num,$car)
{
  global $prepare, $gtime;
  $con = connect();
  if(!($stmt = $con->prepare($prepare['setassign'])))
  {
    $error = 'rideAssign: Prep failed: ' . $con->error;
    loganddie($error);
    return;
  }

  if(!$stmt->bind_param('isii', $car, $gtime, $num, $num))
  {
    $error = 'rideAssign: Bind failed ' . $stmt->error;
    loganddie($error);
    return;
  }

  if(!$stmt->execute())
  {
    $error = 'rideAssign: UPDATE failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();
  $con->close();
}


//splits a single ride into multiple cars
function rideSplit($num,$car,$riders) {
	global $gtime,$prepare;
	$con = connect();

	$num = $con->real_escape_string($num);
	$car = $con->real_escape_string($car);
	$riders = $con->real_escape_string($riders);

	if(!($stmt = $con->prepare($prepare['getride']))){
		die('Prep failed: ' . $con->error);
	}

	if(!$stmt->bind_param('i', $num)){
		die('Bind failed ' . $stmti->error);
	}

	if(!$stmt->execute()){
		die('UPDATE failed: ' . $stmt->error);
	}
	//this opens the original ride
	if(!$stmt->bind_result( $row['pid'], 
				$row['name'], 
				$row['cell'], 
				$row['riders'], 
				$row['car'], 
				$row['pickup'], 
				$row['dropoff'], 
				$row['clothes'], 
				$row['notes'], 
				$row['status'], 
				$row['modified'], 
				$row['tid'], 
				$row['ridecreated'], 
				$row['rideassigned'], 
				$row['timepickedup'],
				$row['timecomplete'],
				$row['timecancelled'],
				$row['tpid'])){
		die('Res Bind Failed: ' . $stmt->error);
	}
	while($stmt->fetch()){
    	
    		$ridersLeft = ($row['riders'] - $riders);
	}		
	$stmt->close();
	//print_r($row);

	// this creates the duplicate ride
	if(!($stmt = $con->prepare($prepare['splitduplicate1']))){
		die('Prep Failed1: ' . $con->error);
	}
	if(!$stmt->bind_param('ssiiiisss',
	                      $row['name'],
			      $row['cell'],
			      $ridersLeft,
			      $row['car'],
			      $row['pickup'],
			      $row['dropoff'],
			      $row['notes'],
			      $row['clothes'],
			      $row['status'])){
		die('Bind Failed1: '. 'name: ' . $row['name'] . ' cell: ' .
		    $row['cell']  . ' riders: ' . $ridersLeft . ' car: ' .
		    $row['car'] . ' pickup: ' . $row['pickup'] .
		    ' dropoff: ' . $row['dropoff'] . ' notes: ' .
		    $row['notes'] . ' clothes: ' . $row['clothes'] .
		    ' ridedate: ' . $row['ridedate'] . ' status: ' .
		    $row['status'] . ' timetaken: ' . $row['timetaken'] .
		    ' loc: ' . $row['loc'] . $stmt->error);
	}	
	if(!$stmt->execute()){
		die('INSERT failed1: ' .  print_r($row) . ' ' . $ridersLeft . ' '  . $stmt->error);
	}
	$stmt->close();

	if(!($stmt = $con->prepare($prepare['splitduplicate2']))){
		die('Prep Failed2: ' . $con->error);
	}
	$newpid = $con->insert_id;
	if(!$stmt->bind_param('sssssi',
	                      $row['ridecreated'], 
			      $row['rideassigned'], 
			      $row['timepickedup'],
			      $row['timecomplete'],
			      $row['timecancelled'],
			      $newpid)){
		die('Bind Failed2: '. 'name: ' . $row['name'] . ' cell: ' .
		    $row['cell']  . ' riders: ' . $ridersLeft . ' car: ' .
		    $row['car'] . ' pickup: ' . $row['pickup'] .
		    ' dropoff: ' . $row['dropoff'] . ' notes: ' .
		    $row['notes'] . ' clothes: ' . $row['clothes'] .
		    ' ridedate: ' . $row['ridedate'] . ' status: ' .
		    $row['status'] . ' timetaken: ' . $row['timetaken'] .
		    ' loc: ' . $row['loc'] . $stmt->error);
	}	
	if(!$stmt->execute()){
		die('INSERT failed2: ' .  print_r($row) . ' ' . $ridersLeft . ' '  . $stmt->error);
	}
	$stmt->close();


	// this assigns the intended ride
	if(!($stmt = $con->prepare($prepare['splitupdate1']))){
		die('Prep Failed3: ' . $con->error);
	}
	if(!$stmt->bind_param('iii', $car, $riders, $num))
		die('Bind Param Failed3: ' . $stmt->error);
	if(!$stmt->execute()){
		die('UPDATE failed3: ' . $stmt->error);
	}
	$stmt->close();

	if(!($stmt = $con->prepare($prepare['splitupdate2']))){
		die('Prep Failed4: ' . $con->error);
	}
	if(!$stmt->bind_param('si', $gtime, $num))
		die('Bind Param Failed4: ' . $stmt->error);
	if(!$stmt->execute()){
		die('UPDATE failed4: ' . $stmt->error);
	}
	$stmt->close();

	$con->close();
}

//Edit the ride information
function rideEdit($num) {
  global $prepare;
  $con = connect();
  $name = $con->real_escape_string($_POST["name"]);
  $cellnumber = $_POST["cellOne"].$_POST["cellTwo"].$_POST["cellThree"];
  $cellnumber = $con->real_escape_string($cellnumber);
  $riders = $con->real_escape_string($_POST["riders"]);
  if(isset($_POST["fromloc"]))
    $fromloc = $con->real_escape_string($_POST["fromloc"]);
  $pickup = $con->real_escape_string($_POST["pickup"]);
  if(isset($_POST["toloc"]))
    $toloc = $con->real_escape_string($_POST["toloc"]);
  $dropoff = $con->real_escape_string($_POST["dropoff"]);
  $clothes = $con->real_escape_string($_POST["clothes"]);
  $notes = $con->real_escape_string($_POST["notes"]);
  $car = $con->real_escape_string($_POST['car']);
  $waiting = 'waiting';

  if(isset($fromloc) && !strcmp($fromloc, 'Other'))
    $pickupid = getLocationID($pickup);
  else
    $pickupid = getLocationID($fromloc);
  if(isset($toloc) && !strcmp($toloc, 'Other'))
    $dropoffid = getLocationID($dropoff);
  else
    $dropoffid = getLocationID($toloc);

  if(!($stmt=mysqli_stmt_init($con))){
    loganddie('Init Failed: ' . mysqli_stmt_error($stmt));
  }
  if(!$stmt->prepare($prepare['rideupdate'])){
    loganddie('Prep Failed: ' . mysqli_stmt_error($stmt));
  }
  $stmt->bind_param('ississssi', $car,
                                 $name,
				 $cellnumber,
				 $riders,
				 $pickupid,
				 $dropoffid,
				 $clothes,
				 $notes,
				 $num);
  if(!$stmt->execute()){
    loganddie('UPDATE failed: ' . $name . ': ' . $stmt->error);
  }
}

/* Add location to DB */
function addLocation($value)
{ 
  global $prepare, $log, $logfile;
  $hash = hash('sha256', $value);
  $hash = substr($hash, 0, 8);
  $con = connect();
  
  $value = $con->real_escape_string($value);

  if(!($stmt = $con->prepare($prepare['addlocation'])))
  {
    $error = 'addLocation: Preparing the statement failed ' .  
    $con->errno . ' ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('ss', $value, $hash))
  {
    $error = 'addLocationID: Binding the parameters failed ' .  
        $stmt->errno . " " . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'addLocation: Can not find location: ' . $stmt->errno() . ' ' . $stmt->error;
    loganddie($error);
    return;
  }

  return $con->insert_id;
}

function getLocation($lid)
{
  global $prepare, $log, $logfile;
  
  $con = connect();
  if(!($stmt = $con->prepare($prepare['location'])))
  {
    $error = 'getLocation: Preparing the statement failed ' .  $con->errno . ' ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('i', $lid))
  {
    $error = 'getLocation: Binding the parameters failed ' .  $stmt->errno . " " . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'getLocation: Can not find location: ' . $stmt->errno() . ' ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->store_result();
  if(!$stmt->num_rows)
    return;
  $stmt->bind_result($row['lid'], $row['name'], $row['value'], $row['code'],
      $row['lat'], $row['long']);
  while($stmt->fetch());
  $stmt->close();
  $con->close();
  return $row;
}

/* Get LocationID from DB
 * If location is not in DB, add it
 */
function getLocationID($value)
{
  global $prepare, $log, $logfile;
  
  $con = connect();

  $value = $con->real_escape_string($value);
  if($value == "Other")
    return addLocation($value);

  if(!($stmt = $con->prepare($prepare['getlocationid'])))
  {
    $error = 'getLocationID: Preparing the statement failed ' .  $con->errno . ' ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('sss', $value, $value, $value))
  {
    $error = 'getLocationID: Binding the parameters failed ' .  $stmt->errno . " " . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'getLocationID: Can not find location: ' . $stmt->errno() . ' ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->bind_result($loc);
  while($stmt->fetch());
  if (!$loc)
  {
    $loc = addLocation($value);
  }
  $stmt->close();
  $con->close();
  return $loc;
}

/*
 * Insert entry in DB for ridecreated time of $pid
*/
function rideAddTime($pid)
{
  global $prepare, $gtime;
  $con = connect();
  if(!($stmt = $con->prepare($prepare['rideaddtime'])))
  {
    $error = 'rideAddTime: Preparing the statement failed ' .  $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('ss', $gtime, $pid))
  {
    $error = 'rideAddTime: Binding the parameters failed ' .  $stmt->errno . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'rideAddTime: INSERT failed: ' . $stmt->errno . ' ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();
  $con->close();
}


//Add ride to DB
function rideAdd()
{
  global $prepare;

  $con = connect();
  $name = $con->real_escape_string($_POST["name"]);
  $cellnumber = $_POST["cell1"] . $_POST["cell2"] . $_POST["cell3"]; 
  $cellnumber = $con->real_escape_string($cellnumber);
  $riders = $con->real_escape_string($_POST["riders"]);
  if(isset($_POST["fromloc"]))
    $fromloc = $con->real_escape_string($_POST["fromloc"]);
  $pickup = $con->real_escape_string($_POST["pickup"]);
  if(isset($_POST["toloc"]))
    $toloc = $con->real_escape_string($_POST["toloc"]);
  $dropoff = $con->real_escape_string($_POST["dropoff"]);
  $clothes = $con->real_escape_string($_POST["clothes"]);
  $notes = $con->real_escape_string($_POST["notes"]);
  $waiting = 'waiting';

  if(isset($fromloc) && !strcmp($fromloc, 'Other'))
    $pickupid = getLocationID($pickup);
  else
    $pickupid = getLocationID($fromloc);
  if(isset($toloc) && !strcmp($toloc, 'Other'))
    $dropoffid = getLocationID($dropoff);
  else
    $dropoffid = getLocationID($toloc);


  if(!($stmt = $con->prepare($prepare['rideaddpatron'])))
  {
    $error = 'rideAdd: Preparing the statement failed ' .  $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('ssisssss', $name, 
			  $cellnumber,
			  $riders,
			  $pickupid,
			  $dropoffid, 
			  $clothes, 
			  $notes, 
			  $waiting))
  {
    $error = 'rideAdd: Binding the parameters failed ' .  $stmt->errno . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'rideAdd: INSERT failed: ' . $stmt->errno . ' ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();
  $pid = $con->insert_id;
  $con->close();
  rideAddTime($pid);
  return $pid;
}


/* Cancel the ride */
function rideCancel($num)
{
  global $gtime,$prepare;
  $con = connect();
  if(!($stmt = $con->prepare($prepare['changeridestatus'])))
  {
    $error = 'rideCancel: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  $status = 'cancelled';
  if(!$stmt->bind_param('si', $status, $num))
  {
    $error = 'rideCancel: Bind Failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'rideCancel: UPDATE failed: ' . $stmt->errno($stmt) . ' ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();

  if(!($stmt = $con->prepare($prepare['ridecanceltime'])))
  {
    $error = 'rideCancel1: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('si', $gtime, $num))
  {
    $error = 'rideCancel1: Bind Failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'rideCancel1: UPDATE failed: ' . $stmt->errno($stmt) . ' ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();
  $con->close();
}

/* Undo the last status change */
function rideUndo($num) {
  global $prepare;
  $con = connect();
  
  $num = $con->real_escape_string($num);

  if(!($stmt = $con->prepare($prepare['getride']))){
    loganddie('rideUndo: Prep Failed: ' . $con->error);
  }

  //this opens the original ride
  if(!$stmt->bind_param('i', $num)){
    loganddie('rideUndo: Bind Failed: ' . $stmt->error);
  }
  if(!$stmt->execute()){
    loganddie('rideUndo: Exec Failed: ' . $stmt->error);
  }
  $rows = array();
  $stmt->bind_result($row['pid'],
                     $row['name'],
		     $row['cell'],
		     $row['riders'],
		     $row['car'],
		     $row['pickup'],
		     $row['dropoff'],
		     $row['clothes'],
		     $row['notes'],
		     $row['status'],
		     $row['modified'],
		     $row['tid'],
		     $row['ridecreated'],
		     $row['rideassigned'],
		     $row['timepickedup'],
		     $row['timecomplete'],
		     $row['timecancelled'],
		     $row['tpid']);
  while($stmt->fetch()){
    if($row['car'] == 0){
      $stat='waiting';
    } else if(!strcmp($row['timepickedup'], '')){
      $stat = 'assigned';
    } else {
      $stat='riding';
    }
  }
  $stmt->close();

  if(!($stmt = $con->prepare($prepare['changeridestatus']))){
    loganddie('rideUndo1: Prep Failed: ' . $con->error);
  }
  if(!$stmt->bind_param('si', $stat, $num)){
    loganddie('rideUndo1: Bind Failed: ' . $stmti->error);
  }
  //$qry = "UPDATE rides SET status = '" . $stat . "' WHERE num='" . $num . "'";
  if(!$stmt->execute()){
    loganddie('rideUndo: INSERT failed: ' . $stmt->error);
  }

  $stmt->close();
  $con->close();
  header("location: ./" . $stat . ".php?num=" . $num);
}

/* Move ride from 'assigned' to 'Riding' */
function rideRiding($num)
{
  global $gtime,$prepare;
  $con = connect();
  if(!($stmt = $con->prepare( $prepare['changeridestatus'])))
  {
    $error = 'rideRiding: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  $status = 'riding';
  if(!$stmt->bind_param('si', $status, $num))
  {
    $error = 'rideRiding: Bind Failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  //$qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
  if(!$stmt->execute())
  {
    $error = 'rideRiding: UPDATE failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();

  if(!($stmt = $con->prepare( $prepare['rideridingtime'])))
  {
    $error = 'rideRiding1: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  $status = 'riding';
  if(!$stmt->bind_param('si', $gtime, $num))
  {
    $error = 'rideRiding1: Bind Failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  //$qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
  if(!$stmt->execute())
  {
    $error = 'rideRiding1: UPDATE failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();
  $con->close();
}


/* Move ride from 'riding' to 'done' */
function rideDone($num)
{
  global $gtime,$prepare;
  $con = connect();
  if(!($stmt = $con->prepare( $prepare['changeridestatus'])))
  {
    $error = 'rideDone: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  $status = 'done';
  if(!$stmt->bind_param('si', $status, $num))
  {
    $error = 'rideDone: Bind Failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  //$qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
  if(!$stmt->execute())
  {
    $error = 'rideDone: UPDATE failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();

  if(!($stmt = $con->prepare( $prepare['ridedonetime'])))
  {
    $error = 'rideDone1: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('si', $gtime, $num))
  {
    $error = 'rideDone1: Bind Failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  //$qry = "UPDATE rides SET status = 'done', timedone = '".date("YmdHis")."' WHERE num='" . $num . "'";
  if(!$stmt->execute())
  {
    $error = 'rideDone1: UPDATE failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  $stmt->close();
  $con->close();
}

//update when the car was last contacted
//Needs to reflect last update/modification from GTB
// so will need to work on this
function carUpdate() {
	global $gtime,$gdate,$prepare;
	$called = 'called';
	$con = connect();
	if(!$stmt->prepare($prepare['carupdate'])){
		die('Failed Prep: ' . mysqli_stmt_error($stmt));
	}
	$stmt->bind_param('isss', $_POST["carnum"], 
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
	if(!$stmt->execute()){
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
	if(!$stmt->prepare($prepare['carupdate'])){
		die('Failed Prep: ' . mysqli_stmt_error($stmt));
	}
	$stmt->bind_param('isss', $_POST["carnum"], 
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
	if(!$stmt->execute()){
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
		if(!$stmt->prepare( "SELECT timedone, pickup FROM rides WHERE ridedate=? AND car =? ORDER BY timedone")){
			die('Prep1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->bind_param('si', $gdate, $i)){
			die('Bind1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->execute()){
			die('Exec1 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->bind_result($row['timedone'], $row['pickup'])){
			die('Bind1 Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while($stmt->fetch()){
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
		if(!$stmt->prepare( "SELECT timeassigned FROM rides WHERE ridedate=? AND car =? ORDER BY timeassigned")){
			die('Prep2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->bind_param('si', $gdate, $i)){
			die('Bind2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->execute()){
			die('Exec2 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->bind_result($row['timeassigned'])){
			die('Bind2 Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while($stmt->fetch()){
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
		if(!$stmt->prepare( "SELECT contacttime,reason FROM contacted WHERE ridedate=? AND carnum =? ORDER BY contacttime asc")){
			die('Prep3 Failed: . ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->bind_param('si', $gdate, $i)){
			die('Bind3 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->execute()){
			die('Exec3 Failed: ' . mysqli_stmt_error($stmt));
		}
		if(!$stmt->bind_result($row['contacttime'],$row['reason'])){
			die('Bind3 Res Failed: ' . mysqli_stmt_error($stmt));
		}
		while($stmt->fetch()){
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

function Test(){
	global $auth;
	echo intval($auth) . "\n";
	return $auth;
}

function getTableValues($ridedate, $status)
{
  global $con, $prepare, $gdate;
  $con = connect();
  if($ridedate == "")
  {
    $ridedate = $gdate;
  }
  if(!($stmt = $con->prepare($prepare['ride'] . " ORDER BY ridetimes.ridecreated ASC")))
  {
    $error = 'getTableValues: Prep failed ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('ss', $gdate, $status))
  {
    $error = 'getTableValues: failed to bind variables ' . $stmt->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute())
  {
    $error = 'getTableValues: Exec failed: ' . $stmt->error;
    loganddie($error);
    return;
  }
  $row = array();
  $stmt->bind_result(
    $row['pid'], 
    $row['name'],
    $row['cell'],
    $row['riders'],
    $row['car'],
    $row['pickup'],
    $row['dropoff'],
    $row['clothes'],
    $row['notes'],
    $row['status'],
    $row['modified'],
    $row['tid'],
    $row['ridecreated'],
    $row['rideassigned'],
    $row['timepickedup'],
    $row['timecomplete'],
    $row['timecancelled'],
    $row['tpid']
  );
  $ret = array($stmt, $con, $row);
  return $ret;
 }

function getTableValuesWaiting($ridedate)
{
  $ret = getTableValues($ridedate, 'waiting');
  $stmt = $ret[0];
  $con = $ret[1];
  $row = $ret[2];
  while($stmt->fetch())
  {
    $rowclass = rowColor($j);
    if ($_GET['pid']==$row['pid'])
    {
      $rowclass = $rowclass . " notice";
    }

    $table .= '<tr class="' . $rowclass . '" id="row' . $row['pid'] . '">';
    $table .= '<div class="assign" id="assign' . $row['pid'] . 
      '"></div>';

    $table .= tblBtnAssign($row['pid']) . "\n";
    $table .= tblBtnSplit($row['pid']) . "\n";
    $table .= tblBtnEdit($row['pid'], 'waiting') . "\n";
    $table .= tblBtnCancel($row['pid']) . "\n";
    $table .= tblRideInfo($row['name']) . "\n";
    $table .= tblRideInfo($row['riders']) . "\n";
    $table .= tblLocationInfo($row['pickup']) . "\n";
    $table .= tblLocationInfo($row['dropoff']) . "\n";
    $table .= tblCell($row['cell'], $row['status']) . "\n";
    $table .= tblRideInfo($row['clothes']) . "\n";
    $table .= tblRideInfo($row['notes']) . "\n";
    $table .= tblCalledIn($row['ridecreated']) . "\n";
	
    $table .= '</tr>'."\n";
    $j++;
  }
  $stmt->close();
  $con->close();
  return $table;
}

function getTableValuesRiding($ridedate)
{
  $ret = getTableValues($ridedate, 'riding');
  $stmt = $ret[0];
  $con = $ret[1];
  $row = $ret[2];
  while($stmt->fetch())
  {
    $rowclass = rowColor($j);
    if ($_GET['pid']==$row['pid'])
    {
      $rowclass = $rowclass . " notice";
    }

    $table .= '<tr class="' . $rowclass . '" id="row' . $row['pid'] . '">';
    $table .= '<div class="' . $status . '" id="' . $status . $row['pid'] . 
      '"></div>';

    $table .= tblBtnDone($row['pid']) . "\n";
    $table .= tblBtnEdit($row['pid'], 'riding') . "\n";
    $table .= tblBtnCancel($row['pid']) . "\n";
    $table .= tblRideInfo($row['car']);
    $table .= tblRideInfo($row['name']) . "\n";
    $table .= tblRideInfo($row['riders']) . "\n";
    $table .= tblLocationInfo($row['pickup']) . "\n";
    $table .= tblLocationInfo($row['dropoff']) . "\n";
    $table .= tblCell($row['cell'], $row['status']) . "\n";
    $table .= tblRideInfo($row['clothes']) . "\n";
    $table .= tblRideInfo($row['notes']) . "\n";
    $table .= tblCalledIn($row['ridecreated']) . "\n";
	
    $table .= '</tr>'."\n";
    $j++;
  }
  $stmt->close();
  $con->close();
  return $table;
}

function getTableValuesAssigned($ridedate)
{
  $ret = getTableValues($ridedate, 'assigned');
  $stmt = $ret[0];
  $con = $ret[1];
  $row = $ret[2];
  while($stmt->fetch())
  {
    $rowclass = rowColor($j);
    if ($_GET['pid']==$row['pid'])
    {
      $rowclass = $rowclass . " notice";
    }

    $table .= '<tr class="' . $rowclass . '" id="row' . $row['pid'] . '">';
    $table .= '<div class="' . $status . '" id="' . $status . $row['pid'] . 
      '"></div>';

    $table .= tblBtnRiding($row['pid']) . "\n";
    $table .= tblBtnDone($row['pid']) . "\n";
    $table .= tblBtnEdit($row['pid'], 'assigned') . "\n";
    $table .= tblBtnCancel($row['pid']) . "\n";
    $table .= tblRideInfo($row['car']);
    $table .= tblRideInfo($row['name']) . "\n";
    $table .= tblRideInfo($row['riders']) . "\n";
    $table .= tblLocationInfo($row['pickup']) . "\n";
    $table .= tblLocationInfo($row['dropoff']) . "\n";
    $table .= tblCell($row['cell'], $row['status']) . "\n";
    $table .= tblRideInfo($row['clothes']) . "\n";
    $table .= tblRideInfo($row['notes']) . "\n";
    $table .= tblCalledIn($row['ridecreated']) . "\n";
	
    $table .= '</tr>'."\n";
    $j++;
  }
  $stmt->close();
  $con->close();
  return $table;
}

function getTableValuesDone($ridedate)
{
  $ret = getTableValues($ridedate, 'done');
  $stmt = $ret[0];
  $con = $ret[1];
  $row = $ret[2];
  while($stmt->fetch())
  {
    $rowclass = rowColor($j);
    if ($_GET['pid']==$row['pid'])
    {
      $rowclass = $rowclass . " notice";
    }

    $table .= '<tr class="' . $rowclass . '" id="row' . $row['pid'] . '">';
    $table .= '<div class="' . $status . '" id="' . $status . $row['pid'] . 
      '"></div>';

    $table .= tblBtnEdit($row['pid'], 'done') . "\n";
    $table .= tblBtnUndo($row['pid']) . "\n";
    $table .= tblRideInfo($row['status']) . "\n";
    $table .= tblDoneCar($row['car']) . "\n";
    $table .= tblRideInfo($row['name']) . "\n";
    $table .= tblRideInfo($row['riders']) . "\n";
    $table .= tblLocationInfo($row['pickup']) . "\n";
    $table .= tblLocationInfo($row['dropoff']) . "\n";
    $table .= tblCell($row['cell'], $row['status']) . "\n";
    $table .= tblTimeWait($row['ridecreated'], $row['rideassigned'],
                          $row['timecomplete'], $row['status']) . "\n";
    $table .= tblTimeRode($row['rideassigned'], $row['timecomplete'],
                          $row['status']) . "\n";
    $table .= tblHome($row['timecomplete'], $row['status']) . "\n";
	
    $table .= '</tr>'."\n";
    $j++;
  }
  $stmt->close();
  $con->close();
  return $table;
}

function getTableValuesCancelled($ridedate)
{
  $ret = getTableValues($ridedate, 'cancelled');
  $stmt = $ret[0];
  $con = $ret[1];
  $row = $ret[2];
  while($stmt->fetch())
  {
    $rowclass = rowColor($j);
    if ($_GET['pid']==$row['pid'])
    {
      $rowclass = $rowclass . " notice";
    }

    $table .= '<tr class="' . $rowclass . '" id="row' . $row['pid'] . '">';
    $table .= '<div class="' . $status . '" id="' . $status . $row['pid'] . 
      '"></div>';

    $table .= tblBtnEdit($row['pid'], 'done') . "\n";
    $table .= tblBtnUndo($row['pid']) . "\n";
    $table .= tblRideInfo($row['status']) . "\n";
    $table .= tblDoneCar($row['car']) . "\n";
    $table .= tblRideInfo($row['name']) . "\n";
    $table .= tblRideInfo($row['riders']) . "\n";
    $table .= tblLocationInfo($row['pickup']) . "\n";
    $table .= tblLocationInfo($row['dropoff']) . "\n";
    $table .= tblCell($row['cell'], $row['status']) . "\n";
    $table .= tblTimeWait($row['ridecreated'], $row['rideassigned'],
                          $row['timecancelled'], $row['status']) . "\n";
    $table .= tblTimeRode($row['timepickedup'], $row['timecomplete'],
                          $row['status']) . "\n";
    $table .= tblHome($row['timecomplete'], $row['status']) . "\n";
	
    $table .= '</tr>'."\n";
    $j++;
  }
  $stmt->close();
  $con->close();
  return $table;
}

function isAuthenticated(){
  if(isset($_SESSION))
  {
    $result  = false;
    /* Remember: A user is logged in iff the entry for the user has loggedin=1
     * and TIMESTAMPDIFF(HOUR, NOW(), logintime) < 12
     */

    $query = 'SELECT COUNT(*) FROM activeusers WHERE username=? AND ticket=?' . 
             ' AND loggedin=1 AND TIMESTAMPDIFF(HOUR, NOW(), logintime) < 12';
    $con = connect();
    if(!($stmt = $con->prepare($query)))
    {
      $error = 'login-auth: Prep Failed: ' . $con->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->bind_param('ss', $_SESSION['username'], $_SESSION['ticket']))
    {
       $error = 'login-auth: Bind Param Failed: ' . $stmt->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->execute())
    {
      $error = 'login-auth: Exec Failed: ' . $stmt->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->bind_result($retcount))
    {
      $error = 'login-auth: Bind Result Failed: ' . $stmt->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->num_rows > 0)
    {
      /* Store logindate */
      while($stmt->fetch());
      if($retcount == 1)
        $result = true;
    }
    else
      $result = false;
    
    $stmt->close();
    $con->close();
  }
  else
    $result = false;

  return $result;
}

function makeUserLoggedIn($sessioninfo){
  if(isset($sessioninfo['username']) &&
     isset($sessioninfo['AUTH_RET']) &&
     isset($sessioninfo['ticket']))
  {
    $con = connect();
    $query  = 'INSERT into activeusers' .
              '(username, ticket, logintime, loggedin) VALUES' .
	      '(?, ?, NOW(), 1)';

    if(!($stmt = $con->prepare($query)))
    {
      $error = 'makeUserLoggedIn: Preparing the statement failed ' .  
          $con->errno . ' ' . $con->error;
      loganddie($error);
      return false;
    }
    if(!$stmt->bind_param('ss',
                          $sessioninfo['username'],
			  $sessioninfo['ticket']))
    {
      $error = 'makeUserLoggedIn: Binding the parameters failed ' .  
          $stmt->errno . " " . $stmt->error;
      loganddie($error);
      return false;
    }
    if(!$stmt->execute())
    {
      $error = 'makeUserLoggedIn: Can not find location: ' . $stmt->errno() . ' ' . $stmt->error;
      loganddie($error);
      return false;
    }
    $stmt->close();
    $con->close();
    return true;
  } else
    return false;
}
?>
