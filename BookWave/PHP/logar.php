<?php
	require '../PHP/conexao.php';
	global $pdo;

if (strlen($_POST['senha']) ==  0){
	session_start();
	$erros[] = utf8_encode('Preencha o campo senha');
	$_SESSION['errosReportados'] = $erros;
	header("Location: ../VIEW/login.php");
}else{
	$senhaUser = $_POST['senha'];
	}


if (strlen($_POST['login']) == 0){
	session_start();
	$erros[] = utf8_encode('Preencha o campo login');
	$_SESSION['errosReportados'] = $erros;
	header("Location: ../VIEW/login.php");
	}else {
	$loginUser = $_POST['login'];
	}

if (strlen($_POST['login']) > 0 && strlen($_POST['senha']) > 0){
	require_once("login.class.php");		
	$login = new Login();
	$login->login_user_params();
	$logar = $login->logar($loginUser, $senhaUser);
}