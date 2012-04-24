<?
	require_once("preheader.php");
        include ('admin/ajaxCRUD.class.php');
	require_login();
	$userID 		= $_SESSION['user_id'];
	$userFirstName	= $_SESSION['user_first_name'];
	$userLastName	= $_SESSION['user_last_name'];

	$numProjects = q1("SELECT COUNT(pkProjectID) FROM tblProject WHERE fkUserID = $userID");
        $projInfo = qr("SELECT * FROM tblProject WHERE fkUserID = $userID ORDER BY fldDateCreated DESC LIMIT 1");
        extract($projInfo);
        $projectID = $projInfo['pkProjectID'];
	include("header.php");

?>
<div>
			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2><a href="/my_account_test.php">My Account</a></h2>
			</div>
			<div class="page-Leftcol col" style="height:auto; min-height: 0px;">
				<div class="left-col-inner" style="height:22px;">
                                    <h2>Sold Rewards</h2>
                                </div>
                        </div>

<?
            $rewards = q("SELECT * FROM tblRewards WHERE fkProjectID = \"$projectID\" AND fkPaymentID <= \"0\" ORDER BY pkRewardID");
            foreach ($rewards as $reward){
                $tblRewards = null;
                extract($reward);
                $rTitle = $reward['fldTitle'];
                ?>
                <div class="page-Leftcol col" style="height:auto; min-height: 0px;">
                    <div class="left-col-inner">
                        <h3><?=$rTitle?>
                        <?  if($reward['fldNumAvailable'] != 0){?>
                                
                        (<?=$reward['fldRewardsLeft']?> left)</h3>

                        <?  } else { echo "</h3>"; }?>
                        <span style="padding:10px 0 20px 20px;">Delivery: <?=getMonthForReward($reward['fldDeliveryMonth'])?> <?=$reward['fldRewardYear']?></span>
                <?
                $tblRewards = new ajaxCRUD("Reward", "tblRewards", "pkRewardID");
                $reward_where_condition = "WHERE fkPaymentID > \"0\" AND fkProjectID = \"$projectID\" AND fldTitle = \"$rTitle\"";
                $tblRewards->addWhereClause($reward_where_condition);
                $tblRewards->omitPrimaryKey();
                $tblRewards->omitField("fldImage");
                $tblRewards->omitField("fkProjectID");
                $tblRewards->omitField("fldSupport");
                $tblRewards->omitField("fldNumAvailable");
                $tblRewards->omitField("fldConfEmail");
                $tblRewards->omitField("fldTitle");
                $tblRewards->omitField("fldDescription");
                $tblRewards->omitField("fldRewardMonth");
                $tblRewards->omitField("fldRewardYear");
                $tblRewards->omitField("fldRewardsLeft");
                $tblRewards->displayAs("fldStreetAddress", "Street Address");
                $tblRewards->displayAs("fldCity", "City");
                $tblRewards->displayAs("fldState", "State");
                $tblRewards->displayAs("fldZipCode", "Zip");
                $tblRewards->displayAs("fldName", "Name");
                $tblRewards->displayAs("fkPaymentID", "Payment Identifier");
                $tblRewards->disallowAdd();
                $tblRewards->disallowDelete();
                $showTable = q1("SELECT COUNT(*) FROM tblRewards WHERE fkPaymentID > \"0\" AND fkProjectID = \"$projectID\" AND fldTitle = \"$rTitle\"");
                if($showTable != 0)
                    $tblRewards->showTable();
                echo "</div></div><br/><br/>";
            }
?>

				    
<?
	include("footer.php");
?>