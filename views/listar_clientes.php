<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Endereco.php';
session_start();

$clienteModel = new Cliente();
$enderecoModel = new Endereco();
$clientes = $clienteModel->listarTodos();

// 🔢 Configuração da Paginação
$clientesPorPagina = 5;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $clientesPorPagina;

// 🔎 Obtendo clientes com limite de paginação
$totalClientes = $clienteModel->contarTotal();
$clientes = $clienteModel->listarComLimite($clientesPorPagina, $offset);
$totalPaginas = ceil($totalClientes / $clientesPorPagina);
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Clientes - The Guardian</title>
    <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/listar_clientes.css">
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
        <h2>Lista de Clientes</h2>
        
        <div class="actions">
            <a href="incluir_cliente.php" class="btn add">➕ Adicionar Cliente</a>
        </div>

        <table>
            <tr>
                <th>ID</th><th>Nome</th><th>Data de Nascimento</th><th>CPF</th><th>RG</th><th>Telefone</th><th>Ações</th>
            </tr>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= date("d/m/Y", strtotime($cliente['data_nascimento'])) ?></td>
                    <td><?= $cliente['cpf'] ?></td>
                    <td><?= $cliente['rg'] ?></td>
                    <td><?= $cliente['telefone'] ?></td>
                    <td>
                        <?php 
                        $enderecos = $enderecoModel->listarPorCliente($cliente['id']);
                        if (count($enderecos) > 0): ?>
                            <a href="listar_enderecos.php?cliente_id=<?= $cliente['id'] ?>">📌 Ver Endereços</a>
                        <?php else: ?>
                            <a href="incluir_endereco.php?cliente_id=<?= $cliente['id'] ?>">➕ Adicionar Endereço</a>
                        <?php endif; ?>
                            |
                            <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-edit">✏ Editar</a>  
                            |
                            <a href="excluir_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-delete"
                                onclick="return confirm('Tem certeza que deseja excluir este cliente? Isso removerá todos os dados associados.')">
                                ❌ Excluir
                            </a>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <!-- 🔹 PAGINAÇÃO -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= intval($i) ?>" class="<?= ($i == $paginaAtual) ? 'active' : '' ?>">
                    <?= intval($i) ?> <!-- 🔹 Remove qualquer caractere estranho -->
                </a>
            <?php endfor; ?>
        </div>


    </div>
</body>
</html>