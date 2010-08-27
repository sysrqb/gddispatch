<form class="fsplit" method="post" action="actions.php?action=split">
<p><label class="left" style="margin:3px 3px 0 0;">Car: </label>
<input class="number" name="car" autocomplete=off /></p>
<p><label class="left" style="margin:3px 0 0 0;">Riders: </label>
<input class="number" name="riders" autocomplete=off /></p>
<input type="hidden" name="num" value="<?php echo $_GET["num"]; ?>" />
<input value="Split" class="split" type="submit" style="margin:3px;"/>
<input value=" Close " class="cancel" type="button" style="margin:3px;" onClick="location.reload(true);" />
</form>

<!-- <form class="fsplit" method="post" action="waiting.asp">
<fieldset><legend>&nbsp;OOPS!&nbsp;</legend>
<p>That ride as already been assigned. Please click close and pick a new ride.</p>
<button type="submit"></button> -->