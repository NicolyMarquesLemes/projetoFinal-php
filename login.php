<?php
session_start();
require_once("backend/conexao.php");
require_once("dao/UsuarioDAO.php");

$mensagem = "";

// Só tenta logar se o formulário foi enviado
if (isset($_POST["btnLogin"])) {

    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $dao = new UsuarioDAO($conn);
     $resultado = $dao->login($email, $senha);

    if (is_array($resultado)) {
        // Salva dados do usuário na sessão
        $_SESSION["usuario_id"] = $resultado["id"];
        $_SESSION["usuario_nome"] = $resultado["nome"];

        // 🔹 REDIRECIONA PARA A PÁGINA INICIAL
        header("Location: index.php");
        exit;

    } elseif ($resultado == "senha_incorreta") {
        $mensagem = "Senha incorreta!";
    } else {
        $mensagem = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body class="auth-body">

<div class="auth-container">
    <h2>🔐 Login</h2>
    <link rel="stylesheet" href="style.css">

    <form method="POST">
        <input type="email" name="email" placeholder="Seu email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="btnLogin">Entrar</button>
    </form>

    <p class="mensagem"><?php echo $mensagem; ?></p>

    <div class="auth-footer">
        <p>Não tem conta? <a href="cadastro.php">Cadastrar</a></p>
    </div>
</div>

</body>
</html>