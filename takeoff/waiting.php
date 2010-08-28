<?php
  // This include the functions, classes, and db connection 
  include("classes.php");
  //include("functions.php");

  $pgId = "waiting";
  include("layout_top.php");
  ?>

<table class="program">
	<tr>
		<th>Pre-Assign</th>
		<th>Assign</th>
		<th>Split</th>
		<th>Edit</th>
		<th>Cancel</th>
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
	$stmt = mysqli_stmt_init($con);
	if(!mysqli_stmt_prepare($stmt, "SELECT * FROM rides WHERE ridedate =?  AND status =?  ORDER BY timetaken ASC")){
		die('Prep failed ' . mysqli_stmt_error($stmt));
	}
	$waiting = 'waiting';
	if(!mysqli_stmt_bind_param($stmt, 'ss',gmdate('Y-m-d'), $waiting)){
		die('failed to bind variables ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_execute($stmt)){
		die('Exec failed: ' . mysqli_stmt_error($stmt));
	}
	$row = array();
	mysqli_stmt_bind_result($stmt,$row['num'], $row['name'],$row['cell'],$row['requested'], $row['riders'],$row['precar'],$row['car'],$row['pickup'],$row['dropoff'],$row['notes'],$row['clothes'],$row['ridedate'],$row['status'],$row['timetaken'],$row['timeassigned'],$row['timedone'],$row['loc']);
	while(mysqli_stmt_fetch($stmt)){
		
	

	//$sql = "SELECT * FROM rides WHERE ridedate = ".$dateofride." AND status = 'waiting' ORDER BY timetaken ASC";
	//$qry = mysql_query($sql);
	$j=0;
	//while($row = mysql_fetch_array($qry)) {
	
		$rowclass = rowColor($j);
		if ($_GET['num']==$row['num']) {$rowclass = $rowclass." notice";}
	
		echo '<tr class="'.$rowclass.'" id="row'.$row['num'].'">';
		echo '<div class="assign" id="assign'.$row['num'].'"></div>';

		tblBtnPreAssign($row['num']);	
		tblBtnAssign($row['num']);
		tblBtnSplit($row['num']);
		tblBtnEdit($row['num'],$pgId);
		tblBtnCancel($row['num']);
		tblRideInfo($row['name']);
		tblRideInfo($row['riders']);
		tblRideInfo($row['pickup']);
		tblRideInfo($row['dropoff']);
		tblCell($row['cell'],$row['status']);
		tblRideInfo($row['clothes']);
		tblRideInfo($row['notes']);
		tblCalledIn($row['timetaken']);
		
		$j++;
		echo '</tr>'."\r";
	}
	?>
</table>

<?php include("layout_bottom.php"); ?>
