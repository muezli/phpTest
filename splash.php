<?php
//minden fájl elején, ha nincs inicializálva a user változó visszadob a loginra
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Kezdőlap</title>
</head>
<body>
<table class=header>
	<tr>
		<td colspan=8 align=center><?php echo ' Bejelentkezve mint: '.$_SESSION['user'].' | '.$_SESSION['login'].'-óta.'?><br><hr></td>
	</tr>
	<tr>
		<td><a href=raktar.php>Raktrákészlet</a></td>
		<td><a href=rendeles.php>Rendelések</a></td>
		<td><a href=aktiv.php>Aktív Kiszállítások</a></td>
		<td><a href=archiv.php>Kész Kiszállítások</a></td>
		<td><a href=ugyfel.php>Ügyfelek</a></td>
		<td><a href=szemelyzet.php>Személyzet</a></td>
		<td><a href=kamionok.php>Kamionok</a></td>
		<td><a href=exit.php>Kilépés</a></td>
	</tr>
</table>
</body>
</html>