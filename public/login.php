<?php
session_start();
require_once "../dao/UsuarioDAO.php";

if(isset($_POST['login'])){

    $dao = new UsuarioDAO();
    $user = $dao->login($_POST['email'], $_POST['senha']);

    if($user){
        $_SESSION['usuario'] = $user;
        header("Location: ../private/dashboard.php");
    } else {
        echo "Login inválido";
    }
}
?>

<form method="POST">
<input name="email" placeholder="Email">
<input type="password" name="senha">
<button name="login">Entrar</button>
</form>