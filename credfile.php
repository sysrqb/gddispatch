<?php

if(file_exists('cred.local.php'))
  require('cred.local.php');
else if(file_exists('cred.php'))
  require('cred.php');
else {
?>
<html><body><br /><br /><div style="text-align: center;">
  <h2>Credentials File Not Found!</h2>
  <p>We couldn't find the credentials file. Please make sure cred.php or 
     cred.local.php exist and define the necessary information. Thanks!</p>
  </div></body></html>
<?php
  exit();
}
?>

