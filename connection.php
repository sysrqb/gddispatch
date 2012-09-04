<?php

function connect(){
  global $host, $username, $password, $db, $prepare, $log, $logfile;

  $con = new mysqli($host,$username,$password);
  if($con->connect_error)
  {
    $error = 'Connection Error (' . $con->connect_errno . ') ' . $con->connect_error;
    $error .= "\n" . 'host: ' . $host;
    $error .= "\n" . 'username: ' . $username; 
    loganddie($error);
  }
  $con->select_db($db);
  return $con;
}
   

function loginLdap($username, $password){
	global $ldapserver,$localadmin;
	global $auth;
	if($username=='admin' && $password=='1234' && $localadmin==TRUE){
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
		return 'location: ./index.php';
	}
}


?>
