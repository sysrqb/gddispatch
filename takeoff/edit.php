<?php
  // This include the functions, classes, and db connection 
  include("classes.php");

  $pgId = "edit";
  include("layout_top.php");
  ?>
	
	
<?php 
	$pgId=$_GET["pg"] ;
	$ridenum = $_GET["num"] ;
	$sql = "SELECT * FROM rides WHERE num = '" . $ridenum . "' ";
	$qry1 = mysql_query($sql);
	$row = mysql_fetch_array($qry1);
    $editride  =  new  Ride($row);
	
    	$rNum = $editride->getAtt('num'); 
    	$rName = $editride->getAtt('name');
    	$rCell = $editride->getAtt('cell');
    	$rRiders = $editride->getAtt('riders');
    	$rPickup = $editride->getAtt('pickup');
    	$rDropoff = $editride->getAtt('dropoff');
    	$rLocation = $editride->getAtt('location');
    	$rClothes = $editride->getAtt('clothes');
    	$rNotes = $editride->getAtt('notes');
    	$rCar = $editride->getAtt('car'); 
	$pCar = $editride->getAtt('precar');
    ?>
    
		<div class="rideform">
			<form name="theForm" class="main" method="post" action="actions.php?action=edit">
			<fieldset><legend>&nbsp;Ride Details&nbsp;</legend>
			<br />
			<p><label class="left">What is your name?</label>
			   <input class="short" name="name" value="<?php echo $rName; ?>" /></p>
			<p><label class="left">What is your cell phone #?</label>
			   <input class="number" name="cellOne" maxlength="3" cols="4" value="<?php echo substr($rCell,0,3); ?>" />-<input class="number" name="cellTwo" maxlength="3" cols="4" value="<?php echo substr($rCell,3,3); ?>" />-<input class="number2" name="cellThree" maxlength="4" cols="4" value="<?php echo substr($rCell,6,4); ?>" /></p>
			<p><label class="left">How many people is this for?</label>
			   <input class="number" name="riders" maxlength="2" value="<?php echo $rRiders; ?>" /></p>
			<p><label class="left">Where can we pick you up?</label>
			   <input class="field" name="pickup" value="<?php echo $rPickup; ?>" /></p>
			<p><label class="left">Where are you staying?</label>
			   <input class="field" name="dropoff" value="<?php echo $rDropoff; ?>" /></p>
     			   <p><label class="left">If they live in apartments:</label>
     			   <select name="aploc" class="combo">
                		<option value="Null"> <b>Select an Apartment:</b> </option>
                		<option value="CO"> Charter Oak </option>
                     		<option value="HTA"> Hill Top </option>
				<option value="CO"> Charter Oak </option>
 				<option value="HV"> Husky Village </option>
                     		<option value="MA"> Mansfield </option>                    		
				<option value="Car"> Carriage </option>
				<option value="Ce"> Celeron </option>
				<option value="HL"> Hunting Lodge </option>
				<option value="NWA"> Northwood </option>
				<option value="NWA"> Northwood </option>
				<option value="NWA"> Northwood </option>
                     		<option value="Other"> Other </option></select></p>
			<p><label class="left">If they live in a dorm:</label>
     			   <select name="dormloc" class="combo">
                		<option value="Null"> <b>Select a Dorm:</b> </option>
                		<option value="BS"> Busby Suites </option>
                     		<option value="E"> East </option>
                     		<option value="N"> North </option>
                     		<option value="NW"> NorthWest </option>
                     		<option value="T"> Towers </option>
                     		<option value="Al"> Alumni </option>
                     		<option value="B"> Buckley </option>
                     		<option value="Gr"> Graduate Housing </option>
                     		<option value="GS"> Garrigus Suites </option>
                     		<option value="HT"> Hill Top </option>
                     		<option value="MM"> McMahon </option>
                     		<option value="Sh"> Shippee </option>
                     		<option value="So"> South </option>
                     		<option value="W"> West </option>
                     		<option value="Other"> Other </option></select></p>

			<p><label class="left">What are you wearing?</label>
			   <input class="field" name="clothes" value="<?php echo $rClothes; ?>" /></p>
     		<p><label class="left">Any additional information?</label>
     			   <textarea name="notes" cols="40" rows="3"><?php echo $rNotes; ?> </textarea></p>
     		<p><label class="left">Car Number?</label>
			   <input class="number" name="car" maxlength="3" cols="3" rows="6" value="<?php if($rCar==0){echo $pCar;} else{echo $rCar;} ?>" /></p>
			   <input type="hidden" name="num" value="<?php echo $rNum; ?>" />
			   <input type="hidden" name="pgId" value="<?php echo $pgId; ?>" />
    			<center><input class="edit" name="submit" type="submit" value="Save Ride" style="font-size:150%;"/></center> 
    			
    		
			</fieldset>
			</form>
		</div>
		<div class="instructions">
			<fieldset><legend>&nbsp;Instructions&nbsp;</legend>
			<p>Update any of the ride details here.</p>
			
			
			</fieldset>
		</div>
	</div>
	<?php include("footer.php"); ?>
</div>
</body>
</html>