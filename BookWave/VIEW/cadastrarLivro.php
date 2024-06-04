<?php
    require_once("../PHP/login.class.php");
    $login = new Login();
    $login->verificar("login.php");

    require '../PHP/conexao.php';
    global $pdo;

    $sql2 = $pdo->query("SELECT * FROM categoria");
    $categorias = $sql2->fetchAll(PDO::FETCH_ASSOC);

    // Inicializa variáveis
    $erros = [];
    $sucesso = '';
    $campos = [];

    if(isset($_SESSION['errosReportados'])){
        $erros = $_SESSION['errosReportados'];
        unset($_SESSION['errosReportados']); // Limpa erros após exibição
    }

    if(isset($_SESSION['cadastroRealizado'])){
        $sucesso = $_SESSION['cadastroRealizado'];
        unset($_SESSION['cadastroRealizado']); // Limpa mensagem de sucesso após exibição
    }

    if(isset($_SESSION['camposForm'])){
        $campos = $_SESSION['camposForm'];
        unset($_SESSION['camposForm']); // Limpa campos após uso
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <title>Cadastrar Livro</title>
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
                    <a> <?php echo $LoginUsuario; ?> </a>
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

    <div><h2>Cadastrar Livro</h2></div>

    <div>
        <?php
            if (!empty($erros)) {
                foreach ($erros as $erro) {
                    echo $erro . "<br>";
                }
            }

            if (!empty($sucesso)) {
                echo $sucesso . "<br>";
            }
        ?>

        <div class="centro-cadastro">
            <form id="form_cadastro" enctype="multipart/form-data" method="POST" action="../PHP/criarLivro.php">  
                
                <div>
                    <label>Categoria</label><br>
                    <select class="input" name="idcategoria" id="inputCategoria" required>
                        <option value="">Selecione a categoria</option>
                        <option value="0">Nenhuma</option>
                        <?php
                        foreach ($categorias as $categoria) {
                            ?>
                            <option value="<?php echo $categoria['idcategoria']; ?>"> <?php echo $categoria['nome']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>Título</label><br>
                    <input class="input" minlength="1" type="text" name="titulo" id="inputTitulo" placeholder="Título" value="<?php echo isset($campos['titulo']) ? $campos['titulo'] : ''; ?>" required><br>
                </div>

                <div>
                    <label>Autor</label><br>
                    <input class="input" minlength="1" type="text" name="autor" id="inputAutor" placeholder="Autor" value="<?php echo isset($campos['autor']) ? $campos['autor'] : ''; ?>" required><br>
                </div>

                <div>
                    <label>Gênero</label><br>
                    <input class="input" minlength="1" type="text" name="genero" id="inputGenero" placeholder="Gênero" value="<?php echo isset($campos['genero']) ? $campos['genero'] : ''; ?>" required><br>
                </div>

                <div>
                    <label>Editora</label><br>
                    <input class="input" minlength="1" type="text" name="editora" id="inputEditora" placeholder="Editora" value="<?php echo isset($campos['editora']) ? $campos['editora'] : ''; ?>" required><br>
                </div>

                <div>
                    <label>Edição</label><br>
                    <input class="input" minlength="1" type="text" name="edicao" id="inputEdicao" placeholder="Edição" value="<?php echo isset($campos['edicao']) ? $campos['edicao'] : ''; ?>" required><br>
                </div>

                <div>
                    <label>Número de Páginas</label><br>
                    <input class="input" minlength="1" type="number" name="numeroPaginas" id="inputNumeroPaginas" placeholder="Número de Páginas" value="<?php echo isset($campos['numeroPaginas']) ? $campos['numeroPaginas'] : ''; ?>" required><br>
                </div>

                <div>
                    <label>Data de Publicação</label><br>
                    <input class="input" type="date" name="dataPublicacao" id="inputDataPublicacao" placeholder="Data de Publicação" value="<?php echo isset($campos['dataPublicacao']) ? $campos['dataPublicacao'] : ''; ?>" required><br>
                </div>

                <div class="foto_campo_input">
                    <label>Capa</label>
                    <input type="file" name="capa" class="form-control">
                </div>

                <br>

                <div class="btn1">
                    <button class="input-botao" type="submit">Cadastrar</button>
                </div>

            </form>   
        </div>
    </div>
</body>
</html>
