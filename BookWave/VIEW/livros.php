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
    <meta charset="utf-8" />
    <title>Livros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/listas.css">
    <script src="https://kit.fontawesome.com/4642f198d2.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
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

<h2> Livros </h2>

<div class="tabela-botao">
    <table id="tabelaF">
        <thead>
           <form method="post" action="livros.php" >
                <tr>
                    <th><input class="form-control" name="titulo" id="titulo" placeholder="Título"></th>
                    <th><button type="submit" class="btn btn-primary">Buscar</button></th>
                </tr>
                <tr>
                    <th>Capa</th>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Gênero</th>
                    <th>Editora</th>
                    <th>Edição</th>
                    <th>Publicação</th>
                    <th>Ações</th>
                </tr>   
           </form>
        </thead>
        <tbody>
        <?php
            if (isset($_POST['titulo'])){
                $pesquisaLivro = addslashes($_POST['titulo']);
                $_POST['titulo'] = "";
            } else {
                $pesquisaLivro = "";
            }

            $consultaLivro = $pdo->query("SELECT * FROM livro l LEFT JOIN categoria c ON c.idCategoria = l.idCategoria WHERE titulo LIKE '%" . $pesquisaLivro . "%' OR autor LIKE '%" . $pesquisaLivro . "%' OR genero LIKE '%" . $pesquisaLivro . "%' OR editora LIKE '%" . $pesquisaLivro . "%'");

            while ($linhaLivro = $consultaLivro->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <tr>
                <td class="capa-livro">
                    <img src="<?php echo $linhaLivro['capa']; ?>" alt="Capa do Livro" style="max-width: 150px; height: auto;">
                </td>
                <td><?php echo !empty($linhaLivro['idcategoria']) ? $linhaLivro['nome'] : "Sem categoria"; ?></td>
                <td><?php echo $linhaLivro['titulo']; ?></td>
                <td><?php echo $linhaLivro['autor']; ?></td>
                <td><?php echo $linhaLivro['genero']; ?></td>
                <td><?php echo $linhaLivro['editora']; ?></td>
                <td><?php echo $linhaLivro['edicao']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($linhaLivro['dataPublicacao'])); ?></td>
                
                <td class="botoes-tabela">
                    <div class="container_acoes">                                
                        <form action="editarLivro.php" class="acao">
                            <input type="hidden" name="idLivro" value="<?php echo $linhaLivro['idlivros'];?>">
                            <div class="btn-editar-livros">  
                                <button class="input-botao">Editar</button>
                            </div>
                        </form>

                        <form action="deletarLivro.php" class="acao">
                            <input type="hidden" name="idLivro" value="<?php echo $linhaLivro['idlivros'];?>">
                            <div class="btn-deletar-livros">
                                <button class="input-botao">Deletar</button>
                            </div>
                        </form>  
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <br>

    <form action="cadastrarLivro.php">
        <div class="btn-cadastrar-livro">
            <button class="input-botao">Cadastrar Livro</button>
        </div>
    </form>
</div>
</body>
</html>
