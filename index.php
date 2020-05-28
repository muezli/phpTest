 <?php
	session_start();
	error_reporting(0);
?>
  <!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Bejelentkezés</title>
</head>
<body>
<table class=center>
	<form method=POST action="login.php">
		<tr>
			<td>Felhasználónév:</td>
			<td><input type=text name=uname required></td>
		</tr>
		<tr>
			<td>Jelszó:</td>
			<td><input type=password name=pass required></td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type=submit value=Bejelentkezés></td>
		</tr>
	</form>
	<?php if($_SESSION['tries'] !== null)echo '<tr><td colspan=2 align=center><font class=alert>'.$_SESSION['tries'].' sikertelen próbálkozás!</font></td></tr>';?>
</table>
</body>
</html>