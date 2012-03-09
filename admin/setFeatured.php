<?
	require_once('../preheader.php');

	$id = $_REQUEST['pkProjectID'];
	$success = qr("UPDATE tblProject SET fldFeatured = 0"); //reset featured project

	$success = qr("UPDATE tblProject SET fldFeatured = 1 WHERE pkProjectID = $id");
	if ($success){
		header("Location: projects.php?id=$id&rep_msg=Project set as featured");
	}


?>