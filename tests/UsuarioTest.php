<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Usuario.php';


class UsuarioTest extends TestCase {
    public function testAutenticacaoCorreta() {
        $usuario = new Usuario();
        $this->assertTrue($usuario->autenticar("camila@gmail.com", "senha123"));
    }

    public function testContarClientes() {
        $usuarioModel = new Usuario();
        $total = $usuarioModel->contarClientes();
        $this->assertIsInt($total, "O total de clientes deve ser um número inteiro.");
        $this->assertGreaterThanOrEqual(0, $total, "O número de clientes deve ser positivo.");
    }


}
?>