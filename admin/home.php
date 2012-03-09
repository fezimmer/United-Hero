<?
	require_once('../preheader.php');
	require_admin();
	include('header.php');
?>
    <p>&nbsp;</p>

    <p align="center">Admin Panel :: Welcome, <?=$_SESSION['user_name']?>.</p>
    <p align="center">&nbsp;</p>

<?
	include('footer.php');
?>