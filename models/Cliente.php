<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Validator.php';

class Cliente {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getDb() {
        return $this->db;
    }

    public function listarTodos() {
        $stmt = $this->db->query("SELECT * FROM clientes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function incluirCliente($nome, $data_nascimento, $cpf, $rg, $telefone) {
        // ✅ Validar os dados com a classe Validator
        Validator::validarNome($nome);
        Validator::validarDataNascimento($data_nascimento);
        Validator::validarCPF($cpf);
        Validator::validarRG($rg);
        Validator::validarTelefone($telefone);

        // 🔎 Verifica se o CPF já existe
        $stmt = $this->db->prepare("SELECT id FROM clientes WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        if ($stmt->fetch()) {
            throw new Exception("Erro: Este CPF já está cadastrado.");
        }

        // ✅ Insere novo cliente
        $stmt = $this->db->prepare("INSERT INTO clientes (nome, data_nascimento, cpf, rg, telefone) 
                                    VALUES (:nome, :data_nascimento, :cpf, :rg, :telefone)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':telefone', $telefone);
        
        return $stmt->execute();
    }

    public function editarCliente($id, $nome, $data_nascimento, $cpf, $rg, $telefone) {
        // ✅ Validar os dados antes de atualizar
        Validator::validarNome($nome);
        Validator::validarDataNascimento($data_nascimento);
        Validator::validarCPF($cpf);
        Validator::validarRG($rg);
        Validator::validarTelefone($telefone);

        // 🔎 Verifica se o cliente existe antes de editar
        $stmt = $this->db->prepare("SELECT id FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if (!$stmt->fetch()) {
            throw new Exception("Erro: Cliente não encontrado.");
        }

        // 🔎 Verifica se o CPF já pertence a outro cliente
        $stmt = $this->db->prepare("SELECT id FROM clientes WHERE cpf = :cpf AND id != :id");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->fetch()) {
            throw new Exception("Erro: Este CPF já está cadastrado para outro cliente.");
        }

        // ✅ Atualiza os dados
        $stmt = $this->db->prepare("UPDATE clientes SET nome = :nome, data_nascimento = :data_nascimento, cpf = :cpf, rg = :rg, telefone = :telefone WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':telefone', $telefone);
        
        return $stmt->execute();
    }

    public function excluirCliente($id) {
        // 🔎 Verifica se o cliente existe antes de excluir
        $stmt = $this->db->prepare("SELECT id FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if (!$stmt->fetch()) {
            throw new Exception("Erro: Cliente não encontrado.");
        }

        // ✅ Exclui o cliente
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM clientes";
        $stmt = $this->db->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }

    public function listarComLimite($limite, $offset) {
        $sql = "SELECT * FROM clientes ORDER BY id ASC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}