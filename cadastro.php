<?php
session_start();
require_once("backend/conexao.php");
require_once("dao/UsuarioDAO.php");

$mensagem = "";

if (isset($_POST["btnCadastrar"])) {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if(empty($nome) || empty($email) || empty($senha)){
        $mensagem = "Preencha todos os campos!";
    } else {
        $dao = new UsuarioDAO($conn);

        $resultado = $dao->cadastrar($nome, $email, $senha);

        if($resultado === true){
            $mensagem = "Cadastro realizado com sucesso! Você já pode fazer login.";
        } elseif($resultado === "email_existente"){
            $mensagem = "Esse email já está cadastrado.";
        } else {
            $mensagem = "Erro ao cadastrar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
</head>
<body class="auth-body">

<div class="auth-container">
    <h2>📝 Cadastro</h2>
    <link rel="stylesheet" href="style.css">

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="btnCadastrar">Cadastrar</button>
    </form>

    <p class="mensagem"><?php echo $mensagem; ?></p>

    <div class="auth-footer">
        <p>Já tem conta? <a href="login.php">Entrar</a></p>
    </div>
</div>

</body>
</html>