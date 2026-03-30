<?php

require_once "config/Database.php";

class NoticiaDAO {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function criar($titulo, $noticia, $autor, $pais) {

        $sql = "INSERT INTO noticias (titulo, noticia, autor, pais)
                VALUES (:t, :n, :a, :p)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":t", $titulo);
        $stmt->bindValue(":n", $noticia);
        $stmt->bindValue(":a", $autor);
        $stmt->bindValue(":p", $pais);

        return $stmt->execute();
    }

    public function listar($pais = null) {

        if($pais){
            $sql = "SELECT * FROM noticias WHERE pais = :pais ORDER BY data DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":pais", $pais);
        } else {
            $sql = "SELECT * FROM noticias ORDER BY data DESC";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id, $autor) {

        $sql = "DELETE FROM noticias WHERE id = :id AND autor = :autor";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":autor", $autor);

        return $stmt->execute();
    }
}