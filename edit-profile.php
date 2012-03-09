<?
	require_once("preheader.php");
	require_login();
	$userID = $_SESSION['user_id'];
	$userFirstName = $_SESSION['user_first_name'];
	$userLastName = $_SESSION['user_last_name'];

	$action = $_REQUEST['action'];
	if ($action == 'updateProfile'){
		extract($_POST);
		$success = qr("UPDATE tblUser SET
						fldEmail 	= \"$fldEmail\",
						fldPhone	= \"$fldPhone\",
						fldAddress 	= \"$fldAddress\",
						fldCity 	= \"$fldCity\",
						fldState 	= \"$fldState\",
						fldZip		= \"$fldZip\"
						WHERE pkUserID = $userID;");

		if ($success){
			$report_msg[] = "Your profile was successfully updated.";
		}
		else{
			$error_msg[] = "There was an error updating your profile. Did you change anything?";
		}

		if ($fldPassword != "" && $fldPassword2 != "" && ($fldPassword == $fldPassword2)){
			$success = qr("UPDATE tblUser SET fldPassword = \"$fldPassword\" WHERE pkUserID = $userID");
			if ($success){
				$report_msg[] = "Your password was successfully changed.";
			}
		}

	}


	$accountInfo = qr("SELECT * FROM tblUser WHERE pkUserID = $userID");
	extract($accountInfo);

	include("header.php");

?>
<style>

legend {
	font-size: 18px; color: #29A6CE;
}
</style>
		<div>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2><a href="/my_account.php">My Account</a></h2>
			</div>

			<div class="page-Leftcol col">
				<div class="left-col-inner">
                    <? echo_msg_box();?>

                    <h2>Edit My Profile</h2>

					<form id="submitForm" name="submitForm" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

						<fieldset>
							<legend>Personal Information</legend>

							<p><b>First Name</b><br />
							<input name="first name" type="text" size="35" maxlength="35" readonly value="<?=$userFirstName?>" /> <i>(readonly)</i>
							</p>

							<p><b>Last Name</b><br />
							<input name="last name" type="text" size="35" maxlength="35" readonly value="<?=$userLastName?>" /> <i>(readonly)</i>
							</p>

							<p><b>Phone</b><br />
							<input name="fldPhone" type="text" size="15" maxlength="20" value="<?=$fldPhone?>"/>
							</p>

							<p><b>Address</b><br />
							<input name="fldAddress" type="text" size="45" value="<?=$fldAddress?>"/>
							</p>

							<p><b>City</b><br />
							<input name="fldCity" type="text" size="15" maxlength="18" value="<?=$fldCity?>"/>
							</p>

							<p><b>State</b><br />
							<select name="fldState">
								<? displayStates($fldState);?>
							</select>
							</p>

							<p><b>Zip</b><br />
							<input name="fldZip" type="text" size="15" maxlength="18" value="<?=$fldZip?>"/>
							</p>

						</fieldset>
						<fieldset>
							<legend>Account Information</legend>

							<p><b>Your Email Address</b><br />
							<input name="fldEmail" type="text" size="35" maxlength="35" value="<?=$fldEmail?>" />
							</p>

							<p><b>Update Your Password</b><br />
							<p>If you do <b>not</b> want to change your password, keep the below blank.</p>

							<p>Password: <input name="fldPassword" type="password" size="32" maxlength="25"  /><br />
							Enter Password Again: <input name="fldPassword2" type="password" size="25" maxlength="25"  />
							</p>
						</fieldset>

						<input type="hidden" name="action" value="updateProfile" />
						<p><button class="button button-blue btn-close" style="font-size: 14px;" onClick="getElementById('submitForm').submit();">Edit Profile</button></p>
					 </form>

				    </div>
					<!-- end inner div -->
				</div>

			<? include('sidebar.inc.php');?>
			<div style="clear:both;"> </div>

<?
	include("footer.php");
?>