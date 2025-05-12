<?php
require_once 'config/database.php';

$pdo = (new Database())->connect();
$senhaHash = password_hash('senha123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
$stmt->execute([$senhaHash, 'camila@gmail.com']);

echo "Senha atualizada com sucesso!";
?>