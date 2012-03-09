<?
include('db_config.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
	
	case 'updatePage' :
		updatepage();
		break;
		
	case 'addGallery' :
		addgallery();
		break;
		
	case 'addclassGallery' :
		addclassgallery();
		break;
		
	case 'deleteGallery' :
		deletegallery();
		break;
		
	case 'addUser' :
		addUser();
		break;
	
	case 'updateUser' :
		updateUser();
		break;

	case 'deleteUser' :
		deleteUser();
		break;
		
	case 'addClass' :
		addClass();
		break;
	
	case 'updateClass' :
		updateClass();
		break;

	case 'deleteClass' :
		deleteClass();
		break;	
		
	default:
	
		header('Location: index.php');
		
}

//////////// ADMIN FUNCTIONS  //////////////////




/* ///////////////  USER FUNCTIONS ////////////////////////////// //////////////
   
   THIS SECTION PROVIDES FUNCTIONALITY FOR ADDING, EDITING, AND DELETING USERS
   
   ///////////////////////////////////////////////////////////////////////////////	
*/ 

function addUser() {

	
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$class_id = $_POST['class_id'];
	

	$query = "INSERT INTO users (first_name, last_name, address, city, state, zip, email, phone, username, password, class_id) VALUES ('$first_name', '$last_name', '$address', '$city', '$state', '$zip', '$email', '$phone', '$username', '$password', '$class_id')";
	$result=mysql_query($query);
	
	
	header ('Location: ../users.php?action=success');

}



