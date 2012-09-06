<?php

require_once('credfile.php');
require_once('config.php');
require_once('global.php');
require_once('connection.php');
require_once('setup.php');
require_once('logger.php');

function init(){
  setup();
  $con = connect();
  $query = 'CREATE TABLE IF NOT EXISTS configs_week (wid SMALLINT NOT NULL' .
           ' AUTO_INCREMENT KEY, numcars SMALLINT DEFAULT 1, week' .
	   ' DATE)';
  if(!$con->query($query))
    die('Failed TO CREATE configs_week TABLE' . "\n");

  $query = 'CREATE TABLE IF NOT EXISTS configs_cars (cid SMALLINT NOT NULL' .
           ' AUTO_INCREMENT KEY, carnum SMALLINT DEFAULT 1, make' .
           ' VARCHAR(20), model VARCHAR(20), year YEAR(4), color' .
           ' VARCHAR(10), licenseplate VARCHAR(9), state VARCHAR(2),' .
	   ' damagetype VARCHAR(10), other VARCHAR(20), descript' .
	   ' VARCHAR(1000))';
  if(!$con->query($query))
    die('Failed TO CREATE configs_cars TABLE' . "\n");

  $query = 'CREATE TABLE IF NOT EXISTS configs_weekcars (uid SMALLINT NOT' .
           ' NULL AUTO_INCREMENT KEY, week SMALLINT NOT NULL, cars SMALLINT' .
	   ' NOT NULL)';
  if(!$con->query($query))
    die('Failed TO CREATE configs_weekcars TABLE' . "\n");

  $con->close();
}

