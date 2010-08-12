<?php require("classes.php"); ?>
<?php include("stats_functions.php"); ?>
<?php $pgId = "stats"; ?>
<?php include("layout_top.php"); 

if ( isset($_GET['oday']) ) {
	$dateofride = date("Ymd", mktime(date("H")-12, date("i"), date("s"), $_GET['omonth'], $_GET['oday'],$_GET['oyear']))."000000";
}

?>	

		<h1> TEST DATA</h1>
		<div id="stats-content">

			<img id="top-chart" src="<?php typeByHour(); ?>" alt="" />

			<img id="top-chart" src="<?php ridesByHour(); ?>" alt="" />

			<img id="ride-meter" src="<?php rideMeter(); ?>" alt="" />

			<img id="pie-chart" src="<?php locationPie(); ?>" alt="" />

			<img id="car-chart" src="<?php ridesByCar(); ?>" alt="" />

			<img id="ride-count" src="<?php rideCounter(); ?>" alt="" />

		</div>
	</div>
	<?php include("footer.php"); ?>
</div>
</body>
</html>

