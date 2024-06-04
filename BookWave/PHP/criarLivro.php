<?php
    session_start();
    require 'conexao.php';
    global $pdo;

    $idcategoria = isset($_POST['idcategoria']) ? addslashes($_POST['idcategoria']) : null;
    $titulo = addslashes($_POST['titulo']);
    $autor = addslashes($_POST['autor']);
    $genero = addslashes($_POST['genero']);
    $editora = addslashes($_POST['editora']);
    $edicao = addslashes($_POST['edicao']);
    $numeroPaginas = addslashes($_POST['numeroPaginas']);
    $dataPublicacao = addslashes($_POST['dataPublicacao']);
    $diretorioFileImg = "";

    // Limpar erros anteriores da sessão
    unset($_SESSION['errosReportados']);
    unset($_SESSION['camposForm']);
    unset($_SESSION['cadastroRealizado']);

    $erros = [];

    if (strlen($idcategoria) == 0 && $idcategoria !== '0') {
        $erros[] = utf8_encode('Preencha o campo categoria.');
    }
    if (strlen($_POST['titulo']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo título.');
    }
    if (strlen($_POST['autor']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo autor.');
    }
    if (strlen($_POST['genero']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo gênero.');
    }
    if (strlen($_POST['editora']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo editora.');
    }
    if (strlen($_POST['edicao']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo edição.');
    }
    if (strlen($_POST['numeroPaginas']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo número de páginas.');
    }
    if (strlen($_POST['dataPublicacao']) ==  0) {
        $erros[] = utf8_encode('Preencha o campo data de publicação.');
    }
    
    // Verificar se a categoria existe, exceto quando é "Nenhuma" (0)
    if ($idcategoria !== '0') {
        try {
            $stmt = $pdo->prepare("SELECT * FROM categoria WHERE idcategoria = :idcategoria");
            $stmt->bindValue(':idcategoria', $idcategoria);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $erros[] = utf8_encode('Categoria inválida.');
            }
        } catch (PDOException $e) {
            $erros[] = utf8_encode('Erro ao verificar categoria: ' . $e->getMessage());
        }
    }

    if (isset($erros) && count($erros) > 0) {
        $_SESSION['camposForm'] = $_POST;
        $_SESSION['errosReportados'] = $erros;
        header("Location: ../VIEW/cadastrarLivro.php");
        exit;
    } else {
        if (isset($_FILES["capa"]['name']) && $_FILES["capa"]['name'] != null) {
            $extensao = strtolower(substr($_FILES['capa']['name'], -4));
            $novo_nome = md5(time()) . $extensao;
            $diretorio = "../IMG/Fotos_Livros/";

            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            $retorno = move_uploaded_file($_FILES['capa']['tmp_name'], $diretorioFileImg = $diretorio . $novo_nome);
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO livro (idcategoria, titulo, autor, genero, editora, edicao, numeroPaginas, dataPublicacao, capa) VALUES (:idcategoria, :titulo, :autor, :genero, :editora, :edicao, :numeroPaginas, :dataPublicacao, :capa)");
            $stmt->execute(array(
                ':idcategoria' => $idcategoria === '0' ? null : $idcategoria,
                ':titulo' => $titulo,
                ':autor' => $autor,
                ':genero' => $genero,
                ':editora' => $editora,
                ':edicao' => $edicao,
                ':numeroPaginas' => $numeroPaginas,
                ':dataPublicacao' => $dataPublicacao,
                ':capa' => $diretorioFileImg
            ));
        
            // Obtém o ID do livro recém-adicionado
            $idLivro = $pdo->lastInsertId();
        
            // Se a categoria não for "Nenhuma", atualiza a quantidade de livros na categoria
            if ($idcategoria !== '0') {
                // Verifica se a quantidadeLivros é NULL e define como 0 se for
                $stmt = $pdo->prepare("UPDATE categoria SET quantidadeLivros = COALESCE(quantidadeLivros, 0) + 1 WHERE idcategoria = :idcategoria");
                $stmt->bindValue(':idcategoria', $idcategoria);
                $stmt->execute();
            }

            
            $_SESSION['cadastroRealizado'] = utf8_encode('Livro cadastrado com sucesso!');
            header("Location: ../VIEW/livros.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['errosReportados'] = [utf8_encode('Erro ao cadastrar livro: ' . $e->getMessage())];
            header("Location: ../VIEW/cadastrarLivro.php");
            exit;
        }
        
    }
?>
