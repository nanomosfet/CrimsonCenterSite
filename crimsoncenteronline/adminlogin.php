<?php
include_once 'header.inc.php';
echo "<div id='bodycontent'><h3>Member Log in</h3>";
$error = $user = $pass = "";
if (isset($_POST['user']))
{
	$user = sanitizeString($_POST['user']);
	$pass = sanitizeString($_POST['pass']);

	if ($user == "" || $pass == "")
	{
		$error = "Not all fields were entered<br />";
	}
	else
	{
		$query = "SELECT user,pass FROM admin
				  WHERE user='$user' AND pass='$pass'";

		if (mysql_num_rows(queryMysql($query)) == 0)
		{
			$error = "Username/Password invalid<br />";
		}
		else
		{
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
			die("You are now logged in admin. Please
			   <a href='admin.php'>click here</a>.");
		}
	}
}

echo <<<_END
<form method='post' action='adminlogin.php'>$error
Username <input type='text' maxlength='16' name='user'
	value='$user' /><br />
Password <input type='password' maxlength='16' name='pass'
	value='$pass' /><br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<input type='submit' value='Login' />
</form></div>
_END;
include_once 'footer.inc.php';
?>