<?php
    session_start();
	require 'conexao.php';
	global $pdo;
    
    try {
            
        $id = addslashes($_GET['idUsuarios']);

        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE idUsuarios = :id');
    
        $stmt->bindParam(':id', $id);
            
        if ($id > 0  && $id != 1){
            $retorno  = $stmt->execute();
        }else{
            ?> <script>alert("Este Usuario não pode ser deletado!");</script> <?php
            ?> <script>window.location.href = "../VIEW/Admin.php";</script> <?php
        }
        if (! $retorno){
            $erros[] = utf8_encode('Erro, tente novamente!');
        }else {
            ?> <script>alert("Usuario deletado com sucesso!");</script> <?php
            ?> <script>window.location.href = "../VIEW/Admin.php";</script> <?php
        }

    }catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }