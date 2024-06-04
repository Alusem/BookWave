<?php
session_start(); // Inicializa a sessão

require '../PHP/conexao.php';
global $pdo;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/forms.css">
    <title>Cadastro</title>
    <style>
        .navbar-nav {
            flex-direction: row;
        }
        .navbar-nav .nav-link {
            padding-right: .5rem;
            padding-left: .5rem;
        }
        .centro-cadastro {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn1 {
            text-align: center;
            margin-top: 20px;
        }
        .input-botao {
            font-size: 15px;
            color: #fff;
            text-transform: uppercase;
            width: 100%;
            height: 50px;
            border-radius: 25px;
            background: #007bff;
            border: none;
            margin: 0px;
            transition: all 0.4s;
        }
        .input-botao:hover {
            background: #0056b3;
        }
    </style>
</head>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center">
        <div class="container-fluid justify-content-center">
            <h1>BookWave</h1>
        </div>
    </nav>
</header>

<body>

    <h2 class="text-center">Cadastre-se</h2>

    <div class="centro-cadastro">
        <?php
        if (isset($_SESSION['errosReportados'])) {
            $erros = $_SESSION['errosReportados'];
            foreach ($erros as $erro) {
                echo $erro;
                echo "<br>";
            }
            unset($_SESSION['errosReportados']);
        }
        ?>
        <br>

        <div>
            <form method="POST" action="../PHP/cadastroAdmin.php">
                <div>
                    <input class="input" type="text" name="login" id="inputLogin" placeholder="Login"><br>
                    <label for="inputLogin"></label>
                </div>

                <div>
                    <input class="input" type="password" name="senha" id="inputSenha" placeholder="Senha"><br>
                    <label for="inputSenha"></label>
                </div>

                <div>
                    <input class="input" type="password" name="confirmarSenha" id="inputConfirmarSenha" placeholder="Confirmar senha"><br>
                    <label for="inputConfirmarSenha"></label>
                </div>

                <div>
                    <input class="input" type="email" name="email" id="inputEmail" placeholder="Email"><br>
                    <label for="inputEmail"></label>
                </div>

                <div class="btn1">
                    <button class="input-botao" type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
