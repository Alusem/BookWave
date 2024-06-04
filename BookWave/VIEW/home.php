<?php
    require_once("../PHP/login.class.php");
    $login = new Login();
    $login->verificar("login.php");    

    require '../PHP/conexao.php';
    global $pdo;
?>

<!DOCTYPE html>
<html lang="pt-br">
   <head>
       <meta charset="utf-8"/>
       <title>Home</title>
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
       <link rel="stylesheet" href="../CSS/listas.css">
       <link rel="stylesheet" href="../CSS/home.css">
   </head>
   <body>

        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <h1>BookWave</h1>

                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                            <a class="nav-link" href="livros.php">Livros</a>
                            <a class="nav-link" href="categorias.php">Categorias</a>
                        </div>
                    </div>

                    <div class="Usuario">
                        <a><?php echo $LoginUsuario; ?></a>
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

        <div class="container mt-5">
            <div class="row">
                <?php
                    // Consulta para obter todas as categorias e a quantidade de livros em cada uma
                    $stmt = $pdo->query("SELECT c.idcategoria, c.nome, COUNT(l.idLivros) AS quantidadeLivros 
                                        FROM categoria c 
                                        LEFT JOIN livro l ON c.idcategoria = l.idcategoria 
                                        GROUP BY c.idcategoria, c.nome");
                    
                    // Iterar sobre os resultados da consulta
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $idcategoria = $row['idcategoria'];
                        $nomeCategoria = $row['nome'];
                        $quantidadeLivros = $row['quantidadeLivros'];
                ?>
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $nomeCategoria; ?></h5>
                            <p class="card-text">Quantidade de livros: <?php echo $quantidadeLivros; ?></p>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>

    </body>
</html>
