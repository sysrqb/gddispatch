<?php
require('functions.php');

/**************************************
 * SetUp: Creates Database and Tables *
 **************************************/

function setUp()
{
  $con = connect();
  if(!$con->query("CREATE DATABASE saferide"))
  {
    breakDown();
    if(!$con->query("CREATE DATABASE saferide"))
    {
      die("FAILED TO CREATE DATABASE\n");
    }
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

/********************************************
 * BreakDown: Drops Database and all Tables *
 *  - Cleanup Function                      *
 ********************************************/

function breakDown()
{
  $con = connect();
  if(!$con->query("DROP DATABASE saferide"))
    die("FAILED TO DROP DATABASE\n");
  $con->close();
}

/************************************************
 * Helper Functions used in the following tests *
 ************************************************/

function TestaddLocation_CheckEmptyHash()
{
  setUp();
  $expect = 21;
  $input = '';
  $returned = addLocation($input);
  if ($returned != $expect)
  {
    echo 'TestaddLocation_CheckEmptyHash: FAILED on ""' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestaddLocation_CheckEmptyHash: SUCCESS' . "\n";
  }
  $con = connect();
  if(!($result = $con->query("SELECT value FROM locations WHERE lid = " . $returned)))
    die('FAILED TO QUERY DB' . "\n");
  if(!$result->num_rows != 0)
  {
    echo 'TestaddLocation_CheckEmptyHash: FAILED on Query' . "\n";
    echo '  Expected: 1 result' . "\n";
    echo '  Returned: ' . $returned . ' result(s)' . "\n";
  }
  else
  {
    echo 'TestaddLocation_CheckEmptyHash: SUCCESS: 1 Result Returned' . "\n";
    $rows = $result->fetch_row();
    if(count($rows) != 1)
    {
      echo 'TestaddLocation_CheckEmptyHash: FAILED on ResultSet' . "\n";
      echo '  Expected: 1 row' . "\n";
      echo '  Returned: ' . count($rows) . ' row(s)' . "\n";
    }
    else
    {
      echo 'TestaddLocation_CheckEmptyHash: SUCCESS: 1 Row Returned' . "\n";
      if($rows[0] != substr(hash('sha256', $input), 0, 8))
      {
        echo 'TestaddLocation_CheckEmptyHash: FAILED on Returned Valuet' . "\n";
        echo '  Expected: "' . md5($input) . '"' . "\n";
        echo '  Returned: "' . $rows[0] . '"' . "\n";
      }
      else
      {
        echo 'TestaddLocation_CheckEmptyHash: SUCCESS: Returned "' . $rows[0] . '"' . "\n";
      }
    }
  }
  breakDown();
}
 

function TestaddLocation_CheckHillTopAptsHash()
{
  setUp();
  $expect = 21;
  $input = 'Hilltop Apartments';
  $returned = addLocation($input);
  if ($returned != $expect)
  {
    echo 'TestaddLocation_CheckHillTopAptsHash: FAILED on ""' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestaddLocation_CheckHillTopAptsHash: SUCCESS' . "\n";
  }
  $con = connect();
  if(!($result = $con->query("SELECT value FROM locations WHERE lid = " . $returned)))
    die('FAILED TO QUERY DB' . "\n");
  if($result->num_rows == 0)
  {
    echo 'TestaddLocation_CheckHillTopAptsHash: FAILED on Query' . "\n";
    echo '  Expected: 1 result' . "\n";
    echo '  Returned: ' . $returned . ' result(s)' . "\n";
  }
  else
  {
    echo 'TestaddLocation_CheckHillTopAptsHash: SUCCESS: 1 Result Returned' . "\n";
    $rows = $result->fetch_row();
    if(count($rows) != 1)
    {
      echo 'TestaddLocation_CheckHillTopAptsHash: FAILED on ResultSet' . "\n";
      echo '  Expected: 1 row' . "\n";
      echo '  Returned: ' . count($rows) . ' row(s)' . "\n";
    }
    else
    {
      echo 'TestaddLocation_CheckHillTopAptsHash: SUCCESS: 1 Row Returned' . "\n";
      if($rows[0] != substr(hash('sha256', $input), 0, 8))
      {
        echo 'TestaddLocation_CheckHillTopAptsHash: FAILED on Returned Value' . "\n";
        echo '  Expected: "' . md5($input) . '"' . "\n";
        echo '  Returned: "' . $rows[0] . '"' . "\n";
      }
      else
      {
        echo 'TestaddLocation_CheckHillTopAptsHash: SUCCESS: Returned "' . $rows[0] . '"' . "\n";
      }
    }
  }
  breakDown();
}

function TestGetLocationID_CheckLID()
{
  setUp();
  $input = "Alumni";
  $expect = 1;
  $returned = getLocationID($input);
  if($returned != $expect)
  {
    echo 'TestGetLocationID_CheckLID: FAILED on Returned Value' . "\n";
    echo '  Expected: "' . $expect . '"' . "\n";
    echo '  Returned: "' . $returned . '"' . "\n";
  }
  else
  {
    echo 'TestGetLocationID_CheckLID: SUCCESS: Returned "' . $returned .
        ' for Alumni ' . "\n";
  }

  $input = "West";
  $expect = 20;
  $returned = getLocationID($input);
  if($returned != $expect)
  {
    echo 'TestGetLocationID_CheckLID: FAILED on Returned Value' . "\n";
    echo '  Expected: "' . $expect . '"' . "\n";
    echo '  Returned: "' . $returned . '"' . "\n";
  }
  else
  {
    echo 'TestGetLocationID_CheckLID: SUCCESS: Returned "' . $returned .
        ' for West ' . "\n";
  }

  $input = "Norwegian Woods";
  $expect = 21;
  $returned = getLocationID($input);
  if($returned != $expect)
  {
    echo 'TestGetLocationID_CheckLID: FAILED on Returned Value for Norwegian Woods1' . "\n";
    echo '  Expected: "' . $expect . '"' . "\n";
    echo '  Returned: "' . $returned . '"' . "\n";
  }
  else
  {
    echo 'TestGetLocationID_CheckLID: SUCCESS: Returned "' . $returned .
        ' for Norwegian Woods1' . "\n";
  }

  $input = "Norwegian Woods";
  $expect = 21;
  $returned = getLocationID($input);
  if($returned != $expect)
  {
    echo 'TestGetLocationID_CheckLID: FAILED on Returned Value for Norwegian Woods2' . "\n";
    echo '  Expected: "' . $expect . '"' . "\n";
    echo '  Returned: "' . $returned . '"' . "\n";
  }
  else
  {
    echo 'TestGetLocationID_CheckLID: SUCCESS: Returned "' . $returned .
        ' for Norwegian Woods2' . "\n";
  }
  breakDown();
}

function TestGetLocation_CheckName()
{
  setUp();
  $input = 1;
  $expect = 'Alumni';
  $returned = getLocation($input);
  if($returned['name'] != $expect)
  {
    echo 'TestGetLocation_CheckName: FAILED on Returned Value for Alumni' . "\n";
    echo '  Expected: "' . $expect . '"' . "\n";
    echo '  Returned: "' . $returned['name'] . '"' . "\n";
  }
  else
  {
    echo 'TestGetLocation_CheckName: SUCCESS: Returned "' . $returned['name'] .
        ' for Alumni' . "\n";
  }

  $input = 21;
  $expect = '';
  $returned = getLocation($input);
  if($returned != $expect)
  {
    echo 'TestGetLocation_CheckName: FAILED on Returned Value for ""' . "\n";
    echo '  Expected: "' . $expect . '"' . "\n";
    echo '  Returned: "' . $returned . '"' . "\n";
  }
  else
  {
    echo 'TestGetLocation_CheckName: SUCCESS: Returned "' . $returned .
        '" for ""' . "\n";
  }

  breakDown();
}

function TestCheckRideCount_CheckAllEQ0()
{
  setUp();
  $expect = 0;
  $returned = checkCount("");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ0: FAILED on ""' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ0: SUCCESS on ""' . "\n";
  }

  $returned = checkCount("waiting");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ0: FAILED on "waiting"' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ0: SUCCESS on "waiting"' . "\n";
  }

  $returned = checkCount("riding");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ0: FAILED on "riding"' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ0: SUCCESS on "riding"' . "\n";
  }

  $returned = checkCount("done");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ0: FAILED on "done"' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ0: SUCCESS on "done"' . "\n";
  }
  breakDown();
}

