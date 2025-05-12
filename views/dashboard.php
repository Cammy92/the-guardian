<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - The Guardian</title>
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <img src="../assets/images/logo.png" alt="Logo The Guardian" class="sidebar-logo">
        
        <nav>
            <ul>
                <li><a href="dashboard.php">Início</a></li>
                <li><a href="listar_clientes.php">Clientes</a></li>
                <li><a href="../controllers/LoginController.php?logout=true" class="logout"> Sair</a></li>
            </ul>
        </nav>
    </div>

    <div class="dashboard-content">
        <h2>Bem-vindo ao Dashboard!</h2>
        <p>Usuário logado: <?= $_SESSION['usuario'] ?></p>

        <!-- Exibe o total de clientes -->
        <div class="total-clientes">
            <?php
            require_once __DIR__ . '/../models/Usuario.php';
            $usuarioModel = new Usuario();
            $totalClientes = $usuarioModel->contarClientes(); // Assume que há um método que retorna o número de clientes
            echo "Total de Clientes: " . $totalClientes;
            ?>
        </div>
    </div>
</body>
</html>