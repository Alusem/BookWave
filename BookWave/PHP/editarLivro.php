<?php
session_start();
require 'conexao.php';
global $pdo;

// Obter dados do formulário
$idLivro = isset($_POST['idLivro']) ? $_POST['idLivro'] : null;
$idcategoria = isset($_POST['idcategoria']) ? $_POST['idcategoria'] : null;
$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
$autor = isset($_POST['autor']) ? $_POST['autor'] : null;
$genero = isset($_POST['genero']) ? $_POST['genero'] : null;
$editora = isset($_POST['editora']) ? $_POST['editora'] : null;
$edicao = isset($_POST['edicao']) ? $_POST['edicao'] : null;
$numeroPaginas = isset($_POST['numeroPaginas']) ? $_POST['numeroPaginas'] : null;
$dataPublicacao = isset($_POST['dataPublicacao']) ? $_POST['dataPublicacao'] : null;

// Limpar erros anteriores da sessão
unset($_SESSION['errosReportados']);
unset($_SESSION['camposForm']);
unset($_SESSION['cadastroRealizado']);

$erros = [];

// Verifique se o ID do livro foi fornecido
if (empty($idLivro)) {
    $erros[] = utf8_encode('ID do livro não foi fornecido.');
}

// Verifique se o ID da categoria foi fornecido
if (empty($idcategoria)) {
    $erros[] = utf8_encode('ID da categoria não foi fornecido.');
}

// Aqui você pode adicionar validações adicionais para outros campos obrigatórios, se necessário

if (count($erros) > 0) {
    $_SESSION['errosReportados'] = $erros;
    header("Location: ../VIEW/editarLivro.php?idLivro=$idLivro");
    exit;
} else {
    try {
        // Preparar e executar a consulta SQL para atualizar o livro
        $stmt = $pdo->prepare("UPDATE livro SET idcategoria = :idcategoria, titulo = :titulo, autor = :autor, genero = :genero, editora = :editora, edicao = :edicao, numeroPaginas = :numeroPaginas, dataPublicacao = :dataPublicacao WHERE idLivros = :idLivro");
        $stmt->execute(array(
            ':idLivro' => $idLivro,
            ':idcategoria' => $idcategoria,
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':genero' => $genero,
            ':editora' => $editora,
            ':edicao' => $edicao,
            ':numeroPaginas' => $numeroPaginas,
            ':dataPublicacao' => $dataPublicacao
        ));
        
        // Atualizar a quantidade de livros na categoria associada
        $stmt = $pdo->prepare("UPDATE categoria SET quantidadeLivros = (SELECT COUNT(*) FROM livro WHERE idcategoria = :idcategoria) WHERE idCategoria = :idcategoria");
        $stmt->execute(array(':idcategoria' => $idcategoria));
        
        // Define uma mensagem de sucesso
        $_SESSION['cadastroRealizado'] = utf8_encode('Livro editado com sucesso!');
        
        // Redireciona de volta para a página de livros
        header("Location: ../VIEW/livros.php");
        exit;
    } catch (PDOException $e) {
        // Se houver uma exceção PDO, exibe uma mensagem de erro
        $_SESSION['errosReportados'] = [utf8_encode('Erro ao editar livro: ' . $e->getMessage())];
        header("Location: ../VIEW/editarLivro.php?idLivro=$idLivro");
        exit;
    }
}
?>
