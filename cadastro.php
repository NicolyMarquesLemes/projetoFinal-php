<?php
session_start();
require_once("conexao.php");
require_once("dao/UsuarioDAO.php");

$mensagem = "";

// Só tenta cadastrar se o formulário foi enviado
if (isset($_POST["btnCadastrar"])) {

    // Pega valores do formulário
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
<body>

<h2>Cadastro de Usuário</h2>

<form method="POST">
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit" name="btnCadastrar">Cadastrar</button>
</form>

<p class="mensagem"><?php echo $mensagem; ?></p>

<a href="index.php" class="voltar">← Voltar para Início</a>

</body>
</html>