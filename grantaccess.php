<?php
require_once('login-auth.php');
  if(!isset($_SESSION))
    exit();
  if(!isset($_SESSION['authenticated_admin']))
    exit();
  if($_SESSION['authenticated_admin'] != true)
    exit();

if(session_status() == PHP_SESSION_DISABLED)
  session_start();
$filename = 'useraccess';
if(file_exists($filename) && filesize($filename) > 0){
  $fd = fopen($filename, 'r');
  $users = fread($fd, filesize($filename));
}
if(isset($_SESSION) && isset($_SESSION['data']) && isset($_SESSION['data']['grantaccesserror']))
  $error = $_SESSION['data']['grantaccesserror'];
else
  $error = 'Session failed?' . "\n";
?>
<html><head><title>
Administrative Page
</title>
</head>
<body  style='background-color:#E03E3E; text-align: center;'>
<p>
<h3>Grant Access to Users</h3>

<form action="adminact.php?action=addusers" method="post">
<?php
  if(isset($_SESSION) && isset($_SESSION['data']) && isset($_SESSION['data']['grantaccesserror'])){
    $error = $_SESSION['data']['grantaccesserror'];
    echo '<p>Error: ' . $error . '</p>';
    unset($_SESSION['data']['grantaccesserror']);
  }
?>
<textarea name="usernames">
<?php
  if(isset($users))echo $users;?>
</textarea>
<p><input type="submit" value="update" /></p>
</form>
<p><a href="admin.php">Back</a></p>
</body></html>
