<?php 

$hostname='localhost';
$username='unitedh2_admin';
$password='360360';
$dbname='unitedh2_unitedhero';

$connection = mysql_connect($hostname, $username,$password) or die ('Unable to connect!'); 
mysql_select_db($dbname) or die ('Unable to select database!');


?>