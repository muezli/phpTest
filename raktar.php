<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	// Sima lekérdező oldal raktárkészlethez, rendezésekkel
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="windows-1250"/>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Raktár készlet</title>
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
	<form method=POST action=raktar.php>
	<tr>
		<td>Cikkszám</td>
		<td>Megnevezés</td>
		<td>Készlet</td>
		<td>Raktárhely</td>
		<td>Tömeg(Kg)</td>
		<td>Rendezés:
			<select  name=rendez>
				<option value=ID <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'ID') echo 'selected=selected'; ?> >Cikkszám</option>
				<option value=name <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'name') echo 'selected=selected' ; ?> >Megnevezés</option>
				<option value=quantity <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'quantity') echo 'selected=selected'; ?> >Készlet</option>
				<option value=location <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'location') echo 'selected=selected'; ?> >Raktárhely</option>
				<option value=weight <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'weight') echo 'selected=selected'; ?> >Tömeg(Kg)</option>
			</select>
			<br>
			<input type=checkbox name=desc value=DESC <?php if(isset($_POST['desc'])) echo 'checked'; ?> ><label for=desc>Csökkenő</label>
		</td>
	</tr>
	<tr>
		<td><input type=text name=ID value=<?php if(isset($_POST['ID'])){echo $_POST['ID'];}else{echo '';} ?> ></td>
		<td><input type=text name=name value=<?php if(isset($_POST['name'])){echo $_POST['name'];}else{echo '';} ?> ></td>
		<td><input type=text name=qty value=<?php if(isset($_POST['qty'])){echo $_POST['qty'];}else{echo '';} ?> ></td>
		<td><input type=text name=loc value=<?php if(isset($_POST['loc'])){echo $_POST['loc'];}else{echo '';} ?> ></td>
		<td><input type=text name=weight value=<?php if(isset($_POST['weight'])){echo $_POST['weight'];}else{echo '';} ?> ></td>
		<td><input type=submit value=Szürés></td>
		</form>
	</tr>
	<tr>
		<td colspan=6><hr></td>
	</tr>
	<?php	
	$ID = (isset($_POST['ID'])) ? '"%'.$_POST['ID'].'%"' :  '"%"' ;
	$name = (isset($_POST['name'])) ? '"%'.$_POST['name'].'%"' :  '"%"' ;
	$qty = (isset($_POST['qty'])) ? '"%'.$_POST['qty'].'%"' :  '"%"' ;
	$loc = (isset($_POST['loc'])) ?  '"%'.$_POST['loc'].'%"' :  '"%"' ;
	$weight = (isset($_POST['weight'])) ? '"%'.$_POST['weight'].'%"' :  '"%"' ;
	$rendez = (isset($_POST['rendez'])) ? $_POST['rendez'] : 'ID';
	$desc = (isset($_POST['desc'])) ? $_POST['desc'] : '';
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT ID, name, quantity, location, weight FROM stock WHERE ID LIKE '.$ID.' AND name LIKE '.$name.' AND quantity LIKE '.$qty.' AND location LIKE '.$loc.' AND weight LIKE '.$weight.' ORDER BY '.$rendez.' '.$desc;
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				echo '<tr class=list><td>'.$row['ID'].'</td><td>'.$row['name'].'</td><td>'.$row['quantity'].'</td><td>'.$row['location'].'</td><td>'.$row['weight'].'</td></tr>';
			}
		}
	}
	?>
	</table>
</body>
</html>