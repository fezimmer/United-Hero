<?php

$MYSQL_HOST = "localhost";

if ($_SERVER['SERVER_NAME'] == "localhost"){
    $MYSQL_LOGIN = "root";
    $MYSQL_PASS = "1732";
}
else{
    $MYSQL_LOGIN = "united_admin";
    $MYSQL_PASS = "17321732";
}

$MYSQL_DB = "united_db";

$db = mysql_connect($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASS);

if(!$db){
	echo('<td><font class=content2>Unable to connect to db' . mysql_error());
	exit;
}

mysql_select_db($MYSQL_DB);

function q($q, $debug = 0){
	$r = mysql_query($q);
	if(mysql_error()){

			echo mysql_error();
			echo "$q<br>";

	}

	if($debug == 1)
		echo "<br>$q<br>";

	if(stristr(substr($q,0,8),"delete") ||	stristr(substr($q,0,8),"insert") || stristr(substr($q,0,8),"update")){
        if(mysql_affected_rows() > 0)
            return true;
        else
            return false;
	}
	if(mysql_num_rows($r) > 1){
		while($row = mysql_fetch_array($r)){
			$results[] = $row;
		}
	}
	else if(mysql_num_rows($r) == 1){
		$results = array();
		$results[] = mysql_fetch_array($r);
	}

	else
		$results = array();

	return $results;
}

function q1($q, $debug = 0){
	$r = mysql_query($q);
	if(mysql_error()){
		echo mysql_error();
		echo "<br>$q<br>";
	}

	if($debug == 1)
		echo "<br>$q<br>";
	$row = @mysql_fetch_array($r);

	if(count($row) == 2)
		return $row[0];
	else
		return $row;
}

function qr($q, $debug = 0){
	$r = mysql_query($q);
	if(mysql_error()){
		echo mysql_error();
		echo "<br>$q<br>";
	}

	if($debug == 1)
		echo "<br>$q<br>";

	if(stristr(substr($q,0,8),"delete") ||	stristr(substr($q,0,8),"insert") || stristr(substr($q,0,8),"update")){
		if(mysql_affected_rows() > 0)
			return true;
		else
			return false;
	}

    $results = array();

    $array_results = mysql_fetch_array($r);

    if (count($array_results) == 0) return array();

    $results[] = $array_results;
    $results = $results[0];


	return $results;
}
?>