<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<head>
		<title><?=$globals['site_name']?> Admin - <?=$page_title?></title>
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<link href="stylesheet.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	</head>
	<body>

<?
		$newProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE fldStatus = 'review'");
		$liveProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE fldStatus = 'approved' AND (fldActualFunding < fldDesiredFundingAmount) AND (fldEndDate > NOW())");

		//$fundedProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE fldActualFunding >= fldDesiredFundingAmount");
		//$unfundedProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE (fldActualFunding < fldDesiredFundingAmount) AND fldStatus = \"approved\" AND (fldEndDate < NOW())");

		$fundedProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE fldStatus = \"funded\"");
		$unfundedProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE fldStatus = \"unfunded\"");

		$unapprovedProjectCount = q1("SELECT COUNT(*) FROM tblProject WHERE fldStatus = 'unapproved'");

                $rewardCount = q1("SELECT COUNT(*) FROM tblRewards WHERE fkPaymentID > \"0\" ");
?>

		<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#333333">
			<tr align="center" valign="middle">
				<td><a href="/index.php" class="menu">HOME</a></td>
				<td>&nbsp;</td>
				<td><a href="projects.php?show=new" class="menu">New</a> (<?=$newProjectCount?>)</td>
				<td><a href="projects.php?show=live" class="menu">Live</a> (<?=$liveProjectCount?>)</td>
				<td><a href="projects.php?show=funded" class="menu">Funded</a> (<?=$fundedProjectCount?>)</td>
				<td><a href="projects.php?show=unfunded" class="menu">Un-funded</a> (<?=$unfundedProjectCount?>)</td>
				<td><a href="projects.php?show=unapproved" class="menu">Unapproved</a> (<?=$unapprovedProjectCount?>)</td>
				<td><a href="projects.php?show=rewards" class="menu">Supported Rewards</a> (<?=$rewardCount?>)</td>
				<td><a href="users.php" class="menu">Project Creators</a></td>
				<td><a href="messages.php" class="menu">Messages</a></td>
				<td><a href="index.php?action=logout" class="menu">Logout</a></td>
			</tr>
		</table>
		<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
			<tr>
				<td>
					<table width="100%"  border="0" cellspacing="0" cellpadding="10">
						<tr>
							<td>