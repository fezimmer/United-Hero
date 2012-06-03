<?
	require_once("preheader.php");
        include ('admin/ajaxCRUD.class.php');
	require_login();
	$userID 		= $_SESSION['user_id'];

	$numProjects = q1("SELECT COUNT(pkProjectID) FROM tblProject WHERE fkUserID = $userID");
        $projInfo = qr("SELECT * FROM tblProject WHERE fkUserID = $userID ORDER BY fldDateCreated DESC LIMIT 1");
        
        extract($projInfo);
        $projectID = $projInfo['pkProjectID'];
        $totRewards = q1("SELECT COUNT(*) FROM tblRewards WHERE fkProjectID = \"$projectID\" AND fkPaymentID > \"0\"");
	include("header.php");

?>

			<div style="clear: both;"></div>
			<div class="search-results-info">
				<h2><a href="/my_account.php">My Account</a></h2>
			</div>
			<div class="page-Leftcol col" style="height:auto; min-height: 0px;">
                            <div class="left-col-inner" style="height:22px;">
                                <h2>Sold Rewards   ~ Your Customer Shipping List ~   <em>(<?=$totRewards?>) Rewards Sold</em></h2>
                            </div>
                        </div>

<?
            $rewards = q("SELECT * FROM tblRewards WHERE fkProjectID = \"$projectID\" AND fkPaymentID <= \"0\" ORDER BY pkRewardID");
            foreach ($rewards as $reward){
                extract($reward);
                $rTitle = $reward['fldTitle'];
                $rDesc = $reward['fldDescription'];
                $rSoldCount = q1("SELECT COUNT(*) FROM tblRewards WHERE fldTitle = \"$rTitle\" AND fkPaymentID > \"0\"");
                ?>
                <div class="page-Leftcol col" style="height:auto; min-height: 0px;">
                    <div class="left-col-inner">
                        <h3>"<?=$rTitle?>" - Purchase Price $<?=$reward['fldSupport']?><br/>
                            <span style="font-style:italic;padding:10px 0 20px 20px;">
                        <?  if($reward['fldNumAvailable'] == 0 || $reward['fldRewardsLeft'] != 0){?>

                        (<?=$rSoldCount?>) Sold

                        <?  } else {?>
                                Sold Out
                        <?  }?>
                            </span>
                        </h3>
                        <span style="padding:10px 0 20px 20px;">Delivery: <?=getMonthForReward($reward['fldRewardMonth'])?> <?=$reward['fldRewardYear']?></span>
                <?
                $showTable = q1("SELECT COUNT(*) FROM tblRewards WHERE fkPaymentID > \"0\" AND fkProjectID = \"$projectID\" AND fldTitle = \"$rTitle\" AND fldDescription = \"$rDesc\"");
                if($showTable != 0){
                    $tblRewards;
                    $tblRewards = new ajaxCRUD("Reward", "tblRewards", "pkRewardID");
                    $tblRewards->addWhereClause("WHERE fkPaymentID > \"0\" AND fkProjectID = \"$projectID\" AND fldTitle = \"$rTitle\" ORDER BY pkRewardID");
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
                    $tblRewards->displayAs("pkRewardID", "ID    ");
                    $tblRewards->displayAs("fldStreetAddress", "Street Address   ");
                    $tblRewards->displayAs("fldCity", "City");
                    $tblRewards->displayAs("fldState", "State");
                    $tblRewards->displayAs("fldZipCode", "Zip");
                    $tblRewards->displayAs("fldName", "Name");
                    $tblRewards->displayAs("fkPaymentID", "Transaction Number    ");
                    $tblRewards->defineCheckbox("fldShipped");
                    $tblRewards->displayAs("fldShipped", "Product Shipped");
                    $tblRewards->disallowAdd();
                    $tblRewards->disallowDelete();

                    $tblRewards->disallowEdit("fldStreetAddress");
                    $tblRewards->disallowEdit("fldCity");
                    $tblRewards->disallowEdit("fldState");
                    $tblRewards->disallowEdit("fldZipCode");
                    $tblRewards->disallowEdit("fldName");
                    $tblRewards->disallowEdit("fkPaymentID");


                    $tblRewards->showTable();
                }
                echo "</div></div><div style='clear: both;'></div>";
            }
?>

<?
	include("footer.php");
?>