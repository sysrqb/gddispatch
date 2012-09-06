<?php

/*
require('credfile.php');
require('config.php');
require('global.php');
require('connection.php');
*/

/**************************************
 * SetUp: Creates Database and Tables *
 **************************************/

function setUp()
{
  $con = connect();
  if(!$con->query("CREATE DATABASE saferide"))
  {
    die("FAILED TO CREATE DATABASE\n");
  }

  $query = 'CREATE TABLE IF NOT EXISTS saferide.patron ';
  $query .= '(pid INTEGER NOT NULL AUTO_INCREMENT KEY, name VARCHAR(30), ';
  $query .= 'cell VARCHAR(15), riders TINYINT NOT NULL DEFAULT 0, car TINYINT, ';
  $query .= 'pickup INTEGER, dropoff VARCHAR(20), clothes VARCHAR(50), ';
  $query .= 'notes VARCHAR(200), status VARCHAR(10), modified SMALLINT)';
  if(!$con->query($query))
    die("FAILED TO CREATE saferide TABLE\n");

  $query = 'CREATE TABLE IF NOT EXISTS saferide.ridetimes '; 
  $query .= '(tid INTEGER NOT NULL AUTO_INCREMENT KEY, ridecreated DATETIME, ';
  $query .= 'rideassigned DATETIME, timepickedup DATETIME, ';
  $query .= 'timecomplete DATETIME, timecancelled DATETIME, pid INTEGER)';
  if(!$con->query($query))
    die("FAILED TO CREATE ridetimes TABLE\n");
    
  $query = 'CREATE TABLE IF NOT EXISTS saferide.activeusers '; 
  $query .= '(did INTEGER NOT NULL AUTO_INCREMENT KEY, username VARCHAR(8), ';
  $query .= 'ticket VARCHAR(50), logintime DATETIME, ';
  $query .= 'loggedin TINYINT)';
  if(!$con->query($query))
    die("FAILED TO CREATE activeusers TABLE\n");
    
  $query = 'CREATE TABLE IF NOT EXISTS saferide.locations ';
  $query .= '(lid INTEGER NOT NULL AUTO_INCREMENT KEY, name VARCHAR(30), ';
  $query .= 'value VARCHAR(8), longitude FLOAT NOT NULL DEFAULT 0, ';
  $query .= 'latitude FLOAT NOT NULL DEFAULT 0)';
  if(!$con->query($query))
    die("FAILED TO CREATE locations TABLE\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Alumni\', \'' . substr(hash('sha256', 'Alumni'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Alumni\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Buckley\', \'' . substr(hash('sha256', 'Buckley'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Buckley\\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Busby Suites\', \'' . substr(hash('sha256', 'Busby Suites'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Busby Suites\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Carriage\', \'' . substr(hash('sha256', 'Carriage'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Carriage\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Charter Oak\', \'' . substr(hash('sha256', 'Charter Oak'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Charter Oak\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'East\', \'' . substr(hash('sha256', 'East'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT East\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Garrigus Suites\', \'' . substr(hash('sha256', 'Garrigus Suites'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Garrigus Suites\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Graduate Housing\', \'' . substr(hash('sha256', 'Graduate Housing'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Graduate Housing\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Hilltop Apartments\', \'' . substr(hash('sha256', 'Hilltop Apartments'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Hilltop Apartments\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Hilltop Dorms\', \'' . substr(hash('sha256', 'Hilltop Dorms'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Hilltop Dorms\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Hunting Lodge\', \'' . substr(hash('sha256', 'Hunting Lodge'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Hunting Lodge\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Husky Village\', \'' . substr(hash('sha256', 'Husky Village'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Husky Village\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Mansfield Apartments\', \'' . substr(hash('sha256', 'Mansfield Apartments'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Mansfield Apartments\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'McMahon\', \'' . substr(hash('sha256', 'McMahon'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT McMahon\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'North\', \'' . substr(hash('sha256', 'North'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT North\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'NorthWest\', \'' . substr(hash('sha256', 'NorthWest'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT NorthWest\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Shippee\', \'' . substr(hash('sha256', 'Shippee'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Shippee\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'South\', \'' . substr(hash('sha256', 'South'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT South\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'Towers\', \'' . substr(hash('sha256', 'Towers'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT Towers\n");

  $query = 'INSERT INTO saferide.locations (name, value) ';
  $query .= 'VALUES (\'West\', \'' . substr(hash('sha256', 'West'), 0, 8) . '\')';
  if(!$con->query($query))
    die("FAILED TO INSERT West\n");

  $con->close();
}

