<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//Egyszerú listázó oldal az alkalmazottakra, rendezéssel
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Személyzet</title>
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
	<form method=POST action=szemelyzet.php>
	<tr>
		<td>Azonosító</td>
		<td>Név</td>
		<td>Státusz</td>
		<td>Rendezés:
			<select  name=rendez>
				<option value=ID <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'ID') echo 'selected=selected'; ?> >Azonosító</option>
				<option value=name <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'name') echo 'selected=selected' ; ?> >Név</option>
				<option value=status <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'stat') echo 'selected=selected'; ?> >Státusz</option>
			</select>
			<br>
			<input type=checkbox name=desc value=DESC <?php if(isset($_POST['desc'])) echo 'checked'; ?> ><label for=desc>Csökkenő</label>
		</td>
	</tr>
	<tr>
		<td><input type=text name=ID value=<?php if(isset($_POST['ID'])){echo $_POST['ID'];}else{echo '';} ?> ></td>
		<td><input type=text name=name value=<?php if(isset($_POST['name'])){echo $_POST['name'];}else{echo '';} ?> ></td>
		<td>
			<select name=status>
				<option value=a <?php if(isset($_POST['status']) && $_POST['status'] == 'act') echo 'selected=selected'; ?> >Aktív</option>
				<option value=s <?php if(isset($_POST['status']) && $_POST['status'] == 'com') echo 'selected=selected'; ?> >Szabadságon</option>
				<option value=all <?php if((isset($_POST['status']) && $_POST['status'] == 'all') || !isset($_POST['status'])) echo 'selected=selected'; ?> >Minden</option>
			</select>
		</td>
		<td><input type=submit value=Szürés></td>
		</form>
	</tr>
	<tr>
		<td colspan=4><hr></td>
	</tr>
	<?php	
	$ID = (isset($_POST['ID'])) ? '"%'.$_POST['ID'].'%"' :  '"%"' ;
	$name = (isset($_POST['name'])) ? '"%'.$_POST['name'].'%"' :  '"%"' ;
	$stat = (isset($_POST['status']) && $_POST['status'] !== 'all') ? '"%'.$_POST['status'].'%"' : '"%"';
	$rendez = (isset($_POST['rendez'])) ? $_POST['rendez'] : 'ID';
	$desc = (isset($_POST['desc'])) ? $_POST['desc'] : '';
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT ID, name, status FROM personel WHERE ID LIKE '.$ID.' AND name LIKE '.$name.' AND status LIKE '.$stat.' ORDER BY '.$rendez.' '.$desc;
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				echo '<tr class=list><td>'.$row['ID'].'</td><td>'.$row['name'].'</td><td>';
				if($row['status']=='a'){
					echo 'Aktív</td></tr>';
				}else{
					echo 'Szabadság</td></tr>';
				}
			}
		}
	}
	?>
	</table>
</body>
</html>