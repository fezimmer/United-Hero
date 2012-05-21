<?
	require_once('../preheader.php');
	require_admin();

	#the code for the class
	include ('ajaxCRUD.class.php');

    #this one line of code is how you implement the class
    ########################################################
    ##

    $tblProject = new ajaxCRUD("Project", "tblProject", "pkProjectID");
    
    //new class for rewards
    $tblRewards = new ajaxCRUD("Reward", "tblRewards", "pkRewardID");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....

    #define a relationship to another table
    #the first field is the fk in the table, the second is the second table, the third is the pk in the second table, and the forth is the field you want to retrieve as the dropdown value
    //$tblProject->defineRelationship("fkUserID", "tblUser", "pkUserID", "CONCAT(fldFName, ' ', fldLName)", "fldFName");
    $tblProject->formatFieldWithFunction("fkUserID", "formatUser");

    //relationship of rewards to project
    //$tblRewards->formatFieldWithFunction("fkUserID", "formatUser");


    //$tblProject->omitPrimaryKey();

	$show = $_REQUEST['show'];

    $id = $_REQUEST['id'];
    if (!$id) $id = $_REQUEST['pkProjectID'];

    if ($id != ""){
	    $where_condition = "WHERE pkProjectID = $id";
	    $tblProject->addWhereClause($where_condition);
	    //$tblProject->setOrientation("vertical");
	}

	$tblProject->emptyTableMessage = "No projects match this filtering criteria";

	//default where condition (show live/approved projects)
	$where_condition = "WHERE fldStatus = 'approved'";

    if ($show){
	    if ($show == "new"){
	    	$where_condition = "WHERE fldStatus = \"review\"";
	    	$h1 = "You are viewing all (<u>new</u>) projects not yet approved.";
	    	$tblProject->disallowEdit("fldStatus");
	    	$tblProject->addButtonToRow("Approve Project!", "approve.php");
	    	$tblProject->addButtonToRow("Unapprove Project!", "approve.php?action=unapprove");
	    	$tblProject->omitFieldCompletely("fldAdminComments");
	    }
	    if ($show == "live"){
	    	$where_condition = "WHERE fldStatus = 'approved' AND (fldActualFunding < fldDesiredFundingAmount) AND (fldEndDate > NOW())";
	    	$h1 = "You are viewing all <u>live</u> (approved) projects.";
	    	$tblProject->addButtonToRow("Set as Featured", "setFeatured.php");
	    }
	    if ($show == "funded"){
	    	//$where_condition = "WHERE fldActualFunding >= fldDesiredFundingAmount";
	    	$where_condition = "WHERE fldStatus = 'funded'";
	    	$h1 = "You are viewing all 100% <u>funded</u> projects.";
	    }
	    if ($show == "unfunded"){
	    	//projects which have not reached 100% funding and have past their time allotted
	    	//$where_condition = "WHERE (fldActualFunding < fldDesiredFundingAmount) AND fldStatus = \"approved\" AND (fldEndDate < NOW())";
	    	$where_condition = "WHERE fldStatus = 'unfunded'";
	    	$h1 = "You are viewing all <u>unfunded</u> (expired) projects.";
	    	$tblProject->addButtonToRow("Refund all donations", "return_process.php");
	    }
	    if ($show == "unapproved"){
	    	$where_condition = "WHERE fldStatus = \"unapproved\"";
	    	$h1 = "You are viewing all <u>unapproved</u> projects (not active on site).";
	    }
	}
