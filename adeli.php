<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Szállítás</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<table align=center>
	<tr>
<?php
	$deliveryID = $_POST['submit'];
	$ordercounter = array();
	$netp = 0;
	$netw = 0;
	$counter = 0;
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT deliverybody.orderID, COUNT(deliverybody.orderID) AS length FROM deliverybody INNER JOIN orderbody ON orderbody.orderID = deliverybody.orderID INNER JOIN stock ON stock.ID = orderbody.productID WHERE deliverybody.deliveryID LIKE "'.$deliveryID.'" GROUP BY deliverybody.orderID ';
		if ($res= $db->query($q)) {
			while($row = $res->fetch_assoc()){
				$ordercounter[$counter]['orderID'] = $row['orderID'];
				$ordercounter[$counter]['length'] = $row['length'];
				$counter++;
			}
		}
	}

	
	if($db = mysqli_connect('localhost','root','','raktar')){
		echo '<td colspan=6><h3>'.$deliveryID. ' azonosító számú szállítás</h3></td></tr>';
		$q = 'SELECT deliveryhead.truckID, trucks.type  FROM deliveryhead INNER JOIN trucks ON trucks.plateID = deliveryhead.truckID WHERE deliveryhead.deliveryID LIKE "'.$deliveryID.'"';
		if ($res= $db->query($q)) {
			$row = $res->fetch_assoc();
			echo '<tr><td align=left>Kamion rendszáma: </td><td><b>'.$row['truckID'].'</b></td><td colspan=2></td><td>Kamion típusa: <td><b>'.$row['type'].'</b></td></tr>';
		}
	}
		
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT personel.name FROM personel INNER JOIN deliveryback ON deliveryback.personelID = personel.ID WHERE deliveryback.deliveryID LIKE "'.$deliveryID.'"';
		if ($res= $db->query($q)) {
			echo '<tr><td align=left>Szállítók: </td>';
			while ($row = $res->fetch_assoc()) {
			echo '<td><b>'.$row['name'].'</b></td>';
			}
			echo '</tr><tr><td colspan=6><hr></td></tr>';
		}
	}
	
	$counter = 1;	
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT deliverybody.orderID, orderbody.productID, orderbody.quantity, stock.name, stock.weight, (stock.weight*orderbody.quantity) AS netW FROM deliverybody INNER JOIN orderbody ON orderbody.orderID = deliverybody.orderID INNER JOIN stock ON stock.ID = orderbody.productID WHERE deliverybody.deliveryID LIKE "'.$deliveryID.'" ORDER BY deliverybody.orderID';
		echo '<tr class=headline><td>Rendelés szám</td><td>Termék cikkszám</td><td>Megnevezés</td><td>Rendelt mennyiség</td><td>Egység tömeg</td><td>Össz. tömeg</td></tr>';
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				$key=array_search($row['orderID'], array_column($ordercounter,'orderID'));
				if($counter == 1){
					echo '<tr class=list><td rowspan='.$ordercounter[$key]['length'].'>'.$row['orderID'].'</td>';
					echo '<td>'.$row['productID'].'</td><td>'.$row['name'].'</td><td>'.$row['quantity'].' db</td><td>'.$row['weight'].' Kg</td><td>'.$row['netW'].' Kg</td></tr>';
				}else{
					echo '<tr class=list><td>'.$row['productID'].'</td><td>'.$row['name'].'</td><td>'.$row['quantity'].' db</td><td>'.$row['weight'].' Kg</td><td>'.$row['netW'].' Kg</td></tr>';
				}
				$netw += $row['netW'];
				$netp += $row['quantity'];
				$counter = ($ordercounter[$key]['length'] == $counter) ? 1 : $counter+1;
			}
		}	
	echo '<tr><td colspan=6><hr></td></tr><tr><td>Összesen</td><td>-</td><td>-</td><td>'.$netp.' db</td><td>-</td><td>'.$netw.' Kg</td></tr>';
	}
?>
</table>
</body>
</html>