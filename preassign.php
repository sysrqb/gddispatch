<form class="passign" method="post" name="form5" action="actions.php?action=preassign">
<label class="left" style="margin:3px 0 0 0;">Car: </label>
<input type="number" size="3px" name="carnum" autocomplete=on />
<br><br>
Not Assigned: <input type="radio" name="car" checked onClick="form5.carnum.value=''" />
<br>
Car 1: <input type="radio" name="car" value=1 onClick="form5.carnum.value=1" />
<br>
Car 2: <input type="radio" name="car" value=2 onClick="form5.carnum.value=2" />
<br>
Car 3: <input type="radio" name="car" value=1 onClick="form5.carnum.value=3"/>
<br>
Car 4: <input type="radio" name="car" value=4 onClick="form5.carnum.value=4"/>
<br>
Car 5: <input type="radio" name="car" value=5 onClick="form5.carnum.value=5"/>
<br>
Car 6: <input type="radio" name="car" value=6 onClick="form5.carnum.value=6"/>
<br>
Car 7: <input type="radio" name="car" value=7 onClick="form5.carnum.value=7"/>
<br>
Car 8: <input type="radio" name="car" value=8 onClick="form5.carnum.value=8"/>
<br>
<br>
<input type="submit" value="Submit" style="margin:2px;" />
<input value="Close" class="cancel" type="button" style="margin:3px;" onClick="location.reload(true);" />
</form>
