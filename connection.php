<?php

function connect(){
  global $host, $username, $password, $db, $port, $prepare, $log, $logfile;

  $con = new mysqli($host,$username,$password, $db, $port);
  if($con->connect_error)
  {
    $error = 'Connection Error (' . $con->connect_errno . ') ' . $con->connect_error;
    $error .= "\n" . 'host: ' . $host;
    $error .= "\n" . 'username: ' . $username; 
    loganddie($error);
  }
  return $con;
}
   
function initconnect(){
  global $host, $username, $password, $db, $port, $prepare, $log, $logfile;

  $con = new mysqli($host,$username,$password, '', $port);
  if($con->connect_error)
  {
    $error = 'Connection Error (' . $con->connect_errno . ') ' . $con->connect_error;
    $error .= "\n" . 'host: ' . $host;
    $error .= "\n" . 'username: ' . $username; 
    loganddie($error);
  }
  return $con;
}

function adminLogin($username, $password){
	global $ldapserver,$localadmin;
	global $auth;
 
	echo 'adminLogin' . "\n";
        $filename = 'useraccess';
	if(file_exists($filename)){
	  if(filesize($filename) > 0){
            $fd = fopen($filename, 'r');
	    $usernames = fread($fd, filesize($filenames));
	    if(strstr($usernames, $username) != false && !strmp($username, $_SESSION['username']))
	         echo 'gibberish';
		 config(TRUE);
		 return;
          }
	}
	elseif($username=='admin' && $password=='1234' && $localadmin==TRUE){
		 config(TRUE);
	}
	/*else{
		$conserver = ldap_connect($ldapserver['host']); //$ldapserver is defined in creds.php
		$ldapbind = ldap_bind($conserveri,$username,$password)
			or die('Could not establish connection with server: ' . $ldapserver['name'] . "\n");
		echo "Bind Successful \n";
		$auth=$TRUE;
		header("location: ./config.php");
		return 'location ./index.php';
	}*/
	else{
		return 'location: ./incoming.php';
	}
}


?>
