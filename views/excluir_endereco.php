<?php
require_once __DIR__ . '/../models/Endereco.php';
require_once __DIR__ . '/../models/Sanitizer.php';
session_start();

$enderecoModel = new Endereco();

// ✅ Pegamos e sanitizamos os IDs passados via GET
$id_cliente = Sanitizer::sanitizeInt($_GET['id_cliente'] ?? null);
$id_endereco = Sanitizer::sanitizeInt($_GET['id_endereco'] ?? null);

if ($id_cliente && $id_endereco) {
    try {
        $enderecoModel->excluirEndereco($id_endereco);
        header("Location: listar_enderecos.php?cliente_id=$id_cliente");
        exit;
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erro ao excluir endereço: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>Erro: ID do endereço ou cliente não recebido.</p>";
}
?>