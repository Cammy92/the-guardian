<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Sanitizer.php';

class Endereco {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getDb() {
        return $this->db;
    }

    public function incluirEndereco($id_cliente, $rua, $numero, $bairro, $cidade, $estado, $cep) {
        // ✅ Validar e Sanitizar os dados
        $id_cliente = Sanitizer::sanitizeInt($id_cliente);
        $rua = Sanitizer::sanitizeString($rua);
        $numero = Sanitizer::sanitizeInt($numero);
        $bairro = Sanitizer::sanitizeString($bairro);
        $cidade = Sanitizer::sanitizeString($cidade);
        $estado = Sanitizer::sanitizeString($estado);
        $cep = Sanitizer::sanitizeString($cep);

        if (!$id_cliente || !$rua || !$numero || !$bairro || !$cidade || !$estado || !$cep) {
            throw new Exception("Erro: Dados inválidos! Verifique os campos preenchidos.");
        }

        // ✅ Inserir endereço vinculado ao cliente
        $stmt = $this->db->prepare("INSERT INTO enderecos (id_cliente, rua, numero, bairro, cidade, estado, cep) 
                                    VALUES (:id_cliente, :rua, :numero, :bairro, :cidade, :estado, :cep)");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);

        return $stmt->execute();
    }
    public function editarEndereco($id, $rua, $numero, $bairro, $cidade, $estado, $cep) {
        $id = Sanitizer::sanitizeInt($id);
        $ruaSanitizado = Sanitizer::sanitizeString($rua);
        $numeroSanitizado = Sanitizer::sanitizeInt($numero);
        $bairroSanitizado = Sanitizer::sanitizeString($bairro);
        $cidadeSanitizado = Sanitizer::sanitizeString($cidade);
        $estadoSanitizado = Sanitizer::sanitizeString($estado);
        $cepSanitizado = Sanitizer::sanitizeString($cep);

        // ✅ Confirma que o endereço existe antes de editar
        $stmt = $this->db->prepare("SELECT id_cliente FROM enderecos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $endereco = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$endereco) {
            throw new Exception("Erro: Endereço não encontrado.");
        }

        // ✅ Verifica se o cliente associado ao endereço ainda existe
        $stmt = $this->db->prepare("SELECT id FROM clientes WHERE id = :id_cliente");
        $stmt->bindParam(':id_cliente', $endereco['id_cliente'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            throw new Exception("Erro: Cliente associado ao endereço não existe.");
        }

        // ✅ Atualizar o endereço
        $stmt = $this->db->prepare("UPDATE enderecos SET rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':rua', $ruaSanitizado);
        $stmt->bindParam(':numero', $numeroSanitizado, PDO::PARAM_INT);
        $stmt->bindParam(':bairro', $bairroSanitizado);
        $stmt->bindParam(':cidade', $cidadeSanitizado);
        $stmt->bindParam(':estado', $estadoSanitizado);
        $stmt->bindParam(':cep', $cepSanitizado);

        return $stmt->execute();
    }

    public function excluirEnderecosPorCliente($id_cliente) {
        // ✅ Sanitizar ID do cliente
        $id_cliente = Sanitizer::sanitizeInt($id_cliente);

        // ✅ Verificar se existem endereços antes de excluir
        $stmt = $this->db->prepare("SELECT id FROM enderecos WHERE id_cliente = :id_cliente");
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return; // Não há endereços, então não precisa excluir
        }

        // ✅ Excluir todos os endereços vinculados ao cliente
        $stmt = $this->db->prepare("DELETE FROM enderecos WHERE id_cliente = :id_cliente");
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function listarTodos() {
        $stmt = $this->db->query("
            SELECT enderecos.*, clientes.nome AS cliente_nome 
            FROM enderecos 
            INNER JOIN clientes ON enderecos.id_cliente = clientes.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorCliente($id_cliente) {
        $stmt = $this->db->prepare("SELECT * FROM enderecos WHERE id_cliente = :id_cliente");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluirEndereco($id_endereco) {
        // ✅ Verifica se o endereço existe antes de excluir
        $stmt = $this->db->prepare("SELECT id FROM enderecos WHERE id = :id_endereco");
        $stmt->bindParam(':id_endereco', $id_endereco);
        $stmt->execute();
        if (!$stmt->fetch()) {
            throw new Exception("Erro: Endereço não encontrado.");
        }

        // ✅ Exclui o endereço
        $stmt = $this->db->prepare("DELETE FROM enderecos WHERE id = :id_endereco");
        $stmt->bindParam(':id_endereco', $id_endereco);
        
        return $stmt->execute();
    }

    public function contarTotalPorCliente($id_cliente) {
        $sql = "SELECT COUNT(*) as total FROM enderecos WHERE id_cliente = :id_cliente";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    public function listarComLimitePorCliente($id_cliente, $limite, $offset) {
        $sql = "SELECT * FROM enderecos WHERE id_cliente = :id_cliente ORDER BY id ASC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM enderecos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}