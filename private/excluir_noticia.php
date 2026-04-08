<?php
session_start();
require_once(__DIR__ . "/../backend/conexao.php");
require_once(__DIR__ . "/../dao/NoticiaDAO.php");

// Verifica login
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Verifica POST
if (!isset($_POST["id"])) {
    header("Location: index.php");
    exit;
}

$id = intval($_POST["id"]);

// Buscar notícia
$sql = $conn->prepare("SELECT * FROM noticias WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows == 0) {
    die("Notícia não encontrada!");
}

$noticia = $resultado->fetch_assoc();

// Verifica autor
if ($noticia["autor"] != $_SESSION["usuario_id"]) {
    die("Sem permissão!");
}

// Deletar imagem
if (!empty($noticia["imagem"])) {
    $caminho = __DIR__ . "/imagens/" . $noticia["imagem"];
    if (file_exists($caminho)) {
        unlink($caminho);
    }
}

// Deletar do banco
$delete = $conn->prepare("DELETE FROM noticias WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: ../index.php");
exit;