function getCars($week){
  $con = connect();
  $query = "SELECT numcars from configs_week where week=?";
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'getcar: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('s', $week))
  {
     $error = 'getcar: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'getcar: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_result($retcount))
  {
    $error = 'getcar: Bind Result Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  /* Get set number of cars */
  while($stmt->fetch());
  $stmt->close();
  $con->close();
  return $retcount;
}

function getWid($week){
  $con = connect();
  $query = "SELECT wid from configs_week where week=?";
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'getcar: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('s', $week))
  {
     $error = 'getcar: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'getcar: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_result($wid))
  {
    $error = 'getcar: Bind Result Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  while($stmt->fetch());
  $stmt->close();
  $con->close();
  return $wid;
}

function setCars($week, $numcars){
  $query = 'INSERT INTO configs_week (numcars, week) VALUES (?, ?)';
  $con = connect();
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'setCars: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(isset($week) && isset($numcars))
  {
    $week = $con->escape_string($week);
    $numcars = $con->escape_string($numcars);
  } else{
    $error = 'Please make sure both the week and number of cars were specified';
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('ds', $numcars, $week))
  {
     $error = 'setCars: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'setCars: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $stmt->close();
  $con->close();
}

function getCar_HTML($week, $numcars){
  $num = getCar($week, $numcars);
  $options = '';
  for($i = 1; $i<$num+1;$i++){
    $options .= "<option value='$i'>$i</option>";
  }
  return $options;
}

function setCarInfo($make, $model, $color, $year, $lp, $state, $week, $carnum){
  $query = 'INSERT INTO configs_cars (make, model, color, year, licenseplate,'.
           ' state, carnum) VALUES (?, ?, ?, ?, ?, ?, ?)';
  $con = connect();
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'setCarInfo: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(isset($make) && isset($model) &&
     isset($color) && isset($year) &&
     isset($lp) && isset($state)){
    $make = $con->escape_string($make);
    $model = $con->escape_string($model);
    $color = $con->escape_string($color);
    $year = $con->escape_string($year);
    $year = intval($year);
    $lp = $con->escape_string($lp);
    $state = $con->escape_string($state);
    $week = $con->escape_string($week);
    $carnum = $con->escape_string($carnum);
  } else{
    $error = 'Please make sure all fields were filled';
    loganddie($error);
    return 0;
  }

  if(!$stmt->bind_param('sssdssd', $make, $model,
                        $color, $year, $lp,
                        $state, $carnum))
  {
     $error = 'setCarInfo: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'setCarInfo: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $insertid = $stmt->insert_id;
  $stmt->close();

  $wid = getWid($week);
  $query = 'INSERT INTO configs_weekcars (week, cars) VALUES (?, ?)';
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'setCarInfo: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('dd', $wid, $insertid))
  {
     $error = 'setCarInfo: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'setCarInfo: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $stmt->close();
  $con->close();
}

function updateCarInfo($make, $model, $color, $year, $lp, $state, $week, $cid){
  $query = 'UPDATE configs_cars SET make=?, model=?, color=?, year=?,' .
           'licenseplate=?, state=? WHERE cid=?';
  $con = connect();
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'setCarInfo: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(isset($make) && isset($model) &&
     isset($color) && isset($year) &&
     isset($lp) && isset($state) &&
     isset($cid)){
    $make = $con->escape_string($make);
    $model = $con->escape_string($model);
    $color = $con->escape_string($color);
    $year = $con->escape_string($year);
    $year = intval($year);
    $lp = $con->escape_string($lp);
    $state = $con->escape_string($state);
    $week = $con->escape_string($week);
    $cid = $con->escape_string($cid);
  } else{
    $error = 'Please make sure all fields were filled';
    loganddie($error);
    return 0;
  }

  if(!$stmt->bind_param('sssdssd', $make, $model,
                        $color, $year, $lp,
                        $state, $cid))
  {
    $error = 'setCarInfo: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'setCarInfo: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $stmt->close();
  $con->close();
}



function getCarInfo($week = ''){
  if(!strcmp($week, ''))
  {
    $time = time();
    $weeks = $time / (60*60*24);
    $dayofweek = (int)$time % 7;
    if($dayofweek > 4)
      $week = $time + ($dayofweek*60*60*24);
    else
      $week = $time - ($dayofweek*60*60*24);
    $week = gmdate('%Y-%m-%d', $week);
  }

  $query = 'SELECT * FROM configs_cars WHERE cid IN' .
           ' (SELECT cars FROM configs_weekcars WHERE week IN' .
           ' (SELECT week FROM configs_week WHERE week=?))';
  $con = connect();
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'getInfo: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('s', $week))
  {
     $error = 'getInfo: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'getInfo: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $row = array();
  if(!$stmt->bind_result($row['cid'], $row['carnum'], $row['make'],
                         $row['model'], $row['year'], $row['color'],
                         $row['licenseplate'], $row['state'],
                         $row['damagetype'], $row['other'], $row['descript']))
  {
    $error = 'getInfo: Bind Result Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $rows = array();
  $i = 0;
  /* Get stored car info */
  while($stmt->fetch()){
    $rows[$i] = $row;
    ++$i;
  }
  $stmt->close();
  $con->close();
  return $row;
}

function getCarInfo_HTML(){
  if(!isset($_POST))
  {
    $info = getCarInfo();
    if(count($info) == 0)
      $info['caropts'] = "<option value='-1' disabled>Week not chosen</option>";
    else
    {
      $num = $info['numcars'];

      for($i = 1; $i<$num+1;$i++){
        $info['caropts'] .= "<option value='$i'>$i</option>";
      }
    }
  } else{    
    $info = getInfo($week);
    $num = $info['numcars'];

    for($i = 1; $i<$num+1;$i++){
      $info['caropts'] .= "<option value='$i'>$i</option>";
    }
  }
}

function getOneCarInfo($week = '', $carnum){
  if(!strcmp($week, ''))
  {
    $time = time();
    $weeks = $time / (60*60*24);
    $dayofweek = (int)$time % 7;
    if($dayofweek > 4)
      $week = $time + ($dayofweek*60*60*24);
    else
      $week = $time - ($dayofweek*60*60*24);
    $week = gmdate('%Y-%m-%d', $week);
  }
  if(!isset($carnum))
    $carnum = 1;

  $query = 'SELECT * FROM configs_cars WHERE carnum=? AND cid IN' .
           ' (SELECT cars FROM configs_weekcars WHERE week IN' .
           ' (SELECT wid FROM configs_week WHERE week=?))';
  $con = connect();
  if(!($stmt = $con->prepare($query)))
  {
    $error = 'getInfo: Prep Failed: ' . $con->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->bind_param('ds', $carnum, $week))
  {
     $error = 'getInfo: Bind Param Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  if(!$stmt->execute())
  {
    $error = 'getInfo: Exec Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  $row = array();
  if(!$stmt->bind_result($row['cid'], $row['carnum'], $row['make'],
                         $row['model'], $row['year'], $row['color'],
                         $row['licenseplate'], $row['state'],
                         $row['damagetype'], $row['other'], $row['descript']))
  {
    $error = 'getInfo: Bind Result Failed: ' . $stmt->error;
    loganddie($error);
    return 0;
  }
  /* Get stored car info */
  while($stmt->fetch());
  $stmt->close();
  $con->close();
  return $row;
}



function getSetCars($week){
  if(strcMP($week, '') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $week) === 1)
  {
    $query = 'SELECT carnum FROM configs_cars WHERE cid IN' .
             ' (SELECT cars FROM configs_weekcars WHERE week IN' .
             ' (SELECT wid FROM configs_week WHERE week=?))';
    $con = connect();
    if(isset($week))
      $week = $con->escape_string($week);
    if(!($stmt = $con->prepare($query)))
    {
      $error = 'getInfo: Prep Failed: ' . $con->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->bind_param('s', $week))
    {
       $error = 'getInfo: Bind Param Failed: ' . $stmt->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->execute())
    {
      $error = 'getInfo: Exec Failed: ' . $stmt->error;
      loganddie($error);
      return 0;
    }
    if(!$stmt->bind_result($row))
    {
      $error = 'getInfo: Bind Result Failed: ' . $stmt->error;
      loganddie($error);
      return 0;
    }
      $cars = array();
      $i = 0;
      /* Store car numbers */
      while($stmt->fetch()){
        $cars[$i] = $row;
        ++$i;
      }
    $stmt->close();
    $con->close();
    return $cars;
  }
}

function getListOfSetCars($week){
  $rows = getSetCars($week);
  if(!is_array($rows)){
    return;
  }
  sort_by_value_dedupe($rows);
  $opts = '<option value=\'NA\'>Please select a car to update</option>' . "\n";
  foreach($rows as $key => $value)
    $opts .= '<option value=\'' . $value . '\'>' . $value . '</option>' . "\n";
  return $opts;
}

function getListOfNotSetCars($week){
  $rows = getSetCars($week);
  $numcars = getCars($week);
  if(!is_array($rows)){
    return 'Not Found';
  }
  $opts = '';
  $i = 1;
  if(count($rows) == 0)
  {
    for($i; ($i - 1) < $numcars; ++$i)
      $opts .= '<option value=\'' . $i . '\'>' . $i . '</option>' . "\n";
  } else {
    sort_by_value($rows);
    for($i; ($i - 1) < $numcars; ++$i){
      if(count($rows) == 0 || $i != $rows['0'])
        $opts .= '<option value=\'' . $i . '\'>' . $i . '</option>' . "\n";
      else
      {
        $value = array_shift($rows);
	if(isset($rows['0']))
          while($value == $rows['0']){
	    array_shift($rows);
	  }
      }
    }
  }
  return $opts;
}

function getCarValuesAsJSON($week, $carnum){
  $car = getOneCarInfo($week, $carnum);
  return json_encode($car);
}

function sort_by_value(&$arr){
  $i = 1;
  for($i; $i < count($arr); ++$i){
    $mem = $i;
    while($i > 0 && $arr[$i] < $arr[$i-1]){
      $tmp = $arr[$i-1];
      $arr[$i-1] = $arr[$i];
      $arr[$i] = $tmp;
      --$i;
    }
    $i = $mem;
  }
}

function sort_by_value_dedupe(&$arr){
  $i = 1;
  for($i; ($i - 0) < count($arr); ++$i){
    $mem = $i;
    while($i > 0 && $arr[$i] < $arr[$i-1]){
      $tmp = $arr[$i-1];
      $arr[$i-1] = $arr[$i];
      $arr[$i] = $tmp;
      --$i;
    }
    if($i > 0 && $arr[$i] == $arr[$i-1]){
      $tmp = $arr[count($arr)-1];
      $arr[count($arr)-1] = $arr[$i];
      $arr[$i] = $tmp;
      array_pop($arr);
      /* After we swap and pop, the last element will not be checked
       * unless we set i to be the previous element, then after the loop
       * increment it will be checked.
       */
      $mem = ($i - 1);
    }
    $i = $mem;
  }
}

?>
