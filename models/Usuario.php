<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Conexao.php';
if (!class_exists("Conexao")) {
    die("Erro: Classe Conexao não encontrada!");
}

class Usuario {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // ✅ Método para cadastrar um novo usuário com senha segura
    public function cadastrar($usuario, $email, $senha) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)");
        return $stmt->execute([$usuario, $email, $senhaHash]);
    }


    public function autenticar($email, $senha) {
        $stmt = $this->db->prepare("SELECT senha FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return true;
        }
        return false;
    }

    public function contarClientes() {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM clientes");
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total']; // Garantindo que retorna um inteiro

    }
}
?>