<?php 
  Header('Cache-Control: no-cache');
  Header('Pragma: no-cache');
?>
<div class="header">
	<center><h1>GUARD Dogs Dispatch <?php echo gmdate("G:i Z") ?></h1></center>
	</div>
	<div class="navbar">
	<ul>
		<li id="navincoming"><a href="incoming.php">Incoming</a></li>
		<li id="navwaiting"><a href="waiting.php">Waiting<span style="font-weight:normal;font-size:90%;">		
			(<? echo checkRideCount("waiting");?>)</span></a></li>
		<li id="navriding"><a href="riding.php">Riding<span style="font-weight:normal;font-size:90%;">
			(<? echo checkRideCount("riding");?>)</span></a></li>
		<li id="navdone"><a href="done.php">Done<span style="font-weight:normal;font-size:90%;">
			(<? echo checkCount("done");?>)</span></a></li>
		<!-- <li id="navcircuit"><a href="circuit.php">Circuit</a></li> -->
		<!--<li><a href="cir2.php?height=280&width=500" class="thickbox" title="">Circuit</a></li>-->
		<li id="navcars"><a href="cars.php">Cars</a></li>
		<li id="navstats"><a href="stats.php">Stats</a></li> 
		
		<li class="clock"><?php echo date("g:i a") ?></li>
	</ul>
	</div>
