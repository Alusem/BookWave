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
       <title>Deletar Livro</title>
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
       <link rel="stylesheet" href="../CSS/forms.css">
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

        <div class="form-criar-crachas">
            <div><h2>Deletar Livro</h2></div>
                <div>
                    <?php
                    
                        $id = $_GET['idLivro'];       

                        $sql = "SELECT * FROM livro WHERE idLivros = :idLivro";
		                $sql = $pdo->prepare($sql);
		                $sql->bindValue("idLivro", $id);
                        $sql->execute();
                        $dados = $sql->fetch(PDO::FETCH_ASSOC);

                        if(is_array($_SESSION) && isset($_SESSION['errosReportados'])){
                            $erros = $_SESSION['errosReportados'];
                            foreach ($erros as $erro) {
                                echo $erro;
                                echo "<br>";
                            }
                        }

                        if (is_array($_SESSION) && isset($_SESSION['cadastroRealizado'])){
                            $sucesso = $_SESSION['cadastroRealizado'];
                            echo $sucesso;
                        }

                    ?>
            <div class="centro-cadastro">
                <form id="form_deletar" enctype="multipart/form-data"  method="POST" action="../PHP/deletarLivro.php">  
                
                    <div>
                        <input type="hidden" name="idLivro" value="<?php echo $id?>">
                    </div>

                    <div>
                        <label>Título</label><br>
                        <input class="input" minlength="1" type="text" name="titulo" id="inputTitulo" placeholder="Título" value="<?php echo $dados['titulo']; ?>" readonly><br>
                    </div>

                    <div>
                        <label>Autor</label><br>
                        <input class="input" minlength="1" type="text" name="autor" id="inputAutor" placeholder="Autor" value="<?php echo $dados['autor']; ?>" readonly><br>
                    </div>

                    <div>
                        <label>Gênero</label><br>
                        <input class="input" minlength="1" type="text" name="genero" id="inputGenero" placeholder="Gênero" value="<?php echo $dados['genero']; ?>" readonly><br>
                    </div>

                    <div>
                        <label>Editora</label><br>
                        <input class="input" minlength="1" type="text" name="editora" id="inputEditora" placeholder="Editora" value="<?php echo $dados['editora']; ?>" readonly><br>
                    </div>

                    <div>
                        <label>Edição</label><br>
                        <input class="input" minlength="1" type="text" name="edicao" id="inputEdicao" placeholder="Edição" value="<?php echo $dados['edicao']; ?>" readonly><br>
                    </div>

                    <div>
                        <label>Número de Páginas</label><br>
                        <input class="input" minlength="1" type="number" name="numeroPaginas" id="inputNumeroPaginas" placeholder="Número de Páginas" value="<?php echo $dados['numeroPaginas']; ?>" readonly><br>
                    </div>

                    <div>
                        <label>Data de Publicação</label><br>
                        <input class="input" type="date" name="dataPublicacao" id="inputDataPublicacao" placeholder="Data de Publicação" value="<?php echo $dados['dataPublicacao']; ?>" readonly><br>
                    </div>

                    <br>

                    <div class="btn1">
                        <button class="input-botão" onclick="return confirm('Tem certeza que deseja deletar este livro?');" type="submit">Deletar</button>
                    </div>
                </form>   
            <div>
    </body>
</html>
