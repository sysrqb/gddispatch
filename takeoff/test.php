<?php
$gmttime = new DateTime(NULL,new DateTimeZone('UTC'));
echo (($gm=$gmttime->format('H') ). "\n");
$time = new DateTime();
echo (($est=$time->format('H')) . "\n");
echo ((24+$gm)-$est . "\n");
$diff = $gmttime->diff($time);
echo ($diff->format('%R%H'."\n"));


$time = new DateTime();
$past = new DateTime("2010-08-24 18:57:00");
$diff = $time->diff($past);
echo ($diff->format("%H:%I\n"));

$tinter = new DateInterval("PT30M");
echo $tinter->format('%H:%I:%S'."\n");

if(($tinter->format('%H')) < ($diff->format('%H'))){
	echo "true\n";
	if($tinter->format('%I') < $diff->format('%I')){
		echo "true\n";
	}
	else{
		echo "false\n";
	}
}
else {
	echo "false\n";
}
?>
