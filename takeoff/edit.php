<?php
  // This include the functions, classes, and db connection 
  include("classes.php");

  $pgId = "edit";
  include("layout_top.php");
  ?>
	
	
<?php 
	$pgId=$_GET["pg"] ;
	$sql = "SELECT * FROM rides WHERE num = '" . $ridenum . "' ";
	$con = connect();
	if(!($stmt = mysqli_stmt_init($con))){
		die('Init Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_prepare($stmt, "SELECT * FROM rides WHERE num=?")){
		die('Prep Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_bind_param($stmt, 'i', $_GET['num'])){
		die('Bind Failed: ' . mysqli_stmt_error($stmt));
	}
	if(!mysqli_stmt_execute($stmt)){
		die('Exec Failed: ' . mysqli_stmt_error($stmt));
	}
	mysqli_stmt_bind_result($stmt, 
				$row['num'], 
				$row['name'],
				$row['cell'],
				$row['requested'], 
				$row['riders'],
				$row['precar'],
				$row['car'],
				$row['pickup'],
				$row['fromloc'],
				$row['dropoff'],
				$row['notes'],
				$row['clothes'],
				$row['ridedate'],
				$row['status'],
				$row['timetaken'],
				$row['timeassigned'],
				$row['timedone'],
				$row['loc']);
	mysqli_stmt_fetch($stmt);
    	$editride  =  new  Ride($row);
	
    	$rNum = $editride->getAtt('num'); 
    	$rName = $editride->getAtt('name');
    	$rCell = $editride->getAtt('cell');
    	$rRiders = $editride->getAtt('riders');
    	$rPickup = $editride->getAtt('pickup');
    	$rFromloc = $editride->getAtt('fromloc');
    	$rDropoff = $editride->getAtt('dropoff');
    	$rLoc = $editride->getAtt('loc');
    	$rClothes = $editride->getAtt('clothes');
    	$rNotes = $editride->getAtt('notes');
    	$rCar = $editride->getAtt('car'); 
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
			<p><label class="left"></label>
     			   <select name="fromloc" class="combo">
                		<option value="Null"> <b>Select a Location:</b> </option>
            		        <option value="Al" <?php if($row['fromloc']=='Al'){ echo 'selected'; } ?> > Alumni</option>
                     		<option value="B" <?php if($row['fromloc']=='B'){ echo 'selected'; } ?> > Buckley </option>
                		<option value="BS" <?php if($row['fromloc']=='BS'){ echo 'selected'; } ?> > Busby Suites </option>
				<option value="Car" <?php if($row['fromloc']=='Car'){ echo 'selected'; } ?> > Carriage </option>
				<option value="Ce" <?php if($row['fromloc']=='Ce'){ echo 'selected'; } ?> > Celeron </option>
                		<option value="CO" <?php if($row['fromloc']=='CO'){ echo 'selected'; } ?> > Charter Oak </option>
                     		<option value="E" <?php if($row['fromloc']=='E'){ echo 'selected'; } ?> > East </option>
                     		<option value="GS" <?php if($row['fromloc']=='GS'){ echo 'selected'; } ?> > Garrigus Suites </option>
                     		<option value="Gr" <?php if($row['fromloc']=='Gr'){ echo 'selected'; } ?> > Graduate Housing </option>
                     		<option value="HTA" <?php if($row['fromloc']=='HTA'){ echo 'selected'; } ?> > Hill Top Apartments </option>
                     		<option value="HT" <?php if($row['fromloc']=='HT'){ echo 'selected'; } ?> > Hill Top Dorms </option>
				<option value="HL" <?php if($row['fromloc']=='HL'){ echo 'selected'; } ?> > Hunting Lodge </option>
 				<option value="HV" <?php if($row['fromloc']=='HV'){ echo 'selected'; } ?> > Husky Village </option>
                     		<option value="MA" <?php if($row['fromloc']=='MA'){ echo 'selected'; } ?> > Mansfield </option>                    		
                     		<option value="MM" <?php if($row['fromloc']=='MM'){ echo 'selected'; } ?> > McMahon </option>
				<option value="N" <?php if($row['fromloc']=='N'){ echo 'selected'; } ?> > North </option>
                     		<option value="NW" <?php if($row['fromloc']=='NW'){ echo 'selected'; } ?> > NorthWest </option>
                     		<option value="Sh" <?php if($row['fromloc']=='Sh'){ echo 'selected'; } ?> > Shippee </option>
                     		<option value="So" <?php if($row['fromloc']=='So'){ echo 'selected'; } ?> > South </option>
                     		<option value="T" <?php if($row['fromloc']=='T'){ echo 'selected'; } ?> > Towers </option>
                     		<option value="W" <?php if($row['fromloc']=='W'){ echo 'selected'; } ?> > West </option>
                     		<option value="Other" <?php if($row['fromloc']=='Other'){ echo 'selected'; } ?> > Other </option></select></p>
			<p><label class="left">Where are you staying?</label>
			   <input class="field" name="dropoff" value="<?php echo $rDropoff; ?>" />
			<p><label class="left"></label>
     			   <select name="loc" class="combo">
                		<option value="Null"> <b>Select a Location:</b> </option>
            		        <option value="Al" <?php if($row['loc']=='Al'){ echo 'selected'; } ?> > Alumni</option>
                     		<option value="B" <?php if($row['loc']=='B'){ echo 'selected'; } ?> > Buckley </option>
                		<option value="BS" <?php if($row['loc']=='BS'){ echo 'selected'; } ?> > Busby Suites </option>
				<option value="Car" <?php if($row['loc']=='Car'){ echo 'selected'; } ?> > Carriage </option>
				<option value="Ce" <?php if($row['loc']=='Ce'){ echo 'selected'; } ?> > Celeron </option>
                		<option value="CO" <?php if($row['loc']=='CO'){ echo 'selected'; } ?> > Charter Oak </option>
                     		<option value="E" <?php if($row['loc']=='E'){ echo 'selected'; } ?> > East </option>
                     		<option value="GS" <?php if($row['loc']=='GS'){ echo 'selected'; } ?> > Garrigus Suites </option>
                     		<option value="Gr" <?php if($row['loc']=='Gr'){ echo 'selected'; } ?> > Graduate Housing </option>
                     		<option value="HTA" <?php if($row['loc']=='HTA'){ echo 'selected'; } ?> > Hill Top Apartments </option>
                     		<option value="HT" <?php if($row['loc']=='HT'){ echo 'selected'; } ?> > Hill Top Dorms </option>
				<option value="HL" <?php if($row['loc']=='HL'){ echo 'selected'; } ?> > Hunting Lodge </option>
 				<option value="HV" <?php if($row['loc']=='HV'){ echo 'selected'; } ?> > Husky Village </option>
                     		<option value="MA" <?php if($row['loc']=='MA'){ echo 'selected'; } ?> > Mansfield </option>                    		
                     		<option value="MM" <?php if($row['loc']=='MM'){ echo 'selected'; } ?> > McMahon </option>
				<option value="N" <?php if($row['loc']=='N'){ echo 'selected'; } ?> > North </option>
                     		<option value="NW" <?php if($row['loc']=='NW'){ echo 'selected'; } ?> > NorthWest </option>
                     		<option value="Sh" <?php if($row['loc']=='Sh'){ echo 'selected'; } ?> > Shippee </option>
                     		<option value="So" <?php if($row['loc']=='So'){ echo 'selected'; } ?> > South </option>
                     		<option value="T" <?php if($row['loc']=='T'){ echo 'selected'; } ?> > Towers </option>
                     		<option value="W" <?php if($row['loc']=='W'){ echo 'selected'; } ?> > West </option>
                     		<option value="Other" <?php if($row['loc']=='Other'){ echo 'selected'; } ?> > Other </option></select></p>
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
