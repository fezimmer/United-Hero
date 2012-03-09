<?php 
$from = $_GET['from'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<style type="text/css">
body {
	background: url(../images/background.png) repeat-x #A1C1E0;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #666;
}
div#login-content {
	width:300px;
	height:200px;
	background:#FFF;
	margin:auto;
	margin-top:100px;
	padding:20px;
}
div#login-content input {
	width:291px;
	font-size:18px;
	padding:3px;
}
input.submit {
width:120px;
}
</style>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>The Tire Experience Admin</title>

</head>
<body>
<div>
    <div id="login-content">
        <form method="POST" action="_lib/login.php">
        <h3>Username:</h3> 
        <input type="text" name="username" class="forms">
        <h3>Password:</h3> 
        <input type="password" name="password" class="forms">
        <br /><br />
        <input type="submit" value="Submit" name="login" class="submit" style="width:120px;">
        <input name="from" type="hidden" value="<? echo $from; ?>" />
        </form>
    </div>
</div>
</body>
</html>