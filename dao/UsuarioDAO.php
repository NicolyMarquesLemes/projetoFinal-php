<?php

require_once "config/Database.php";
require_once "models/Usuario.php";

class UsuarioDAO {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function cadastrar(Usuario $u) {

        $sql = "INSERT INTO usuarios (nome, email, senha)
                VALUES (:nome, :email, :senha)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nome", $u->getNome());
        $stmt->bindValue(":email", $u->getEmail());

        $stmt->bindValue(":senha", password_hash($u->getSenha(), PASSWORD_DEFAULT));

        return $stmt->execute();
    }

    public function login($email, $senha) {

        $sql = "SELECT * FROM usuarios WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($senha, $user['senha'])) {
            return $user;
        }

        return false;
    }
}