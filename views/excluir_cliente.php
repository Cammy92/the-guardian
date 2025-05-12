<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Endereco.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$clienteModel = new Cliente();
$enderecoModel = new Endereco();

$id_cliente = Sanitizer::sanitizeInt($_GET['id'] ?? null);

try {
    if (!$id_cliente) {
        throw new Exception("Erro: Cliente inválido.");
    }

    // ✅ Remover endereços antes de excluir o cliente para evitar erro de chave estrangeira
    $enderecoModel->excluirEnderecosPorCliente($id_cliente);

    // ✅ Excluir o cliente
    $clienteModel->excluirCliente($id_cliente);

    header("Location: listar_clientes.php");
    exit;
} catch (Exception $e) {
    echo "<p style='color: red;'>".$e->getMessage()."</p>";
}
?>