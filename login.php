<?php require('credfile.php'); ?>
<html><head>
<style type="text/css">
body {
  padding-top: 10%;
  text-align: center;
  background-color: gray;
}
</style>
</head>
<body>
<form method='post' action='actions.php?action=login' class='main'>
	<p>Username: <input type='text' name='user' /></p>
	<p>Password: <input type='password' name='pass' /></p>
	<p><input name='submit' type='submit' class='login' value='Login' />
</form>
</body></html>
