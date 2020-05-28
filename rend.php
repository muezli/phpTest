<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//rendelések fejlécének kibontása, rendelések tartalmának kifejtése
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Rendelés</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<table align=center>
	<tr>
<?php
	$orderID = $_POST['submit'];
	$npack = 0;
	$nweight = 0;
	if($db = mysqli_connect('localhost','root','','raktar')){
		echo '<td colspan=5><h3>'.$orderID. ' azonosító számú rendelés</h3></td></tr>';
		$q = 'SELECT costumers.name, costumers.addr FROM costumers INNER JOIN orderheaders ON costumers.ID=orderheaders.customerID WHERE orderheaders.ID = '.$orderID;
		if ($res= $db->query($q)) {
			$row = $res->fetch_assoc();
			echo '<tr><td colspan=2>Megrendelő neve: '.$row['name'].'</td><td colspan=3>Megrendelő címe: '.$row['addr'].'</td></tr><tr><td colspan=5><hr></td></tr>';
		}
		$q = 'SELECT orderbody.orderID, orderbody.productID, stock.name, orderbody.quantity, stock.weight, (stock.weight*orderbody.quantity) AS TotalWeight FROM orderbody INNER JOIN orderheaders ON orderheaders.ID = orderbody.orderID INNER JOIN stock ON stock.ID = orderbody.productID WHERE orderbody.orderID = '.$orderID;
		echo '<tr class=headline><td>Cikkszám</td><td>Terméknév</td><td>Mennyiség</td><td>Egység Tömeg</td><td>Össz. tömeg</td></tr>';
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				$npack += $row['quantity'];
				$nweight += $row['TotalWeight'];
				echo '<tr class=list><td>'.$row['productID'].'</td><td>'.$row['name'].'</td><td>'.$row['quantity'].' db</td><td>'.$row['weight'].' Kg</td><td>'.$row['TotalWeight'].' Kg</td></tr>';
			}
		}	
	}
	echo '<tr><td colspan=5><hr></td></tr><tr><td >Összesen</td><td>-</td><td>'.$npack.' db</td><td>-</td><td>'.$nweight.' Kg</td></tr>';
?>
</table>
</body>
</html>