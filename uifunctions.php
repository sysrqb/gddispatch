<?php

/* Required Includes:
 *  - global.php
*/

//Creates the assign button
function tblBtnAssign($ride)
{
  $row = '<td class="btn">';
  $row .= '<button class="assign" onClick="highlight(\'row' . $ride . 
      '\');assignride(\'assign.php?num=' . $ride . '\',\'assign' . 
      $ride . '\')">Assign</button>';
  $row .= '</td>'."\r";
  return $row;
}

//Creates the Split button
function tblBtnSplit($ride)
{
  $row = '<td class="btn">';
  $row .= '<button class="split" onClick="highlight(\'row' . 
      $ride . '\');assignride(\'split.php?num=' . $ride . '\',\'assign' . 
      $ride . '\')">Split</button>';
  $row .= '</td>'."\r";
  return $row;
}

//Creates the Edit button
function tblBtnEdit($ride,$pg)
{
  $row = '<td class="btn">';
  $row .= '<button class="edit" onClick="window.location=\'edit.php?pg=' . 
    $pg . '&num=' . $ride . '\'">Edit</button>';
  $row .= '</td>' . "\r";
  return $row;
}

//Creates the Done button
function tblBtnRiding($ride)
{
  $row = '<td class="btn">';
  $row .= '<button class="riding" onClick="window.location=\'actions.php?num=' . 
    $ride . '&action=riding\'">Riding</button>';
  $row .= '</td>' . "\r";
  return $row;
}

//Creates the Done button
function tblBtnDone($ride)
{
  $row = '<td class="btn">';
  $row .= '<button class="done" onClick="window.location=\'actions.php?num=' . 
    $ride . '&action=done\'">Done</button>';
  $row .= '</td>' . "\r";
  return $row;
}

//Creates the Cancel button
function tblBtnCancel($ride)
{
  $row = '<td class="btn">';
  $row .= '<button class="cancel" onClick="window.location=\'actions.php?num=' 
    . $ride . '&action=cancel\'">Cancel</button>';
  $row .= '</td>' . "\r";
  return $row;
}

//Creates the Undo button
function tblBtnUndo($ride)
{
  $row = '<td class="btn">';
  $row .= '<button class="undo" onClick="window.location=\'actions.php?num=' . 
    $ride . '&action=undo\'">Undo</button>';
  $row .= '</td>' . "\r";
  return $row;
}

//Prints the ride info
function tblRideInfo($info)
{
  $row = '<td>';
  $row .= $info;
  $row .= '</td>' . "\r";
  return $row;
}

//Prints the ride info
function tblLocationInfo($info)
{
  $loc = getLocation($info);
  $row = '<td>';
  $row .= $loc['name'];
  $row .= '</td>' . "\r";
  return $row;
}

/*  car an finished?? */
function tblDoneCar($car)
{
  $row = '<td>';
  $row .= '<a href="done.php?car='.$car.'">'.$car.'</a>';
  $row .= '</td>' . "\r";
  return $row;
}

/* $ps stands for pickup/status, the two input types for this function */
function tblCell($cell,$ps)
{
  if ($ps=="ng")
  {
    $cellnum = '-';
  }
  else
  {
    $cellnum = '(' . substr($cell,0,3) . ') ' . substr($cell,3,3) 
      . '-' . substr($cell,6,4);
  }
	
  $row = '<td>';
  $row .= $cellnum;
  $row .= '</td>' . "\r";
  return $row;
}

/* Returns the amount of time between now and $intime.
 * Determined response time is considered short, medium, or long. 
 * Returns difference.
 */
function tblCalledIn($intime)
{
  global $gtime;
  $gmt = date_parse($gtime);
  $called = date_parse($intime);
  $hourdiff = $gmt['hour'] - $called['hour'];
  $mindiff = $gmt['minute'] - $called['minute'];
  if($hourdiff>0)
  {
    if($mindiff<0)
    {
      $hour = ($gmt['hour'] - 1) - $called['hour'];
      $min = (60 + $gmt['minute']) - $called['minute'];
    }
    else
    {
      $hour = $hourdiff;
      $min = $mindiff;
    }
  }
  else
  {
    $hour = $hourdiff;
    $min = $mindiff;
  }
		
  if($hour > 0)
  {
    $tclass = 'long';
  }
  elseif($min < 30)
  {
    $tclass = 'short';
  }
  elseif($min < 50)
  {
    $tclass = 'med';
  }
  else
  {
    /* otherwise it's just been way too long */
    $tclass = 'long';
  }
  $row = '<td><span class="' . $tclass . '">';
  if($hour==1)
  {
    $row .= $hour . ' hour ' . $min . ' min</span></td>' . "\r";
  }
  elseif($hour>1)
  {
    $row .= $hour . ' hours ' . $min . ' min</span></td>' . "\r";
  }
  else
  {
    $row .= $min . ' min</span></td>' . "\r";
  }
  return $row;
}
	

