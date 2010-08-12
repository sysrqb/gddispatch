<?php
//error_reporting(E_ERROR);
ini_set('display_errors', '1');

include("classes.php");
    
switch($_GET["action"]) {
	case "cancel":
		rideCancel($_GET["num"]);
    	header("location: ./done.php?num=".$_GET["num"]);
    	break;
    case "done":
    	rideDone($_GET["num"]);
    	header("location: ./done.php?num=".$_GET["num"]);
    	break;
    case "undo":
    	rideUndo($_GET["num"]);
    	break;
    case "assign":
    	rideAssign($_POST["num"],$_POST["car"]);
    	header("location: ./riding.php?num=".$_POST["num"]);
    	break;
    case "preassign":
    	prerideAssign($_POST["num"],$_POST["car"]);
    	header("location: ./waiting.php?num=".$_POST["num"]);
    	break;
    case "split":
    	rideSplit($_POST["num"],$_POST["car"],$_POST["riders"]);
    	header("location: ./riding.php?num=".$_POST["num"]);
    	break;
    case "edit":
    	rideEdit($_POST["num"]);
    	header("location: ./".$_POST["pgId"].".php?num=".$_POST["num"]);
    	break;
    case "addnewride":
    	rideAdd();
    	header("location: ./waiting.php?num=".mysql_insert_id());
    	break;
    case "circuit":
    	rideCircuit();
    	header("location: ./done.php?num=".mysql_insert_id());
    	break;
    case "contact":
    	carUpdate();
    	header("location: ./cars.php");
    	break;
    case "home":
    	carHome();
    	header("location: ./cars.php");
    	break;
    default:
    	header("location: ./waiting.php");
    	break;
}
    	
    


  
?>
