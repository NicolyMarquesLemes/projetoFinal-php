<?php
require_once "../helpers/verifica_login.php";
require_once "../dao/NoticiaDAO.php";

if(isset($_POST['salvar'])){

    $dao = new NoticiaDAO();

    $dao->criar(
        $_POST['titulo'],
        $_POST['noticia'],
        $_SESSION['usuario']['id'],
        $_POST['pais']
    );

    echo "Notícia criada!";
}
?>

<form method="POST">

Título:
<input name="titulo"><br>

Notícia:
<textarea name="noticia"></textarea><br>

País:
<input name="pais"><br>

<button name="salvar">Publicar</button>

</form>