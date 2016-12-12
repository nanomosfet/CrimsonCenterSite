<?php // login.php
$dbhost = 'crimsoncenter.com';
$dbname = 'crimsoncenter';
$dbuser = 'crimso';
$dbpass = '';
ini_set('memory_limit', '1000M');
ini_set('upload_max_filesize', '2000M');
ini_set('post_max_size', '2000M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);
mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAA9DNsLu3g_Kt8778DJ6ni9RStdFhB6oqnI11w_TUSzE2vfJfN3RSBB0EkvoGocIn4sf4f5IduvwWXIg");
function createTable($name, $query)
{
    if (tableExists($name))
    {
        echo "Table '$name' already exists<br />";
    }
    else
    {
        queryMysql("CREATE TABLE $name($query)");
        echo "Table '$name' created<br />";
    }
}

function tableExists($name)
{
    $result = queryMysql("SHOW TABLES LIKE '$name'");
    return mysql_num_rows($result);
}

function queryMysql($query)
{
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}
function destroySession()
{
$_SESSION=array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');
        session_destroy();
}

function sanitizeString($var)
{
    
    
    return mysql_real_escape_string($var);
}
function showstaffNav()
{
 $navigation = "<ul class='feature'><li><a href='admin.php?action=staff'>Add someone new</a></li>";
        $query = "select * from staff";
        $result = queryMysql($query);
        $num = mysql_num_rows($result);
        for($j = 0 ; $j < $num ; ++$j) {
            $row = mysql_fetch_row($result);
            $navigation .= "<li><a href='admin.php?action=staff&id=$row[0]'>Edit:$row[1]</a></li>";
        }
        $navigation .= "</ul>";
        return $navigation;
}
function validate_email($field) {
if ($field == "") return false;
else if (!((strpos($field, ".") > 0) &&
(strpos($field, "@") > 0)) ||
preg_match("/[^a-zA-Z0-9.@_-]/", $field))
return false;
return true;
}
function showsidebar() {
    echo '<div id="right">
    <div id="sidebar"><div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Crimson-Center-for-Speech-and-Language/321854046866" width="251" show_faces="true" border_color="" stream="true" header="true"></fb:like-box>


    </div></div>';
}
//  $img_base = base directory structure for thumbnail images
//  $w_dst = maximum width of thumbnail
//  $h_dst = maximum height of thumbnail
//  $n_img = new thumbnail name
//  $o_img = old thumbnail name
function convertPic($img_base, $w_dst, $h_dst, $n_img, $o_img)
  {ini_set('memory_limit', '100M');   //  handle large images
   unlink($img_base.$n_img);         //  remove old images if present
   unlink($img_base.$o_img);
   $new_img = $img_base.$n_img;

   $file_src = $img_base."img.jpg";  //  temporary safe image storage
   unlink($file_src);
   move_uploaded_file($_FILES['Filedata']['tmp_name'], $file_src);

   list($w_src, $h_src, $type) = getimagesize($file_src);  // create new dimensions, keeping aspect ratio
   $ratio = $w_src/$h_src;
   if ($w_dst/$h_dst > $ratio) {$w_dst = floor($h_dst*$ratio);} else {$h_dst = floor($w_dst/$ratio);}

   switch ($type)
     {case 1:   //   gif -> jpg
        $img_src = imagecreatefromgif($file_src);
        break;
      case 2:   //   jpeg -> jpg
        $img_src = imagecreatefromjpeg($file_src);
        break;
      case 3:  //   png -> jpg
        $img_src = imagecreatefrompng($file_src);
        break;
     }
   $img_dst = imagecreatetruecolor($w_dst, $h_dst);  //  resample

   imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $w_dst, $h_dst, $w_src, $h_src);
   imagejpeg($img_dst, $new_img);    //  save new image

   unlink($file_src);  //  clean up image storage
   imagedestroy($img_src);
   imagedestroy($img_dst);
  }


?>