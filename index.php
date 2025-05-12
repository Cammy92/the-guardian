<?php
session_start();

// Se o usuário já estiver autenticado, vai direto para o dashboard
if (isset($_SESSION['usuario'])) {
    header("Location: views/dashboard.php");
    exit;
}

// Se não estiver logado, redireciona para a página de login
header("Location: views/login.php");
exit;
?>