<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//egyszerű listázó oldal megrendelőkhöz, rendezéssel
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Ügyfelek</title>
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
	<form method=POST action=ugyfel.php>
	<tr>
		<td>Azonosító</td>
		<td>Név</td>
		<td>Cím</td>
		<td>Születési dátum</td>
		<td>Elérhetőség</td>
		<td>Rendezés:
			<select  name=rendez>
				<option value=ID <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'ID') echo 'selected=selected'; ?> >Azonosító</option>
				<option value=name <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'name') echo 'selected=selected' ; ?> >Név</option>
				<option value=addr <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'addr') echo 'selected=selected'; ?> >Cím</option>
				<option value=birthd <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'birthd') echo 'selected=selected'; ?> >Szül. dátum</option>
				<option value=contact <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'contact') echo 'selected=selected'; ?> >Elérhetőség</option>
			</select>
			<br>
			<input type=checkbox name=desc value=DESC <?php if(isset($_POST['desc'])) echo 'checked'; ?> ><label for=desc>Csökkenő</label>
		</td>
	</tr>
	<tr>
		<td><input type=text name=ID value=<?php if(isset($_POST['ID'])){echo $_POST['ID'];}else{echo '';} ?> ></td>
		<td><input type=text name=name value=<?php if(isset($_POST['name'])){echo $_POST['name'];}else{echo '';} ?> ></td>
		<td><input type=text name=addr value=<?php if(isset($_POST['addr'])){echo $_POST['addr'];}else{echo '';} ?> ></td>
		<td><input type=text name=birthd value=<?php if(isset($_POST['birthd'])){echo $_POST['birthd'];}else{echo '';} ?> ></td>
		<td><input type=text name=contact value=<?php if(isset($_POST['contact'])){echo $_POST['contact'];}else{echo '';} ?> ></td>
		<td><input type=submit value=Szürés></td>
		</form>
	</tr>
	<tr>
		<td colspan=6><hr></td>
	</tr>
	<?php	
	$ID = (isset($_POST['ID'])) ? '"%'.$_POST['ID'].'%"' :  '"%"' ;
	$name = (isset($_POST['name'])) ? '"%'.$_POST['name'].'%"' :  '"%"' ;
	$addr = (isset($_POST['addr'])) ? '"%'.$_POST['addr'].'%"' :  '"%"' ;
	$birthd = (isset($_POST['birthd'])) ? '"%'.$_POST['birthd'].'%"' :  '"%"' ;
	$contact = (isset($_POST['contact'])) ? '"%'.$_POST['contact'].'%"' :  '"%"' ;
	$rendez = (isset($_POST['rendez'])) ? $_POST['rendez'] : 'ID';
	$desc = (isset($_POST['desc'])) ? $_POST['desc'] : '';
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT ID, name, addr, birthd, contact FROM costumers WHERE ID LIKE '.$ID.' AND name LIKE '.$name.' AND addr LIKE '.$addr.' AND birthd LIKE '.$birthd.' AND contact LIKE '.$contact.' ORDER BY '.$rendez.' '.$desc;
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				//iconv("UTF-8", "ISO-8859-1", $text) iconv_get_encoding
				echo '<tr class=list><td>'.$row['ID'].'</td><td>'.$row['name'].'</td><td>'.$row['addr'].'</td><td>'.$row['birthd'].'</td><td>'.$row['contact'].'</td></tr>';
			}
		}
	}
	?>
	</table>
</body>
</html>