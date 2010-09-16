<?php
  // This include the functions, classes, and db connection 
  include("classes.php");
//  include('functions.php');

  $pgId = "done";
  include("layout_top.php");

  ?>

<table class="program">
	<tr>
		<th>Edit</th>
		<th>Undo</th>
		<th>Status</th>
		<th>Car</th>
		<th>Name</th>
		<th>Riders</th>
		<th>Pickup</th>
		<th>Dropoff</th>
		<th>Cell</th>
		<th style="width:100px;">Waited</th>
		<th style="width:100px;">Rode</th>
		<th>Home At</th>
	</tr>
	<?php
//	$filterCar = "";
//	if ($_GET['car']<>"") {$filterCar = ' AND car='.$_GET['car'].' ';}
	
//	$sql = "SELECT * FROM rides WHERE ridedate = ".$dateofride." AND (status = 'done' OR status = 'cpmissed' OR status = 'cancelled') ".$filterCar." ORDER BY status,car,timedone ASC";
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Failed Init: ' . mysqli_stmt_error($stmt));
	}
	$done = 'done';
	$noshow = 'noshow';
	$cancelled = 'cancelled';
	if(!mysqli_stmt_prepare($stmt, "SELECT * FROM rides WHERE ridedate=? and (status=? or status=? or status=?)  ORDER BY car ASC")){
		die('Failed Prepare: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_bind_param($stmt, 'ssss', gmdate('Y-m-d'), $done, $noshow, $cancelled)){
		die('Failed Bind: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_execute($stmt)){
		die('Exec Failed: ' . mysqli_stmt_error($stmt));
	}
	$row = array();
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
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
	}
	$j=0;
	while(mysqli_stmt_fetch($stmt)) {
		$rowclass = rowColor($j);
		if ($_GET['num']==$row['num']) {$rowclass = $rowclass." notice";}
	
		echo '<tr class="'.$rowclass.'" id="row'.$row['num'].'">';
		echo '<div class="assign" id="assign'.$row['num'].'"></div>';
		tblBtnEdit($row['num'],$pgId);
		tblBtnUndo($row['num']);
		tblRideInfo($row['status']);
		tblDoneCar($row['car']);
		tblRideInfo($row['name']);
		tblRideInfo($row['riders']);
		tblRideInfo($row['pickup']);
		tblRideInfo($row['dropoff']);
		tblCell($row['cell'],$row['pickup']);
		tblTimeWait($row['timetaken'],$row['timeassigned'],$row['timedone'],$row['status'],$row['pickup']);
		tblTimeRode($row['timedone'],$row['timeassigned'],$row['status'],$row['pickup']);
		tblHome($row['timedone'],$row['status']);
		$j++;
		echo '</tr>'."\r";
	}

	?>
</table>

<?php include("layout_bottom.php"); ?>
