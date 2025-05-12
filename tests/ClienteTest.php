<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Cliente.php';

class ClienteTest extends TestCase {
    private $clienteModel;

    protected function setUp(): void {
        $this->clienteModel = new Cliente();
    }

    /* ✅ Teste para inclusão de cliente */
    public function testIncluirCliente() {
        $cpfAleatorio = rand(10000000000, 99999999999); // 🔹 Gera CPF único para evitar conflitos
        
        $resultado = $this->clienteModel->incluirCliente("Teste Nome", "1995-08-20", $cpfAleatorio, "12345678", "11999999999");
        $this->assertTrue($resultado, "A inclusão do cliente deve ser bem-sucedida.");
    }

    /* ✅ Teste para edição de cliente existente */
    public function testEditarClienteSucesso() {
        $cpfAleatorio = rand(10000000000, 99999999999);
        $this->clienteModel->incluirCliente("Teste Nome", "1995-08-20", $cpfAleatorio, "12345678", "11999999999");

        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '$cpfAleatorio'");
        $id_cliente = $stmt->fetchColumn();

        $resultado = $this->clienteModel->editarCliente($id_cliente, "Nome Editado", "1994-07-15", $cpfAleatorio, "87654321", "11988887777");
        $this->assertTrue($resultado, "A edição do cliente deve ser bem-sucedida.");
    }

    /* ✅ Teste para erro ao tentar editar com CPF duplicado */
    public function testEditarClienteCpfDuplicado() {
        $cpf1 = rand(10000000000, 99999999999);
        $cpf2 = rand(10000000000, 99999999999);

        $this->clienteModel->incluirCliente("Cliente 1", "2000-05-12", $cpf1, "12345678", "11999999999");
        $this->clienteModel->incluirCliente("Cliente 2", "1998-04-20", $cpf2, "87654321", "11988887777");

        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '$cpf2'");
        $id_cliente = $stmt->fetchColumn();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Erro: Este CPF já está cadastrado para outro cliente.");
        $this->clienteModel->editarCliente($id_cliente, "Cliente Alterado", "2000-05-12", $cpf1, "99999999", "11777777777");
    }

    /* ✅ Teste para erro ao tentar editar um cliente inexistente */
    public function testEditarClienteInexistente() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Erro: Cliente não encontrado.");
        $this->clienteModel->editarCliente(999, "Nome Falso", "1980-10-05", "11223344556", "123123123", "11888888888");
    }

    /* ✅ Teste para exclusão de cliente */
    public function testExcluirClienteSucesso() {
        $cpfAleatorio = rand(10000000000, 99999999999);
        $this->clienteModel->incluirCliente("Teste Nome", "1995-08-20", $cpfAleatorio, "12345678", "11999999999");

        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '$cpfAleatorio'");
        $id_cliente = $stmt->fetchColumn();

        $resultado = $this->clienteModel->excluirCliente($id_cliente);
        $this->assertTrue($resultado, "A exclusão do cliente deve ser bem-sucedida.");
    }

    /* ✅ Teste para erro ao tentar excluir um cliente inexistente */
    public function testExcluirClienteInexistente() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Erro: Cliente não encontrado.");
        $this->clienteModel->excluirCliente(999);
    }

    /* ✅ Teste para contar total de clientes */
    public function testContarTotalClientes() {
        $total = $this->clienteModel->contarTotal();
        $this->assertGreaterThanOrEqual(0, $total, "O total de clientes deve ser maior ou igual a zero");
    }

    /* ✅ Teste para listar clientes com limite */
    public function testListarClientesComLimite() {
        $clientes = $this->clienteModel->listarComLimite(5, 0);
        $this->assertLessThanOrEqual(5, count($clientes), "O número de clientes retornado não pode ser maior que o limite definido");
    }
}
?>