function updateUser() {

	$user_id = $_POST['user_id'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$class_id = $_POST['class_id'];
	

	$query = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', address = '$address', city = '$city', state = '$state', zip = '$zip', email = '$email', phone = '$phone', username = '$username', password = '$password', class_id = $class_id WHERE user_id = '$user_id'";
	$result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
	
	
	header ('Location: ../users.php?action=success');

}



function deleteUser() {

	$id = $_GET['user_id'];
	
	$query = "DELETE FROM users WHERE user_id = '$id'";
	$result=mysql_query($query);
	
	header('location: ../users.php?action=success');

}




/* ///////////////  CLASS FUNCTIONS ////////////////////////////// //////////////
   
   THIS SECTION PROVIDES FUNCTIONALITY FOR ADDING, EDITING, AND DELETING CLASSES
   
   ///////////////////////////////////////////////////////////////////////////////	
*/ 

function addClass() {

	
	$class_name = $_POST['class_name'];
	$class_description = $_POST['class_description'];
	

	$query = "INSERT INTO classes (class_name, class_description) VALUES ('$class_name', '$class_description')";
	$result=mysql_query($query);
	
	
	header ('Location: ../classes.php?action=success');

}



function updateClass() {

	$class_id = $_POST['class_id'];
	$class_name = $_POST['class_name'];
	$class_description = $_POST['class_description'];
		

	$query = "UPDATE classes SET class_name = '$class_name', class_description = '$class_description' WHERE class_id = '$class_id'";
	$result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
	
	
	header ('Location: ../classes.php?action=success');

}



function deleteClass() {

	$id = $_GET['class_id'];
	
	$query = "DELETE FROM classes WHERE class_id = '$id'";
	$result=mysql_query($query);
	
	header('location: ../classes.php?action=success');

}




/* ///////////////  PAGE FUNCTIONS ////////////////////////////// 
   
   THIS SECTION PROVIDES FUNCTIONALITY FOR EDITING SITE PAGES 	

   //////////////////////////////////////////////////////////////	
*/ 


function updatepage() {

	$page_id = $_POST['page_id'];
	$page_text = $_POST['page_text'];
	$page_title = $_POST['page_title'];
	$page_keywords = $_POST['page_keywords'];
	$page_descrip = $_POST['page_descrip'];
	
	$oldimage1 = $_POST['oldimage1'];
	$oldimage2 = $_POST['oldimage2'];
	$oldimage3 = $_POST['oldimage3'];
	$oldimage3 = $_POST['oldimage4'];
	
	 $upload_dir = '../../page_images/';
	 $thumbs_dir = '../../page_images/thumbs/';
	 
	
	$new_file1 = $_FILES['image1']['name']; 
   	
	if($new_file1 == '') {
	
	$filename1 = $oldimage1;
	
	} else {
           
	
			 if (move_uploaded_file($_FILES['image1']['tmp_name'], $upload_dir . $new_file1)) { 
						 
					  $filename1=$new_file1;
					  cropImage(413, 413, $upload_dir . $new_file1, 'jpg', $upload_dir . $new_file1);
					  cropImage(70, 70, $upload_dir . $new_file1, 'jpg', $thumbs_dir . $new_file1);
					  
			  
			}
	
	}
	
	
	
	$new_file2 = $_FILES['image2']['name']; 
	
	if($new_file2 == '') {
	
	$filename2 = $oldimage2;
	
	} else {
               
		 if (move_uploaded_file($_FILES['image2']['tmp_name'], $upload_dir . $new_file2)) { 
					 
				  $filename2=$new_file2;
				  
				  cropImage(413, 413, $upload_dir . $new_file2, 'jpg', $upload_dir . $new_file2);
				  cropImage(70, 70, $upload_dir . $new_file2, 'jpg', $thumbs_dir . $new_file2);
				  
		  
		}
	
	}
	
	$new_file3 = $_FILES['image3']['name']; 
	
	if($new_file3 == '') {
	
	$filename3 = $oldimage3;
	
	} else {
               
		 if (move_uploaded_file($_FILES['image3']['tmp_name'], $upload_dir . $new_file3)) { 
					 
				  $filename3=$new_file3;
				  
				  cropImage(413, 413, $upload_dir . $new_file3, 'jpg', $upload_dir . $new_file3);
				  cropImage(70, 70, $upload_dir . $new_file3, 'jpg', $thumbs_dir . $new_file3);
				  
		  
		}
	
	}
	
	$new_file4 = $_FILES['image4']['name']; 
	
	if($new_file4 == '') {
	
	$filename4 = $oldimage4;
	
	} else {
               
		 if (move_uploaded_file($_FILES['image4']['tmp_name'], $upload_dir . $new_file4)) { 
					 
				  $filename4=$new_file4;
				  
				  cropImage(413, 413, $upload_dir . $new_file4, 'jpg', $upload_dir . $new_file4);
				  cropImage(70, 70, $upload_dir . $new_file4, 'jpg', $thumbs_dir . $new_file4);
				  
		  
		}
	
	}
	
	$query = "UPDATE pages SET page_text = '$page_text', page_title = '$page_title', page_keywords = '$page_keywords', page_descrip = '$page_descrip', image_1 = '$filename1', image_2 = '$filename2', image_3 = '$filename3', image_4 = '$filename4' WHERE page_id = '$page_id'";
	$result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
	
	
	header ('Location: ../main.php?action=success');

}



/* ///////////////  GALLERY FUNCTIONS ////////////////////////////// //////////////
   
   THIS SECTION PROVIDES FUNCTIONALITY FOR ADDING, EDITING, AND DELETING GALLERIES
   
   THERE IS ALSO AN ADDITIONAL HELPER FUNCTION USED FOR CROPPING IMAGES 	

   ///////////////////////////////////////////////////////////////////////////////	
*/ 


function addgallery() {
	
	$gallery_name = $_POST['gallery_name'];
	$thedate = date("F j, Y");
	
	$query = "INSERT INTO galleries (created_date, gallery_name) VALUES ('$thedate', '$gallery_name')";
	$result=mysql_query($query);
	
	header('location: ../galleries.php?action=success');

}

function deletegallery() {
	
	$id = $_GET['gallery_id'];
	
	$query = "DELETE FROM galleries WHERE id = '$id'";
	$result=mysql_query($query);
	
	header('location: ../galleries.php?action=success');

}


//// ADD GALLERY TO A CLASS /////////


function addclassgallery() {
	
	$gallery_name = $_POST['gallery_name'];
	$class_id = $_POST['class_id'];
	$thedate = date("F j, Y");
	
	// make dir for gallery images
	/*$dirname = str_replace( ' ', '', $gallery_name );  
	mkdir("../../images/class_galleries/".$dirname."/");
	chmod("../../images/class_galleries/".$dirname."/", 0777);
	
	$thumbs_dir = $dirname.'_thumbs';
	mkdir("../../images/class_galleries/".$thumbs_dir."/");
	chmod("../../images/class_galleries/".$thumbs_dir."/", 0777);*/
	
	
	
	$query = "INSERT INTO galleries (created_date, gallery_name, class_id) VALUES ('$thedate', '$gallery_name', '$class_id')";
	$result=mysql_query($query);
	
	$location = 'location: ../upload_class_gallery.php?action=success&class_id='.$class_id;
	
	header($location);

}



function cropImage($nw, $nh, $source, $stype, $dest) {

          $size = getimagesize($source);

          $w = $size[0];

          $h = $size[1];

          switch($stype) {

              case 'gif':
	  
	          $simg = imagecreatefromgif($source);

              break;

              case 'jpg':

              $simg = imagecreatefromjpeg($source);

              break;

              case 'png':

              $simg = imagecreatefrompng($source);

              break;

          }

          $dimg = imagecreatetruecolor($nw, $nh);

          $wm = $w/$nw;

          $hm = $h/$nh;

          $h_height = $nh/2;

          $w_height = $nw/2;

          if($wm > $hm) {

              $adjusted_width = $w / $hm;

              $half_width = $adjusted_width / 2;

              $int_width = $half_width - $w_height;

			  imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);

       		  } elseif(($w <$h) || ($w == $h)) {

              $adjusted_height = $h / $wm;
			  
              $half_height = $adjusted_height / 2;

              $int_height = $half_height - $h_height;

              imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);


          } else {

              imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);

          }

		 imagejpeg($dimg,$dest,100);

      }






?>