<?php
include_once 'header.inc.php';
$query = "select * from homepage";
$result = queryMysql($query);
$row = mysql_fetch_row($result);
$mstatement = "<table class='hometable'>
    <tr><td><font color='A30B08'><B>C</font></B>hallenge</td><td>Encourage beyond skill level</td><tr>
    <tr><td><font color='A30B08'><B>R</font></B>egulate</td><td>Help manage emotional shifts & responses</td><tr>
    <tr><td><font color='A30B08'><B>I</font></B>nteract</td><td>Engage in shared activities & emotions</td><tr>
    <tr><td><font color='A30B08'><B>M</font></B>otivate</td><td>Introduce communicative temptations</td><tr>
    <tr><td><font color='A30B08'><B>S</font></B>tructure</td><td>Set boundaries for safety, pleasure & compliance</td><tr>
    <tr><td><font color='A30B08'><B>O</font></B>bserve</td><td>Study reactions to decoding/encoding information</td><tr>
    <tr><td><font color='A30B08'><B>N</font></B>urture</td><td>Foster growth through emotional bonding</td><tr></table>";

$aboutus = $row[1];



echo "
<div id='bodycontent'>";
showsidebar();
echo "<div id='left'>





<h1>About us</h1>
<p>$aboutus</p>
<center><p>$mstatement</p></center>";
$query = "SELECT * FROM service_title";
$result = queryMysql($query);
$num = mysql_num_rows($result);
if($num >= 1) {print "<h2>Our services</h2>";}
for($j = 0 ; $j < $num ; ++$j){
    $getrow = mysql_fetch_row($result);
    if(file_exists("uploads/services/$getrow[0].jpg")){ $img = "<img src='uploads/services/$getrow[0].jpg' border='1' align='right'/>";}else{
    $img = "";
}
    echo " <div id='services'>
    <h4>$getrow[1]</h4> <ul>$img";
    $query1 = "SELECT * FROM service_title, service_list WHERE service_title.id=service_list.title_id AND service_list.title_id=$getrow[0]";
    $res = querymysql($query1);
    $num_list = mysql_num_rows($res);
    for ($i = 0 ; $i < $num_list ; ++$i){
        $row = mysql_fetch_row($res);
        echo "<li>$row[4]</li>";
    }
    echo "</ul></div>";
}


echo "
</blockquote>
</div><!-- End of services div -->
</div>";

include("footer.inc.php");
?>