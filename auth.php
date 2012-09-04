<?php
require('functions.php');

if(isset($_REQUEST['ticket'])){
  $url = $CASValidateServer;
  $url = parse_url($url);


  //Start session here so we can handle errors later
  session_start();

  // extract host and path:
  $cashost = $url['host'];
  $path = $url['path'];
  
  //Set GET values, service is already url encoded
  $query = 'service=' . urlencode($LocalAuthValidator);
  $query .= '&ticket=' . $_REQUEST['ticket'];
  
  // open a socket connection on port 80 - timeout: 30 sec
  $fp = fsockopen("ssl://" . $cashost, 443, $errno, $errstr, 10);
  if($fp){
    fputs($fp, "GET $path?$query HTTP/1.1\r\n");
    fputs($fp, 'Host: ' . $cashost . "\r\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    $result = '';
    while(!feof($fp)) {
      // receive the results of the request
      $result .= fgets($fp, 128);
    }
    // close the socket connection:
    fclose($fp);

    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';

    //Parse XML response from CAS
    $p = xml_parser_create();
    xml_parse_into_struct($p, $content, $val, $index);
    xml_parser_free($p);
    
    if(isset($index['CAS:AUTHENTICATIONFAILURE'])){
      $errind = $index['CAS:AUTHENTICATIONFAILURE'][0];
      $ret =  array(
        'status' => "Authentication Error!<br/>\n",
        'statno' => 1,
        'error' => $val[$errind]['attributes']['CODE'] . "<br />\n",
        'reason' => $val[$errind]['value'] . "<br />\n",
      );
      //Destroy session here, hopefully redirect will return valid ticket
      session_destroy();
      if ($ret['error'] == 'INVALID_TICKET')
        //If invalid ticket, retrieve new ticket from CAS
        //Count redirects, to prevent looping and browser timeout
        header('Status: 307 Temporary Redirect');
        header('Location: ' . $CASserver . urlencode($LocalAuthValidator));
    }
    else if(isset($index['CAS:AUTHENTICATIONSUCCESS'])) {
      $userind = $index['CAS:USER'][0];
      $ret = array( 
        'status' => "Authentication Successful!<br/>\n",
        'statno' => 0,
        'username' => $val[$userind]['value'],
      );
      
      //Successfully authenticated
      $_SESSION['username'] = $ret['username'];
      $_SESSION['AUTH_RET'] = $ret;
      $_SESSION['ticket'] = $_REQUEST['ticket'];
      if(!makeUserLoggedIn($_SESSION))
      {
        header('Status: 307 Temporary Redirect');
        header('Location: ' .
             ($_SERVER['HTTPS'] ? 'https://' : 'http://') .
	     $_SERVER['SERVER_NAME'] . '/failedlogin.html');
      } else{
        if(!isset($pgID))
	  $pgID = 'incoming';
        header('Status: 307 Temporary Redirect');
        header('Location: ' .
               ($_SERVER['HTTPS'] ? 'https://' : 'http://') .
	       $_SERVER['SERVER_NAME'] . '/'. $pgID . '.php');
      }
      echo 'Login Successful!';
    }
    else{
      $message =  'Add catch to SessionInfo snippet' . "\n";
      $message .= "\nIndex:" .  print_r($index) . "\nValue:" . print_r($val);
      $message = wordwrap($message, 70);
      mail("it.guarddogs@gmail.com", 'Unknown Authentication Error', $message);

      $_SESSION['error'] = 1;
      $ret = array(
        'status' => 'Unknown Authentication Error' . "<br/>\n",
        'statno' => 2,
      );
      $_SESSION['AUTH_RET'] = $ret;
    }
  }
  else {
    $ret = array(
      'status' => 'Connection Error',
      'statno' => 20, //hopefully there aren't more than 19 authentication based errors
      'reason' => $errstr,
      'errno' => $errno,
    );
    $_SESSION['error'] = 1;
    $_SESSION['AUTH_RET'] = $ret;
    $message =  'Add catch to SessionInfo snippet' . "\n";
    $message .= "Error: $errstr ($errno)";
    $message = wordwrap($message, 70);
    mail("it.guarddogs@gmail.com", 'Unknown Connection Error to CAS Server', $message);
  }
}
?>
