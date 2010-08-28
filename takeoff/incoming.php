<?php include("classes.php"); ?>
<?php $pgId = "incoming"; ?>
<?php include("layout_top.php"); ?>	

		<div class="rideform">
			<form name="theForm" class="main" method="post" action="actions.php?action=addnewride">
			<fieldset><legend>&nbsp;Ride Details&nbsp;</legend>
			
			<br />
			<p><label class="left">What is your name?</label>
			   <input class="short" name="name" /></p>
			<p><label class="left">What is your cell phone #?</label>
			   <input class="number" name="cell1" maxlength="3" cols="3" onkeyup="autotab1(this.value)" autocomplete=off />-<input class="number" name="cell2" maxlength="3" cols="3" onkeyup="autotab2(this.value)" autocomplete=off />-<input class="number2" name="cell3" maxlength="4" cols="3" autocomplete=off onblur="phonecheck(document.theForm.cell1.value+document.theForm.cell2.value+this.value)" />
			   <div style="float:left;" id="phonecheck"></div></p>
			<p><label class="left">How many people is this for?</label>
			   <input class="number" name="riders" maxlength="2" autocomplete=off /></p>
			<p><label class="left">Where can we pick you up?</label>
			   <input class="field" name="pickup" /></p>
			<p><label class="left">Where are you staying?</label>
			   <input class="field" name="dropoff" /></p>
			<p><label class="left">If they live in apartments:</label>
     			   <select name="loc" class="combo">
                		<option value="Null"> <b>Select a Location:</b> </option>
            		        <option value="Al"> Alumni </option>
                     		<option value="B"> Buckley </option>
                		<option value="BS"> Busby Suites </option>
				<option value="Car"> Carriage </option>
				<option value="Ce"> Celeron </option>
                		<option value="CO"> Charter Oak </option>
                     		<option value="E"> East </option>
                     		<option value="GS"> Garrigus Suites </option>
                     		<option value="Gr"> Graduate Housing </option>
                     		<option value="HTA"> Hill Top Apartments </option>
                     		<option value="HT"> Hill Top Dorms </option>
				<option value="HL"> Hunting Lodge </option>
 				<option value="HV"> Husky Village </option>
                     		<option value="MA"> Mansfield </option>                    		
                     		<option value="MM"> McMahon </option>
				<option value="N"> North </option>
                     		<option value="NW"> NorthWest </option>
                     		<option value="Sh"> Shippee </option>
                     		<option value="So"> South </option>
                     		<option value="T"> Towers </option>
                     		<option value="W"> West </option>
                     		<option value="Other"> Other </option></select></p>
			<p><label class="left">What are you wearing?</label>
			   <input class="field" name="clothes" /></p>
     			<p><label class="left">Any additional information?</label>
     			   <textarea name="notes" cols="40" rows="3"></textarea></p>
    			<center><input name="submit" type="submit" class="assign" value="Add Ride" style="font-size:150%;" /></center> 
			</fieldset>
			</form>
		</div>
		<div class="instructions">
			<fieldset><legend>&nbsp;Instructions&nbsp;</legend>
			<p>When covering the incoming line, here are a few guidelines to follow:</p>
			<p>Answer the phone <b>"GUARD Dogs! Can we be your safe ride tonight?"</b> and begin asking the questions once the patron has confirmed they want a ride.</p>
			<p>If you can't understand the patron on the phone because it is too loud where they are ask them politely to find a quieter area.</p>
			<p>If someone calls asking to be taken home but you suspect they are going to a party explain that we don't take patrons to parties, only return them to where they live.</p>
			<p>If it is still early in the night (before 12 a.m.) and someone requests a ride to Carriage tell them that they will need to show their residence ID card when they are picked up.</p>
			<p>If you have ANY questions, please ask the Supervisor - <b>that's why they're there!</b>.</p>
			<br />
			<p>But most importantly, <b>HAVE FUN!</b></p>
			</fieldset>
		</div>
	</div>
	<?php include("footer.php"); ?>
</div>
</body>
</html>
