
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

// Verificar se é o autor
if ($noticia["autor"] != $_SESSION["usuario_id"]) {
    echo "Você não tem permissão para editar!";
    exit();
}

$mensagem = "";

// Atualizar
if (isset($_POST["atualizar"])) {

    $titulo = trim($_POST["titulo"]);
    $texto = trim($_POST["noticia"]);
    $pais = trim($_POST["pais"]);
    $imagemNome = $noticia["imagem"]; // manter antiga

    // Se enviou nova imagem
    if (!empty($_FILES["imagem"]["name"])) {
        $arquivo = $_FILES["imagem"];
        $ext = pathinfo($arquivo["name"], PATHINFO_EXTENSION);
        $imagemNome = uniqid() . "." . $ext;
        move_uploaded_file($arquivo["tmp_name"], "imagens/" . $imagemNome);
    }

    // Update
    $update = $conn->prepare("
        UPDATE noticias 
        SET titulo = ?, noticia = ?, pais = ?, imagem = ?
        WHERE id = ?
    ");
    $update->bind_param("ssssi", $titulo, $texto, $pais, $imagemNome, $id);

    if ($update->execute()) {
        $mensagem = "Notícia atualizada com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Notícia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Editar Notícia</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="titulo" value="<?php echo htmlspecialchars($noticia["titulo"]); ?>" required><br><br>
    <textarea name="noticia" rows="5" cols="40" required><?php echo htmlspecialchars($noticia["noticia"]); ?></textarea><br><br>
    <input type="text" name="pais" value="<?php echo htmlspecialchars($noticia["pais"]); ?>" required><br><br>

    <!-- Imagem atual -->
    <?php if (!empty($noticia["imagem"])): ?>
        <img src="imagens/<?php echo $noticia["imagem"]; ?>" width="150"><br><br>
    <?php endif; ?>

    <input type="file" name="imagem"><br><br>
    <button type="submit" name="atualizar">Atualizar</button>
</form>

<p><?php echo $mensagem; ?></p>
<a href="dashboard.php">← Voltar</a>

</body>
</html>