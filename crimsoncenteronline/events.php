<?php
include ('header.inc.php');
?>
<div id="bodycontent"><?php
showsidebar();?><div id="left">
    <h1>Forthcoming events</h1>
    <center><h4>Club events/trips for the next six
months</h4></center>
    <table>
        
        <?php
        $query = "SELECT * FROM events";
        $result = queryMysql($query);
        $num = mysql_num_rows($result);

        for ($j = 0 ; $j < $num ; ++$j) {
            $row = mysql_fetch_row($result);
            echo "<tr>
              <td>
            <h3>$row[1]</h3>";
            if(file_exists("uploads/eventthumbs/$row[0].jpg")) echo "<img src='uploads/eventthumbs/$row[0].jpg' border='1' class='floatright'/>";
            echo "
              <p>$row[2]</p>
              <p>$row[4]</p>
            <B><p>$row[3]</p></B>
              </td>
          </tr>";


       }
       ?>
    </table>
</div>
</div>
<?php
include ('footer.inc.php');

?>