if ($show != "rewards"){

    if ($show != 'new'){
    	//only show add form on "New Projects" page
    	$tblProject->disallowAdd();
    }

    if ($show == 'live' || $show == 'funded' || $show == 'unfunded'){
    	$tblProject->addButtonToRow("View Breakdown of Donations", "projects.php?viewBreakdown=true");
    }

	//show the 'view project' button on all pages (todo: may need to flush out better requirements on when to show this button)
	$tblProject->addButtonToRow("View Project", "../project.php");
        $tblProject->addButtonToRow("View Supported Rewards" , "projects.php?show=rewards");

	$tblProject->omitAddField("fldFeatured");
	$tblProject->omitAddField("fldActualFunding");
	$tblProject->omitAddField("fldDateCreated");
	$tblProject->omitAddField("fldApprovedDate");
	$tblProject->omitAddField("fldProjectApprover");

    $tblProject->addValueOnInsert("fldDateCreated", "NOW()");
    
        $tblProject->addWhereClause($where_condition);

	$numRows = $tblProject->getNumRows();

	//table is getting too big; let's make it vertical no matter what.
	$tblProject->setOrientation("vertical");

    $tblProject->setFileUpload("fldImage", "../project_images/", "../project_images/");
    //$tblProject->appendUploadFilename("Registration_Number");

    $tblProject->addOrderBy("ORDER BY pkProjectID DESC");
    $tblProject->setLimit(25);
    $tblProject->setCSSFile('bluedream.css');

    //$tblProject->turnOffPaging(15);
    //$tblProject->addButton("Show All Projects", "projects.php");

   	if ($numRows > 0){
   		//$tblProject->addAjaxFilterBoxAllFields();
    	$tblProject->addAjaxFilterBox("fldTitle");
    	$tblProject->addAjaxFilterBox("fldTags");
    	$tblProject->addAjaxFilterBox("fldStatus");
        }

    $tblProject->setTextareaHeight("fldDescription", 150);

	# display headers as reasonable titles
	$tblProject->displayAs("pkProjectID", "Project ID");
	$tblProject->displayAs("fldTitle", "Title");
    $tblProject->displayAs("fldDescription", "Description");
	$tblProject->displayAs("fldLocation", "Location");
	$tblProject->displayAs("fldDesiredFundingAmount", "Desired Funding $$");
	$tblProject->displayAs("fldVideoHTML", "Video URL");
	$tblProject->displayAs("fldTags", "Tags");
	$tblProject->displayAs("fldStatus", "Status");
	$tblProject->displayAs("fldActualFunding", "Actual Funding $$");
	$tblProject->displayAs("fldDateCreated", "Date Projected Created");
	$tblProject->displayAs("fldImage", "Photo");
	$tblProject->displayAs("fldFeatured", "Featured?");
	$tblProject->displayAs("fkUserID", "Creator Name");
	$tblProject->displayAs("fldEndDate", "Funding Cutoff Date/Time");

	$tblProject->displayAs("fldAdminComments", "Admin Comments");
	$tblProject->setTextareaHeight("fldAdminComments", 150);
	$tblProject->displayAs("fldApprovedDate", "Date Approved");
	$tblProject->displayAs("fldProjectApprover", "Project Approver");

	if ($show != "live"){
		$tblProject->omitFieldCompletely("fldProjectApprover");
		$tblProject->omitFieldCompletely("fldApprovedDate");
	}

	$tblProject->disallowEdit("fldFeatured");
	$tblProject->disallowEdit("fldDateCreated");
	$tblProject->disallowEdit("fldActualFunding");
	$tblProject->disallowEdit("fldApprovedDate");
	$tblProject->disallowEdit("fkUserID");
	$tblProject->disallowEdit("fldEndDate"); //per requirement on Fri 9/23/2011 9:47 AM - "We do NOT want to have the ability to change the amount of days when a project is live."

	//$tblProject->setAjaxFilterBoxSize('OwnerID',5);

    //$allowableValues = array("review", "approved", "unapproved");
    $allowableValues = array("review");
    $tblProject->defineAllowableValues("fldStatus", $allowableValues);

    $tblProject->formatFieldWithFunction("fldFeatured", "formatFeaturedField");
    $tblProject->formatFieldWithFunction("fldImage", "displayImage");
    $tblProject->formatFieldWithFunction("fldDesiredFundingAmount", "displayCurrency");
    $tblProject->formatFieldWithFunction("fldActualFunding", "displayCurrency");
    $tblProject->formatFieldWithFunction("fldDateCreated", "displaySimpleDate");
    $tblProject->formatFieldWithFunction("fldApprovedDate", "displaySimpleDate");
    $tblProject->formatFieldWithFunction("fldStatus", "displayStatus");

	$tblProject->formatFieldWithFunctionAdvanced("fldEndDate", "displayEndDate");
}
	include("header.php");

    //rewards section
    if ($show == "rewards"){
            $projectID 	= $_REQUEST['pkProjectID'];
            if($projectID != null){
                $singleRewardMsg = " AND fkProjectID = \"" . $projectID . "\"";
            }
            $reward_where_condition = "WHERE fkPaymentID > \"0\"" . $singleRewardMsg;
            $h1 = "You are viewing <u>supported rewards</u>.";

            $tblRewards->omitPrimaryKey();
            $tblRewards->addWhereClause($reward_where_condition);
            $tblRewards->displayAs("fldTitle", "Title");
            $tblRewards->displayAs("fldDescription", "Description");
            $tblRewards->displayAs("fldSupport", "Support");
            $tblRewards->displayAs("fldNumAvailable", "Number Available");
            $tblRewards->displayAs("fldRewardMonth", "Delivery Month");
            $tblRewards->displayAs("fldRewardYear", "Delivery Year");
            $tblRewards->displayAs("fkPaymentID", "Transaction Number");
            $tblRewards->displayAs("fkProjectID", "Project");
            $tblRewards->displayAs("fldStreetAddress", "Street Address");
            $tblRewards->displayAs("fldCity", "City");
            $tblRewards->displayAs("fldState", "State");
            $tblRewards->displayAs("fldZipCode", "Zip");
            $tblRewards->displayAs("fldName", "Name");
            $tblRewards->displayAs("fldConfEmail", "Email");
            $tblRewards->omitField("fldRewardsLeft");
            $tblRewards->omitField("fldImage");
            $tblRewards->omitField("fldNumAvailable");
            $tblRewards->addAjaxFilterBox("fldTitle");
            $tblRewards->addAjaxFilterBox("fldRewardMonth");
            $tblRewards->addAjaxFilterBox("fldRewardYear");
            $tblRewards->addAjaxFilterBox("fkPaymentID");
            $tblRewards->addAjaxFilterBox("fkProjectID");
            $tblRewards->addAjaxFilterBox("fldConfEmail");
            $tblRewards->defineCheckbox("fldShipped");
            $tblRewards->displayAs("fldShipped", "Product Shipped");
            $tblRewards->addAjaxFilterBox("fldShipped");
            $tblRewards->disallowAdd();
            $tblRewards->disallowDelete();
            $tblRewards->showTable();
    }

	$viewBreakdown = $_REQUEST['viewBreakdown'];
	if ($viewBreakdown){
		$tblPayment = new ajaxCRUD("Donations", "tblPayment", "pkPaymentID");
		$where_condition = "WHERE fkProjectID = $id";
		$tblPayment->addWhereClause($where_condition);
		$tblPayment->disallowDelete();
		$tblPayment->disallowAdd();
		$tblPayment->turnOffAjaxEditing();
		$tblPayment->omitField("fkProjectID");
		$tblPayment->omitField("fldOrderNum");
		$tblPayment->omitField("fldRef");
		$tblPayment->displayAs("fldDatetime", "Payment Date");
		$tblPayment->emptyTableMessage = "No donations have been made to this project.";
		$tblPayment->formatFieldWithFunction("fldAmount", "displayCurrency");
		$h1 = "Payments made to this Project";
	}

	if ($h1){
		echo "<h1>$h1</h1><br /><br />\n";
	}

	if ($viewBreakdown){
		#show payment table
		$tblPayment->showTable();
		unset($error_msg);
		unset($report_msg);
		echo "<br /><br /><hr /><br />\n";
	}

	#show project table
        if ($show != "rewards"){
            $tblProject->showTable();
        }

    include("footer.php");

    function formatFeaturedField($value){
    	if ($value == 1) return "<b>YES - THIS PROJECT IS FEATURED PROJECT</b>";
    	return "NO";
    }

    function formatUser($userID){
    	$userName = q1("SELECT CONCAT(fldFName, ' ', fldLName) as user FROM tblUser WHERE pkUserID = $userID");
    	$link = " <i><a href='users.php?id=$userID'>view/edit user</a></i>";
    	return $userName . $link;
    }

    function displayImage($value){
    	return "<img src='/magick.php/$value?resize(100)' width='100' />\n";
    }

    function displayCurrency($value){
    	return to_money($value);
    }

    function displayStatus($value){
    	if ($value == "approved"){
    		return "APPROVED!!";
    	}
		else if ($value == "review"){
			return "UNDER REVIEW";
		}
    	return $value;
    }

    function displayEndDate($value, $id){

    	if (!$value) return "Calculated after approval";
    	$timeRemaining = getTimeRemaining($id);
		$daysRemaining = $timeRemaining['days'];

		$formattedDate = makeSimpleDate($value);

		if ($daysRemaining > 0){
			if ($daysRemaining <= 10){
				$daysRemainingText = " <span style='font-size: 14px; color: red;'>($daysRemaining days remaining)</span>";
			}
			else{
				$daysRemainingText = " ($daysRemaining days remaining)";
			}
			return $formattedDate . $daysRemainingText;
		}
		else{
			return "$formattedDate (<b style='color: red;'>EXPIRED</b>)";
		}
	}

	function displaySimpleDate($value){
		return makeSimpleDate($value);
	}

?>