/* Returns the total wait time.
 * Determined response time is considered short, medium, or long. 
 * Returns difference.
 */
function tblTimeWait($called, $assigned, $done, $status)
{
  if ($status=="missed" || $status=="cancelled")
  {
    $calledin = date_parse($called);
    $donezed = date_parse($done);
    $hourdiff = $donezed['hour'] - $calledin['hour'];
    $mindiff = $donezed['minute'] - $calledin['minute'];
    if($hourdiff>0)
    {
      if($mindiff<0)
      {
        $hour = ($donezed['hour'] - 1) - $calledin['hour'];
        $min = (60 + $donezed['minute']) - $calledin['minute'];
      }
      else
      {
        $hour = $hourdiff;
        $min = $mindiff;
      }
    }
    else
    {
      $hour = $hourdiff;
      $min = $mindiff;
    }
  }
  else
  {
    $calledin = date_parse($called);
    $assIgned = date_parse($assigned);
    $hourdiff = $assIgned['hour'] - $calledin['hour'];
    $mindiff = $assIgned['minute'] - $calledin['minute'];
    if($hourdiff>0)
    {
      if($mindiff<0)
      {
        $hour = ($assIgned['hour'] - 1) - $calledin['hour'];
        $min = (60 + $assIgned['minute']) - $calledin['minute'];
      }
      else
      {
        $hour = $hourdiff;
        $min = $mindiff;
      }
    }
    else
    {
      $hour = $hourdiff;
      $min = $mindiff;
    }
  }
  if($hour > 0)
  {
    $tclass = 'long';
  }
  elseif($min < 30)
  {
      $tclass = 'short';
  }
  elseif($min < 50)
  {
    $tclass = 'med';
  }
  else
  {
    /* otherwise it's just been way too long */
    $tclass = 'long';
  }
  $row = '<td><span class="' . $tclass . '">';
  if($hour==1)
  {
    $row .= $hour . ' hour ' . $min . ' min</span></td>' . "\r";
  }
  elseif($hour>1)
  {
    $row .= 'Too Long</span></td>' . "\r";;
  }
  else
  {
    $row .= $min . ' min</span></td>' . "\r";
  }
  return $row;
}


/* Returns the total ride time.
 * Determined response time is considered short, medium, or long.
 * Returns difference.
 */
function tblTimeRode($assigned, $done, $status)
{
  if ($status=="missed" || $status=="cancelled")
  {
    /* well they never rode, did they? */
    $tclass = '0';
    $diff = '0';
    $row = '<td><span class="0">Cancelled</span></td>' . "\r";
    return $row;
  }
  elseif ($status =="waiting")
  {
    $tclass = '0';
    $row = '<td><span class="0">Still Waiting</span></td>' . "\r";
    return $row;
  } 
  else
  {
    $assIgned = date_parse($assigned);
    $donezed = date_parse($done);
    $hourdiff = $donezed['hour'] - $assIgned['hour'];
    $mindiff = $donezed['minute'] - $assIgned['minute'];
    if($hourdiff>0)
    {
      if($mindiff<0)
      {
        $hour = ($donezed['hour'] - 1) - $assIgned['hour'];
	$min = (60 + $donezed['minute']) - $assIgned['minute'];
      }
      else
      {
        $hour = $hourdiff;
        $min = $mindiff;
      }
    }
    else
    {
      $hour = $hourdiff;
      $min = $mindiff;
    }
  }
  if($hour > 0)
  {
    $tclass = 'long';
  }
  elseif($min < 30)
  {
    $tclass = 'short';
  }
  elseif($min < 50)
  {
    $tclass = 'med';
  }
  else
  {
    /* otherwise it's just been way too long */
    $tclass = 'long';
  }
  $row = '<td><span class="' . $tclass . '">';
  if($hour==1)
  {
    $row .= $hour . ' hour ' . $min . ' min</span></td>' . "\r";
  }
  elseif($hour>1)
  {
    $row .= 'Too Long</span></td>' . "\r";;
  }
  else
  {
    $row .= $min . ' min</span></td>' . "\r";
  }
  return $row;
}

/* Prints whether or not the patron has arrived home
 * If not, they are still intransit. 
 * If they have arrived, it states what time they were dropped off.
 */
function tblHome($tdone,$tstatus)
{
  if ($tstatus=="done")
  {
    $done = date_parse($tdone);
    if($done['hour'] < 5)
    {
      $athome = ($done['hour']+19) . ':' . $done['minute'];
    }
    else
    {
      $athome = ($done['hour']-4) . ':' . $done['minute'];
    }
  }
  else
  {
    /* null for now */
    $athome = '-';
  }
  $row = '<td>';
  $row .= $athome;
  $row .= '</td>'."\r";
  return $row;
}

/* Color the rows
 *  Odd rows are Gray
 *  Even rows are White
 */

function rowColor($i)
{
  if (fmod($i,2) == 0)
  {
    $bgCol = 'white';
  }
  else
  {
    $bgCol = 'grey';
  }
  return $bgCol;
}
