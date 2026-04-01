<?php
session_start();
require_once("conexao.php");
require_once("dao/UsuarioDAO.php");

// Se o usuário não estiver logado, redireciona
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
    <title>Excluir Conta</title>
</head>
<body>

<h2>Excluir Conta</h2>

<p>Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.</p>

<form method="POST">
    <button type="submit" name="btnExcluir">Sim, excluir minha conta</button>
    <a href="index.php" class="voltar">Não, voltar para o início</a>
</form>

<p class="mensagem"><?php echo $mensagem; ?></p>

</body>
</html>