<?php
session_start();
require 'conexao.php';
global $pdo;

$idCategoria = isset($_POST['idCategoria']) ? $_POST['idCategoria'] : null;
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;

// Limpar erros anteriores da sessão
unset($_SESSION['errosReportados']);
unset($_SESSION['cadastroRealizado']);

$erros = [];

if (empty($idCategoria)) {
    $erros[] = utf8_encode('ID da categoria não foi fornecido.');
}

// Verifique se os outros campos obrigatórios foram preenchidos
if (empty($nome)) {
    $erros[] = utf8_encode('Nome da categoria não foi fornecido.');
}

if (count($erros) > 0) {
    $_SESSION['errosReportados'] = $erros;
    header("Location: ../VIEW/editarCategoria.php?idCategoria=$idCategoria");
    exit;
} else {
    try {
        $stmt = $pdo->prepare("UPDATE categoria SET nome = :nome WHERE idCategoria = :idCategoria");
        $stmt->execute(array(
            ':idCategoria' => $idCategoria,
            ':nome' => $nome
        ));
        
        $_SESSION['cadastroRealizado'] = utf8_encode('Categoria editada com sucesso!');
        header("Location: ../VIEW/categorias.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errosReportados'] = [utf8_encode('Erro ao editar categoria: ' . $e->getMessage())];
        header("Location: ../VIEW/editarCategoria.php?idCategoria=$idCategoria");
        exit;
    }
}
?>
