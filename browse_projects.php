<?
	require_once("preheader.php");
	include("header.php");

	$submitButtonHTML = "<a href=\"#?w=780\" rel=\"signupForm\" class=\"cta start-project poplight\" title=\"Submit Your Project\">Submit Your Project</a>\n";
	if(is_logged_in()){
		$submitButtonHTML = "<a href=\"my_account.php\" class=\"cta start-project\" title=\"Submit Your Project\">Submit Your Project</a>\n";
	}

        //start pagination code
        $itemsPerPage = 15; //max number of projects displayed per page
        $indexesPerPage = 10;  //max number of pages displayed at bottom of page (index group)

        $curPage = $_GET['pageNum'];
        if($curPage == NULL){
            $curPage = 1;
        }

        //sets statement for use in projectlist.inc.php, in the form of LIMIT startIndex, numberItems
        $limitStatement = "LIMIT " . (($itemsPerPage*$curPage)-$itemsPerPage) . " , " . $itemsPerPage;

        $numItems = q("SELECT pkProjectID FROM tblProject");
        $numPages = ceil(sizeof($numItems)/$itemsPerPage);//calculate number of pages rounding up to closest page

        //checks to see if multiple index groups are needed
        if($numPages > $indexesPerPage){ //multiple groups are needed

            //decides what index group a page is locaed
            if(($curPage % $indexesPerPage) == 0){ //find closest index group
                $startPage = (($curPage - $indexesPerPage) + 1);
            } else { //rounding to closest index group
                $startPage = ((floor($curPage/$indexesPerPage)*$indexesPerPage) + 1);
            }

            //logic to show weather index group has groups before/after
            //check for index group after first
            if(($numPages - $startPage) > $indexesPerPage){//has group(s) after
                $indexPages = $indexesPerPage;
                $endPages = false;

                //check for index group before
                if($startPage > $indexesPerPage){//has group(s) before
                    $beginPages = false;
                } else {
                    $beginPages = true;
                }

            } else {//has no group after
                $indexPages = ($numPages - $startPage + 1);
                $endPages = true;//at the end
                $beginPages = false;//if inside this loop, this must be false
            }

        } else { //only one group, all projects fit within '$indexesPerPage' number of pages
            $indexPages = $numPages;
            $startPage = 1;

            // no other groups
            $endPages = true;
            $beginPages = true;
        }
        //end pagination

        //get list of projects call for projectlist.inc.php
        $projects = q("SELECT pkProjectID, fldTitle, fldDescription, fldLocation, fldDesiredFundingAmount, fldVideoHTML, fldTags, fldStatus, fldActualFunding, fldDateCreated, fkUserID, fldImage FROM tblProject WHERE (fldStatus = 'approved' OR fldStatus = 'funded') ORDER BY fldDateCreated $limitStatement");
?>

			 <div style="clear: both;"></div>
			<div class="search-results-info">
				<h2>Browse Projects</h2>
			</div>
			 <div class="boxLine_first_new">
				<div class="blogTitle_box blogTitle_box_new" id="postsDiv">
				  <ul>
<?
				include('projectlist_limit.inc.php');
?>


					 <!--li class="first"> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/01.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/02.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/03.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li class="first"> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/04.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/05.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/06.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li class="first"> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/07.jpg" alt="" border="0" height="128" width="208"> </div>
						<h4>Project Title</h4>
						<p><strong>project tags, tag </strong></p>
						</a> </li>
					 <li> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
						  <h4>Project Title</h4>
						  <p><strong>project tags, tag </strong></p>
						</div>
						</a> </li>
						<li> <a href="/project_details.php">
						<div class="img-box">
						  <div class="hoverlay"> </div>
						  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
						  <h4>Project Title</h4>
						  <p><strong>project tags, tag </strong></p>
						</div>
						</a> </li>
						<li class="first"> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/01.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/02.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/03.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li class="first"> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/04.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/05.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/06.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li class="first"> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/07.jpg" alt="" border="0" height="128" width="208"> </div>
							<h4>Project Title</h4>
							<p><strong>project tags, tag </strong></p>
							</a> </li>
						 <li> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
							  <h4>Project Title</h4>
							  <p><strong>project tags, tag </strong></p>
							</div>
							</a> </li>
							<li> <a href="/project_details.php">
							<div class="img-box">
							  <div class="hoverlay"> </div>
							  <img src="images/projects/thumbs/08.jpg" alt="" border="0" height="128" width="208">
							  <h4>Project Title</h4>
							  <p><strong>project tags, tag </strong></p>
							</div>
							</a> </li-->
				  </ul>
				</div>
				<ul class="page page-blog align-center">
                                                <?if($numPages > 1) {?>
                                                    <li class="page-left"><?if($curPage != 1){ echo "<a href=\"browse_projects.php?pageNum=".($curPage-1)."\" title=\"Previous Page\">Prev</a>"; }else{ echo "<span style=\"color:#999999;\">Prev</span>";}?></li>
                                                    <?if(!$beginPages){?>
                                                    <li class="page-page"><a href="browse_projects.php?pageNum=<?=($startPage - $indexesPerPage)?>">...</a></li>
                                                    <?}?>
                                                    <? for($count = $startPage; $count < ($startPage + $indexPages); $count++){?>
                                                    <li class="page-page<?if($curPage == $count){ echo " active";}?>"><a href="browse_projects.php?pageNum=<?=$count?>"><?=$count?></a></li>
                                                    <?}
                                                    if(!$endPages){?>
                                                    <li class="page-page"><a href="browse_projects.php?pageNum=<?=($startPage + $indexPages)?>">...</a></li>
                                                    <?}?>
                                                    <li class="page-left"><?if($curPage != $numPages){ echo "<a href=\"browse_projects.php?pageNum=".($curPage+1)."\" title=\"Next Page\">Next</a>"; }else{ echo "<span style=\"color:#999999;\">Next</span>";}?></li>
                                                <?}?>
					</ul>
			 </div>
			<div class="boxLine_featured blogTitle_box">
				<div class="blocked action">
				<img src="images/start-icon.png" class="icon" />
				<div class="action-intro">
					<h2>Start your Project</h2>
					<div style="clear: both;"></div>
					<!--a href="#" class="cta start-project" title="Submit Your Project">Submit Your Project</a-->
					<?=$submitButtonHTML?>
					<div class="no-account">
					Don't have an account?<br/>
						<a href="#?w=780" rel="signupForm" class="signup poplight" title="United Hero Account Creation">Signup</a> it's easy and free!
					</div>
				</div>
				</div>

				<!--div class="blocked">
					<div style="clear:both;"> </div>
						<h2>Tags </h2>
						<div class="tag-container">
						<?
							foreach ($fullTagArray as $tag){
								echo "<a href=\"#\">$tag</a>\n";
							}
						?>
							<!--a href="#">A Tag</a> <a href="#">More Tag</a>
							<a href="#">A Tag</a> <a href="#">Another Tag</a>
							<a href="#">A Tag</a> <a href="#">test Tag</a>
							<a href="#">A Tag</a> <a href="#">Tag</a-->
							<div style="clear:both;"> </div>
						</div>
					</div>
				</div-->

				<? include('sidebar.inc.php');?>

			</div>
			 <div style="clear: both;"></div>
		  </div>
		<?
	include("footer.php");
?>