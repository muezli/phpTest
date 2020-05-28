<?php
	session_start();
	//error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//aktív szállítások generálását hajtja végre
	//TODO: egyszeri connect/ konstansok változókba
	
	//változók inicializálása
	$ordercounter = array();
	$trucks = array();
	$truckorderpairs = array();
	$availablepers = array();
	$perpointer = 0;
	$counter = 0;
	$weightlimit = 0;
	$lastID = 0;
	//aktív rendelések lekérdezése, rendelésenkénti összesített tömeggel
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT orderheaders.ID, SUM(orderbody.quantity*stock.weight) AS netW FROM orderheaders INNER JOIN orderbody ON orderbody.orderID = orderheaders.ID INNER JOIN stock ON stock.ID = orderbody.productID WHERE orderheaders.status LIKE "act" GROUP BY orderheaders.ID ORDER BY netW';
		if ($res= $db->query($q)) {
			while($row = $res->fetch_assoc()){
				$ordercounter[$counter]['ID'] = $row['ID'];
				$ordercounter[$counter]['netW'] = $row['netW'];
				$counter++;
			}
		}
	}
	
	//kaminok lekérdezése kapacitással
	$counter = 0;
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q ='SELECT plateID, capacityKG, capacityPerson FROM trucks WHERE status LIKE "act" ORDER BY capacityKG';
		if ($res= $db->query($q)) {
			while($row = $res->fetch_assoc()){
				$trucks[$counter]['plateID'] = $row['plateID'];
				$trucks[$counter]['capacityKG'] = $row['capacityKG'];
				$trucks[$counter]['capacityPerson'] = $row['capacityPerson'];
				$counter++;
			}
		}
	}
	
	//utolsó szállítási szám megkeresése
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT MAX(deliveryID) AS LD FROM deliveryhead';
		if ($res= $db->query($q)) {
			$row = $res->fetch_assoc();
			$lastID = $row['LD']+1;
		}
	}	
	
	//alkalmazottak bázisának építése
	$counter = 0;
	if($db = mysqli_connect('localhost','root','','raktar')){
		$q = 'SELECT ID FROM personel where status LIKE "a"';
		if ($res= $db->query($q)) {
			while($row = $res->fetch_assoc()){
				$availablepers[$counter]['ID'] = $row['ID'];
				$counter++;
			}
		}
	}
	
	//rendelések kaminokhoz rendelése tömeg figyelembe vételével, személyzet számának felvétele (250kilónként 1 ember)
	$counter = 0;
	for($i=0; $i<count($ordercounter); $i++){
		while($weightlimit+$ordercounter[$counter]['netW']<$trucks[$i]['capacityKG'] && $counter<count($ordercounter)){
			$weightlimit+=$ordercounter[$counter]['netW'];
			$truckorderpairs[$counter]['plateID'] = $trucks[$i]['plateID'];
			$truckorderpairs[$counter]['orderID'] = $ordercounter[$counter]['ID'];
			$truckorderpairs[$counter]['personel'] = ($weightlimit>250) ? 2 : 1;
			$counter++;
		}
		if($counter>=count($ordercounter)){
			$i = $counter;
		}
		$weightlimit = 0;
	}
	
	//adataok insertálása delivery táblákba
	if($db = mysqli_connect('localhost','root','','raktar')){
		for($i=0;$i<count($truckorderpairs);$i++){
			$q='INSERT INTO deliveryhead (status,truckID) VALUES ("act","'.$truckorderpairs[$i]['plateID'].'")';
			if ($res= $db->query($q)){
				$q = 'SELECT MAX(deliveryID) AS dID FROM deliveryhead';
				if($res=$db->query($q)){
					$row = $res->fetch_assoc();
					$lastID = $row['dID'];
					$q='INSERT INTO deliverybody (deliveryID,orderID) VALUES ("'.$lastID.'","'.$truckorderpairs[$i]['orderID'].'")';
					$db->query($q);
				}
			}
			$currentID = $truckorderpairs[$i]['plateID'];
			while($currentID==$truckorderpairs[$i+1]['plateID']){
				$i++;
				$q='INSERT INTO deliverybody (deliveryID,orderID) VALUES ("'.$lastID.'","'.$truckorderpairs[$i]['orderID'].'")';
				$db->query($q);
			}
			$q = 'INSERT INTO deliveryback (deliveryID, personelID) VALUES ("'.$lastID.'","'.$availablepers[$perpointer]['ID'].'")';
			$db->query($q);
			$perpointer++;
			if($truckorderpairs[$i]['personel'] == 2){
				$q = 'INSERT INTO deliveryback (deliveryID, personelID) VALUES ("'.$lastID.'","'.$availablepers[$perpointer]['ID'].'")';
				$db->query($q);
				$perpointer++;
			}
			$lastID++;
		}
	}
	header('Location:aktiv.php');
	
?>