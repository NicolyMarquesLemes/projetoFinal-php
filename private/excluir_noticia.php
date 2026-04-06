<?php
require_once(__DIR__ . "/../backend/verifica_login.php");
require_once(__DIR__ . "/../backend/conexao.php");

// Verificar ID
if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET["id"];

// Buscar notícia
$sql = $conn->prepare("SELECT * FROM noticias WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows == 0) {
    echo "Notícia não encontrada!";
    exit();
}

$noticia = $resultado->fetch_assoc();

// Verificar autor
if ($noticia["autor"] != $_SESSION["usuario_id"]) {
    echo "Sem permissão!";
    exit();
}

// Deletar
$delete = $conn->prepare("DELETE FROM noticias WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: dashboard.php");
exit();