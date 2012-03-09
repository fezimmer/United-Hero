<?
	require_once('../preheader.php');

	#the code for the class
	include ('ajaxCRUD.class.php');

    #this one line of code is how you implement the class
    ########################################################
    ##

    $tblProject = new ajaxCRUD("Project", "tblProject", "pkProjectID");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....

    #define a relationship to another table
    #the first field is the fk in the table, the second is the second table, the third is the pk in the second table, and the forth is the field you want to retrieve as the dropdown value
    $tblProject->defineRelationship("fkUserID", "tblUser", "pkUserID", "CONCAT(fldFName, ' ', fldLName)", "fldFName");

    //$tblProject->omitPrimaryKey();

    $id = $_REQUEST['id'];
    if ($id != ""){
	    $where_condition = "WHERE pkProjectID = $id ";
	    $tblProject->addWhereClause($where_condition);
	    $tblProject->setOrientation("vertical");
	}

    $tblProject->setFileUpload("fldImage", "../project_images/", "../project_images/");
    //$tblProject->appendUploadFilename("Registration_Number");

    $where_condition = "WHERE  DATEDIFF(  `fldEndDate` , SYSDATE( ) ) <0 ";
    $tblProject->addWhereClause($where_condition);

    $tblProject->addOrderBy("ORDER BY pkProjectID DESC");
    $tblProject->setLimit(25);
    $tblProject->setCSSFile('bluedream.css');

    $tblProject->addButtonToRow("Return donations", "return_process.php");
    //$tblProject->turnOffPaging(15);
    $tblProject->addButton("Show Table View", "projects.php");

   	//$tblProject->addAjaxFilterBoxAllFields();
    $tblProject->addAjaxFilterBox("fldTitle");
    $tblProject->addAjaxFilterBox("fldTags");
    $tblProject->addAjaxFilterBox("fldStatus");

	# display headers as reasonable titles
	$tblProject->displayAs("pkProjectID", "id");
	$tblProject->displayAs("fldTitle", "Title");
    $tblProject->displayAs("fldDescription", "Description");
	$tblProject->displayAs("fldLocation", "Location");
	$tblProject->displayAs("fldDesiredFundingAmount", "Desired $$");
	$tblProject->displayAs("fldVideoHTML", "Video URL");
	$tblProject->displayAs("fldTags", "Tags");
	$tblProject->displayAs("fldStatus", "Status");
	$tblProject->displayAs("fldActualFunding", "Actual $$");
	$tblProject->displayAs("fldDateCreated", "Created");
	$tblProject->displayAs("fldImage", "Photo");
	$tblProject->displayAs("fldFeatured", "Featured?");
	$tblProject->displayAs("fkUserID", "Submittor");

	$tblProject->disallowEdit("fldFeatured");
	$tblProject->omitAddField("fldFeatured");

	//$tblProject->setAjaxFilterBoxSize('OwnerID',5);

    $allowableValues = array("review", "approved");
    $tblProject->defineAllowableValues("fldStatus", $allowableValues);

    $tblProject->formatFieldWithFunction("fldFeatured", "formatFeaturedField");

	#actually show to the table
    $tblProject->showTable();

    function formatFeaturedField($value){
    	if ($value == 1) return "YES";
    	return "";
    }


?>
