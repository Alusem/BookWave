<?php

	error_reporting(E_ALL & ~E_NOTICE);
	require 'conexao.php';
	global $pdo;

class Login {
	// DEFININDO VARIÁVEIS
	private $SenhaUsuario, $tabela, $campoLogin, $campoSenha;
	public $LoginUsuario, $msgErro;
	
	// DEFINIR AS INFORMAÇÕES DA CLASSE
	function login_user_params($tabela = "usuarios", $campoLogin = "login", $campoSenha = "senha") {
		$this->tabela = $tabela;
		$this->campoLogin = $campoLogin;
		$this->campoSenha = $campoSenha;
	}
	
	// FAZENDO LOGIN DO USUARIO
	function logar($login,$senha) {
		try{

			require 'conexao.php';
			global $pdo;

			// Informações do formulário
			$this->SenhaUsuario = $senha;
			$this->LoginUsuario = $login;
			
			$consulta = $pdo->query("SELECT ".$this->campoLogin.",".$this->campoSenha." FROM ".$this->tabela." WHERE ".$this->campoLogin." = '".$this->LoginUsuario."' LIMIT 0,1");
			$campos = $consulta->fetch(PDO::FETCH_ASSOC);

			// Se o usuário existir
			if($campos != 0):

				// Se a senha estiver incorreta
				$consulta2 = $pdo->query("SELECT ".$this->campoSenha." FROM ".$this->tabela." WHERE ".$this->campoLogin." = '".$this->LoginUsuario."' LIMIT 0,1");
				$campos2 = $consulta2->fetch(PDO::FETCH_ASSOC);

				if(MD5($senha) != $campos2['senha']):
					session_start();
					$erros[] = utf8_encode('Senha inv&aacute;lida');
					$_SESSION['errosReportados'] = $erros;
					header("Location: ../VIEW/login.php");

				// Se a senha estiver correta
				else:

					// Coloca as informações em sessões
					session_start();
					$_SESSION['LoginUsuario'] = $login;
					$_SESSION['SenhaUsuario'] = $senha;

					// Se for necessário redirecionar
					header("Location: ../VIEW/home.php");
				endif;

			// Se o usuário não existir
			else:
				session_start();
				$erros[] = utf8_encode('Usuario inv&aacute;lido');
				$_SESSION['errosReportados'] = $erros;
				header("Location: ../VIEW/login.php");
				endif;

		}catch(Exception $e){
			echo "Erro: ".$e->getMessage()." - Arquivo: ".$e->getFile()." - Linha: ".$e->getLine();
		}
	}
	
	// VERIFICA SE O USUÁRIO ESTÁ LOGADO
	function verificar($redireciona = false) {
		session_start();
		// Se estiver logado
		if(isset($_SESSION['LoginUsuario']) and isset($_SESSION['SenhaUsuario'])):
			global $LoginUsuario;
			$LoginUsuario = $_SESSION["LoginUsuario"];
			return true;
		// Se não estiver logado
		else:
			// Se for necessário redirecionar
			if ($redireciona):
				header("Location: ".$redireciona."");
			endif;
			return false;
			exit;
		endif;
	}
	
	// LOGOUT
	function logout($redireciona = false) {
		
		// Limpa a Sessão
		$_SESSION = array(); 			 
		// Destroi a Sessão
		session_destroy();
		// Modifica o ID da Sessão
		//session_regenerate_id();
		// Se for necessário redirecionar
		if ($redireciona):
			header("Location: ".$redireciona."");
			exit;
		endif;
	}
}
?>