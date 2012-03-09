<?php
    $website_name = $_SERVER['HTTP_HOST'];
    $website_name = str_replace("www.", "", $website_name);

	header("Location: http://mail.google.com/a/$website_name");
?>
