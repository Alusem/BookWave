<?php
    session_start();
    require 'conexao.php';
    global $pdo;

    $login = addslashes($_POST['login']);
    $senha = addslashes(MD5($_POST['senha']));  
    $email = addslashes($_POST['email']);  
    $id = addslashes($_POST['idUsuarios']);

    if (strlen($_POST['login']) ==  0){
        $erros[] = utf8_encode('Preencha o campo login ');
    } else {
        $sql = "SELECT * FROM usuarios WHERE login = :login";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":login", $login);
        $sql->execute();
        $retorno = $sql->fetch(PDO::FETCH_ASSOC);

        if($sql->rowCount() > 0 && $id != $retorno["idUsuarios"]){
            $erros[] = utf8_encode('Usuário já cadastrado.');
        }
    }

    if (strlen($_POST['senha']) ==  0){
        $erros[] = utf8_encode('Preencha o campo senha');
    }

    if (strlen($_POST['confirmarSenha']) ==  0){
        $erros[] = utf8_encode('Preencha o campo confirmar senha');
    }

    if ($_POST['confirmarSenha'] != $_POST['senha']){
        $erros[] = utf8_encode('As senhas não coincidem');
    }

    if (strlen($_POST['email']) ==  0){
        $erros[] = utf8_encode('Preencha o campo email');
    } else {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(":email", $email);
        $sql->execute();
        $retorno2 = $sql->fetch(PDO::FETCH_ASSOC);

        if($sql->rowCount() > 0 && $id != $retorno2["idUsuarios"]){
            $erros[] = utf8_encode('Email já cadastrado.');
        }
    }

    if (isset($erros) && count($erros) > 0) {
        $_SESSION['camposForm'] = $_POST;
        $_SESSION['errosReportados'] = $erros;
        header("location: ../VIEW/AdminE.php?idUsuarios=" . $id);
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE usuarios SET login = :login, senha = :senha, email = :email WHERE idUsuarios = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':email', $email);

            $retorno = $stmt->execute();

            if (!$retorno) {
                ?> <script>alert("Erro, tente novamente!");</script> <?php
                header("location: ../VIEW/AdminE.php?idUsuarios=" . $id);
            } else {
                ?> <script>alert("Usuário editado com sucesso!");</script> <?php
                ?> <script>window.location.href = "../VIEW/Admin.php";</script> <?php
            }
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
?>
