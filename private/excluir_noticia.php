<?php
include("verifica_login.php");
include("conexao.php");

$dao->deletar($id);
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
    echo "Você não tem permissão para excluir!";
    exit();
}

// Deletar imagem (se existir)
if (!empty($noticia["imagem"]) && file_exists("imagens/" . $noticia["imagem"])) {
    unlink("imagens/" . $noticia["imagem"]);
}

// Deletar notícia
$delete = $conn->prepare("DELETE FROM noticias WHERE id = ?");
$delete->bind_param("i", $id);

if ($delete->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Erro ao excluir!";
}
?>