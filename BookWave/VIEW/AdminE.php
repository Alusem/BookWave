<?php

	require_once("../PHP/login.class.php");
    $login = new Login();
    $login->verificar("login.php");

    require '../PHP/conexao.php';
	global $pdo;
	
	$consulta = $pdo->query("SELECT isAdmin FROM usuarios WHERE '$LoginUsuario' = login");
	$campo = $consulta->fetch(PDO::FETCH_ASSOC);
	if($campo['isAdmin'] != 0 ){
		
		if (isset($_GET['idUsuarios'])){

			$id = $_GET['idUsuarios'];       

			$sql = "SELECT * FROM usuarios WHERE idUsuarios = :idUsuarios";
			$sql = $pdo->prepare($sql);
			$sql->bindValue("idUsuarios", $id);
			$sql->execute();
			$dados = $sql->fetch(PDO::FETCH_ASSOC);
		}
?>

			<!DOCTYPE html>
			<html lang="pt-br">
			<head>
				<meta charset="UTF-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
				<link rel="stylesheet" href="../CSS/forms.css">
				<title>Usuario Login</title>
			</head>
			<body>
				<header>
					<nav class="navbar navbar-expand-lg navbar-light bg-light">
						<div class="container-fluid">
							<h1>Sam Crach&aacute;s</h1>
                
							<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
								<div class="navbar-nav">
									<a class="nav-link active" aria-current="page" href="home.php">Home</a>
									<a class="nav-link" href="lista.php">Funcion&aacute;rios</a>
									<a class="nav-link" href="empresas.php">Empresas</a>
									<a class="nav-link" href="configuracoes.php">Configura&ccedil;oes</a>
								</div>
							</div>

							<div class="Usuario">
								<a> <?php echo $LoginUsuario; ?> </a></li>
							</div>

							<?php
								$consulta = $pdo->query("SELECT isAdmin FROM usuarios WHERE '$LoginUsuario' = login");
								$campo = $consulta->fetch(PDO::FETCH_ASSOC);
								if ($campo['isAdmin'] != 0){
							?>

							<div class="Usuario">
								<a href="Admin.php"> <?php echo "ADM";?> </a>
							</div>

							<?php
								}
							?>

							<div class="Usuario">
								<a href="login.php">Sair</a>
							</div>

						</div>
					</nav>
				</header>

				<h2> Editar Usu&aacute;rio </h2>

				<div class=centro-cadastro>
					<?php
					
					if(is_array($_SESSION) && isset($_SESSION['errosReportados'])){
						$erros = $_SESSION['errosReportados'];
							foreach ($erros as $erro) {
								echo $erro;
								echo "<br>";
							}
						}
					?>

				<br> 

					<div>
						<form method="POST" action="../PHP/editarAdmin.php">

							<div>
								<input type="hidden" name="idUsuarios" value="<?php echo $id?>">
							</div>
							
							<div>
								<input class="input" type="text" name="login" id="inputLogin" placeholder="Login" value="<?php if(isset($dados)){ echo ($dados['login']);} ?>"><br>
								<label for="inputLogin"></label>
							</div>

							<div>
								<input class="input" type="password" name="senha" id="inputSenha" placeholder="Senha" value=""><br>
								<label for="inputSenha"></label>
							</div>

							<div>
								<input class="input" type="password" name="confirmarSenha" id="inputSenha" placeholder="Confirmar senha" value=""><br>
								<label for="inputSenha"></label>
							</div>

							<div>
								<input class="input" type="email" name="email" id="inputLogin" placeholder="Email" value="<?php if(isset($dados)){ echo ($dados['email']);} ?>"><br>
								<label for="inputLogin"></label>
							</div>

							<div class="btn1">
								<button class="input-botao" type="submit">Editar</button>
							</div>
						</form>
					</div>
				</div>
			</body>
			</html>

		<?php
    }else{
		?> <script>alert("Usuario sem acesso!");</script> <?php
        ?> <script>window.location.href = "../VIEW/home.php";</script> <?php
	}
?>

