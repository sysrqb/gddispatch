<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
if ($pgId<>"incoming") {	echo '<meta HTTP-EQUIV="Refresh" CONTENT="150" />';}
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GUARD Dogs Rides | <?php echo ucfirst($pgId) ?></title>


<script type="text/javascript" src="./jquery/jquery.js"></script>
<script type="text/javascript" src="./jquery/thickbox.js"></script>
<script type="text/javascript">
<!--
function smallNumber(num){ if (num < 10) return "0" + num; else return num; }
function clockfun(){var time = new Date(); var minute = smallNumber(time.getMinutes()); var secs = smallNumber(time.getSeconds()); document.getElementById("clock").innerHTML = time.getHours() + ":" + minute + ":" + secs; }
//-->
</script>

<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript">
</script>
<link rel="stylesheet" href="./jquery/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body id="<?php echo ($pgId) ?>" onload="clockfun(); setInterval('clockfun()', 500)">
<div class="container">
  <?php require('./header.php'); ?>
  <div class="main-content">
