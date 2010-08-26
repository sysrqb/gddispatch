<?php

$con = new mysqli($host,$username,$password);
if($con->connect_error){
        die('Connection Error (' . $con->connect_errno . ') ' . $con->connect_error);
}
$con->select_db($db);

?>
