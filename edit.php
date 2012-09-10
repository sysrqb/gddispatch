<?php
  // This include the functions, classes, and db connection 
  include("classes.php");

  $pgId = "edit";
  include("layout_top.php");

  $pgId=$_GET["pg"] ;
  $con = connect();
  if(!($stmt = $con->prepare($prepare['getride']))){
    $error = 'Edit: Prep Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->bind_param('i', $_GET['num'])){
    $error = 'Edit: Bind Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  if(!$stmt->execute()){
    $error = 'Edit: Exec Failed: ' . $con->error;
    loganddie($error);
    return;
  }
  $stmt->bind_result(
    $row['pid'], 
    $row['name'],
    $row['cell'],
    $row['riders'],
    $row['car'],
    $row['pickup'],
    $row['dropoff'],
    $row['clothes'],
    $row['notes'],
    $row['status'],
    $row['modified'],
    $row['tid'],
    $row['ridecreated'],
    $row['rideassigned'],
    $row['timepickedup'],
    $row['timecomplete'],
    $row['timecancelled'],
    $row['tpid']
  );
  while($stmt->fetch());
  $pickupinlist = true;
  $dropoffinlist = true;
  $pickuplocationinfo = getLocation($row['pickup']);
  $pickuplocation = $pickuplocationinfo['code'];
  if(!strcmp($pickuplocation, ''))
  {
    $pickupinlist = false;
    $pickuplocation = $pickuplocationinfo['name'];
  }
  $dropofflocationinfo = getLocation($row['dropoff']);
  $dropofflocation = $dropofflocationinfo['code'];
  if(!strcmp($dropofflocation, ''))
  {
    $dropoffinlist = false;
    $dropofflocation = $dropofflocationinfo['name'];
  }
  print_r($row);
