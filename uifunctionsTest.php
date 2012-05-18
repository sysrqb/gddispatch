<?php

require("global.php");
require("uifunctions.php");
date_default_timezone_set('America/New_York');

$expect = '<td class="btn">';
$expect .= '<button class="assign" onClick="highlight(\'row0'; 
$expect .= '\');assignride(\'assign.php?num=0\',\'assign0';
$expect .= '\')">Assign</button>';
$expect .= '</td>'."\r";
$returned = tblBtnAssign("0");
if ($returned != $expect)
{
  echo 'FAILED: tblBtnAssign' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblBtnAssign\n";
}


$expect = '<td class="btn">';
$expect .= '<button class="split" onClick="highlight(\'row0';
$expect .= '\');assignride(\'split.php?num=0\',\'assign0';
$expect .= '\')">Split</button>';
$expect .= '</td>'."\r";
$returned = tblBtnSplit("0");
if ($returned != $expect)
{
  echo 'FAILED: tblBtnSplit' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblBtnSplit\n";
}

$expect = '<td class="btn">';
$expect .= '<button class="edit" onClick="window.location=\'edit.php?pg=0'; 
$expect .= '&num=1\'">Edit</button>';
$expect .= '</td>' . "\r";
$returned = tblBtnEdit("1", "0");
if ($returned != $expect)
{
  echo 'FAILED: tblBtnEdit' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblBtnEdit\n";
}

$expect = '<td class="btn">';
$expect .= '<button class="done" onClick="window.location=\'actions.php?num=0'; 
$expect .= '&action=done\'">Done</button>';
$expect .= '</td>'."\r";
$returned = tblBtnDone("0");
if ($returned != $expect)
{
  echo 'FAILED: tblBtnDone' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblBtnDone\n";
}

$expect = '<td class="btn">';
$expect .= '<button class="cancel" onClick="window.location=\'actions.php?num=0';
$expect .= '&action=cancel\'">Cancel</button>';
$expect .= '</td>'."\r";
$returned = tblBtnCancel("0");
if ($returned != $expect)
{
  echo 'FAILED: tblBtnCancel' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblBtnCancel\n";
}

$expect = '<td class="btn">';
$expect .= '<button class="undo" onClick="window.location=\'actions.php?num=0'; 
$expect .= '&action=undo\'">Undo</button>';
$expect .= '</td>'."\r";
$returned = tblBtnUndo("0");
if ($returned != $expect)
{
  echo 'FAILED: tblBtnUndo' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblBtnUndo\n";
}

$expect = '<td>';
$expect .= "Kevin";
$expect .= '</td>' . "\r";
$returned = tblRideInfo("Kevin");
if ($returned != $expect)
{
  echo 'FAILED: tblRideInfo' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblRideInfo\n";
}

$expect = '<td>';
$expect .= '<a href="done.php?car=0">0</a>';
$expect .= '</td>' . "\r";
$returned = tblDoneCar("0");
if ($returned != $expect)
{
  echo 'FAILED: tblDoneCar' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblDoneCar\n";
}


$expect = '<td>(555) 555-5555</td>' . "\r";
$returned = tblCell("5555555555", "");
if ($returned != $expect)
{
  echo 'FAILED: tblCell' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblCell\n";
}

$expect = '<td>-</td>' . "\r";
$returned = tblCell("5555555555", "ng");
if ($returned != $expect)
{
  echo 'FAILED: tblCell' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblCell\n";
}

$expect = '<td>-</td>' . "\r";
$returned = tblCell("-", "ng");
if ($returned != $expect)
{
  echo 'FAILED: tblCell' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblCell\n";
}

$expect = '<td><span class="long">1 hour 1 min</span></td>' . "\r";
$returned = tblCalledIn(gmdate('Y-m-d H:i:s', time() - 61*60));
if ($returned != $expect)
{
  echo 'FAILED: tblCalledIn' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblCalledIn\n";
}

$expect = '<td><span class="med">36 min</span></td>' . "\r";
$returned = tblCalledIn(gmdate('Y-m-d H:i:s', time() - 36*60));
if ($returned != $expect)
{
  echo 'FAILED: tblCalledIn' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblCalledIn\n";
}

$expect = '<td><span class="short">6 min</span></td>' . "\r";
$returned = tblCalledIn(gmdate('Y-m-d H:i:s', time() - 6*60));
if ($returned != $expect)
{
  echo 'FAILED: tblCalledIn' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblCalledIn\n";
}

$expect = '<td><span class="short">2 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 25*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="med">32 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 55*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="long">1 hour 37 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 120*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="long">Too Long</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 200*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="med">45 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 45*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "missed");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="long">1 hour 5 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 65*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "missed");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="long">Too Long</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 205*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "missed");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="med">45 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 45*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "cancelled");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="long">1 hour 5 min</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 65*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "cancelled");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="long">Too Long</span></td>' . "\r";
$returned = tblTimeWait(gmdate('Y-m-d H:i:s', time() - 205*60), 
    gmdate('Y-m-d H:i:s', time() - 23*60),
    gmdate('Y-m-d H:i:s', time()),
    "cancelled");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeWait' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeWait\n";
}

$expect = '<td><span class="short">10 min</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 10*60), 
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td><span class="med">31 min</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 31*60), 
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td><span class="long">1 hour 1 min</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 61*60), 
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td><span class="long">Too Long</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 121*60), 
    gmdate('Y-m-d H:i:s', time()),
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td><span class="0">Still Waiting</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 121*60), 
    NULL,
    "waiting");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td><span class="0">Cancelled</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 121*60), 
    gmdate('Y-m-d H:i:s', time()),
    "missed");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td><span class="0">Cancelled</span></td>' . "\r";
$returned = tblTimeRode(gmdate('Y-m-d H:i:s', time() - 121*60), 
    gmdate('Y-m-d H:i:s', time()),
    "cancelled");
if ($returned != $expect)
{
  echo 'FAILED: tblTimeRode' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblTimeRode\n";
}

$expect = '<td>' . date('H:i') . '</td>' . "\r";
$returned = tblHome(gmdate('Y-m-d H:i:s'), 
    "done");
if ($returned != $expect)
{
  echo 'FAILED: tblHome' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblHome\n";
}

$expect = '<td>-</td>' . "\r";
$returned = tblHome(gmdate('Y-m-d H:i:s'), 
    "cancelled");
if ($returned != $expect)
{
  echo 'FAILED: tblHome' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: tblHome\n";
}


$expect = 'white';
$returned = rowColor(0);
if ($returned != $expect)
{
  echo 'FAILED: rowColor' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: rowColor\n";
}

$expect = 'grey';
$returned = rowColor(1);
if ($returned != $expect)
{
  echo 'FAILED: rowColor' . "\n";
  echo 'Received: ' . $returned . "\n";
  echo 'Expected: ' . $expect . "\n";
}
else
{
  echo "SUCCESS: rowColor\n";
}
