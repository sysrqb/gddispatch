<?php
  // This include the functions, classes, and db connection 
  include("classes.php");

  $pgId = "riding";
  include("layout_top.php");
  ?>

<table class="program">
	<tr>
		<th>Done</th>
		<th>Edit</th>
		<th>Cancel</th>
		<th>Car</th>
		<th>Name</th>
		<th>Riders</th>
		<th>Pickup</th>
		<th>Dropoff</th>
		<th>Cell</th>
		<th>Clothes</th>
		<th>Notes</th>
		<th>Time</th>
	</tr>
	<?php
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Failed Init: ' . mysqli_stmt_error($stmt));
	}
	$riding = 'riding';
	if(!mysqli_stmt_prepare($stmt, "SELECT * FROM rides WHERE ridedate=? and status=? ORDER BY car ASC")){
		die('Failed Prepare: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_bind_param($stmt, 'ss', gmdate('Y-m-d'), $riding)){
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
		tblBtnDone($row['num']);
		tblBtnEdit($row['num'],$pgId);
		tblBtnCancel($row['num']);
		tblRideInfo($row['car']);
		tblRideInfo($row['name']);
		tblRideInfo($row['riders']);
		tblRideInfo($row['pickup']);
		tblRideInfo($row['dropoff']);
		tblCell($row['cell'],$row['status']);
		tblRideInfo($row['clothes']);
		tblRideInfo($row['notes']);
		tblCalledIn($row['timeassigned']);
		
		$j++;
		echo '</tr>'."\r";
	}
	mysqli_close($con);
	?>
</table>

<?php include("layout_bottom.php"); ?>
