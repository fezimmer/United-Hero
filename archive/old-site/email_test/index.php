<?

ob_start(); 
include ("includes/config.php");

echo '<form name="emailform" method="post" action="add_email.php">';
echo '<table border="0" cellspacing="1" align=center cellpadding="0" class=style8>';
echo '<tr>';
echo '<td width="152">Email(no special char) :</td>';
echo "<td colspan=2><input size=20 maxlength=\"15\" value=\"\" name=\"email\" type=\"text\"> <input size=22 value=\"@$domain_name\" disabled type=\"text\">";
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Password(6 char min):</td>';
echo '<td colspan="2"><input size=20 name="password" value="" type="password"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Retype Password:</td>';
echo '<td colspan="2"><input size=20 name="confirm" value="" type="password"></td>';
echo '</tr>';
echo '<tr>';
echo '<td> </td>';
echo '<td colspan="2"> </td>';
echo '</tr>';
echo '<tr>';
echo '<td> </td>';
echo '<td width="298">';
echo '<div align="left">';
echo '<input name="submit" type="submit" id="submit" value="Signup">';
echo '</div></td><td width="209"> </td>';
echo '</tr>';
echo '</table>';
echo '</form>';

?>