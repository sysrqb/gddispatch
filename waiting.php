<?php
  // This include the functions, classes, and db connection 
  include("classes.php");
  //include("functions.php");

  $pgId = "waiting";
  include("layout_top.php");
?>
      <table class="program">
	<tr>
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
	<?php echo getTableValuesWaiting('');?>
    </table>
<?php include("layout_bottom.php"); ?>
