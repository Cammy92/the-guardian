<?php
require_once __DIR__ . '/../models/Endereco.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$enderecoModel = new Endereco();
$erro = null;
$sucesso = null;
$id_cliente = Sanitizer::sanitizeInt($_GET['cliente_id'] ?? null);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // ✅ Sanitizar inputs
        $id_cliente = Sanitizer::sanitizeInt($_POST['id_cliente']);
        $rua = Sanitizer::sanitizeString($_POST['rua']);
        $numero = Sanitizer::sanitizeInt($_POST['numero']);
        $bairro = Sanitizer::sanitizeString($_POST['bairro']);
        $cidade = Sanitizer::sanitizeString($_POST['cidade']);
        $estado = Sanitizer::sanitizeString($_POST['estado']);
        $cep = Sanitizer::sanitizeString($_POST['cep']);

        // ✅ Validar se os dados foram corretamente sanitizados
        if (!$id_cliente || !$rua || !$numero || !$bairro || !$cidade || !$estado || !$cep) {
            throw new Exception("Erro: Dados inválidos! Verifique os campos preenchidos.");
        }

        $enderecoModel->incluirEndereco($id_cliente, $rua, $numero, $bairro, $cidade, $estado, $cep);
        header("Location: listar_enderecos.php?cliente_id=$id_cliente");
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
    <title>Adicionar Endereço</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/incluir_endereco.css">
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
    <div class="content">
        <h2>Adicionar Endereço</h2>

        <?php if ($erro): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">

            <label>Rua: <input type="text" name="rua" required></label><br>
            <label>Número: <input type="text" name="numero" required></label><br>
            <label>Bairro: <input type="text" name="bairro" required></label><br>
            <label>Cidade: <input type="text" name="cidade" required></label><br>
            <label>Estado: <input type="text" name="estado" required></label><br>
            <label>CEP: <input type="text" name="cep" required></label><br>

            <button type="submit">Salvar Endereço</button>

            <br>
            <div class="actions">
                <a href="listar_clientes.php" class="btn back">🔙 Voltar para Lista de Clientes</a>
            </div>
        </form>
    </div>
</body>
</html>