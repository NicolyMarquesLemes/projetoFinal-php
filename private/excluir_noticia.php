<?php
session_start();
require_once(__DIR__ . "/../backend/conexao.php");
require_once(__DIR__ . "/../dao/NoticiaDAO.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST["id"])) {
    header("Location: index.php");
    exit;
}

$id = intval($_POST["id"]);

$sql = $conn->prepare("SELECT * FROM noticias WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows == 0) {
    die("Notícia não encontrada!");
}

$noticia = $resultado->fetch_assoc();

if ($noticia["autor"] != $_SESSION["usuario_id"]) {
    die("Sem permissão!");
}

if (!empty($noticia["imagem"])) {
    $caminho = __DIR__ . "/imagens/" . $noticia["imagem"];
    if (file_exists($caminho)) {
        unlink($caminho);
    }
}

$delete = $conn->prepare("DELETE FROM noticias WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: ../index.php");
exit;