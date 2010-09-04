<form method='post' action='actions.php?action=login' class='main'>
	<p>Username: <input type='text' name='user' /></p>
	<p>Password: <input type='password' name='pass' value='<?php echo sha1(readline()); ?>'/></p>
	<p><input type='submit' value='Login' />
</form>
