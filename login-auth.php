<?php
/* Before including this file, ensure function.php is included above it */
require_once('functions.php');
session_start();
if(!isAuthenticated()){
  header('Status: 307 Temporary Redirect');
  header('Location: ' . $CASserver . urlencode($LocalAuthValidator));
}
