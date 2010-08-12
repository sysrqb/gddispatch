<?php
  // This include the functions, classes, and db connection 
  include("classes.php");

  $pgId = "done";
  include("layout_top.php");


if ( isset($_GET['oday']) ) {
	$dateofride = date("Ymd", mktime(date("H")-12, date("i"), date("s"), $_GET['omonth'], $_GET['oday'],$_GET['oyear']))."000000";
}


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
		<th style="width:60px;">Waited</th>
		<th style="width:60px;">Rode</th>
		<th>Home At</th>
	</tr>
	<?php
	$filterCar = "";
	if ($_GET['car']<>"") {$filterCar = ' AND car='.$_GET['car'].' ';}
	
	$sql = "SELECT * FROM rides WHERE ridedate = ".$dateofride." AND (status = 'done' OR status = 'cpmissed' OR status = 'cancelled') ".$filterCar." ORDER BY status,car,timedone ASC";
	$qry = mysql_query($sql);
	$j=0;
	while($row = mysql_fetch_array($qry)) {
	
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