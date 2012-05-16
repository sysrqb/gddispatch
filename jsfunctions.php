<?php
<script type="text/javascript">
function loginPrompt(ID, visibility) {
  if (document.layers){
    document.layers[ID].visibility = visibility ? "show" : "hide";
  }
  else if (document.getElementById) {
    (document.getElementById(ID)).style.visbility = visibility ? "show" : "hide";
  }
}
