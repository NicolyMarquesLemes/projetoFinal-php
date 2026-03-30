<?php
require "auth/verifica.php";
require "config/Database.php";

$conn = (new Database())->getConnection();

$id = $_SESSION['user']['id'];

$noticias = $conn->query("SELECT * FROM noticias WHERE autor=$id");
?>

<h1>Minhas Notícias</h1>

<a href="noticias/nova.php">Nova notícia</a> |
<a href="auth/logout.php">Sair</a>

<hr>

<?php foreach($noticias as $n): ?>
    <div>
        <h3><?= $n['titulo'] ?></h3>

        <a href="noticias/editar.php?id=<?= $n['id'] ?>">Editar</a>
        <a href="noticias/excluir.php?id=<?= $n['id'] ?>">Excluir</a>
    </div>
<?php endforeach; ?>