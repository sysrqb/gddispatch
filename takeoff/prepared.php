<?php
require("connection.php");
	$ridecount = $con->prepare("SELECT * FROM rides WHERE DATE(ridedate) = ? AND status =?");
	$

		
