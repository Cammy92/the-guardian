<?php
require_once __DIR__ . '/../models/Endereco.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$enderecoModel = new Endereco();
$erro = null;
$id_endereco = Sanitizer::sanitizeInt($_GET['id'] ?? null);
$endereco = null;

// ✅ Obtendo os dados do endereço via ID
if ($id_endereco) {
    $endereco = $enderecoModel->buscarPorId($id_endereco);
}

// ✅ Atualizando os dados do endereço ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // ✅ Sanitizar inputs
        $dados = [
            'id' => Sanitizer::sanitizeInt($_POST['id']),
            'rua' => Sanitizer::sanitizeString($_POST['rua']),
            'numero' => Sanitizer::sanitizeInt($_POST['numero']),
            'bairro' => Sanitizer::sanitizeString($_POST['bairro']),
            'cidade' => Sanitizer::sanitizeString($_POST['cidade']),
            'estado' => Sanitizer::sanitizeString($_POST['estado']),
            'cep' => Sanitizer::sanitizeString($_POST['cep'])
        ];

        foreach ($dados as $key => $valor) {
            if (!$valor) {
                throw new Exception("Erro: O campo {$key} é inválido!");
            }
        }

        $enderecoModel->editarEndereco(...array_values($dados));
        header('Location: listar_enderecos.php?cliente_id=' . $endereco['id_cliente']);
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
    <title>Editar Endereço</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/editar_endereco.css">
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
        <h2>Editar Endereço</h2>

        <?php if ($erro): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <?php if (!$endereco): ?>
            <p>Endereço não encontrado!</p>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $endereco['id'] ?>">

                <label for="rua">Rua:</label>
                <input type="text" id="rua" name="rua" value="<?= $endereco['rua'] ?>" required>

                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?= $endereco['numero'] ?>" required>

                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" value="<?= $endereco['bairro'] ?>" required>

                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?= $endereco['cidade'] ?>" required>

                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?= $endereco['estado'] ?>" required>

                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" value="<?= $endereco['cep'] ?>" required>

                <button type="submit">Salvar Alterações</button>
            </form>

            <br>
            <div class="actions">
                <a href="listar_enderecos.php?cliente_id=<?= $endereco['id_cliente'] ?>" class="btn back">🔙 Voltar para Endereços</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>