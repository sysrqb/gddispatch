<?php
/* Before including this file, ensure function.php is included above it */
session_start();
if(!isAuthenticated()){
  header('Status: 307 Temporary Redirect');
  header('Location: ' . $CASserver . urlencode($LocalAuthValidator));
}
