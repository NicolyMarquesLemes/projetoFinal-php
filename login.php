<?php
session_start();
require_once("conexao.php");
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
<body>

<h2>Login</h2>

<form method="POST">
    <input type="email" name="email" placeholder="Seu email" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit" name="btnLogin">Entrar</button>
</form>

<p class="mensagem"><?php echo $mensagem; ?></p>

<!-- Botão para voltar à página inicial -->
<a href="index.php" class="voltar">← Voltar para Início</a>

</body>
</html>