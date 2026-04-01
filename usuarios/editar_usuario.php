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

// Pega os dados atuais do usuário
$usuario = $dao->buscarPorId($_SESSION["usuario_id"]);

if (!$usuario) {
    die("Usuário não encontrado.");
}

// Atualiza os dados se o formulário for enviado
if (isset($_POST["btnAtualizar"])) {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if (empty($nome) || empty($email)) {
        $mensagem = "Preencha todos os campos obrigatórios!";
    } else {
        // Atualiza o usuário no banco
        $resultado = $dao->atualizar($_SESSION["usuario_id"], $nome, $email, $senha);

        if ($resultado === true) {
            $mensagem = "Dados atualizados com sucesso!";
            $_SESSION["usuario_nome"] = $nome; // atualiza o nome na sessão
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
    <title>Editar Usuário</title>
</head>
<body>

<h2>Editar Usuário</h2>

<form method="POST">
    <input type="text" name="nome" placeholder="Nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required><br>
    <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required><br>
    <input type="password" name="senha" placeholder="Nova senha (deixe vazio para não alterar)"><br>
    <button type="submit" name="btnAtualizar">Atualizar</button>
</form>

<p class="mensagem"><?php echo $mensagem; ?></p>

<a href="index.php" class="voltar">← Voltar para Início</a>

</body>
</html>