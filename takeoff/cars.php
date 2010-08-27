<?php require("classes.php"); ?>
<?php include("cars_functions.php"); ?>
<?php $pgId = "cars"; ?>
<?php include("layout_top.php"); ?>
	
		<div id="cars-content">
			<div id="legend-content">
				<div id="car-legend" >
					<img class="car-circuit" />
					<label>Circuit Car</label>
				</div>
				<div id="car-legend" >
					<img class="car-normal" />
					<label>Normal Car</label>
				</div>
				<div id="car-legend" >
					<img class="car-call" />
					<label>Call Now</label>
				</div>
				<div id="car-legend" >
					<img class="car-chill" />
					<label>Chilling</label>
				</div>
				<div id="car-legend" >
					<img class="car-here" />
					<label>Not Out</label>
				</div>
				<div id="car-legend" >
					<img class="car-done" />
					<label>Back Home</label>
				</div>
			</div>
			
			<div id="car-boxes">
				<?php carBoxes(); ?>
			</div>
		</div>
	</div>
	<?php include("footer.php"); ?>
</div>
</body>
</html>

