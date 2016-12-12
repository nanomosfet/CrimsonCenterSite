<?php
include 'header.inc.php';
echo "<div id='bodycontent'>";
showsidebar();
echo "<div id='left'>";
$query = "select * from contact";
        $res = queryMysql($query);
        $rows = mysql_fetch_row($res);
if(isset($_POST['emailpost'])){
 $name = $_POST['name'];
 $lastname = $_POST['lastname'];
 $email = $_POST['email'];
 $phone = $_POST['phone'];
 $text = $_POST['text'];
    if(strlen($name) > 0 && strlen($lastname) > 0 && validate_email($email) > 0 && strlen($phone) > 0) {

    $body = "$name $lastname wants to get in contact with you<br>";
    $body .= "You can contact them with the email: $email or phone: $phone<br> Here are some additional details $name provided $text";
    mail("$rows[0]","New contact request",$body);
    $error = "We will get back to you as fast as we can. Thank you";


}else{$error = "You did not fill in all the forms correctly";}
}
if(!isset($error)){
            $error = "";
        }

        echo "<h1>Contact us</h1>
        <p>Phone: $rows[1]</p>
        <p>Fax:$rows[2]</p>";
        $query = "select * from location";
        $res = queryMysql($query);
        $num = mysql_num_rows($res);
        echo "<h2>The Crimson Center has $num locations available to serve you:</h2><br/>";
        for($j = 0 ; $j < $num ; ++$j) {
            $row = mysql_fetch_row($res);
            echo "  <h3>$row[1]</h3>
                    <p>Address: $row[2]</p>
                    <p><a href='$row[5]'>Directions to $row[1]</a></p>";
        }
        echo '<p><div id="map" style="width: 500px; height: 300px"></div></p>';
echo "<p>$error</p>

	<form method='post' action='contact.php'>
        <h2>Send us a message</h2>
	<table>
        <tr>
	<td><font color='red'>*</font>Name: </td><td><input type='text' name='name' maxlength=30/></td>
	</tr>
        <tr>
	<td><font color='red'>*</font>Lastname:</td><td> <input type='text' name='lastname'></td>
	</tr>
        <tr>
	<td><font color='red'>*</font>Email:</td><td> <input type='text' name='email'  maxlength=100/></td>
	</tr>
        <tr>
        <td><font color='red'>*</font>Phone(Please include area code):</td><td> <input type='text' name='phone' maxlength=100/></td>
        </tr>
        <tr>
         <td>If you would like to add any details add them here:</td><td> <textarea name='text' cols='40' rows='4'></textarea></td>
      </tr>
        <tr>
        <td><input type='hidden' name='emailpost' value='true'>
	<input type='submit' value='submit email'/></td>
	</tr>
        <tr>
	<td><font color='red'>* - denotes required</font></td>
	</form></tr></table>";
echo "</div></div>";
include 'footer.inc.php';
?>
