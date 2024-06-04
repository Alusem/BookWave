<?php

	$localhost = "127.0.0.1:3306";
	$user = "root";
	$passw = "";
	$banco = "bookwave";

	global $pdo;

	try{

		$pdo = new PDO("mysql:dbname=".$banco."; host=".$localhost, $user, $passw);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}catch(PDOException $e){
		echo "ERRO: ".$e->getMessage();
		exit;
	}


  