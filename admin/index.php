<?php
	$query = $_SERVER['QUERY_STRING'];
	header("Location: login.php?$query");
?>