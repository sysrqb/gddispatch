<?php
require("connection.php");
	$ridecount = mysqli_stmt_prepare($con, "SELECT * FROM rides WHERE DATE(ridedate) = ? AND status =?");
	$totalcount = mysqli_stmt_prepare($con, "SELECT SUM(riders) as total FROM rides WHERE ridedate = ? AND status = ?");
?>	
