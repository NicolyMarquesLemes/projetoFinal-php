<?php
class NoticiaDAO {
    private $conn;

public function listarPorPais($pais){
    $sql = "SELECT n.id, n.titulo, n.noticia, n.data, n.autor, u.nome
FROM noticias n
JOIN usuarios u ON n.autor = u.id
            WHERE n.pais = ?
            ORDER BY n.data DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $pais);
    $stmt->execute();
    $result = $stmt->get_result();
    $noticias = [];
    while($row = $result->fetch_assoc()){
        $noticias[] = $row;
    }
    return $noticias;
}

public function listarPaises(){
    $sql = "SELECT DISTINCT pais FROM noticias ORDER BY pais ASC";
    $result = $this->conn->query($sql);
    $paises = [];
    while($row = $result->fetch_assoc()){
        $paises[] = $row['pais'];
    }
    return $paises;
}
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function criar($titulo, $texto, $data, $autor, $imagem, $pais) {
        $sql = "INSERT INTO noticias (titulo, noticia, data, autor, imagem, pais) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $titulo, $texto, $data, $autor, $imagem, $pais);
        return $stmt->execute();
    }

public function listar() {
    $sql = "SELECT n.id, n.titulo, n.noticia, n.data, n.imagem, n.pais, n.autor, u.nome 
            FROM noticias n 
            JOIN usuarios u ON n.autor = u.id 
            ORDER BY n.data DESC";
    $result = $this->conn->query($sql);
    $noticias = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $noticias[] = $row;
        }
    }
    return $noticias;
}

    public function buscarPorId($id) {
        $sql = "SELECT * FROM noticias WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function editar($id, $titulo, $texto, $pais, $imagem = null) {
        if($imagem){
            $sql = "UPDATE noticias SET titulo = ?, noticia = ?, pais = ?, imagem = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssi", $titulo, $texto, $pais, $imagem, $id);
        } else {
            $sql = "UPDATE noticias SET titulo = ?, noticia = ?, pais = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $titulo, $texto, $pais, $id);
        }
        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM noticias WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>