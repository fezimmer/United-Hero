<?
	require_once("preheader.php");
	require_login();
	$userID 		= $_SESSION['user_id'];
	$userFirstName	= $_SESSION['user_first_name'];
	$userLastName	= $_SESSION['user_last_name'];

	$creatorInfo	= qr("SELECT fldFName, fldLName, fldEmail FROM tblUser WHERE pkUserID = $userID");
	$creatorName	= $creatorInfo['fldFName'];
	$creatorEmail	= $creatorInfo['fldEmail'];
        
	include("header.php");

?>
		<div>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2><a href="/my_account.php">My Account</a> <p style="float:right"><a href="edit-profile.php">edit my profile</a></h2>
			</div>

			<div class="page-Leftcol col">
				<div class="left-col-inner">
                    <? echo_msg_box();?>

                    <h2>Submit A Project</h2>

					<? include("proj_submission_form.inc.php");?>
				    </div>
					<!-- end inner div -->
				</div>

			<? include('rewards_sidebar.inc.php');?>
			<div style="clear:both;"> </div>

<?
	include("footer.php");
?>
