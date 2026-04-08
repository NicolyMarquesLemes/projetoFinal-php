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

// Busca dados atuais
$usuario = $dao->buscarPorId($_SESSION["usuario_id"]);

if (!$usuario) {
    die("Usuário não encontrado!");
}

// Atualiza os dados
if (isset($_POST["btnAtualizar"])) {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if (empty($nome) || empty($email)) {
        $mensagem = "Preencha todos os campos obrigatórios!";
    } else {
        $resultado = $dao->atualizar($_SESSION["usuario_id"], $nome, $email, $senha);

        if ($resultado === true) {
            $mensagem = "Dados atualizados com sucesso!";
            $_SESSION["usuario_nome"] = $nome; // Atualiza sessão
        } elseif ($resultado === "email_existente") {
            $mensagem = "Este email já está cadastrado para outro usuário.";
        } else {
            $mensagem = "Erro ao atualizar os dados.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="auth-container">
    <h2>Editar Usuário</h2>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        <input type="password" name="senha" placeholder="Nova senha (deixe vazio para não alterar)">
        <button type="submit" name="btnAtualizar">Atualizar</button>
    </form>

    <?php if(!empty($mensagem)) echo '<p class="mensagem">'.$mensagem.'</p>'; ?>

    <a href="../index.php" class="voltar">← Voltar para Início</a>
</div>

</body>
</html>