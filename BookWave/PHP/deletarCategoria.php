<?php
session_start();
require 'conexao.php';
global $pdo;

$idCategoria = isset($_POST['idCategoria']) ? $_POST['idCategoria'] : null;

// Limpar erros anteriores da sessão
unset($_SESSION['errosReportados']);
unset($_SESSION['cadastroRealizado']);

$erros = [];

if (empty($idCategoria)) {
    $erros[] = utf8_encode('ID da categoria não foi fornecido.');
}

if (count($erros) > 0) {
    $_SESSION['errosReportados'] = $erros;
    header("Location: ../VIEW/editarCategoria.php?idCategoria=$idCategoria");
    exit;
} else {
    try {
        // Verificar se a categoria possui livros associados
        $stmt = $pdo->prepare("SELECT COUNT(*) AS totalLivros FROM livro WHERE idcategoria = :idCategoria");
        $stmt->bindValue(":idCategoria", $idCategoria);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalLivros = $row['totalLivros'];

        // Se a categoria possuir livros, mostrar mensagem de erro e redirecionar
        if ($totalLivros > 0) {
            $_SESSION['errosReportados'] = [utf8_encode('Não é possível deletar a categoria pois ela possui livros associados.')];
            header("Location: ../VIEW/editarCategoria.php?idCategoria=$idCategoria");
            exit;
        }

        // Caso contrário, deletar a categoria
        $stmt = $pdo->prepare("DELETE FROM categoria WHERE idCategoria = :idCategoria");
        $stmt->execute(array(
            ':idCategoria' => $idCategoria
        ));
        
        $_SESSION['cadastroRealizado'] = utf8_encode('Categoria deletada com sucesso!');
        header("Location: ../VIEW/categorias.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errosReportados'] = [utf8_encode('Erro ao deletar categoria: ' . $e->getMessage())];
        header("Location: ../VIEW/editarCategoria.php?idCategoria=$idCategoria");
        exit;
    }
}
?>
