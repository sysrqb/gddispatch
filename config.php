<?php 
date_default_timezone_set('America/New_York');


function config($access){
	if($access != TRUE){
		header('location: ./cars.php');
	}
	else{
	  session_start();
	  $_SESSION['authenticated_admin'] = true;
	  header('Location: ./admin.php');
	}
}
?>
