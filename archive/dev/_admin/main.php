<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$('div#accounts').hide();
	
	$('a.view-accounts').click(function() {
		$('div#accounts').fadeIn();
	});
	
	$('a.delete-account').click(function() {
		
		var account_id = $(this).attr('id');
		
		alert(account_id);
		
		var data_string = '?id='+account_id;
		var theURL = 'delete_account.php'+data_string;
		
		//alert(theURL);
		
		$.ajax({
		
		url: theURL,
		type: 'GET',
		success: function(work){
					
					
					//$('#form-container').empty().animate({ opacity: 1.0 }, 100).html(work);
					
				}
		});	// end ajax
		
	});

});
</script>

<style type="text/css">
body {
	background: url(../images/background.png) repeat-x #A1C1E0;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #666;
}
div#content_container {
	width:800px;
	min-height:200px;
	background:#FFF;
	margin:auto;
	margin-top:30px;
	padding:20px;
}
a.view-accounts {

	padding:4px 12px 4px 12px;
	background:#A1C1E0;
	color:#FFF;
	text-decoration:none;
}
a.view-accounts:hover {
	background:#999;
}
div#accounts {
	margin-top:30px;
}
div#accounts ul {
	list-style:none;
	margin:0px;
	padding:0px;
	margin-top:15px;
}

div#accounts ul li {
	padding:8px;
	background:#e8e8e8;
	font-size:14px;
	position:relative;
	margin-top:2px;
	
}
div#accounts ul li span {
	color:#FFF;
}
 
 div#titles {
 height:20px;
 border-bottom:dotted 1px #CCC;
}

 div#titles div {
float:left;
font-weight:bold;
}

div.firstname {
 	width:150px;
}
div.email {
 	width:200px;
}
div.age {
 	width:33px;
}
div#actions {
 	width:110px;
	position:absolute;
	right:8px;
	top:8px;
}

</style>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>United Hero Admin</title>

</head>
<?php 
//include('_lib/checkuser.php');
include('_lib/db_config.php');
// this var gives the main_nav.php file an onstate for tab
$section = 'pages'; 
$action = $_GET['action'];

//include('_includes/header.php'); 
//include('_includes/main_nav.php');

?>
<body>

   <div id="content_container">
  
            
            <!-- start dynamic content -->
            
           
				<?
				
				$query = "SELECT * FROM user_emailaccount_info ORDER BY location ASC";
				$result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
                
				$num_accounts = mysql_num_rows($result);
				
				echo'<br/><a href="#" class="view-accounts">View Email Accounts</a>';
				?>
				<div id="accounts">
                <?
				echo'<h2>There are <strong>'.$num_accounts.'</strong> email account sign ups.</h2>';
				
				?>
                <div id="titles">
                	<div class="firstname">Users name</div> <div class="email">Users Email</div> <div class="location">Location</div>
                </div>
                <ul>
			
				<?
               
			    while($row = mysql_fetch_object($result)) 
                { 
				
				echo '<li>'.$row->firstname.' '. $row->lastname.' &nbsp;<span>|</span>&nbsp; <a href="mailto:'.$row->email_address.'">'.$row->email_address.'</a> &nbsp;<span>|</span> &nbsp; '.$row->location.'<div id="actions"><a href="#" id="'.$row->user_id.'" class="delete-account">delete account</a></div></li>';
				
                
                
                }
				

                ?> 
                </ul>
                </div>
                

    </div> <!-- end content_container div -->

</body>
</html>
