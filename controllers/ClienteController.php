<?php
require_once __DIR__ .  '/../models/Cliente.php';

class ClienteController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    public function listarClientes() {
        return $this->clienteModel->listarTodos();
    }
}
?>