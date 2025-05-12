<?php
require_once __DIR__ . '/../models/Endereco.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$enderecoModel = new Endereco();

// ✅ Pegamos o ID do cliente via GET e sanitizamos
$id_cliente = Sanitizer::sanitizeInt($_GET['cliente_id'] ?? null);
$enderecos = [];
$erro = null;

// 🔢 Configuração da Paginação
$enderecosPorPagina = 5;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $enderecosPorPagina;

// 🔎 Obtendo endereços com limite de paginação
if ($id_cliente) {
    $totalEnderecos = $enderecoModel->contarTotalPorCliente($id_cliente);
    $enderecos = $enderecoModel->listarComLimitePorCliente($id_cliente, $enderecosPorPagina, $offset);
    $totalPaginas = ceil($totalEnderecos / $enderecosPorPagina);
} else {
    $erro = "Nenhum cliente selecionado!";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Endereços do Cliente</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/listar_enderecos.css">
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
        <h2>Endereços do Cliente</h2>

        <?php if (isset($erro)): ?>
            <p class="error"><?= $erro ?></p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th><th>Rua</th><th>Número</th><th>Bairro</th><th>Cidade</th><th>Estado</th><th>CEP</th><th>Ações</th>
                </tr>
                <?php foreach ($enderecos as $endereco): ?>
                    <tr>
                        <td><?= $endereco['id'] ?></td>
                        <td><?= $endereco['rua'] ?></td>
                        <td><?= $endereco['numero'] ?></td>
                        <td><?= $endereco['bairro'] ?></td>
                        <td><?= $endereco['cidade'] ?></td>
                        <td><?= $endereco['estado'] ?></td>
                        <td><?= $endereco['cep'] ?></td>
                        <td>
                            <a href="editar_endereco.php?id=<?= $endereco['id'] ?>">✏ Editar</a> |
                            <a href="excluir_endereco.php?id_cliente=<?= $id_cliente ?>&id_endereco=<?= $endereco['id'] ?>"
                            onclick="return confirm('Tem certeza que deseja excluir este endereço?')">
                            ❌ Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <!-- 🔹 PAGINAÇÃO -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?cliente_id=<?= $id_cliente ?>&pagina=<?= intval($i) ?>" class="<?= ($i == $paginaAtual) ? 'active' : '' ?>">
                        <?= intval($i) ?>
                    </a>
                <?php endfor; ?>
            </div>

            <br>
            <div class="actions">
                <a href="incluir_endereco.php?cliente_id=<?= $id_cliente ?>" class="btn add">➕ Adicionar Endereço</a>
                <a href="listar_clientes.php" class="btn back">🔙 Voltar para Lista de Clientes</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>