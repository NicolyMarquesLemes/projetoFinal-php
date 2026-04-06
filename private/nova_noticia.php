<?php
session_start();
require_once("backend/conexao.php");
require_once("dao/NoticiaDAO.php");

// Se não estiver logado, redireciona
if(!isset($_SESSION["usuario_id"])){
    header("Location: login.php");
    exit;
}

$dao = new NoticiaDAO($conn);
$mensagem = "";

// Inicializa variáveis
$titulo = "";
$texto = "";
$pais = "";
$imagemNome = "";
$autor = $_SESSION["usuario_id"] ?? "";

if(isset($_POST["btnCriar"])) {
    $titulo = trim($_POST["titulo"]);
    $texto = trim($_POST["texto"]);
    $pais = trim($_POST["pais"]);

    // Upload de imagem (opcional)
    if(isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0){
        $imagemNome = time() . "_" . $_FILES["imagem"]["name"];
        move_uploaded_file($_FILES["imagem"]["tmp_name"], "imagens/" . $imagemNome);
    } else {
        $imagemNome = null;
    }

    if(empty($titulo) || empty($texto) || empty($pais)){
        $mensagem = "Preencha todos os campos obrigatórios!";
    } else {
        $data = date("Y-m-d H:i:s");
        $resultado = $dao->criar($titulo, $texto, $data, $autor, $imagemNome, $pais);
        if($resultado){
            $mensagem = "Notícia criada com sucesso!";
            // Limpa os campos
            $titulo = $texto = $pais = $imagemNome = "";
        } else {
            $mensagem = "Erro ao criar a notícia.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Nova Notícia</title>
</head>
<body>
<h2>Criar Nova Notícia</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="titulo" placeholder="Título" value="<?php echo htmlspecialchars($titulo); ?>" required><br>
    <textarea name="texto" placeholder="Texto da notícia" required><?php echo htmlspecialchars($texto); ?></textarea><br>
    <input type="text" name="pais" placeholder="País" value="<?php echo htmlspecialchars($pais); ?>" required><br>
    <input type="file" name="imagem"><br>
    <button type="submit" name="btnCriar">Criar Notícia</button>
</form>

<p><?php echo $mensagem; ?></p>
<a href="index.php">← Voltar para Início</a>

</body>
</html>