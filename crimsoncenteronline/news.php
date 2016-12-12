<?php include 'header.inc.php';?>
<div id="bodycontent"><?php
showsidebar();?><div id="left">
        <h1>News</h1>
        <table>
        <?php
        $query = "SELECT * FROM news";
        $result = queryMysql($query);
        $num = mysql_num_rows($result);
      
        for ($j = 0 ; $j < $num ; ++$j) {
            $row = mysql_fetch_row($result);
            echo "<tr><td>
            <h3>$row[1]</h3>
            <p>$row[3]</p>

              <p>";
            if(file_exists("uploads/newsthumbs/$row[0].jpg")) echo "<img src='uploads/newsthumbs/$row[0].jpg' border='1' class='floatright'/>";
            echo"$row[2]</p></td></tr>";
          

       }
       ?>
    </table>
    
</div></div>

<?php include 'footer.inc.php';?>