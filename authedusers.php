<?php
  require_once('adminfunctions.php');
  require_once('login-auth.php');
  if(!isset($_SESSION))
    exit();
  if(!isset($_SESSION['authenticated_admin']))
    exit();
  if($_SESSION['authenticated_admin'] != true)
    exit();


?>
<html><head><title>
Administrative Page
</title>
<style type="text/css">
table, th, td
{
  border: 1px solid black;
  text-align: center;
  background-color: lightgrey;
}
</style>
</head>
<body style='background-color:#00B000; text-align: center;'>
<h2>Car Administration - Active Users</h2>
<br />
<a href="admin.php" style="text-align: left;">Back to admin page</a>
<br />
<p>
  <table style="width: 99%;">
    <tr><th>Username</th><th>Login Time</th></tr>
    <?php echo getActiveUsers(); ?>
  </table>
</p>


</body></html>
