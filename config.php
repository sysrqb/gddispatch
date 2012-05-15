<?php 
function config($access){
	if($access != TRUE){
		header('location: ./cars.php');
	}
	else{
		echo "<html><title>Administrative Page</title><body style='background-color:#FF0000'>How Many Cars?<br>Add Car Information?</body></html>";
	}
}
?>
