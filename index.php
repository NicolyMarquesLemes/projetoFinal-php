<?php
session_start();
require_once("backend/conexao.php");
require_once("dao/NoticiaDAO.php");
require_once("dao/UsuarioDAO.php");

$daoNoticia = new NoticiaDAO($conn);
$daoUsuario = new UsuarioDAO($conn);

// Filtro por país
$paisFiltro = $_GET['pais'] ?? '';

// Buscar notícias
$noticias = $paisFiltro ? $daoNoticia->listarPorPais($paisFiltro) : $daoNoticia->listar();

// Países para filtro
$paises = $daoNoticia->listarPaises();

// Buscar dados do usuário logado (se houver)
$usuarioLogado = null;
if (isset($_SESSION["usuario_id"])) {
    $usuarioLogado = $daoUsuario->buscarPorId($_SESSION["usuario_id"]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Portal de Notícias</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>📰 Portal de Notícias</h1>

<!-- Menu -->
<?php if ($usuarioLogado): ?>
    <p>
        Olá, <strong><?php echo htmlspecialchars($usuarioLogado["nome"]); ?></strong> |
        <a href="logout.php">Sair</a> |
        <a href="usuarios/editar_usuario.php">Editar Perfil</a> |
        <a href="usuarios/excluir_usuario.php" onclick="return confirm('Deseja realmente excluir sua conta?');">Excluir Conta</a>
    </p>

    <a href="private/nova_noticia.php" class="nova">+ Nova Notícia</a>

<?php else: ?>
    <p>
        <a href="login.php">Login</a> |
        <a href="cadastro.php">Cadastrar</a>
    </p>
<?php endif; ?>

<hr>

<!-- Filtro -->
<form method="GET">
    <label>Filtrar por país:</label>
    <select name="pais" onchange="this.form.submit()">
        <option value="">Todos</option>
        <?php foreach($paises as $p): ?>
            <option value="<?php echo htmlspecialchars($p); ?>" <?php if($paisFiltro == $p) echo "selected"; ?>>
                <?php echo htmlspecialchars($p); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<hr>

<!-- Notícias -->
<?php if(count($noticias) > 0): ?>
    <?php foreach($noticias as $n): ?>
        <div class="noticia">

            <?php if(!empty($n['imagem'])): ?>
                <img src="imagens/<?php echo htmlspecialchars($n['imagem']); ?>" width="200">
            <?php endif; ?>

            <h2><?php echo htmlspecialchars($n['titulo']); ?></h2>
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($n['nome']); ?></p>
            <p><strong>País:</strong> 
                <?php 
                    $pais = $n['pais'] ?? 'Desconhecido';
                    $flagFile = "bandeiras/".strtolower($pais).".png";
                    if(file_exists($flagFile)) echo '<img src="'.$flagFile.'" width="20"> ';
                    echo htmlspecialchars($pais);
                ?>
            </p>
            <p><strong>Data:</strong> <?php echo date("d/m/Y H:i", strtotime($n['data'])); ?></p>
            <p><?php echo nl2br(htmlspecialchars(substr($n['noticia'],0,200))); ?>...</p>

            <a href="noticia.php?id=<?php echo $n['id']; ?>">Leia mais</a>

            <!-- Ações do autor -->
            <?php if($usuarioLogado && $usuarioLogado['id'] == $n['autor']): ?>
                <div class="acoes">
                    <a href="private/editar_noticia.php?id=<?php echo $n['id']; ?>" class="editar">Editar</a>
                    <form method="POST" action="private/excluir_noticia.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                        <button type="submit" class="excluir"
                                onclick="return confirm('Tem certeza que deseja excluir?')">
                            Excluir
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhuma notícia encontrada.</p>
<?php endif; ?>

</body>
</html>