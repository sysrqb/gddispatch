<?php
error_reporting(E_ERROR);
ini_set('display_errors', '1');

include("functions.php"); 



// database connection
$con = mysql_connect('localhost', 'gdphoneroom', 'sAuO=7[u9mAF?');
if (!$con) {
  die('Could not connect: ' . mysql_error());}
$db = 'saferideprogram';
mysql_select_db($db,$con);

/****************************
Time Making Functions
****************************/



/****************************
Table Making Functions
****************************/



/****************************
Ride Class
****************************/

class Ride {

    var $num;
    var $name;
    var $cell;
    var $pickup;
    var $dropoff;
    var $location;
    var $clothes;
    var $precar;
    var $car;
    var $notes;
    var $riders;
    var $status;
    var $ridedate;
    var $timetaken;
    var $timeassigned;
    var $timedone;    

    // this builds the object
    function __construct( $attribs ) {

        $this->num = $attribs['num'];
        $this->name = $attribs['name'];
        $this->cell = $attribs['cell'];
        $this->pickup = $attribs['pickup'];
        $this->dropoff = $attribs['dropoff'];
        $this->location = $attribs['location'];
        $this->clothes = $attribs['clothes'];
	$this->precar = $attribs['precar'];
        $this->car = $attribs['car'];
        $this->notes = $attribs['notes'];
        $this->riders = $attribs['riders'];
        $this->status = $attribs['status'];
        $this->ridedate = $attribs['ridedate'];
        $this->timetaken = $attribs['timetaken'];
        $this->timeassigned = $attribs['timeassigned'];
        $this->timedone = $attribs['timedone'];
    }
    
    // this is used to retrieve an attribute from the object (ride)
    function getAtt($theAtt) {
    	return $this->$theAtt;
    }
    
}

?>
