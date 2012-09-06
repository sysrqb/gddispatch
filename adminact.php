<?php
require_once('login-auth.php');
require_once('adminfunctions.php');

if(isset($_GET) && isset($_GET['action']))
{
  switch ($_GET['action']){
    case "carinfoupdate":
      if(isset($_GET['week']))
        echo getListOfSetCars($_GET['week']);
      break;
    case "carinfoadd":
      if(isset($_GET['week']))
        echo getListOfNotSetCars($_GET['week']);
      break;
    case "numcars":
      if(isset($_POST['week'])){
        setCars($_POST['week'], $_POST['numcars']);
        $_SESSION['data']['week'] = $_POST['week'];
      }
      else{
        $error = 'Please insert the date of the' .
          'Thursday that began/begins the weekend you wish to view/edit.';
      }
      header('Status: 307 Temporary Redirect');
      header('Location: admin.php');
      session_start();
      if(isset($error))
        $_SESSION['data']['carnumerror'] = 'Error: ' . $error;
      $_SESSION['data']['week'] = $_POST['week'];
      break;
    case 'carinfofields':
      if(isset($_GET['week']) && isset($_GET['carnum']))
        echo getCarValuesAsJSON($_GET['week'], $_GET['carnum']);
      break;
    case "dbinit":
      init();
      break;
    case "carinfo":
      if(isset($_POST['carinfoop']) && isset($_POST['week']) && 
         isset($_POST['make']) && isset($_POST['model']) &&
         isset($_POST['color']) && isset($_POST['year']) &&
	 isset($_POST['lp']) && isset($_POST['state']) &&
	 isset($_POST['carnum']))
      {
        if(!strcmp($_POST['carinfoop'], 'add'))
          setCarInfo($_POST['make'], $_POST['model'], $_POST['color'],
                     $_POST['year'], $_POST['lp'], $_POST['state'],
                     $_POST['week'], $_POST['carnum']);
        elseif(!strcmp($_POST['carinfoop'], 'update')){
          if(isset($_POST['cid']))
            updateCarInfo($_POST['make'], $_POST['model'], $_POST['color'],
                       $_POST['year'], $_POST['lp'], $_POST['state'],
                       $_POST['week'], $_POST['cid']);
          else
	    echo 'Are you pentesting?' . "\n";
	}
        else{
	  $error = 'Are you pentesting?' . "\n";
	}
      }
      else{
	$error = 'Please make sure all fields are filled' . "\n";
      }
      header('Status: 307 Temporary Redirect');
      header('Location: admin.php');
      session_start();
      if(isset($error))
        $_SESSION['data']['carinfoerror'] = 'Error: ' . $error;
      break;
    case 'addusers':
      if(isset($_POST) && isset($_POST['usernames'])){
        $filename = 'useraccess';
        if(($fd = fopen($filename, 'w')))
          fwrite($fd, $_POST['usernames']);
	else
	  $error = 'Failed to open/write to file!';
      } else
        $error = 'Field was not sent?';
      session_start();
      echo $error;
      if(isset($error))
        $_SESSION['data']['grantaccesserror'] = $error;
      header('Status: 307 Temporary Redirect');
      header('Location: grantaccess.php');
      break;
    default:
      break;
  }
}

if(isset($_POST) && isset($_POST['action']))
{
  switch ($_POST['action']){
	default:
		break;
  }
}
