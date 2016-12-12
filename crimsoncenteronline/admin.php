<?PHP
include 'header.inc.php';
if (!isset($_SESSION['user']))
	die("<br /><br /><div id='bodycontent'><p>You need to login to view this page</p></div>");
$action = "";
if(isset($_GET['action'])) {
    $action = sanitizeString($_GET['action']);
}
switch($action) {
    case "events":
        echo <<<_END
        <div id='bodycontent'><table>
        <tr>
            <th>Headline</th>
            <th>Events</th>
            <th>Where</th>
            <th>When</th>
            <th>Picture</th>
        </tr>
_END;


          if(!isset($_POST['edit']) && isset($_POST['headline']) && isset($_POST['event']) && isset($_POST['location']) && isset($_POST['time'])) {

              $headline = sanitizeString($_POST['headline']);
              $event = sanitizeString($_POST['event']);
              $event = nl2br($_POST['event']);
              $location = sanitizeString($_POST['location']);
              $time = sanitizeString($_POST['time']);
              if(strlen($_POST['headline']) > 0 || strlen($_POST['event']) > 0 || strlen($_POST['location']) > 0 || strlen($_POST['time']) > 0){
              $query = "insert into events values('','$headline','$event','$location','$time')";
              $result = queryMysql($query);
              echo "Your new event has been added";      }else{echo "Please fill in all the forms";}
              }
          if(isset($_POST['delete'])){
              $deleteid = sanitizeString($_POST['delete']);
              $query = "DELETE FROM events WHERE id='$deleteid'";
              $result = queryMysql($query);
              echo "Your Event has been deleted";

          }
          if(isset($_POST['edit']) && isset($_POST['headline']) && isset($_POST['event']) && isset($_POST['location']) && isset($_POST['time'])){
              $editid = sanitizeString($_POST['edit']);
              $headline = sanitizeString($_POST['headline']);
              $event = sanitizeString($_POST['event']);
              $location = sanitizeString($_POST['location']);
              $time = sanitizeString($_POST['time']);
              $query = "SELECT * FROM events where id='$editid'";
              $result = queryMysql($query);
              $num = mysql_num_rows($result);
              for($j = 0 ; $j < $num ; ++$j) {
                  $row = mysql_fetch_row($result);
                  $query = "UPDATE events SET headline = '$headline' WHERE headline='$row[1]'";
                  $result = queryMysql($query);
                  $query = "UPDATE events SET event = '$event' WHERE event='$row[2]'";
                  $result = queryMysql($query);
                  $query = "UPDATE events SET place = '$location' WHERE place='$row[3]'";
                  $result = queryMysql($query);
                  $query = "UPDATE events SET date = '$time' WHERE date='$row[4]'";
                  $result = queryMysql($query);
                 }
                 echo "Your news has been Updated";
          }
          if(isset($_POST['delete_pic'])){
            $delete_pic_id = $_POST['delete_pic'];
            $file = "uploads/eventthumbs/$delete_pic_id.jpg";
            unlink($file);
            $error = "Picture has been deleted";
        }
          if (isset($_FILES['image']['name']) && isset($_POST['pic_uploadid']))
{
	ini_set('memory_limit', '100M');
              $uploadid = $_POST['pic_uploadid'];
        $saveto = "uploads/eventthumbs/$uploadid.jpg";
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok = TRUE;

	switch($_FILES['image']['type'])
	{
		case "image/gif":   $src = imagecreatefromgif($saveto); break;

		case "image/jpeg":  // Both regular and progressive jpegs
		case "image/pjpeg":	$src = imagecreatefromjpeg($saveto); break;

		case "image/png":   $src = imagecreatefrompng($saveto); break;

		default:			$typeok = FALSE; break;
	}

	if ($typeok)
	{
		list($w, $h) = getimagesize($saveto);
		$max = 200;
		$tw  = $w;
		$th  = $h;

		if ($w > $h && $max < $w)
		{
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h)
		{
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w)
		{
			$tw = $th = $max;
		}

		$tmp = imagecreatetruecolor($tw, $th);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
		imageconvolution($tmp, array( // Sharpen image
							    array(-1, -1, -1),
							    array(-1, 16, -1),
							    array(-1, -1, -1)
						       ), 8, 0);
		imagejpeg($tmp, $saveto);
		imagedestroy($tmp);
		imagedestroy($src);
	}
}
 $query = "SELECT * FROM events";
        $result = queryMysql($query);
        $num = mysql_num_rows($result);

        for ($j = 0 ; $j < $num ; $j++) {
            $row = mysql_fetch_row($result);
            
            echo "<tr><form method='post' action='admin.php?action=events'>
              <td><input type='text' name='headline' value='$row[1]'></td>
              <td><textarea name='event' cols='60' rows='20'>$row[2]</textarea></td>
              <td><input type='text' name='location' value='$row[3]' maxlength='256'  /></td>
              <td><input type='text' name='time' value='$row[4]' maxlength='256'  /><input type='hidden' name='edit' value='$row[0]'></td>
            <td><p><input type='submit' value='edit'></form><form method='post' action='admin.php?action=events'><input type='hidden' name='delete' value='$row[0]'><input type='submit' value='delete'></form></p></td>
            <td><form method='post' action='admin.php?action=events' enctype='multipart/form-data'>
       <p>Upload photo(optional):<input type='file' name='image' /><input type='hidden' name='pic_uploadid' value='$row[0]'><input type='submit' value='upload'></form>";
            if(file_exists("uploads/eventthumbs/$row[0].jpg")) echo "<img src='uploads/eventthumbs/$row[0].jpg' border='1' />";
            echo "
            <form method='post' action='admin.php?action=events'><input type='hidden' name='delete_pic' value='$row[0]'><input type='submit' value='Delete Picture'></form></td>
            </tr></div>";
          }
          echo <<<_END
         <form method="post" action="admin.php?action=events">
             <p><a href='admin.php'>Back to admins main menu</a></p>
             <p>New Headline: <input type="text" name="headline" maxlength="256" /></p>
             <p>New Event info: <textarea name="event" cols='60' rows='20'></textarea></p>
             <p>New Event location: <input type="text" name="location" maxlength="256"  /></p>
             <p>New Event time: <input type="text" name="time" maxlength="256" /></p>
                 <p><input type="submit"/></p>
                 </form></div>
_END;
      break;
      case "news":
        echo <<<_END
        <div id='bodycontent'><div id="news">
            <h2>Add or edit your news</h2>
            <p>In your news text you see random BR things, dont delete them. they add structure to your text</p>
_END;


          if(!isset($_POST['edit']) && isset($_POST['headline']) && isset($_POST['news']) && !isset($_POST['delete'])) {

              $headline = sanitizeString($_POST['headline']);
              $news = sanitizeString($_POST['news']);
              $news = nl2br($_POST['news']);
              $date = sanitizestring($_POST['date']);

              if(strlen($_POST['headline']) > 0 && strlen($_POST['news']) > 0){
              $query = "insert into news values('','$headline','$news','$date')";
              $result = queryMysql($query);
              echo "Your new event has been added";      }else{echo "Please fill in all the forms";}
              }
          if(isset($_POST['delete'])){
              $deleteid = sanitizeString($_POST['delete']);
              $query = "DELETE FROM news WHERE id='$deleteid'";
              $result = queryMysql($query);
              echo "Your news has been deleted";

          }
          if(isset($_POST['edit']) && isset($_POST['headline']) && isset($_POST['news'])){
              $editid = sanitizeString($_POST['edit']);
              $headline = sanitizeString($_POST['headline']);
              $news = sanitizeString($_POST['news']);
              $news = nl2br($_POST['news']);
              $date = sanitizestring($_POST['date']);


              $query = "SELECT * FROM news where id='$editid'";
              $result = queryMysql($query);
              $num = mysql_num_rows($result);
              for($j = 0 ; $j < $num ; ++$j) {
                  $row = mysql_fetch_row($result);
                  $query = "UPDATE news SET headline = '$headline' WHERE id='$row[0]'";
                  $result = queryMysql($query);
                  $query = "UPDATE news SET news = '$news' WHERE id='$row[0]'";
                  $result = queryMysql($query);
                  $query = "UPDATE news SET date = '$date' WHERE id='$row[0]'";
                  $result = queryMysql($query);
              }
              echo "Your news has been Updated";
          }
          if(isset($_POST['delete_pic'])){
            $delete_pic_id = $_POST['delete_pic'];
            $file = "uploads/newsthumbs/$delete_pic_id.jpg";
            unlink($file);
            $error = "Picture has been deleted";
        }
          if (isset($_FILES['image']['name']) && isset($_POST['pic_uploadid']))
{
              $id = $_POST['pic_uploadid'];
             convertPic("uploads/newsthumbs/", 250, 250, $id, $id);
          }
	
	
 echo <<<_END
         <form method="post" action="admin.php?action=news">
             <p><a href='admin.php'>Back to admins main menu</a></p>
             <p>New Headline: <input type="text" name="headline" maxlength="256" /></p>
             <p>News Date: <input type="text" name="date" /></p>
             <p>News text: <textarea name="news" cols='60' rows='20'></textarea></p>
             <p><B>Upload a picture in the edit section!</B></p>
                 <p><input type="submit"/></p>
                 </form>
_END;


        $query = "SELECT * FROM news";
        $result = queryMysql($query);
        $num = mysql_num_rows($result);

        for ($j = 0 ; $j < $num ; $j++) {
            $row = mysql_fetch_row($result);
            echo "<p><form method='post' action='admin.php?action=news'></p>
              <p>News headline: <input type='text' name='headline' value='$row[1]'></p>
              <p>News date: <input type='text' name='date' value='$row[3]'></p>
                <p>News: <textarea name='news' cols='60' rows='20'>$row[2]</textarea>
            <input type='hidden' name='edit' value='$row[0]'></p>

            <p><input type='submit' value='edit'></form>
            <form method='post' action='admin.php?action=news'>
            <input type='hidden' name='delete' value='$row[0]'>
            <input type='submit' value='delete'></form></p>
            <p>Upload a a picture:<form method='post' action='admin.php?action=news' enctype='multipart/form-data'>
       <p>Upload photo(optional):<input type='file' name='image' />
            <input type='hidden' name='pic_uploadid' value='$row[0]'>
            <input type='submit' value='upload'></form><form method='post' action='admin.php?action=news'><input type='hidden' name='delete_pic' value='$row[0]'><input type='submit' value='Delete Picture'></form>";
            if(file_exists("uploads/newsthumbs/$row[0].jpg")) echo "<img src='uploads/newsthumbs/$row[0].jpg' border='1' />";
          
          }
         echo "</div></div>";
      break;
      case "admin":
          $updatepassword = $oldpass = $newpass1 = $newpass2 = $error = "";
          if(isset($_POST['updatepassword']) && isset($_POST['oldpass']) && isset($_POST['newpass1']) && isset($_POST['newpass2'])){
              $updatepassword = sanitizeString($_POST['updatepassword']);
              $oldpass = sanitizeString($_POST['oldpass']);
              $newpass1 = sanitizeString($_POST['newpass1']);
              $newpass2 = sanitizeString($_POST['newpass2']);
              $query = "SELECT * FROM admin WHERE pass = '$oldpass'";
              if(mysql_num_rows(queryMysql($query)) && $newpass1 == $newpass2 && strlen($newpass1) > 5 && strlen($newpass2) > 5){
                  $query = "update admin set pass = '$newpass1' where pass='$oldpass'";
                  $result = queryMysql($query);
                  $error = "Your passowrd has been updated:)";
              }else{
                  $error = "Make sure your new passwords match and they are longer than 5 characters";
              }
          }

          echo <<<_END
   <div id="bodycontent"><h2>Update your current admin password</h2>
       <p><a href='admin.php'>Back to admins main menu</a></p>
          <p>$error</p>
       <p><form method="post" action="admin.php?action=admin"></p>
       <p>Old password: <input type="text" name="oldpass"></p>
       <p>New Password: <input type="text" name="newpass1"></p>
       <p>Retype new password: <input type="text" name="newpass2"><input type="hidden" name="updatepassword"></p>
       <p><input type="submit" value="submit"></form></div>
_END;

break;
     case "staff":
        $string1 = "<p>";
        $string2 = "</p>";
        $tag1 = htmlentities($string1);
        $tag2 = htmlentities($string2);

         if(!isset($_POST['editid']) && !isset($_POST['deleteid']) && isset($_POST['staffname']) && isset($_POST['stafftitle']) && isset($_POST['stafftext'])) {

            $staffname = sanitizeString($_POST['staffname']);
            $stafftitle = sanitizeString($_POST['stafftitle']);
            $stafftext = sanitizeString($_POST['stafftext']);
            $staffemail = sanitizeString($_POST['staffemail']);
            $query = "insert into staff values('','$staffname','$stafftext','$stafftitle','$staffemail')";
            $result = queryMysql($query);
            $error = "$staffname has been added to your staff page";

        }
        if(isset($_POST['editid'])){
            $staffname = sanitizeString($_POST['staffname']);
            $stafftitle = sanitizeString($_POST['stafftitle']);
             $staffemail = sanitizeString($_POST['staffemail']);
             $stafftext = sanitizeString($_POST['stafftext']);
             
             $id = sanitizeString($_POST['editid']);
             $query = "update staff set staffname ='$staffname' where id = '$id'";
             $result = queryMysql($query);
             $query = "update staff set stafftext ='$stafftext' where id = '$id'";
             $result = queryMysql($query);
             $query = "update staff set stafftitle ='$stafftitle' where id = '$id'";
             $result = queryMysql($query);
             $query = "update staff set staffemail ='$staffemail' where id = '$id'";
             $result = queryMysql($query);
             $error = "$staffname has been updated";



        }

        if(isset($_POST['deleteid'])){
             $id = sanitizeString($_POST['deleteid']);
             $query = "DELETE FROM staff WHERE id='$id'";
             $result = queryMysql($query);
             $error = "The staff member has been deleted";


        }
        if(isset($_POST['delete_pic'])){
            $delete_pic_id = $_POST['delete_pic'];
            $file = "uploads/$delete_pic_id.jpg";
            unlink($file);
            $error = "Picture has been deleted";
        }


         if(isset($_GET['id'])){
            if (isset($_FILES['image']['name']) && isset($_POST['uploadid']))
{
	$uploadid = $_POST['uploadid'];
        ini_set('memory_limit', '100M');
        $saveto = "uploads/$uploadid.jpg";
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok = TRUE;

	switch($_FILES['image']['type'])
	{
		case "image/gif":   $src = imagecreatefromgif($saveto); break;

		case "image/jpeg":  // Both regular and progressive jpegs
		case "image/pjpeg":	$src = imagecreatefromjpeg($saveto); break;

		case "image/png":   $src = imagecreatefrompng($saveto); break;

		default:			$typeok = FALSE; break;
	}

	if ($typeok)
	{
		list($w, $h) = getimagesize($saveto);
		$max = 250;
		$tw  = $w;
		$th  = $h;

		if ($w > $h && $max < $w)
		{
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h)
		{
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w)
		{
			$tw = $th = $max;
		}

		$tmp = imagecreatetruecolor($tw, $th);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
		imageconvolution($tmp, array( // Sharpen image
							    array(-1, -1, -1),
							    array(-1, 16, -1),
							    array(-1, -1, -1)
						       ), 8, 0);
		imagejpeg($tmp, $saveto);
		imagedestroy($tmp);
		imagedestroy($src);
	}
}

            $navigation = showstaffNav();
            $id = sanitizeString($_GET['id']);
            $query = "SELECT * from staff where id='$id'";
            $result = queryMysql($query);
            $num = mysql_num_rows($result);

            for($j = 0 ; $j < $num ; ++$j){
                $row = mysql_fetch_row($result);
                echo <<<_END
    <div id="bodycontent"><h2>Add to your current staff members</h2>
       <p><a href='admin.php'>Back to admins main menu</a></p><div id='staffnav'><p class='feature'>$navigation</p></div>
       <form method="post" action="admin.php?action=staff&id=$row[0]" enctype="multipart/form-data">
       <p>Upload photo(optional):<input type="file" name="image" /><input type="hidden" name="uploadid" value="$row[0]"><input type="submit" value="upload"></form>
           <form method="post" action="admin.php?action=staff&id=$row[0]"><input type="hidden" name="delete_pic" value="$row[0]"><input type="submit" value="Delete Picture"></form></p><p>
_END;
                if(file_exists("uploads/$row[0].jpg")) echo "<img src='uploads/$row[0].jpg' border='1' />";

                echo "
                </p><p><form method='post' action='admin.php?action=staff'></p>
       <h3>Edit the information of the current staff member</h3>
       <p>Full name: <input type='text' name='staffname' value='$row[1]'></p>
       <p>Title: <input type='text' name='stafftitle' value='$row[3]'></p>
       <p>Email(optional): <input type='text' name='staffemail' value='$row[4]'></p>
       <p>Information about this person(add $tag1 before and $tag2 after your paragraph's like this $tag1 Example paragraph$tag2)</p><textarea name='stafftext' cols='60' rows='20'>$row[2]</textarea>

                <p><input type='hidden' name='editid' value='$row[0]'><input type='submit' value='Edit'></form>


                <form method='post' action='admin.php?action=staff'>
                <input type='hidden' name='deleteid' value='$row[0]'>
                    <input type='submit' value='Delete'></form>
";
            }
            }else{
        $navigation = showstaffNav();
        if(!isset($error)){
            $error = "";
        }
                echo "
  <div id='bodycontent'><h2>Add to your current staff members</h2>
       <p><a href='admin.php'>Back to admins main menu</a></p><div id='staffnav'><p class='feature'>$navigation</p></div>
           <p>$error</p>
       <p><form method='post' action='admin.php?action=staff'></p>
       <h3>Add a new persons information here</h3>
       <p>Full name: <input type='text' name='staffname'></p>
       <p>Title: <input type='text' name='stafftitle'></p>
       <p>Email(optional): <input type='text' name='staffemail'></p>
       <p>Information about this person(add $tag1 before and $tag2 after your paragraph's like this $tag1 Example paragraph$tag2)</p><textarea name='stafftext' cols='60' rows='20'></textarea></p>

        <p><input type='submit' value='add'></p></form></div>";

            }



         break;
    case "homepage":

        $query = "select * from homepage";
        $res = queryMysql($query);
        if(mysql_num_rows($res) == 0) {
            $query = "insert into homepage values('Our mission is to be the greatest','This is where you edit your about us')";
            $res = queryMysql($query);
            $error = "default values have been added into your homepage";

        }
        if(isset($_POST['edithomepage'])){
            $mstatement = sanitizeString($_POST['mstatement']);

            $aboutus = sanitizeString($_POST['aboutus']);

            $query = "select * from homepage";
            $res = queryMysql($query);
            $row = mysql_fetch_row($res);
            $statement = sanitizeString($row[0]);
            $query = "update homepage set mStatement='$mstatement' where mStatement='$statement'";
            $query2 = "update homepage set aboutus='$aboutus' where mStatement='$statement'";
            $res = queryMysql($query);
             $res = queryMysql($query2);
             $error = "Your homepage has been updated";
        }
        if (isset($_FILES['image']['name']) && isset($_POST['uploadid']))
{
	$uploadid = $_POST['uploadid'];
        ini_set('memory_limit', '100M');
        $saveto = "uploads/services/$uploadid.jpg";
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok = TRUE;

	switch($_FILES['image']['type'])
	{
		case "image/gif":   $src = imagecreatefromgif($saveto); break;

		case "image/jpeg":  // Both regular and progressive jpegs
		case "image/pjpeg":	$src = imagecreatefromjpeg($saveto); break;

		case "image/png":   $src = imagecreatefrompng($saveto); break;

		default:			$typeok = FALSE; break;
	}

	if ($typeok)
	{
		list($w, $h) = getimagesize($saveto);
		$max = 250;
		$tw  = $w;
		$th  = $h;

		if ($w > $h && $max < $w)
		{
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h)
		{
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w)
		{
			$tw = $th = $max;
		}

		$tmp = imagecreatetruecolor($tw, $th);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
		imageconvolution($tmp, array( // Sharpen image
							    array(-1, -1, -1),
							    array(-1, 16, -1),
							    array(-1, -1, -1)
						       ), 8, 0);
		imagejpeg($tmp, $saveto);
		imagedestroy($tmp);
		imagedestroy($src);
	}
}

       if(isset($_POST['add_service'])){
           $service_title = sanitizeString($_POST['service_title']);
           $query = "insert into service_title values('','$service_title')";
           $res = queryMysql($query);
           $error = "Your Service has been added";
       }
       if(isset($_POST['add_service_list'])){
           $service_title_id = sanitizeString($_POST['service_title_id']);
           $service_list = sanitizeString($_POST['service_list']);
           $service_title = $_POST['service_title'];
           $query = "insert into service_list values('','$service_title_id','$service_list')";
           $res = queryMysql($query);
           $error = "You have added a bullet point to $service_title";
       }
       if(isset($_POST['edit_service_title_id'])){
           $title_id = $_POST['edit_service_title_id'];
           $service_title = $_POST['service_title'];
           $query = "UPDATE service_title SET service_title='$service_title' WHERE id='$title_id'";
           $res = queryMysql($query);
           $error = "Your service title has been updated";
       }
       if(isset($_POST['edit_service_list_id'])){
           $service_list_id = $_POST['edit_service_list_id'];
           $service_list = sanitizeString($_POST['service_list']);
           $query = "UPDATE service_list SET service='$service_list' WHERE id='$service_list_id'";
           $res = queryMysql($query);
           $error = "Your bullet point has been updated";
       }
       if(isset($_POST['delete_service_list_id'])){
           $service_id = $_POST['delete_service_list_id'];
           $query = "DELETE FROM service_list where id='$service_id'";
           $res = queryMysql($query);
           $error = "your bullet point has been deleted";
       }
       if(isset($_POST['delete_service_title_id'])){
           $service_title_id = $_POST['delete_service_title_id'];
           $query = "DELETE FROM service_title WHERE id='$service_title_id'";
           $res = queryMysql($query);
           $query = "DELETE FROM service_list WHERE title_id='$service_title_id'";
           $res = queryMysql($query);
           $error = "Your service has been deleted";
       }
       if(isset($_POST['delete_pic'])){
            $delete_pic_id = $_POST['delete_pic'];
            $file = "uploads/services/$delete_pic_id.jpg";
            unlink($file);
            $error = "Picture has been deleted";
        }
        $query = "select * from homepage";
        $result = queryMysql($query);
        $row = mysql_fetch_row($result);
        $mstatement = $row[0];
        $aboutus = $row[1];
if(!isset($error)){
            $error = "";
        }

        echo "
            <div id='bodycontent'>
            <h2>Edit your homepage</h2>
            <p><a href='admin.php'>Back to admins main menu</a></p>
            <p>Please fill in BOTH the mission statement and the about us</p>
            <p>$error</p>
            <h2>Mission Statement</h2>
                <p><form method='post' action='admin.php?action=homepage'>
                    <textarea name='mstatement' cols='40' rows='4'>$mstatement</textarea></p>

            <h2>About us</h2>
                <p><textarea name='aboutus' cols='40' rows='4'>$aboutus</textarea><input type='hidden' name='edithomepage' value='true'><input type='submit' value='edit'></form></p>";
                echo "
    <div id='services'>
    <h3>Edit your services:</h3>
    <h3>Add a new service</h3>
<form method='post' action='admin.php?action=homepage'>
<p>

        Service title: <input type='text' name='service_title'>
        <input type='hidden' name='add_service' value='yes'>
        <input type='submit' value='Add'>
    </p></form>";
        $query = "SELECT * FROM service_title";
$result = queryMysql($query);
$num = mysql_num_rows($result);
 echo "<h4>Edit a current service</h4>";
for($j = 0 ; $j < $num ; ++$j){
    $getrow = mysql_fetch_row($result);
    if(file_exists("uploads/services/$getrow[0].jpg")){ $img = "<img src='uploads/services/$getrow[0].jpg' border='1' align='right'/>";}else{
    $img = "";
}

    echo "<form method='post' action='admin.php?action=homepage'>
        <p>Service Title: <input type='text' name='service_title' value='$getrow[1]'>$img</p>
        <input type='hidden' name='edit_service_title_id' value='$getrow[0]'>
       <input type='submit' value='Edit title'></form><form method='post' action='admin.php?action=homepage'>
        <input type='hidden' name='delete_service_title_id' value='$getrow[0]'>
        <input type='submit' value='delete service'></form>
    <form method='post' action='admin.php?action=homepage' enctype='multipart/form-data'>
       <p>Upload photo(optional):<input type='file' name='image' />
    <input type='hidden' name='uploadid' value='$getrow[0]'>
    <input type='submit' value='upload'></form>
<form method='post' action='admin.php?action=homepage'><input type='hidden' name='delete_pic' value='$getrow[0]'><input type='submit' value='Delete Picture'></form></p>
    <ul><form method='post' action='admin.php?action=homepage'>
       <li>
        Add new service bullet: <input type='text' name='service_list'></li>
        <input type='hidden' name='add_service_list'>
        <input type='submit' value='Add bullet'>
        <input type='hidden' name='service_title_id' value='$getrow[0]'>
        <input type='hidden' name='service_title' value='$getrow[1]'></form>";
    $query1 = "SELECT * FROM service_title, service_list WHERE service_title.id=service_list.title_id AND service_list.title_id=$getrow[0]";
    $res = querymysql($query1);
    $num_list = mysql_num_rows($res);
    for ($i = 0 ; $i < $num_list ; ++$i){
        $row = mysql_fetch_row($res);
        echo "<form method='post' action='admin.php?action=homepage'>
        <li>
            Edit service: <input type='text' name='service_list' value='$row[4]'>
            <input type='hidden' name='edit_service_list_id' value='$row[2]'></br>
            <input type='submit' value='Edit bullet'></form>
            <form method='post' action='admin.php?action=homepage'>
            <input type='hidden' name='delete_service_list_id' value='$row[2]'>
            <input type='submit' value='Delete bullet'></form>
        </li>";
    }
    echo "</ul>";
}

echo "
</div><!-- End of services div -->
</div>";
        break;
    case "resources":

        if(isset($_POST['editlinkid'])) {
            if(isset($_POST['url']) && isset($_POST['urlname']) && isset($_POST['linkinfo'])){
                $url = sanitizestring($_POST['url']);
                $urlname = sanitizestring($_POST['urlname']);
                $linkinfo = sanitizestring($_POST['linkinfo']);
                $editlinkid = $_POST['editlinkid'];
                $query = "update links set url='$url' where id='$editlinkid'";
                $res = queryMysql($query);
                $query = "update links set urlname='$urlname' where id='$editlinkid'";
                $res = queryMysql($query);
                $query = "update links set linkinfo='$linkinfo' where id='$editlinkid'";
                $res = queryMysql($query);
                $error = "Your link has been updated";
            }else{
               $error = "You did not fill in all the link values";
            }
        }
        if(isset($_POST['deletelinkid'])){
            $deletelinkid = $_POST['deletelinkid'];
            $query = "DELETE from links where id='$deletelinkid'";
            $res = queryMysql($query);
            $error = "Your link has been deleted";

        }
        if(isset($_POST['addlinkid'])){
            if(isset($_POST['url']) && isset($_POST['urlname']) && isset($_POST['linkinfo'])){
                $url = sanitizestring($_POST['url']);
                $urlname = sanitizestring($_POST['urlname']);
                $linkinfo = sanitizestring($_POST['linkinfo']);
                $query = "insert into links values('','$url','$urlname','$linkinfo')";
                $res = queryMysql($query);
            }else{$error = "You did not fill out the add form all the way";}

        }
if(!isset($error)){
            $error = "";
        }
        echo "<div id='bodycontent'><center><h2>Edit your resources page</h2>
                <p><a href='admin.php'>Back to admins main menu</a></p>
                <p><font color='red'>$error</font></p>";




        $query = "SELECT * FROM links";
        $res = queryMysql($query);
        $num = mysql_num_rows($res);
        echo "<ul>";
       echo "<li><form method='post' action='admin.php?action=resources'>
            Enter the URL(Must include the http:// part): <input type='text' name='url'>Enter the link name(please no apostraphies): <input type='text' name='urlname'><li>
            <p>enter the link info<textarea name='linkinfo' cols='40' rows='4'></textarea><input type='hidden' name='addlinkid' value='yes'><input type='submit' value='Add'></form></p>";

        for($j = 0 ; $j < $num ; ++$j){
            $row = mysql_fetch_row($res);
            echo "<li><form method='post' action='admin.php?action=resources'>
            Edit link URL: <input type='text' name='url' value='$row[1]'>Edit link name: <input type='text' name='urlname' value='$row[2]'><li>
            <p>Enter link info: <textarea name='linkinfo' cols='40' rows='4'>$row[3]</textarea><input type='hidden' name='editlinkid' value='$row[0]'><input type='submit' value='edit'></form>
            <form method='post' action='admin.php?action=resources'><input type='hidden' name='deletelinkid' value='$row[0]'><input type='submit' value='delete'></form></p>";

        }
        echo "</ul>";

        echo "</center></div>";
        break;
        case "contact":
        echo "<div id='bodycontent'><div id='left'>";
            if(isset($_POST['addid'])){
            $locname = sanitizeString($_POST['locname']);
            $adress1 = sanitizeString($_POST['adress1']);
            $adress2 = sanitizeString($_POST['adress2']);
            $query = "insert into location values('','$locname','$adress1','','','$adress2')";
            $res = queryMysql($query);
            $error = "You have successfully added a new location";
            //This is for the geocoding of the lats and longs
            $query = "SELECT * FROM location WHERE 1";
            $result = querymysql($query);
            // Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

// Iterate through the rows, geocoding each address
while ($row = @mysql_fetch_assoc($result)) {
  $geocode_pending = true;

  while ($geocode_pending) {
    $address = $row["address"];
    $id = $row["id"];
    $request_url = $base_url . "&q=" . urlencode($address);
    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];

      $query = sprintf("UPDATE location " .
             " SET lat = '%s', lng = '%s' " .
             " WHERE id = '%s' LIMIT 1;",
             mysql_real_escape_string($lat),
             mysql_real_escape_string($lng),
             mysql_real_escape_string($id));
      $update_result = queryMysql($query);
      if (!$update_result) {
        die("Invalid query: " . mysql_error());
      }
    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo "Address " . $address . " failed to geocoded. ";
      echo "Received status " . $status . "
\n";
    }
    usleep($delay);
  }
}
        }

        if(isset($_POST['deleteid'])){
            $deleteid = $_POST['deleteid'];
            $query = "delete from location where id='$deleteid'";
            queryMysql($query);
            $error = "You have successfully deleted the location";
        }
        if(isset($_POST['editid'])){
            $locname = sanitizeString($_POST['locname']);
            $adress1 = sanitizeString($_POST['adress1']);
            $adress2 = sanitizeString($_POST['adress2']);
            $editid = $_POST['editid'];
            $query = "update location set name='$locname' where id='$editid'";
            queryMysql($query);
             $query = "update location set address='$adress1' where id='$editid'";
            queryMysql($query);
            $query = "update location set type='$adress2' where id='$editid'";
            queryMysql($query);
            $error = "$locname has beed edited";
            //This is for the geocoding of the lats and longs
            $query = "SELECT * FROM location WHERE 1";
            $result = querymysql($query);
            // Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

// Iterate through the rows, geocoding each address
while ($row = @mysql_fetch_assoc($result)) {
  $geocode_pending = true;

  while ($geocode_pending) {
    $address = $row["address"];
    $id = $row["id"];
    $request_url = $base_url . "&q=" . urlencode($address);
    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];

      $query = sprintf("UPDATE location " .
             " SET lat = '%s', lng = '%s' " .
             " WHERE id = '%s' LIMIT 1;",
             mysql_real_escape_string($lat),
             mysql_real_escape_string($lng),
             mysql_real_escape_string($id));
      $update_result = mysql_query($query);
      if (!$update_result) {
        die("Invalid query: " . mysql_error());
      }
    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo "Address " . $address . " failed to geocoded. ";
      echo "Received status " . $status . "
\n";
    }
    usleep($delay);
  }
}
        }
        if(isset($_POST['updatecontact'])){
            $phone = sanitizestring($_POST['phone']);
            $fax = sanitizestring($_POST['fax']);
            $email = sanitizestring($_POST['email']);
            $query = "select * from contact";
            $res = queryMysql($query);
            $row = mysql_fetch_row($res);
            $query = "update contact set email='$email' where email='$row[0]'";
            queryMysql($query);
            $query = "update contact set phone='$phone' where email='$row[0]'";
            queryMysql($query);
            $query = "update contact set fax='$fax' where email='$row[0]'";
            queryMysql($query);
            $error = "you have updated your contact information";
         }
         if(!isset($error)){
            $error = "";
        }

        $query = "select * from contact";
        $res = queryMysql($query);
        $num = mysql_num_rows($res);
        if($num == 0){
            $query = "insert into contact values('default@hotmail.com','default','default')";
            queryMysql($query);
        }
        $row = mysql_fetch_row($res);

        echo "<form method='post' action='admin.php?action=contact'>
        <h2>Update Phone and fax and email</h2>
        <p>$error</p>
        <p>Phone:<input type='text' name='phone' value='$row[1]'></p>
        <p>Fax:<input type='text' name='fax' value='$row[2]'></p>
        <p>Email(This is the email that will recive the contact info the customers leave):<input type='text' name='email' value='$row[0]'></p>
        <input type='hidden' name='updatecontact' value='$row[0]'><input type='submit' value='Update'></form>";

        echo "<h2>Add a new location</h2>
            <h2><form method='post' action='admin.php?action=contact'></h2>
                    <p>Location Name:<input type='text' name='locname'></p>
                    <p>Address:<input type='text' name='adress1'></p>
                    <p>Enter the google directions link to the address:<input type='text' name='adress2'></p>
            <p><input type='hidden' name='addid'><input type='submit' value='add'></form></p>";;
        echo "<h2>Update locatations:</h2>";
        $query = "select * from location";
        $res = queryMysql($query);
        $num = mysql_num_rows($res);
        for($j = 0 ; $j < $num ; ++$j) {
            $row = mysql_fetch_row($res);
            echo "
            <h2>Enter Adress</h2><form method='post' action='admin.php?action=contact'></h2>
                    <p>Location Name:<input type='text' name='locname' value='$row[1]'></p>
                    <p>Address:<input type='text' name='adress1' value='$row[2]'></p>
                    <p>Enter the google directions link to the address:<input type='text' name='adress2' value='$row[5]'></p>
            <p><input type='hidden' name='editid' value='$row[0]'><input type='submit' value='edit'></form>
            <form method='post' action='admin.php?action=contact'><input type='hidden' name='deleteid' value='$row[0]'><input type='submit' value='delete'></form></p>";
        }
         echo "</div></div>";
            break;
    default:
          echo "<div id='bodycontent'><p><a href='admin.php?action=events'>Events management</a></p>";
          echo "<p><a href='admin.php?action=news'>news management</a></p>";
          echo "<p><a href='admin.php?action=resources'>resources management</a></p>";
          echo "<p><a href='admin.php?action=homepage'>homepage management</a></p>";
          echo "<p><a href='admin.php?action=contact'>Contact page management</a></p>";
          echo "<p><a href='admin.php?action=admin'>youre acount management</a></p>";
          echo "<p><a href='admin.php?action=staff'>youre staff management</a></p></div>";

break;
}


include_once 'footer.inc.php';
?>