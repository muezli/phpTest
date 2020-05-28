<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//egyszerű listázó oldal, kamionflotta listázására, rendezéssel
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Kamionok</title>
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
	<form method=POST action=kamionok.php>
	<tr>
		<td>Rendszám</td>
		<td>Típus</td>
		<td>Teherbírás</td>
		<td>Férőhely</td>
		<td>Státusz</td>
		<td>Rendezés:
			<select  name=rendez>
				<option value=plateID <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'plateID') echo 'selected=selected'; ?> >Rendszám</option>
				<option value=type <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'type') echo 'selected=selected' ; ?> >Típus</option>
				<option value=capacityKG <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'capacityKG') echo 'selected=selected'; ?> >Teherbírás</option>
				<option value=capacityPerson <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'capacityPerson') echo 'selected=selected'; ?> >Férőhely</option>
				<option value=status <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'status') echo 'selected=selected'; ?> >Státusz</option>
			</select>
			<br>
			<input type=checkbox name=desc value=DESC <?php if(isset($_POST['desc'])) echo 'checked'; ?> ><label for=desc>Csökkenő</label>
		</td>
	</tr>
	<tr>
		<td><input type=text name=plateID value=<?php if(isset($_POST['plateID'])){echo $_POST['plateID'];}else{echo '';} ?> ></td>
		<td><input type=text name=type value=<?php if(isset($_POST['type'])){echo $_POST['type'];}else{echo '';} ?> ></td>
		<td><input type=text name=capacityKG value=<?php if(isset($_POST['capacityKG'])){echo $_POST['capacityKG'];}else{echo '';} ?> ></td>
		<td><input type=text name=capacityPerson value=<?php if(isset($_POST['capacityPerson'])){echo $_POST['capacityPerson'];}else{echo '';} ?> ></td>
		<td>
			<select name=status>
				<option value=act <?php if(isset($_POST['status']) && $_POST['status'] == 'act') echo 'selected=selected'; ?> >Aktív</option>
				<option value=ser <?php if(isset($_POST['status']) && $_POST['status'] == 'ser') echo 'selected=selected'; ?> >Szervízben</option>
				<option value=all <?php if((isset($_POST['status']) && $_POST['status'] == 'all') || !isset($_POST['status'])) echo 'selected=selected'; ?> >Minden</option>
			</select>
		</td>
		<td><input type=submit value=Szürés></td>
		</form>
	</tr>
	<tr>
		<td colspan=6><hr></td>
	</tr>
	<?php	
	$plateID = (isset($_POST['plateID'])) ? '"%'.$_POST['plateID'].'%"' :  '"%"' ;
	$type = (isset($_POST['type'])) ? '"%'.$_POST['type'].'%"' :  '"%"' ;
	$capacityKG = (isset($_POST['capacityKG'])) ? '"%'.$_POST['capacityKG'].'%"' :  '"%"' ;
	$capacityPerson = (isset($_POST['capacityPerson'])) ? '"%'.$_POST['capacityPerson'].'%"' :  '"%"' ;
	$status = (isset($_POST['status']) && $_POST['status'] !== 'all') ? '"%'.$_POST['status'].'%"' : '"%"';
	$rendez = (isset($_POST['rendez'])) ? $_POST['rendez'] : 'plateID';
	$desc = (isset($_POST['desc'])) ? $_POST['desc'] : '';
	if($db = mysqli_connect('localhost','root','','raktar')){
		//$q = 'SELECT plateID, type, capacityKG, capacityPerson, status FROM raktar.trucks WHERE plateID LIKE '.$plateID.' AND type LIKE '.$type.' AND capacityKG LIKE '.$capacityKG.' AND capacityPerson LIKE '.$capacityPerson.' AND status LIKE '.$status.' ORDER BY '.$rendez.' '.$desc;
		$q = 'SELECT plateID, type, capacityKG, capacityPerson, status FROM trucks WHERE plateID LIKE '.$plateID.' AND type LIKE '.$type.' AND capacityKG LIKE '.$capacityKG.' AND capacityPerson LIKE '.$capacityPerson.' AND status LIKE '.$status.' ORDER BY '.$rendez.' '.$desc;
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				//iconv("UTF-8", "ISO-8859-1", $text) iconv_get_encoding
				echo '<tr class=list><td>'.$row['plateID'].'</td><td>'.$row['type'].'</td><td>'.$row['capacityKG'].'</td><td>'.$row['capacityPerson'].'</td><td>';
				if($row['status']=='act'){
					echo 'Aktív</td></tr>';
				}else{
					echo 'Szervízben</td></tr>';
				}
			}
		}
	}
	?>
	</table>
</body>
</html>