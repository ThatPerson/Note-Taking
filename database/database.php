<?php
include("secrets.php");

//connection to the database
$dbhandle = mysql_connect($url, $usr, $pwd)
  or die("Unable to connect to MySQL");
  mysql_select_db($dbd, $dbhandle);
  ?>
