<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//egyszerű listázó oldal teljesített szállításokhoz
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Kész kiszállítások</title>
<link rel="stylesheet" type="text/css" href="style.css">
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

<table class=center>
<?php
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT DISTINCT deliveryhead.deliveryID, deliveryhead.truckID, trucks.status FROM deliveryhead INNER JOIN trucks ON trucks.plateID = deliveryhead.truckID INNER JOIN deliverybody ON deliverybody.deliveryID = deliveryhead.deliveryID WHERE deliveryhead.status LIKE "com"';
		if ($res= $db->query($q)){
				echo '<tr><td colspan=8>Kész kiszállítások</td></tr><tr><td colspan=8><hr></td></tr><tr><td colspan=2>Szállítási szám</td><td colspan=2>Kamion rendszáma</td><td>Kamion státusza</td></tr>';
				while ($row = $res->fetch_assoc()) {
					echo '<tr class=list><td>'.$row['deliveryID'].'</td><td><form method=POST action=deli.php target=_blank><button type=submit name=submit value='.$row['deliveryID'].'>Megnéz</button></form></td><td>'.$row['truckID'].'</td><td><form method=POST action=kamionok.php target=_blank><button type=submit name=plateID value='.$row['truckID'].'>Megnéz</button></form></td><td>';
					if($row['status']=='act'){
						echo 'Aktív';
					}else{
						echo 'Szervízben';
					}
					echo '</td></tr>';
				}
		}
	}

?>
</table>
</body>
</html>