<?php
session_start();
require_once(__DIR__ . "/../backend/conexao.php");
require_once(__DIR__ . "/../dao/UsuarioDAO.php");


// Verifica se está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$dao = new UsuarioDAO($conn);
$mensagem = "";

// Confirma exclusão
if (isset($_POST["btnExcluir"])) {
    $id = $_SESSION["usuario_id"];

    if ($dao->excluir($id)) {
        // Logout após exclusão
        session_destroy();
        header("Location: index.php");
        exit;
    } else {
        $mensagem = "Erro ao excluir a conta. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Excluir Conta</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="auth-container">
    <h2>Excluir Conta</h2>

    <p>Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.</p>

    <form method="POST">
        <button type="submit" name="btnExcluir" class="excluir">Sim, excluir minha conta</button>
        <a href="../index.php" class="voltar">Não, voltar para o início</a>
    </form>

    <?php if(!empty($mensagem)) echo '<p class="mensagem">'.$mensagem.'</p>'; ?>
</div>

</body>
</html>