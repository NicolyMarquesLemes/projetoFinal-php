<?php
include("verifica_login.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Bem-vindo, <?php echo $_SESSION["usuario_nome"]; ?>!</h2>

<a href="logout.php">Sair</a>

</body>
</html>