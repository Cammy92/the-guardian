<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$erro = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clienteModel = new Cliente();

    // ✅ Sanitizar inputs para evitar SQL Injection
    $dados = [
        'nome' => Sanitizer::sanitizeString($_POST['nome']),
        'data_nascimento' => Sanitizer::sanitizeDate($_POST['data_nascimento']),
        'cpf' => Sanitizer::sanitizeCPF($_POST['cpf']),
        'rg' => Sanitizer::sanitizeRG($_POST['rg']),
        'telefone' => Sanitizer::sanitizeTelefone($_POST['telefone'])
    ];

    // ✅ Validar se os dados foram corretamente sanitizados
    foreach ($dados as $key => $valor) {
        if (!$valor) {
            $erro = "Erro: O campo {$key} é inválido!";
            break;
        }
    }

    if (!$erro) {
        try {
            $clienteModel->incluirCliente(...array_values($dados));
            header('Location: listar_clientes.php');
            exit;
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Incluir Cliente</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/incluir_cliente.css">
</head>
<body>
    <div class="sidebar">
        <img src="../assets/images/logo.png" alt="Logo The Guardian" class="sidebar-logo">
        
        <nav>
            <ul>
                <li><a href="dashboard.php">🏠 Início</a></li>
                <li><a href="listar_clientes.php">👤 Clientes</a></li>
                <li><a href="../controllers/LoginController.php?logout=true" class="logout">🚪 Sair</a></li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <h2>Incluir Novo Cliente</h2>

        <?php if ($erro): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" maxlength="11" required>

            <label for="rg">RG:</label>
            <input type="text" id="rg" name="rg" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>

            <button type="submit">Cadastrar</button>
        </form>

        <br>
        <div class="actions">
            <a href="listar_clientes.php" class="btn back">🔙 Voltar para Lista de Clientes</a>
        </div>
    </div>
</body>
</html>