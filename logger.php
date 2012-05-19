<?php
/* Requires:
 *  - global.php
*/

function logappend($str)
{
  global $time, $log, $logfile;
  if($log)
  {
    $fd = fopen($logfile, "ab");
    if(!$fd)
    {
      $fd = fopen($logfile . "-error.log", "ab");
      if(!fd)
        return false;
      else
      {
        $error = $time . ": Failed to open log file!\n";
        fwrite($fd, $error);
	fclose($fd);
      }
    }
    else
    {
      $ret = fwrite($fd, $time . ": " . $str . "\n");
      if(!$ret)
      {
        $ret = fwrite($fd, $time . ": " . $str . "\n");
	if(!$ret)
	  return 0;
      }
      fclose($fd);
      return $ret;
    }
  }
  else
    return 0;
}

function loganddie($error)
{
  global $die;
  if(logappend($error))
  {
    if($die)
    {
      die;
    }
  }
  else
  {
    if($die == 1)
    {
      die($error . "\n");
    }
    else
    {
      if($quiet)
      {
        echo $error . "\n";
      }
    }
  }
}
