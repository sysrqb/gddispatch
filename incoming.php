<?php //include("classes.php"); ?>
<?php $pgId = "incoming"; ?>
<?php include("layout_top.php"); ?>	
<script type="text/javascript" src="incoming.js" ></script>

		<div class="rideform">
			<form id="theForm" name="theForm" class="main" method="post" action="actions.php?action=addnewride">
			<fieldset><legend>&nbsp;Ride Details&nbsp;</legend>
			
			<br />
			<p><label class="left">What is your name?</label>
			   <input class="short" name="name" /></p>
			<p><label class="left">What is your cell phone #?</label>
			   <input class="number" name="cell1" maxlength="3" onkeyup="autotab1(this.value)" />
			     -
			   <input class="number" name="cell2" maxlength="3" onkeyup="autotab2(this.value)" />
			     -
			   <input class="number2" name="cell3" maxlength="4"
			       onblur="phonecheck(document.theForm.cell1.value+document.theForm.cell2.value+this.value)" />
			     <input type="checkbox" name="override" value="dup" />
			   <label style="float:left;" id="phonecheck"></label></p>
			<p><label class="left">How many people is this for?</label>
			   <input class="number" name="riders" maxlength="2" /></p>
			<p><label class="left">Where can we pick you up?</label>
                              <select name="fromloc" class="combo" onchange="checkPick('pickup')">
                		<option value="Null">Select a Location:</option>
            		        <option value="Al">Alumni</option>
                     		<option value="B">Buckley</option>
                		<option value="BS">Busby Suites</option>
				<option value="Car">Carriage</option>
				<option value="Ce">Celeron</option>
                		<option value="CO">Charter Oak</option>
                     		<option value="E">East</option>
                     		<option value="GS">Garrigus Suites</option>
                     		<option value="Gr">Graduate Housing</option>
                     		<option value="HTA">Hilltop Apartments</option>
                     		<option value="HT">Hilltop Dorms</option>
				<option value="HL">Hunting Lodge</option>
 				<option value="HV">Husky Village</option>
                     		<option value="MA">Mansfield Apartments</option>                    		
                     		<option value="MM">McMahon</option>
				<option value="N">North</option>
                     		<option value="NW">NorthWest</option>
                     		<option value="Sh">Shippee</option>
                     		<option value="So">South</option>
                     		<option value="T">Towers</option>
                     		<option value="W">West</option>
                     		<option value="Other">Other</option></select></p>

			  <label class="left"></label>
    			   <p><input class="field" name="pickup" onfocus="otherPlace('pickup')" onblur="checkFilled('pickup')" placeholder="Only use if Other" disabled="disabled"/></p>
			<p><label class="left">Where are you staying?</label>
     			   <select name="toloc" class="combo" onchange="checkPick('dropoff')">
                		<option value="Null">Select a Location:</option>
            		        <option value="Al"> Alumni </option>
                     		<option value="B"> Buckley </option>
                		<option value="BS"> Busby Suites </option>
				<option value="Car"> Carriage </option>
				<option value="Ce"> Celeron </option>
                		<option value="CO"> Charter Oak </option>
                     		<option value="E"> East </option>
                     		<option value="GS"> Garrigus Suites </option>
                     		<option value="Gr"> Graduate Housing </option>
                     		<option value="HTA"> Hilltop Apartments </option>
                     		<option value="HT"> Hilltop Dorms </option>
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
			<p><label class="left"></label>
			  <input class="field" name="dropoff" onfocus="otherPlace('dropoff')" onblur="checkFilled('dropoff')" placeholder="Only use if Other" disabled="disabled"/></p>
			<p><label class="left">What are you wearing?</label>
			   <input class="field" name="clothes" /></p>
     			<p><label class="left">Any additional information?</label>
     			   <textarea name="notes" cols="40" rows="3"></textarea></p>
    			<input name="submitform" type="button" class="assign" value="Add Ride" style="font-size:150%; float: right;" onclick="verifyIncoming()"/>
			</fieldset>
			</form>
		</div>
		<div class="instructions">
			<fieldset><legend>&nbsp;Instructions&nbsp;</legend>
			<p>When covering the incoming line, here are a few guidelines to follow:</p>
			<p>Answer the phone <b>"Hello, GUARD Dogs! Can we be your safe ride tonight?"</b> and begin asking the questions once the patron has confirmed they want a ride.</p>
			<p>If you can't understand the patron on the phone because it is too loud where they are ask them politely to find a quieter area.</p>
			<p>If someone calls asking to be taken home but you suspect they are going to a party explain that we don't take patrons to parties, only return them to where they live.</p>
			<p>If it is still early in the night (before 12 a.m.) and someone requests a ride to Carriage or Celeron tell them that they will need to show their residence ID card when they are picked up.</p>
			<p>If you have ANY questions, please ask the Supervisor - <b>that's why they're there!</b> =)</p>
			<br />
			<p>But most importantly, <b>HAVE FUN!</b></p>
			</fieldset>
		</div>
	<?php include("layout_bottom.php"); ?>
