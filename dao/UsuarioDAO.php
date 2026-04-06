<?php
require_once(__DIR__ . "/../backend/conexao.php");

class UsuarioDAO {

    private $conn;

    public function excluir($id) {
    // Deleta notícias do usuário
    $sql = "DELETE FROM noticias WHERE autor = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Agora deleta o usuário
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

    public function atualizar($id, $nome, $email, $senha = null) {
    // Verifica se outro usuário já tem este email
    $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return "email_existente"; // email já usado
    }

    // Se a senha foi preenchida, atualiza ela
    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $senhaHash, $id);
    } else {
        // Atualiza apenas nome e email
        $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $nome, $email, $id);
    }

    if ($stmt->execute()) {
        return true; // sucesso
    } else {
        return false; // erro ao atualizar
    }
}

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // 🔹 Criar usuário (cadastro)
    public function cadastrar($nome, $email, $senha) {

        // Verificar se email já existe
        $check = $this->conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return "email_existe";
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = $this->conn->prepare("
            INSERT INTO usuarios (nome, email, senha)
            VALUES (?, ?, ?)
        ");

        $sql->bind_param("sss", $nome, $email, $senhaHash);

        return $sql->execute();
    }

    // 🔹 Login
    public function login($email, $senha) {

        $sql = $this->conn->prepare("
            SELECT id, nome, senha FROM usuarios WHERE email = ?
        ");

        $sql->bind_param("s", $email);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($resultado->num_rows == 1) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario["senha"])) {
                return $usuario; // sucesso
            } else {
                return "senha_incorreta";
            }

        } else {
            return "usuario_nao_encontrado";
        }
    }

    // 🔹 Buscar por ID
    public function buscarPorId($id) {

        $sql = $this->conn->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();

        return $sql->get_result()->fetch_assoc();
    }

}