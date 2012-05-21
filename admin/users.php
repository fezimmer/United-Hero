<?
	require_once('../preheader.php');
	require_admin();

	#the code for the class
	include ('ajaxCRUD.class.php');

    #this one line of code is how you implement the class
    ########################################################
    ##

    $tblUser = new ajaxCRUD("User", "tblUser", "pkUserID");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....

    #define a relationship to another table
    #the first field is the fk in the table, the second is the second table, the third is the pk in the second table, and the forth is the field you want to retrieve as the dropdown value
    //$tblUser->defineRelationship("fkUserID", "tblUser", "pkUserID", "CONCAT(fldFName, ' ', fldLName)", "fldFName");

    $tblUser->omitPrimaryKey();

    $id = $_REQUEST['id'];

    if ($id != ""){
	    $where_condition = "WHERE pkUserID = $id";
	    $tblUser->addWhereClause($where_condition);
	    $tblUser->setOrientation("vertical");
	}

	//$tblUser->emptyTableMessage = "No projects match this filtering criteria";
	$tblUser->disallowAdd();
	$tblUser->disallowDelete();
	//$tblUser->omitAddField("fldProjectApprover");
	$tblUser ->omitFieldCompletely("fldUsername");
	$tblUser ->omitFieldCompletely("fldPassword");
	$tblUser ->omitFieldCompletely("fldIPAddress");
	$tblUser ->omitFieldCompletely("fldActive");
	//$tblUser ->omitFieldCompletely("fldType");

    //$tblUser->addValueOnInsert("fldDateCreated", "NOW()");
    $tblUser->addWhereClause("WHERE fldActive = 1");



	//table is getting too big; let's make it vertical no matter what.
	//$tblUser->setOrientation("vertical");
    //$tblUser->setFileUpload("fldImage", "../project_images/", "../project_images/");

    $tblUser->addOrderBy("ORDER BY fldLName ASC");
    $tblUser->setLimit(50);
    $tblUser->setCSSFile('bluedream.css');

    //$tblUser->turnOffPaging(15);
    //$tblUser->addButton("Show All Projects", "projects.php");

   	$numRows = $tblUser->getNumRows();
   	if ($numRows > 0){
   		//$tblUser->addAjaxFilterBoxAllFields();
    	$tblUser->addAjaxFilterBox("fldFName");
    	$tblUser->addAjaxFilterBox("fldLName");
    	$tblUser->addAjaxFilterBox("fldEmail");
    }

    $tblUser->setTextareaHeight("fldDescription", 150);

	# display headers as reasonable titles
	$tblUser->displayAs("pkUserID", "id");
	$tblUser->displayAs("fldFName", "First");
    $tblUser->displayAs("fldLName", "Last");
	$tblUser->displayAs("fldEmail", "Email");
	$tblUser->displayAs("fldPhone", "Phone");

	$tblUser->displayAs("fldAddress", "Address");
	$tblUser->displayAs("fldCity", "City");
	$tblUser->displayAs("fldState", "State");
	$tblUser->displayAs("fldZip", "Zip");
	$tblUser->displayAs("fldType", "User Type");
	$tblUser->displayAs("fldSignupDate", "Signup Date");
	$tblUser->displayAs("fldLastLogin", "Last Login");

	//$tblUser->disallowEdit("fldType");
	$allowableValues = array("user", "admin");
	$tblUser->defineAllowableValues("fldType", $allowableValues);

	$tblUser->disallowEdit("fldSignupDate");
	$tblUser->disallowEdit("fldLastLogin");

	//$tblUser->setAjaxFilterBoxSize('OwnerID',5);

	$tblUser->formatFieldWithFunctionAdvanced("fldType", "displayUserType");
	$tblUser->formatFieldWithFunction("fldSignupDate", "displaySimpleDate");
	$tblUser->formatFieldWithFunction("fldLastLogin", "displaySimpleDate");

	//$allowableValues = array("review", "approved", "unapproved");
	$states = array(
				array("AL","Alabama"),
				array("AK","Alaska"),
				array("AZ","Arizona"),
				array("AR","Arkansas"),
				array("CA","California"),
				array("CO","Colorado"),
				array("CT","Connecticut"),
				array("DE","Delaware"),
				array("DC","District Of Columbia"),
				array("FL","Florida"),
				array("GA","Georgia"),
				array("HI","Hawaii"),
				array("ID","Idaho"),
				array("IL","Illinois"),
				array("IN","Indiana"),
				array("IA","Iowa"),
				array("KS","Kansas"),
				array("KY","Kentucky"),
				array("LA","Louisiana"),
				array("ME","Maine"),
				array("MD","Maryland"),
				array("MA","Massachusetts"),
				array("MI","Michigan"),
				array("MN","Minnesota"),
				array("MS","Mississippi"),
				array("MO","Missouri"),
				array("MT","Montana"),
				array("NE","Nebraska"),
				array("NV","Nevada"),
				array("NH","New Hampshire"),
				array("NJ","New Jersey"),
				array("NM","New Mexico"),
				array("NY","New York"),
				array("NC","North Carolina"),
				array("ND","North Dakota"),
				array("OH","Ohio"),
				array("OK","Oklahoma"),
				array("OR","Oregon"),
				array("PA","Pennsylvania"),
				array("RI","Rhode Island"),
				array("SC","South Carolina"),
				array("SD","South Dakota"),
				array("TN","Tennessee"),
				array("TX","Texas"),
				array("UT","Utah"),
				array("VT","Vermont"),
				array("VA","Virginia"),
				array("WA","Washington"),
				array("WV","West Virginia"),
				array("WI","Wisconsin"),
				array("WY","Wyoming")
				);

	$tblUser->defineAllowableValues("fldState", $states);

	include("header.php");

	#actually show to the table
    $tblUser->showTable();

    include("footer.php");

    function displayUserType($data){
    	if ($data == 'admin'){
    		return "This user is a <b><i>UH ADMIN</i></b>";
    	}
    	return "";
    }

	function displaySimpleDate($value){
		if ($value){
			return makeSimpleDate($value);
		}
		else{
			return "N/A";
		}
	}

?>