?>
<script type="text/javascript" src="incoming.js" ></script>
    
		<div class="rideform">
			<form name="theForm" class="main" method="post" action="actions.php?action=edit">
			<fieldset><legend>&nbsp;Ride Details&nbsp;</legend>
			<br />
			<p><label class="left">What is your name?</label>
			   <input class="short" name="name" value="<?php echo $row['name']; ?>" /></p>
			<p><label class="left">What is your cell phone #?</label>
			   <input class="number" name="cellOne" maxlength="3" cols="4" value="<?php echo substr($row['cell'],0,3); ?>" />-<input class="number" name="cellTwo" maxlength="3" cols="4" value="<?php echo substr($row['cell'],3,3); ?>" />-<input class="number2" name="cellThree" maxlength="4" cols="4" value="<?php echo substr($row['cell'],6,4); ?>" /></p>
			<p><label class="left">How many people is this for?</label>
			   <input class="number" name="riders" maxlength="2" value="<?php echo $row['riders']; ?>" /></p>
			<p><label class="left">Where can we pick you up?</label>
     			   <select name="fromloc" class="combo" onchange="checkPick('pickup')">
                		<option value="Null"> <b>Select a Location:</b> </option>
            		        <option value="Al" 
				<?php
				      if($pickupinlist && $pickuplocation =='Al'){
				        echo 'selected';
				      } ?> 
				> Alumni</option>
                     		<option value="B" 
				<?php
				      if($pickupinlist && $pickuplocation =='B'){
				        echo 'selected';
				      } ?> 
				> Buckley </option>
                		<option value="BS" 
				<?php
				      if($pickupinlist && $pickuplocation =='BS'){
				        echo 'selected';
				      } ?> 
				> Busby Suites </option>
				<option value="Car" 
				<?php
				      if($pickupinlist && $pickuplocation =='Car'){
				        echo 'selected';
				      } ?> 
				> Carriage </option>
				<option value="Ce" 
				<?php
				      if($pickupinlist && $pickuplocation =='Ce'){
				        echo 'selected';
				      } ?> 
				> Celeron </option>
                		<option value="CO" 
				<?php
				      if($pickupinlist && $pickuplocation =='CO'){
				        echo 'selected';
				      } ?> 
				> Charter Oak </option>
                     		<option value="E" 
				<?php
				      if($pickupinlist && $pickuplocation =='E'){
				        echo 'selected';
				      } ?> 
				> East </option>
                     		<option value="GS" 
				<?php
				      if($pickupinlist && $pickuplocation =='GS'){
				        echo 'selected';
				      } ?> 
				> Garrigus Suites </option>
                     		<option value="Gr" 
				<?php
				      if($pickupinlist && $pickuplocation =='Gr'){
				        echo 'selected';
				      } ?> 
				> Graduate Housing </option>
                     		<option value="HTA" 
				<?php
				      if($pickupinlist && $pickuplocation =='HTA'){
				        echo 'selected';
				      } ?> 
				> Hill Top Apartments </option>
                     		<option value="HT" 
				<?php
				      if($pickupinlist && $pickuplocation =='HT'){
				        echo 'selected';
				      } ?> 
				> Hill Top Dorms </option>
				<option value="HL" 
				<?php
				      if($pickupinlist && $pickuplocation =='HL'){
				        echo 'selected';
				      } ?> 
				> Hunting Lodge </option>
 				<option value="HV" 
				<?php
				      if($pickupinlist && $pickuplocation =='HV'){
				        echo 'selected';
				      } ?> 
				> Husky Village </option>
                     		<option value="MA" 
				<?php
				      if($pickupinlist && $pickuplocation =='MA'){
				        echo 'selected';
				      } ?> 
				> Mansfield </option>
                     		<option value="MM" 
				<?php
				      if($pickupinlist && $pickuplocation =='MM'){
				        echo 'selected';
				      } ?> 
				> McMahon </option>
				<option value="N" 
				<?php
				      if($pickupinlist && $pickuplocation =='N'){
				        echo 'selected';
				      } ?> 
				> North </option>
                     		<option value="NW" 
				<?php
				      if($pickupinlist && $pickuplocation =='NW'){
				        echo 'selected';
				      } ?> 
				> NorthWest </option>
                     		<option value="Sh" 
				<?php
				      if($pickupinlist && $pickuplocation =='Sh'){
				        echo 'selected';
				      } ?> 
				> Shippee </option>
                     		<option value="So" 
				<?php
				      if($pickupinlist && $pickuplocation =='So'){
				        echo 'selected';
				      } ?> 
				> South </option>
                     		<option value="T" 
				<?php
				      if($pickupinlist && $pickuplocation =='T'){
				        echo 'selected';
				      } ?> 
				> Towers </option>
                     		<option value="W" 
				<?php
				      if($pickupinlist && $pickuplocation =='W'){
				        echo 'selected';
				      } ?> 
				> West </option>
                     		<option value="Other" 
				<?php
				      if($pickupinlist && $pickuplocation =='Other'){
				        echo 'selected';
				      } ?> 
				> Other </option></select></p>
			<p><label class="left"></label>
			   <input class="field" name="pickup"
			     <?php
			       if($pickupinlist)
			         echo "disabled";
			       else
			         echo 'value="' . $pickuplocation . '"';
			    ?>
			  onfocus="otherPlace('pickup')" onblur="checkFilled('pickup')"  />
			</p>
			<p><label class="left">Where are you staying?</label>
     			   <select name="toloc" class="combo" onchange="checkPick('dropoff')">
                		<option value="Null"> <b>Select a Location:</b> </option>
            		        <option value="Al" 
				<?php
				      if($dropoffinlist && $dropofflocation =='Al'){
				        echo 'selected';
				      } ?> 
				> Alumni</option>
                     		<option value="B" 
				<?php
				      if($dropoffinlist && $dropofflocation =='B'){
				        echo 'selected';
				      } ?> 
				> Buckley </option>
                		<option value="BS" 
				<?php
				      if($dropoffinlist && $dropofflocation =='BS'){
				        echo 'selected';
				      } ?> 
				> Busby Suites </option>
				<option value="Car" 
				<?php
				      if($dropoffinlist && $dropofflocation =='Car'){
				        echo 'selected';
				      } ?> 
				> Carriage </option>
				<option value="Ce" 
				<?php
				      if($dropoffinlist && $dropofflocation =='Ce'){
				        echo 'selected';
				      } ?> 
				> Celeron </option>
                		<option value="CO" 
				<?php
				      if($dropoffinlist && $dropofflocation =='CO'){
				        echo 'selected';
				      } ?> 
				> Charter Oak </option>
                     		<option value="E" 
				<?php
				      if($dropoffinlist && $dropofflocation =='E'){
				        echo 'selected';
				      } ?> 
				> East </option>
                     		<option value="GS" 
				<?php
				      if($dropoffinlist && $dropofflocation =='GS'){
				        echo 'selected';
				      } ?> 
				> Garrigus Suites </option>
                     		<option value="Gr" 
				<?php
				      if($dropoffinlist && $dropofflocation =='Gr'){
				        echo 'selected';
				      } ?> 
				> Graduate Housing </option>
                     		<option value="HTA" 
				<?php
				      if($dropoffinlist && $dropofflocation =='HTA'){
				        echo 'selected';
				      } ?> 
				> Hill Top Apartments </option>
                     		<option value="HT" 
				<?php
				      if($dropoffinlist && $dropofflocation =='HT'){
				        echo 'selected';
				      } ?> 
				> Hill Top Dorms </option>
				<option value="HL" 
				<?php
				      if($dropoffinlist && $dropofflocation =='HL'){
				        echo 'selected';
				      } ?> 
				> Hunting Lodge </option>
 				<option value="HV" 
				<?php
				      if($dropoffinlist && $dropofflocation =='HV'){
				        echo 'selected';
				      } ?> 
				> Husky Village </option>
                     		<option value="MA" 
				<?php
				      if($dropoffinlist && $dropofflocation =='MA'){
				        echo 'selected';
				      } ?> 
				> Mansfield </option>
                     		<option value="MM" 
				<?php
				      if($dropoffinlist && $dropofflocation =='MM'){
				        echo 'selected';
				      } ?> 
				> McMahon </option>
				<option value="N" 
				<?php
				      if($dropoffinlist && $dropofflocation =='N'){
				        echo 'selected';
				      } ?> 
				> North </option>
                     		<option value="NW" 
				<?php
				      echo $dropoffinlist . ' ' .  strcmp($dropofflocation,'NW') . "\n";
				      if($dropoffinlist && $dropofflocation =='NW'){
				        echo 'selected';
				      } ?> 
				> NorthWest </option>
                     		<option value="Sh" 
				<?php
				      if($dropoffinlist && $dropofflocation =='Sh'){
				        echo 'selected';
				      } ?> 
				> Shippee </option>
                     		<option value="So" 
				<?php
				      if($dropoffinlist && $dropofflocation =='So'){
				        echo 'selected';
				      } ?> 
				> South </option>
                     		<option value="T" 
				<?php
				      if($dropoffinlist && $dropofflocation =='T'){
				        echo 'selected';
				      } ?> 
				> Towers </option>
                     		<option value="W" 
				<?php
				      if($dropoffinlist && $dropofflocation =='W'){
				        echo 'selected';
				      } ?> 
				> West </option>
                     		<option value="Other" 
				<?php
				      if($dropoffinlist && $dropofflocation =='Other'){
				        echo 'selected';
				      } ?> 
				> Other </option></select></p>
			<p><label class="left"></label>
			   <input class="field" name="dropoff" 
			     <?php
			       if($dropoffinlist)
			         echo "disabled";
			       else
			         echo 'value="' . $dropofflocation . '"';
			    ?>
			  onfocus="otherPlace('dropoff')" onblur="checkFilled('dropoff')" />
			<p><label class="left">What are you wearing?</label>
			   <input class="field" name="clothes" value="<?php echo $row['clothes']; ?>" /></p>
     		<p><label class="left">Any additional information?</label>
     			   <textarea name="notes" cols="40" rows="3"><?php echo $row['notes']; ?> </textarea></p>
     		<p><label class="left">Car Number?</label>
			   <input class="number" name="car" maxlength="3" cols="3" rows="6" value="<?php if($row['car']!=0){echo $row['car'];} ?>" /></p>
			   <input type="hidden" name="num" value="<?php echo $row['pid']; ?>" />
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
