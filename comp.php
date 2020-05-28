<?php
	session_start();
	error_reporting(0);
	if(!(isset($_SESSION['user']))) header('Location:index.php');
	//Teljesítés regisztrálása adatbázisba
	
	if($db = mysqli_connect('localhost','root','','raktar')){
		$dID = $_POST['submit'];
		$q = 'UPDATE deliveryhead SET status="com" WHERE deliveryID = '.$dID;
		$db->query($q);
		$q = 'SELECT orderID FROM deliverybody WHERE deliveryID ='.$dID;
		if ($res= $db->query($q)) {
			while ($row = $res->fetch_assoc()) {
				$q = 'UPDATE orderheaders SET status="com" WHERE ID ='.$row['orderID'];
				$db->query($q);
			}
		}		
	}
	header('Location:aktiv.php');
?>