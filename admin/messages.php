<?
	require_once('../preheader.php');
	require_admin();

	#the code for the class
	include ('ajaxCRUD.class.php');

    #this one line of code is how you implement the class
    ########################################################
    ##

    $tblMessage = new ajaxCRUD("Message", "tblMessage", "pkMessageID");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....

    #define a relationship to another table
    #the first field is the fk in the table, the second is the second table, the third is the pk in the second table, and the forth is the field you want to retrieve as the dropdown value
    //$tblMessage->defineRelationship("fkUserID", "tblMessage", "pkUserID", "CONCAT(fldFName, ' ', fldLName)", "fldFName");

    $tblMessage->omitPrimaryKey();

    $id = $_REQUEST['id'];

    if ($id != ""){
	    $where_condition = "WHERE pkMessageID = $id";
	    $tblMessage->addWhereClause($where_condition);
	    $tblMessage->setOrientation("vertical");
	}

	$tblMessage->emptyTableMessage = "No messages.";
	$tblMessage->disallowAdd();
	//$tblMessage->disallowDelete();
	//$tblMessage->omitAddField("fldProjectApprover");
	//$tblMessage ->omitFieldCompletely("fldType");

    //$tblMessage->addValueOnInsert("fldDateCreated", "NOW()");
    //$tblMessage->addWhereClause("WHERE fldActive = 1");

	//$numRows = $tblMessage->getNumRows();

    //$tblMessage->setFileUpload("fldImage", "../project_images/", "../project_images/");

    $tblMessage->addOrderBy("ORDER BY fldDateSubmitted DESC");
    $tblMessage->setLimit(50);
    $tblMessage->setCSSFile('bluedream.css');

    //$tblMessage->turnOffPaging(15);
    //$tblMessage->addButton("Show All Projects", "projects.php");

   	if ($numRows > 0){
   		//$tblMessage->addAjaxFilterBoxAllFields();
    	//$tblMessage->addAjaxFilterBox("fldLName");
    }

    $tblMessage->setTextareaHeight("fldMessage", 150);

	# display headers as reasonable titles
	$tblMessage->displayAs("pkMessageID", "id");
	$tblMessage->displayAs("fldName", "Name");
    $tblMessage->displayAs("fldEmail", "Email");
	$tblMessage->displayAs("fldMessage", "Message");
	$tblMessage->displayAs("fldIPAddress", "IP Address");
	$tblMessage->displayAs("fldDateSubmitted", "Date Submitted");

	//$tblMessage->disallowEdit("fldSignupDate");
	//$tblMessage->disallowEdit("fldLastLogin");

	//$tblMessage->setAjaxFilterBoxSize('OwnerID',5);

    //$allowableValues = array("review", "approved", "unapproved");
    //$tblMessage->defineAllowableValues("fldStatus", $allowableValues);

	//$tblMessage->formatFieldWithFunctionAdvanced("fldEndDate", "displayEndDate");

	include("header.php");

	#actually show to the table
    $tblMessage->showTable();

    include("footer.php");

?>