<a href="cars.php">X</a>
	<form method="post" action="actions.php?action=contact">
	<input class="car-call" name="Contact" type="submit" value="Contacted" /> 
	<input type="hidden" name="carnum" value="<?php echo $_GET["carnum"]; ?>" />
	</form>
	<form method="post" action="actions.php?action=home">
	<input class="car-done" name="Home" type="submit" value="Headed Home" />
	<input type="hidden" name="carnum" value="<?php echo $_GET["carnum"]; ?>" />
	</form>