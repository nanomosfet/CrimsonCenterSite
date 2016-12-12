<?php
include 'header.inc.php';
echo "<div id='bodycontent'>";
showsidebar();
echo "<div id='left'><h2>Resources</h2>";
$query = "SELECT * FROM links";
$res = queryMysql($query);
$num = mysql_num_rows($res);

for($j = 0 ; $j < $num ; ++$j){
    $row = mysql_fetch_row($res);
    echo "<p><ul><li><a href='$row[1]' target='tab'>$row[2]</a></li></ul>
    $row[3]</p>";

}

echo "</div></div>";
include 'footer.inc.php';
?>
