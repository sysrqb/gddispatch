<?php
require('adminfunctions.php');

switch ($_POST['action']){
	case numcars:
		if(isset($_POST['week'])){
			getcars($_POST['week']);
		}
		else{
			echo "Please Go Back and insert the date of the Thursday that began/begins the weekend you wish to view/edit.";
		}
		break;
	case init:
		init();
		break;
	case info:
		info();
		break;
	default:
		break;
}
