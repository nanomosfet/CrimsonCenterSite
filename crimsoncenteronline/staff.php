<?php
include 'header.inc.php';
$img = "<img src='uploads/defaultstaff.jpg' align='left' />";

$heading = "Meet our staff";
$text = "Please click on one of the names to the left to view their profile.";
$navigation = "<div id='right'>
    <div id='sidebar'>
                        
                            <ul>
                                <li>
                                    <h2>Staff members</h2>
                                        <ul>";
$query = "select * from staff ORDER BY `id` ASC";
$result = queryMysql($query);
$num = mysql_num_rows($result);
for($j = 0 ; $j < $num ; ++$j) {
    $row = mysql_fetch_row($result);
    $navigation .= "<li><a href='staff.php?id=$row[0]'>$row[1]</a></li>";
}
$navigation .= "<li><a href='staff.php'>Staff Main</a></li></li></ul></ul></div></div>";
if(isset($_GET['id'])) {
$id = sanitizeString($_GET['id']);
$query = "select * from staff where id = '$id'";
$getrow = mysql_fetch_row(queryMysql($query));
if(file_exists("uploads/$id.jpg")){ $img = "<img src='uploads/$id.jpg' border='1' align='left' class='floatright'/>";}else{
    $img = "";
}
$stafftext = stripslashes($getrow[2]);
echo <<<_END
   <div id="bodycontent">$navigation<div id="left">
        
        <h1>$getrow[1]</h1>
       
        <center><h3>$getrow[3]</h3></center>
        <center><h4>$getrow[4]</h4></center>
         
        <p>$img $stafftext</p>
               </div></div>

_END;
}else{
    echo <<<_END
   <div id="bodycontent">$navigation<div id="left">
       <h1>$heading</h1>
         
        
        
        $img <p>$text</p>
               
               </div></div>

_END;
}

include 'footer.inc.php';
?>
