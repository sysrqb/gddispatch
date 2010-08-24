<?php

$gmttime = gmdate("Y-m-d H:i:s");
$time = date("Y-m-d H:i:s");
$diff = date_diff($gmttime,$time);
echo date_format($diff, 'H:i:si');
?>
