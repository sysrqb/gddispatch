<?php require('credfile.php'); ?>
<html><head>
<style type="text/css">
body {
  padding-top: 10%;
  text-align: center;
  background-color: gray;
}
</head>
<body>
</style>
<h2>Welcome to the GUARD Dogs Dispatch Site</h2>
<br />
<a href=<?php echo $CASserver . urlencode($LocalAuthValidator)?>><button type="button">Click here to login</button></a>
</body></html>
