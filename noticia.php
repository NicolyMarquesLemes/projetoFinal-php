<?php
require_once("backend/conexao.php");

// Verificar ID
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = $_GET["id"];

// Buscar notícia + autor
$sql = $conn->prepare("
    SELECT n.*, u.nome 
    FROM noticias n
    JOIN usuarios u ON n.autor = u.id
    WHERE n.id = ?
");

$sql->bind_param("i", $id);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows == 0) {
    echo "Notícia não encontrada!";
    exit();
}

$noticia = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $noticia["titulo"]; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="index.php">⬅ Voltar</a>

<div style="background:white; padding:20px; margin:20px; border-radius:10px;">

    <h1><?php echo $noticia["titulo"]; ?></h1>

    <!-- Info -->
    <small>
        Autor: <?php echo $noticia["nome"]; ?> |
        Data: <?php echo date("d/m/Y H:i", strtotime($noticia["data"])); ?> |
        País: <?php echo $noticia["pais"]; ?>
    </small>

    <hr>

    <!-- Imagem -->
    <?php if (!empty($noticia["imagem"])) { ?>
        <img src="imagens/<?php echo $noticia["imagem"]; ?>" width="400"><br><br>
    <?php } ?>

    <!-- Texto completo -->
    <p><?php echo nl2br($noticia["noticia"]); ?></p>

</div>

</body>
</html>