<?php
include("classes.php");
    
switch($_GET["action"]){ //retrieves case from respective page
	case "cancel": //if case button was clicked on riding or waiting page
	rideCancel($_GET["num"]); //call the rideCancel function in function.php
    	header("location: ./done.php?num=".$_GET["num"]);//redirect page to done page
    	break;
    case "done": //if done was clicked on riding page
    	rideDone($_GET["num"]); //call rideDone in function.php file
    	header("location: ./done.php?num=".$_GET["num"]); //redirect page to done page
    	break;
    case "undo": //if undo was clicked on done page
    	rideUndo($_GET["num"]); //call rideUndo function in function.php
    	break;
    case "assign": //if assign was clicked on waiting page
    	rideAssign($_POST["num"],$_POST["car"]); //call rideAssign in function.php
    	header("location: ./riding.php?num=".$_POST["num"]); //redirect to riding page
    	break;
    case "preassign": //if reassign was clicked on waiting page
    	prerideAssign($_POST["num"],$_POST["precar"]); //call prerideAssign function in function.php
    	header("location: ./waiting.php?num=".$_POST["num"]); //remain on waiting page
    	break;
    case "split": //if split was called on waiting page
    	rideSplit($_POST["num"],$_POST["car"],$_POST["riders"]);//call rideSplit function in function.php
    	header("location: ./riding.php?num=".$_POST["num"]); //redirect to riding page for the first part of the split ride
    	break;
    case "edit": //if edit was clicked on waiting or riding page
    	rideEdit($_POST["num"]); //call rideEdit function in function.php
    	header("location: ./".$_POST["pgId"].".php?num=".$_POST["num"]); //redirect back to the previous page
    	break;
    case "addnewride"://if a new ride was added from the incoming page
    	rideAdd(); //calls the rideAdd function
    	header("location: ./waiting.php?num=".mysql_insert_id());//redirects to the waiting page
    	break;
    case "contact"://if car was contacted on the cars page
    	carUpdate();//calls carUpdate function in function.php
    	header("location: ./cars.php");//redirects to cars page
    	break;
    case "home"://if car was returned home on cars page
    	carHome();//calls carHome in function.php
    	header("location: ./cars.php");//redirects back to cars page
    	break;
    default://default case if none of the others are correct
    	header("location: ./waiting.php");//redirects to waiting page
    	break;
}

?>
