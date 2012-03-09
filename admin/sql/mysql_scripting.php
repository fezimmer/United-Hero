<?php
// FILL THESE IN WITH YOUR SERVERS DETAILS
//$MYSQL_HOST = "localhost";
//$MYSQL_LOGIN = "loudcanv_db";
//$MYSQL_PASS = "!huI9j9@";
//$MYSQL_DB = "loudcanv_db";

//mysql_connect($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASS);
include_once('../../preheader.php');

?>
<html>
<head><title>MySQL Command Line</title></head>
<body onLoad="document.forms[0].elements['query'].focus()">
<?php
if (isset($_POST['submitquery'])) {
        if (get_magic_quotes_gpc()) $_POST['query'] = stripslashes($_POST['query']);
        echo('<p><b>Query:</b><br />'.nl2br($_POST['query']).'</p>');
	mysql_select_db($_POST['db']);
        $result = mysql_query($_POST['query']);
        if ($result) {
                if (@mysql_num_rows($result)) {
                        ?>
                        <p><b>Result Set:</b></p>
                        <table border="1">
                        <thead>
                        <tr>
                        <?php
                        for ($i=0;$i<mysql_num_fields($result);$i++) {
                                echo('<th>'.mysql_field_name($result,$i).'</th>');
                        }
                        ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysql_fetch_row($result)) {
                                echo('<tr>');
                                for ($i=0;$i<mysql_num_fields($result);$i++) {
                                        echo('<td>'.$row[$i].'</td>');
                                }
                                echo('</tr>');
                        }
                        ?>
                        </tbody>
                        </table>
                        <?php
                } else {
                        echo('<p><b>Query OK:</b> '.mysql_affected_rows().' rows affected.</p>');
                }
        } else {
                echo('<p><b>Query Failed:</b> '.mysql_error().'</p>');
        }
        echo('<hr />');
}
?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<p>Target Database:
<select name="db">
<?php
$dbs = mysql_list_dbs();
for ($i=0;$i<mysql_num_rows($dbs);$i++) {
        $dbname = mysql_db_name($dbs,$i);
        if ($dbname == $_POST['db'])
                echo("<option selected>$dbname</option>");
        else
                echo("<option>$dbname</option>");
}
?>
</select>
</p>
<p>SQL Query:<br />
<textarea onFocus="this.select()" cols="60" rows="5" name="query">
<?=htmlspecialchars($_POST['query'])?>
</textarea>
</p>
<p><input type="submit" name="submitquery" value="Submit Query (Alt-S)" accesskey="S" /></p>
</form>
</body>
</html>
