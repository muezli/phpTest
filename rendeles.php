<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//Sima lekérdező oldal rendelések fejlécéhez, rendezésekkel
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css">
<title>Rendelések</title>
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
	<form method=POST action=rendeles.php>
	<tr>
		<td>Azonosító</td>
		<td>Megrendelő</td>
		<td>Dátum</td>
		<td>Cím</td>
		<td>Státusz</td>
		<td>Rendezés:
			<select  name=rendez>
				<option value=orderheaders.ID <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'orderheaders.ID') echo 'selected=selected' ;?> >Azonosító</option>
				<option value=costumers.name <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'costumers.name') echo 'selected=selected' ;?>>Ügyfél</option>
				<option value=orderheaders.date <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'orderheaders.date') echo 'selected=selected' ;?>>Rendelés dátuma</option>
				<option value=orderheaders.addr <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'orderheaders.addr') echo 'selected=selected' ;?>>Cím</option>
				<option value=status <?php if(isset($_POST['rendez']) && $_POST['rendez'] == 'status') echo 'selected=selected'; ?> >Státusz</option>
			</select>
			<br>
			<input type=checkbox name=desc value=DESC <?php if(isset($_POST['desc'])) echo 'checked'; ?> ><label for=desc>Csökkenő</label>
		</td>
	</tr>
	<tr>
		<td><input type=text name=ID value=<?php if(isset($_POST['ID'])){echo $_POST['ID'];}else{echo '';} ?> ></td>
		<td><input type=text name=cID value=<?php if(isset($_POST['cID'])){echo $_POST['cID'];}else{echo '';} ?> ></td>
		<td><input type=text name=date value=<?php if(isset($_POST['date'])){echo $_POST['date'];}else{echo '';} ?> ></td>
		<td><input type=text name=addr value=<?php if(isset($_POST['addr'])){echo $_POST['addr'];}else{echo '';} ?> ></td>
		<td>
			<select name=status>
				<option value=act <?php if(isset($_POST['status']) && $_POST['status'] == 'act') echo 'selected=selected'; ?> >Aktív</option>
				<option value=com <?php if(isset($_POST['status']) && $_POST['status'] == 'com') echo 'selected=selected'; ?> >Teljesített</option>
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
	$ID = (isset($_POST['ID'])) ? '"%'.$_POST['ID'].'%"' :  '"%"' ;
	$cID = (isset($_POST['cID'])) ? '"%'.$_POST['cID'].'%"' :  '"%"' ;
	$date = (isset($_POST['date'])) ? '"%'.$_POST['date'].'%"' :  '"%"' ;
	$addr = (isset($_POST['addr'])) ?  '"%'.$_POST['addr'].'%"' :  '"%"' ;
	$rendez = (isset($_POST['rendez'])) ? $_POST['rendez'] : 'orderheaders.ID';
	$status = (isset($_POST['status']) && $_POST['status'] !== 'all') ? '"%'.$_POST['status'].'%"' : '"%"';
	$desc = (isset($_POST['desc'])) ? $_POST['desc'] : '';
	if($db = mysqli_connect('localhost','root','')){
		$q = 'SELECT orderheaders.ID AS ID, costumers.name AS name, orderheaders.date AS date, orderheaders.addr AS addr , orderheaders.status AS status
			FROM orderheaders 
			INNER JOIN costumers ON costumers.ID = orderheaders.customerID 
			WHERE orderheaders.ID LIKE '.$ID.' AND costumers.name LIKE '.$cID.' AND orderheaders.date LIKE '.$date.' AND orderheaders.addr LIKE '.$addr.' AND status LIKE '.$status.' ORDER BY '.$rendez.' '.$desc;
		echo '<form method=POST action=rend.php target=_blank>';
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				echo '<tr class=list><td>'.$row['ID'].'</td><td>'.$row['name'].'</td><td>'.$row['date'].'</td><td>'.$row['addr'].'</td><td>';
				if($row['status']=='act'){
					echo 'Aktív';
				}else{
					echo'Teljesítve';
				}
				echo'</td><td><button type=submit name=submit value='.$row['ID'].'>Megnéz</button></td></tr>';
			}
		}
	}
	?>
	</form>
	</table>
</body>
</html>