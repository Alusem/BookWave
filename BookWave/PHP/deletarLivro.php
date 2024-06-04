<?php
session_start();
require_once("../PHP/login.class.php");
$login = new Login();
$login->verificar("login.php");    

require '../PHP/conexao.php';
global $pdo;

$idLivro = isset($_POST['idLivro']) ? $_POST['idLivro'] : null;

// Limpar erros anteriores da sessão
unset($_SESSION['errosReportados']);
unset($_SESSION['cadastroRealizado']);

$erros = [];

if (empty($idLivro)) {
    $erros[] = "ID do livro não foi fornecido.";
}

if (count($erros) > 0) {
    $_SESSION['errosReportados'] = $erros;
    header("Location: ../VIEW/editarLivro.php?idLivro=$idLivro");
    exit;
} else {
    try {
        // Obter o ID da categoria do livro que está sendo deletado
        $stmt = $pdo->prepare("SELECT idcategoria FROM livro WHERE idLivros = :idLivro");
        $stmt->bindValue(":idLivro", $idLivro);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idcategoria = $row['idcategoria'];

        // Atualizar a quantidade de livros na categoria correspondente
        $stmt = $pdo->prepare("UPDATE categoria SET quantidadeLivros = quantidadeLivros - 1 WHERE idCategoria = :idcategoria");
        $stmt->bindValue(":idcategoria", $idcategoria);
        $stmt->execute();

        // Excluir o livro do banco de dados
        $stmt = $pdo->prepare("DELETE FROM livro WHERE idLivros = :idLivro");
        $stmt->bindValue(":idLivro", $idLivro);
        $stmt->execute();

        $_SESSION['cadastroRealizado'] = "Livro deletado com sucesso!";
        header("Location: ../VIEW/livros.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errosReportados'] = ["Erro ao deletar livro: " . $e->getMessage()];
        header("Location: ../VIEW/editarLivro.php?idLivro=$idLivro");
        exit;
    }
}
?>
