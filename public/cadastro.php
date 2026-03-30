<?php
require "../config/DataBase.php";

$conn = (new Database())->getConnection();
$mensagem = "";

if(isset($_POST['cadastrar'])){
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if(!empty($nome) && !empty($email) && !empty($senha)){

        // Verifica se o email já existe
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $mensagem = "Email já cadastrado!";
        } else {
            // Criptografa a senha
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere usuário
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":senha", $senhaHash);

            if($stmt->execute()){
                $mensagem = "Cadastro realizado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar usuário.";
            }
        }
    } else {
        $mensagem = "Preencha todos os campos!";
    }
}
?>

<h2>Cadastro</h2>
<p><?= $mensagem; ?></p>

<form method="POST">
    Nome:<br>
    <input type="text" name="nome" required><br><br>

    Email:<br>
    <input type="email" name="email" required><br><br>

    Senha:<br>
    <input type="password" name="senha" required><br><br>

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>

<a href="login.php">Já possui conta? Faça login</a>