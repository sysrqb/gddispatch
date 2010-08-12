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
	$sql = "SELECT * FROM rides WHERE ridedate = ".$dateofride." AND status = 'riding' ORDER BY car ASC";
	$qry = mysql_query($sql);
	$j=0;
	while($row = mysql_fetch_array($qry)) {
	
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
	?>
</table>

<?php include("layout_bottom.php"); ?>