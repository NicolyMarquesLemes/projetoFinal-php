<?php
session_start();
require_once("conexao.php");
require_once("dao/NoticiaDAO.php");

$dao = new NoticiaDAO($conn);

// Filtrar por país, se selecionado
$paisFiltro = $_GET['pais'] ?? '';

// Buscar notícias
if($paisFiltro){
    $noticias = $dao->listarPorPais($paisFiltro);
} else {
    $noticias = $dao->listar();
}

// Listar países disponíveis para filtro
$paises = $dao->listarPaises();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Portal de Notícias - Mundo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .noticia { border:1px solid #ccc; padding:10px; margin:10px 0; border-radius:5px; }
        .noticia img { display:block; margin-bottom:5px; }
        .filtro { margin-bottom:20px; }
    </style>
</head>
<body>
    <h1>Portal de Notícias - Mundo</h1>

    <!-- Menu de login/cadastro -->
    <?php if(isset($_SESSION["usuario_id"])): ?>
        <p>Olá, <?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?> | 
           <a href="dashboard.php">Dashboard</a> | 
           <a href="logout.php">Sair</a>
        </p>
    <?php else: ?>
        <p><a href="login.php">Login</a> | <a href="cadastro.php">Cadastrar</a></p>
    <?php endif; ?>

    <!-- Filtro por país -->
    <form method="GET" class="filtro">
        <label>Filtrar por país:</label>
        <select name="pais" onchange="this.form.submit()">
            <option value="">Todos</option>
            <?php foreach($paises as $p): ?>
                <option value="<?php echo $p; ?>" <?php if($paisFiltro==$p) echo "selected"; ?>>
                    <?php echo htmlspecialchars($p); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Listagem de notícias -->
    <?php if(count($noticias) > 0): ?>
        <?php foreach($noticias as $n): ?>
            <div class="noticia">
                <?php if(!empty($n['imagem'])): ?>
                    <img src="imagens/<?php echo $n['imagem']; ?>" width="150">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($n['titulo']); ?></h2>
                <p><strong>Autor:</strong> <?php echo htmlspecialchars($n['nome']); ?></p>
                <p><strong>País:</strong> 
                    <?php
                        $flagFile = "bandeiras/".strtolower($n['pais']).".png";
                        if(file_exists($flagFile)){
                            echo '<img src="'.$flagFile.'" width="25"> ';
                        }
                    ?>
                    <?php echo htmlspecialchars($n['pais']); ?>
                </p>
                <p><strong>Data:</strong> <?php echo date("d/m/Y H:i", strtotime($n['data'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars(substr($n['noticia'],0,200))); ?>...</p>
                <a href="noticia.php?id=<?php echo $n['id']; ?>">Leia mais</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma notícia encontrada.</p>
    <?php endif; ?>
</body>
</html>