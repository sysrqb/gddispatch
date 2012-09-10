<?php
  // This include the functions, classes, and db connection 
  include("classes.php");

  $pgId = "assigned";
  include("layout_top.php");
?>

    <table class="program">
      <tr>
        <th>Riding</th>
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
      <?php echo getTableValuesAssigned('');?>
    </table>

<?php include("layout_bottom.php"); ?>