function TestCheckRideCount_CheckAllEQ1()
{
  setUp();
  $expect = 0;
  $returned = checkCount("");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ1: FAILED on ""' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ1: SUCCESS on ""' . "\n";
  }

  $returned = checkCount("waiting");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ1: FAILED on "waiting"' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ1: SUCCESS on "waiting"' . "\n";
  }

  $returned = checkCount("riding");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ1: FAILED on "riding"' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ1: SUCCESS on "riding"' . "\n";
  }

  $returned = checkCount("done");
  if($returned != $expect)
  {
    echo 'TestCheckRideCount_CheckAllEQ1: FAILED on "done"' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestCheckRideCount_CheckAllEQ1: SUCCESS on "done"' . "\n";
  }
  breakDown();
}

function TestRideAdd_CheckSuccessfulAllFields()
{
  global $prepare, $gdate;
  setUp();
  $con = connect();
  $expect = '1';
  $name = 'K';
  $cell1 = '555';
  $cell2 = '555';
  $cell3 = '5555';
  $riders = '5';
  $pickup = 'Alumni';
  $dropoff = 'West';
  $clothes = '';
  $notes = '';
  $_POST['name'] = $name;
  $_POST['cell1'] = $cell1;
  $_POST['cell2'] = $cell2;
  $_POST['cell3'] = $cell3;
  $_POST['riders'] = $riders;
  $_POST['pickup'] = $pickup;
  $_POST['dropoff'] = $dropoff;
  $_POST['clothes'] = $clothes;
  $_POST['notes'] = $notes;

  $returned = rideAdd();
  if($returned != $expect)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED: rideAdd' . "\n";
    echo '  Expected: ' . $expect . "\n";
    echo '  Returned: ' . $returned . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS: rideAdd' . "\n";
  }
  if(!($stmt = $con->prepare($prepare['ride'])))
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED: on PREPARE' . "\n";
    $con->close();
    breakDown();
    return;
  }
  $status = 'waiting';
  if(!$stmt->bind_param('ss', $gdate, $status))
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED: on BIND_PARAM' . "\n";
    $stmt->close();
    $con->close();
    breakDown();
    return;
  }
  if(!$stmt->execute())
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED: on EXECUTE' . "\n";
    $stmt->close();
    $con->close();
    breakDown();
    return;
  }
  if(!$stmt->bind_result(
    $row['pid'], 
    $row['name'],
    $row['cell'],
    $row['riders'],
    $row['car'],
    $row['pickup'],
    $row['dropoff'],
    $row['clothes'],
    $row['notes'],
    $row['status'],
    $row['modified'],
    $row['tid'],
    $row['ridecreated'],
    $row['rideassigned'],
    $row['timepickedup'],
    $row['timecomplete'],
    $row['timecancelled'],
    $row['pidt']
  ))
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED: on BIND_RESULT' . "\n";
    echo '  ERROR: ' . $stmt->field_count . "\n";
    $stmt->close();
    $con->close();
    breakDown();
    return;
  }
  while($stmt->fetch());

  $pickupLoc = getLocation($row['pickup']);
  $dropoffLoc = getLocation($row['dropoff']);
  if($row['name'] != $name)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on name' . "\n";
    echo '  Expected: ' . $name . "\n";
    echo '  Returned: ' . $row['name'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on name' . "\n";
  }
  if($row['cell'] != ($cell1 . $cell2 . $cell3))
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on cell' . "\n";
    echo '  Expected: ' . $cell1 . $cell2 . $cell3 . "\n";
    echo '  Returned: ' . $row['cell'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on cell' . "\n";
  }
  if($row['riders'] != $riders)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on riders' . "\n";
    echo '  Expected: ' . $riders . "\n";
    echo '  Returned: ' . $row['riders'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on riders' . "\n";
  }
  if($pickupLoc['name'] != $pickup)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on pickup' . "\n";
    echo '  Expected: ' . $pickup . "\n";
    echo '  Returned: ' . $pickupLoc['name'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on pickup' . "\n";
  }
  if($dropoffLoc['name'] != $dropoff)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on dropoff' . "\n";
    echo '  Expected: ' . $dropoff . "\n";
    echo '  Returned: ' . $dropoffLoc['name'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on dropoff' . "\n";
  }
  if($row['clothes'] != $clothes)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on clothes' . "\n";
    echo '  Expected: ' . $clothes . "\n";
    echo '  Returned: ' . $row['clothes'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on clothes' . "\n";
  }
  if($row['notes'] != $notes)
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: FAILED on notes' . "\n";
    echo '  Expected: ' . $notes . "\n";
    echo '  Returned: ' . $row['notes'] . "\n";
  }
  else
  {
    echo 'TestRideAdd_CheckSuccessfulAllFields: SUCCESS on notes' . "\n";
  }
  $stmt->close();
  $con->close();
  breakDown();
}

$start = time();
TestaddLocation_CheckEmptyHash();
TestaddLocation_CheckHillTopAptsHash();
TestGetLocation_CheckName();
TestCheckRideCount_CheckAllEQ0();
TestCheckRideCount_CheckAllEQ1();
TestRideAdd_CheckSuccessfulAllFields();
$end = time();
echo 'Total Time: ' . ($end - $start) . ' seconds' . "\n";
