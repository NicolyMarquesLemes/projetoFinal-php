<?php
require_once(__DIR__ . "/../backend/conexao.php");

class UsuarioDAO {

    private $conn;

    // Construtor
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

        $sql = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nome, $email, $senhaHash);

        return $sql->execute();
    }

    // 🔹 Login
    public function login($email, $senha) {
        $sql = $this->conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($senha, $usuario["senha"])) {
                return $usuario;
            } else {
                return "senha_incorreta";
            }
        } else {
            return "usuario_nao_encontrado";
        }
    }

    // 🔹 Buscar por ID
    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 🔹 Atualizar usuário
    public function atualizar($id, $nome, $email, $senha = null) {
        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nome, $email, $senhaHash, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nome, $email, $id);
        }
        return $stmt->execute();
    }

    // 🔹 Excluir usuário
    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}