<?php
require_once "../dao/NoticiaDAO.php";

$dao = new NoticiaDAO();

$pais = $_GET['pais'] ?? null;

$noticias = $dao->listar($pais);
?>

<h1>Portal de Notícias 🌍</h1>

<form>
    <select name="pais">
        <option value="">Todos</option>
        <option value="Brasil">Brasil 🇧🇷</option>
        <option value="EUA">EUA 🇺🇸</option>
    </select>
    <button>Filtrar</button>
</form>

<?php foreach($noticias as $n): ?>

<h2><?= $n['titulo'] ?></h2>
<p><?= substr($n['noticia'], 0, 100) ?>...</p>
<p><b>País:</b> <?= $n['pais'] ?></p>

<hr>

<?php endforeach; ?>