<?php

require('adminfunctions.php');

/*echo setCars('2012-09-06', 6);
echo setCarInfo('Make', 'Model', 'Color', '1970', 'LicensePl', 'St', '2012-09-06', 1);
if(preg_match('/^\d{4}-\d{2}-\d{2}$/', '2012-09-06'))
  echo 'Passes Regex' . "\n";
else
  die('Fails Regex' . "\n");*/

echo "\nTESTING getListOfSetCars:\n";
echo getListOfSetCars('2012-09-06');
echo "\nTESTING getListOfNotSetCars:\n";
echo getListOfNotSetCars('2012-09-06');
echo "\nTESTING getCarValuesAsJSON:\n";
echo getCarValuesAsJSON('2012-09-06', 1);
echo "\n";
