<?php 
	session_start();	
	error_reporting(0);
	$name = preg_replace("/[^a-z]+/", "",$_POST['uname']);
	$pw = preg_replace("/[^a-z]+/", "",$_POST['pass']);	
	
	//Sima bejelntkeztetés, ha van users táblában tovább engedi egyébként visszadobja.
	//Session változókban: bejelentkezett felhasználó neve és bejelentkezés dátuma
	if($db = mysqli_connect('localhost','root','')){
		$q = 'SELECT * FROM torontali.users WHERE users.name LIKE "'.$name.'" AND users.password LIKE "'.$pw.'";';
		if($res = $db->query($q)){
			if($res->num_rows == 1){
				$_SESSION['user'] = $name;
				$_SESSION['login'] = date('Y/m/d').'  '.date('H:i:s');
				header('Location:splash.php');
				exit();
			}
		}
	}
	if($_SESSION['tries'] == null){
		$_SESSION['tries'] = 0;
	}
	$_SESSION['tries']++;
	header('Location:index.php');
	exit();
?>