<?
	require_once("preheader.php");
	include("header.php");
        $sText = $_GET['s'];

        //start pagination code
        $itemsPerPage = 20; //max number of projects displayed per page
        $indexesPerPage = 10;  //max number of pages displayed at bottom of page (index group)

        $curPage = $_GET['pageNum'];
        if($curPage == NULL){
            $curPage = 1;
        }

        //sets statement for use in projectlist.inc.php, in the form of LIMIT startIndex, numberItems
        $limitStatement = "LIMIT " . (($itemsPerPage*$curPage)-$itemsPerPage) . " , " . $itemsPerPage;

        //fills project list searching for match within title, tags, description, creator name
        $projects = q("SELECT tblProject.pkProjectID, tblProject.fldTitle, tblProject.fldDescription, tblProject.fldTags, tblProject.fkUserID, tblProject.fldImage, tblUser.pkUserID, tblUser.fldFName, tblUser.fldLName, tblUser.fldUsername FROM tblProject INNER JOIN tblUser ON tblProject.fkUserID = tblUser.pkUserID WHERE tblProject.fldStatus = 'approved' AND (tblProject.fldTitle LIKE '%$sText%' OR tblProject.fldDescription LIKE '%$sText%' OR tblProject.fldTags LIKE '%$sText%' OR tblUser.fldFName LIKE '%$sText%' OR tblUser.fldLName LIKE '%$sText%') ORDER BY fldDateCreated $limitStatement");
        $numPages = ceil(sizeof($projects)/$itemsPerPage);//calculate number of pages rounding up to closest page

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
?>
	     <div>
			 <div style="clear: both;"></div>

				<div class="search-results-info">
					<h2>Search result for <em>"<?=$sText?>"</em>,  <?=sizeof($projects)?> Result(s)</h2>
				</div>

			  <div class="4boxLine_first_new">
				<div class="blogTitle_box blogTitle_box_new" id="postsDiv">
				  <ul>
<?
					if(sizeof($projects) != 0){
						include('projectlist_limit_4.inc.php');
					} else {
?>
						<p>
						  <br/><br/>
						  <br/><br/>
						  <br/><br/>
						  <strong>Your query produced no results...</strong>
						</p>
<?
					}
?>
				  </ul>
				</div>
				 <ul class="page page-blog align-center">
						<?if($numPages > 1) {?>
							<li class="page-left"><?if($curPage != 1){ echo "<a href=\"search_result.php?pageNum=".($curPage-1)."&s=".$sText."\" title=\"Previous Page\">Prev</a>"; }else{ echo "<span style=\"color:#999999;\">Prev</span>";}?></li>
							<?if(!$beginPages){?>
							<li class="page-page"><a href="search_result.php?pageNum=<?=($startPage - $indexesPerPage)?>&s=<?=$sText?>">...</a></li>
							<?}?>
							<? for($count = $startPage; $count < ($startPage + $indexPages); $count++){?>
							<li class="page-page<?if($curPage == $count){ echo " active";}?>"><a href="search_result.php?pageNum=<?=$count?>&s=<?=$sText?>"><?=$count?></a></li>
							<?}
							if(!$endPages){?>
							<li class="page-page"><a href="search_result.php?pageNum=<?=($startPage + $indexPages)?>&s=<?=$sText?>">...</a></li>
							<?}?>
							<li class="page-left"><?if($curPage != $numPages){ echo "<a href=\"search_result.php?pageNum=".($curPage+1)."&s=".$sText."\" title=\"Next Page\">Next</a>"; }else{ echo "<span style=\"color:#999999;\">Next</span>";}?></li>
						<?}?>
				</ul>
			 </div>
			 <div class="boxLine_featured blogTitle_box">			   </div>
		   <div style="clear: both;"></div>
		  </div>
		</div>
     <?
	include("footer.php");
?>