<?php
    require_once("../PHP/login.class.php");
    $login = new Login();
    $login->verificar("login.php");

    require '../PHP/conexao.php';
    global $pdo;

    // Consulta todas as categorias
    $sql2 = $pdo->query("SELECT * FROM categoria");
    $categorias = $sql2->fetchAll(PDO::FETCH_ASSOC);

    // Inicializa variáveis
    $erros = [];
    $sucesso = '';
    $campos = [];

    // Verifica se há erros, sucesso ou campos preenchidos
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
    <title>Cadastrar Categoria</title>
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
                    // Verifica se o usuário é administrador
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

    <div><h2>Cadastrar Categoria</h2></div>

    <div>
        <?php
            // Exibe erros e sucesso, se houver
            if (!empty($erros)) {
                foreach ($erros as $erro) {
                    echo $erro . "<br>";
                }
            }

            if (!empty($sucesso)) {
                echo $sucesso . "<br>";
                unset($sucesso); // Limpa mensagem de sucesso após exibição
            }
        ?>

        <div class="centro-cadastro">
            <form id="form_cadastro" enctype="multipart/form-data" method="POST" action="../PHP/criarCategoria.php">  
                
                <div>
                    <label>Nome</label><br>
                    <input class="input" minlength="1" type="text" name="nome" id="inputNome" placeholder="Nome" value="<?php echo isset($campos['nome']) ? $campos['nome'] : ''; ?>" required><br>
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
