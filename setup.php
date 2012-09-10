<?php


require_once('credfile.php');
require_once('config.php');
require_once('global.php');
require_once('connection.php');


/**************************************
 * SetUp: Creates Database and Tables *
 **************************************/

function setUp()
{
  global $db, $username;
  $con = initconnect();
  if(!$con->query("CREATE DATABASE $db"))
  {
    die("FAILED TO CREATE DATABASE\n");
  }

  $query = "CREATE TABLE IF NOT EXISTS $db.patron ";
  $query .= '(pid INTEGER NOT NULL AUTO_INCREMENT KEY, name VARCHAR(30), ';
  $query .= 'cell VARCHAR(15), riders TINYINT NOT NULL DEFAULT 0, car TINYINT, ';
  $query .= 'pickup INTEGER, dropoff VARCHAR(20), clothes VARCHAR(50), ';
  $query .= 'notes VARCHAR(200), status VARCHAR(10), modified SMALLINT)';
  if(!$con->query($query))
    die("FAILED TO CREATE $db TABLE\n");

  $query = "CREATE TABLE IF NOT EXISTS $db.ridetimes "; 
  $query .= '(tid INTEGER NOT NULL AUTO_INCREMENT KEY, ridecreated DATETIME, ';
  $query .= 'rideassigned DATETIME, timepickedup DATETIME, ';
  $query .= 'timecomplete DATETIME, timecancelled DATETIME, pid INTEGER)';
  if(!$con->query($query))
    die("FAILED TO CREATE ridetimes TABLE\n");
    
  $query = "CREATE TABLE IF NOT EXISTS $db.activeusers "; 
  $query .= '(did INTEGER NOT NULL AUTO_INCREMENT KEY, username VARCHAR(8), ';
  $query .= 'ticket VARCHAR(50), logintime DATETIME, ';
  $query .= 'loggedin TINYINT)';
  if(!$con->query($query))
    die("FAILED TO CREATE activeusers TABLE\n");
    
  $query = "CREATE TABLE IF NOT EXISTS $db.locations ";
  $query .= '(lid INTEGER NOT NULL AUTO_INCREMENT KEY, name VARCHAR(30), ';
  $query .= 'value VARCHAR(8), code VARCHAR(5), longitude FLOAT NOT NULL DEFAULT 0, ';
  $query .= 'latitude FLOAT NOT NULL DEFAULT 0)';
  if(!$con->query($query))
    die("FAILED TO CREATE locations TABLE\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Alumni\', \'' . substr(hash('sha256', 'Alumni'), 0, 8) . '\', \'Al\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Alumni: ($con->connect_errno) $con->connect_error\n$query\n");
    
  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Buckley\', \'' . substr(hash('sha256', 'Buckley'), 0, 8) . '\', \'B\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Buckley: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Busby Suites\', \'' . substr(hash('sha256', 'Busby Suites'), 0, 8) . '\', \'BS\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Busby Suites: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Celeron\', \'' . substr(hash('sha256', 'Celeron'), 0, 8) . '\', \'Ce\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Celeron: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Carriage\', \'' . substr(hash('sha256', 'Carriage'), 0, 8) . '\', \'Car\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Carriage: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Charter Oak\', \'' . substr(hash('sha256', 'Charter Oak'), 0, 8) . '\', \'CO\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Charter Oak: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'East\', \'' . substr(hash('sha256', 'East'), 0, 8) . '\', \'E\')';
  if(!$con->query($query))
    die("FAILED TO INSERT East: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Garrigus Suites\', \'' . substr(hash('sha256', 'Garrigus Suites'), 0, 8) . '\', \'GS\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Garrigus Suites: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Graduate Housing\', \'' . substr(hash('sha256', 'Graduate Housing'), 0, 8) . '\', \'Gr\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Graduate Housing: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Hilltop Apartments\', \'' . substr(hash('sha256', 'Hilltop Apartments'), 0, 8) . '\', \'HTA\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Hilltop Apartments: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Hilltop Dorms\', \'' . substr(hash('sha256', 'Hilltop Dorms'), 0, 8) . '\', \'HT\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Hilltop Dorms: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Hunting Lodge\', \'' . substr(hash('sha256', 'Hunting Lodge'), 0, 8) . '\', \'HL\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Hunting Lodge: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Husky Village\', \'' . substr(hash('sha256', 'Husky Village'), 0, 8) . '\', \'HV\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Husky Village: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Mansfield Apartments\', \'' . substr(hash('sha256', 'Mansfield Apartments'), 0, 8) . '\', \'MA\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Mansfield Apartments: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'McMahon\', \'' . substr(hash('sha256', 'McMahon'), 0, 8) . '\', \'MM\')';
  if(!$con->query($query))
    die("FAILED TO INSERT McMahon: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'North\', \'' . substr(hash('sha256', 'North'), 0, 8) . '\', \'N\')';
  if(!$con->query($query))
    die("FAILED TO INSERT North: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'NorthWest\', \'' . substr(hash('sha256', 'NorthWest'), 0, 8) . '\', \'NW\')';
  if(!$con->query($query))
    die("FAILED TO INSERT NorthWest: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Shippee\', \'' . substr(hash('sha256', 'Shippee'), 0, 8) . '\', \'Sh\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Shippee: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'South\', \'' . substr(hash('sha256', 'South'), 0, 8) . '\',\'So\')';
  if(!$con->query($query))
    die("FAILED TO INSERT South: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Towers\', \'' . substr(hash('sha256', 'Towers'), 0, 8) . '\', \'T\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Towers: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'West\', \'' . substr(hash('sha256', 'West'), 0, 8) . '\', \'W\')';
  if(!$con->query($query))
    die("FAILED TO INSERT West: ($con->connect_errno) $con->connect_error\n");

  $query = "INSERT INTO $db.locations (name, value, code) ";
  $query .= 'VALUES (\'Other\', \'' . substr(hash('sha256', 'Other'), 0, 8) . '\', \'Other\')';
  if(!$con->query($query))
    die("FAILED TO INSERT West: ($con->connect_errno) $con->connect_error\n");

  if(!$con->query("GRANT ALL PRIVILEGES ON $db.* TO '$username'@'%' WITH GRANT OPTION"))
  {
    die("FAILED TO GRANT PRIVILEGES TO $username\n");
  }

  echo "Successfully Created Database and Tables!\n";
  $con->close();
}
