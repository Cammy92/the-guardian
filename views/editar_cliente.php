<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$clienteModel = new Cliente();
$erro = null;
$id_cliente = Sanitizer::sanitizeInt($_GET['id'] ?? null);
$cliente = null;

if ($id_cliente) {
    $cliente = $clienteModel->buscarPorId($id_cliente);
}

// ✅ Atualizando os dados do cliente ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $dados = [
            'id' => Sanitizer::sanitizeInt($_POST['id']),
            'nome' => Sanitizer::sanitizeString($_POST['nome']),
            'data_nascimento' => Sanitizer::sanitizeDate($_POST['data_nascimento']),
            'cpf' => Sanitizer::sanitizeCPF($_POST['cpf']),
            'rg' => Sanitizer::sanitizeRG($_POST['rg']),
            'telefone' => Sanitizer::sanitizeTelefone($_POST['telefone'])
        ];

        foreach ($dados as $key => $valor) {
            if (!$valor) {
                throw new Exception("Erro: O campo {$key} é inválido!");
            }
        }

        $clienteModel->editarCliente(...array_values($dados));
        header("Location: listar_clientes.php");
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/editar_cliente.css">
</head>
<body>
    <div class="sidebar">
        <img src="../assets/images/logo.png" alt="Logo The Guardian" class="sidebar-logo">
        
        <nav>
            <ul>
                <li><a href="dashboard.php">Início</a></li>
                <li><a href="listar_clientes.php">Clientes</a></li>
                <li><a href="../controllers/LoginController.php?logout=true" class="logout">Sair</a></li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <h2>Editar Cliente</h2>

        <?php if ($erro): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <?php if (!$cliente): ?>
            <p>Cliente não encontrado!</p>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= $cliente['nome'] ?>" required>

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $cliente['data_nascimento'] ?>" required>

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" value="<?= $cliente['cpf'] ?>" required>

                <label for="rg">RG:</label>
                <input type="text" id="rg" name="rg" value="<?= $cliente['rg'] ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?= $cliente['telefone'] ?>" required>

                <button type="submit">Salvar Alterações</button>
            </form>

            <div class="actions">
                <a href="listar_clientes.php" class="btn back">🔙 Voltar para Lista de Clientes</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>