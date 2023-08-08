<?php
	
	$host 		= "localhost";
	$dbname 	= "ukas";
	$charset 	= "utf8";
	$root 		= "root";
	$password 	= "";

	try{
		$db = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset;", $root, $password);
	}catch(PDOExeption $error){
		echo $error->getMessage();
	}

	// CSRF Token
	if (!$_POST) {
		$_SESSION['_token'] = md5(time() . rand(0, 999));
	}
?>
