<?php
session_start();
require 'conexao.php';
global $pdo;

$nome = addslashes($_POST['nome']);

// Limpar erros anteriores da sessão
unset($_SESSION['errosReportados']);
unset($_SESSION['camposForm']);
unset($_SESSION['cadastroRealizado']);

$erros = [];

if (strlen($nome) == 0) {
    $erros[] = utf8_encode('Preencha o campo nome.');
}

if (isset($erros) && count($erros) > 0) {
    $_SESSION['camposForm'] = $_POST;
    $_SESSION['errosReportados'] = $erros;
    header("Location: ../VIEW/cadastrarCategoria.php");
    exit;
} else {
    try {
        $stmt = $pdo->prepare("INSERT INTO categoria (nome) VALUES (:nome)");
        $stmt->execute(array(
            ':nome' => $nome
        ));
        
        $_SESSION['cadastroRealizado'] = utf8_encode('Categoria cadastrada com sucesso!');
        header("Location: ../VIEW/categorias.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errosReportados'] = [utf8_encode('Erro ao cadastrar categoria: ' . $e->getMessage())];
        header("Location: ../VIEW/cadastrarCategoria.php");
        exit;
    }
}
?>
