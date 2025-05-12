<?php
session_start();

require_once __DIR__ .  '/../models/Usuario.php';

if (isset($_GET['logout']) && $_GET['logout'] === "true") {
    session_unset();
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}


$usuarioModel = new Usuario();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($usuarioModel->autenticar($email, $senha)) {
        // Armazena o usuário na sessão
        $_SESSION['usuario'] = $email;

        // ✅ Redireciona corretamente para o dashboard
        header("Location: ../views/dashboard.php");
        exit;
    } else {
        echo "Email ou senha inválidos!";
    }
}


class LoginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login($dados) {
        if ($this->usuarioModel->autenticar($dados['email'], $dados['senha'])) {
            $_SESSION['usuario'] = $dados['email'];
            header("Location: ../views/clientes.php");
            exit;
        } else {
            echo "Email ou senha inválidos!";
        }
    }

    public function logout() {
        session_start(); // Garante que a sessão está iniciada
        session_unset(); // Limpa todas as variáveis da sessão
        session_destroy(); // Destrói a sessão
        // Apagar o cookie da sessão, se existir
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        header("Location: ../views/login.php");
        die(); // Garante que nada mais seja executado
    }
}
?>