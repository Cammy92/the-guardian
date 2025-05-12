<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Endereco.php';

class EnderecoTest extends TestCase {
    private $enderecoModel;
    private $clienteModel;

    protected function setUp(): void {
        $this->clienteModel = new Cliente();
        $this->enderecoModel = new Endereco();

        // ✅ Limpa tabelas antes dos testes para evitar CPFs duplicados
        $this->enderecoModel->getDb()->prepare("DELETE FROM enderecos WHERE rua = 'Rua Teste'")->execute();
        $this->clienteModel->getDb()->prepare("DELETE FROM clientes WHERE cpf = '12345678901'")->execute();
    }

    public function testIncluirEnderecoSucesso() {
        // ✅ Criar um cliente e obter o ID
        $this->clienteModel->incluirCliente("Cliente Teste", "1990-05-15", "12345678901", "12345678", "11999999999");
        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '12345678901'");
        $id_cliente = $stmt->fetchColumn();

        if (!$id_cliente) {
            throw new Exception("Erro: Cliente não encontrado.");
        }

        // ✅ Adicionar endereço com dados válidos
        $resultado = $this->enderecoModel->incluirEndereco($id_cliente, "Rua Teste", "123", "Bairro Teste", "Cidade Teste", "SP", "12345678");
        $this->assertTrue($resultado, "A inclusão do endereço deve ser bem-sucedida.");
    }

    public function testIncluirEnderecoDadosInvalidos() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Erro: Dados inválidos! Verifique os campos preenchidos.");
        $this->enderecoModel->incluirEndereco(1, "", "", "", "", "", "");
    }

    public function testListarEnderecosPorCliente() {
        // ✅ Criar um cliente
        $this->clienteModel->incluirCliente("Cliente Teste", "1990-05-15", "12345678901", "12345678", "11999999999");

        // ✅ Buscar o ID do cliente recém-criado
        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '12345678901'");
        $id_cliente = $stmt->fetchColumn();

        if (!$id_cliente) {
            throw new Exception("Erro: Cliente não encontrado. Certifique-se de que ele foi criado antes de adicionar um endereço.");
        }

        // ✅ Adicionar endereços vinculados ao cliente
        $this->enderecoModel->incluirEndereco($id_cliente, "Rua 1", "100", "Bairro A", "Cidade X", "SP", "12345678");
        $this->enderecoModel->incluirEndereco($id_cliente, "Rua 2", "200", "Bairro B", "Cidade Y", "RJ", "87654321");

        // ✅ Testar listagem de endereços
        $enderecos = $this->enderecoModel->listarPorCliente($id_cliente);
        $this->assertCount(2, $enderecos, "Devem existir exatamente 2 endereços para este cliente.");

        // ✅ Verificar se os dados do primeiro endereço estão corretos
        $this->assertEquals("Rua 1", $enderecos[0]['rua']);
        $this->assertEquals("100", $enderecos[0]['numero']);
        $this->assertEquals("Bairro A", $enderecos[0]['bairro']);
    }

    public function testExcluirEnderecoSucesso() {
        // ✅ Criar um cliente e obter o ID
        $this->clienteModel->incluirCliente("Cliente Teste", "1990-05-15", "12345678901", "12345678", "11999999999");
        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '12345678901'");
        $id_cliente = $stmt->fetchColumn();

        // ✅ Criar um endereço vinculado ao cliente
        $this->enderecoModel->incluirEndereco($id_cliente, "Rua Teste", "123", "Bairro Teste", "Cidade Teste", "SP", "12345678");
        $stmt = $this->enderecoModel->getDb()->query("SELECT id FROM enderecos WHERE id_cliente = $id_cliente");
        $id_endereco = $stmt->fetchColumn();

        // ✅ Excluir o endereço
        $resultado = $this->enderecoModel->excluirEndereco($id_endereco);
        $this->assertTrue($resultado, "A exclusão do endereço deve ser bem-sucedida.");
    }

        /* ✅ Teste para edição de endereço */public function testEditarEnderecoSucesso() {
        $clienteModel = new Cliente();

        // ✅ Criar um cliente de teste para evitar erro de chave estrangeira
        $clienteModel->incluirCliente("Teste Nome", "1990-01-01", "12345678901", "12345678", "11999999999");

        // ✅ Buscar ID do cliente
        $stmt = $clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '12345678901'");
        $id_cliente = $stmt->fetchColumn();

        // ✅ Criar um endereço vinculado ao cliente
        $this->enderecoModel->incluirEndereco($id_cliente, "Rua Teste", 123, "Centro", "São Paulo", "SP", "01010101");

        // ✅ Buscar o ID do endereço recém-criado
        $stmt = $this->enderecoModel->getDb()->query("SELECT id FROM enderecos WHERE rua = 'Rua Teste'");
        $id_endereco = $stmt->fetchColumn();

        // ✅ Editar o endereço
        $resultado = $this->enderecoModel->editarEndereco($id_endereco, "Rua Editada", 456, "Vila", "Rio de Janeiro", "RJ", "22222222");
        $this->assertTrue($resultado, "A edição do endereço deve ser bem-sucedida.");
    }

    /* ✅ Teste para erro ao tentar editar um endereço inexistente */
    public function testEditarEnderecoInexistente() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Erro: Endereço não encontrado.");
        $this->enderecoModel->editarEndereco(999, "Nome Falso", 999, "Bairro Falso", "Cidade Falsa", "XX", "99999999");
    }

    public function testExcluirClienteSucesso() {
        $this->clienteModel = new Cliente();
        $this->enderecoModel = new Endereco();

        // ✅ Criar um cliente para teste
        $this->clienteModel->incluirCliente("Teste Cliente", "1990-05-15", "12345678901", "12345678", "11999999999");

        // ✅ Buscar o ID do cliente criado
        $stmt = $this->clienteModel->getDb()->query("SELECT id FROM clientes WHERE cpf = '12345678901'");
        $id_cliente = $stmt->fetchColumn();

        $this->assertNotNull($id_cliente, "O cliente deveria ser criado.");

        // ✅ Criar dois endereços vinculados ao cliente
        $this->enderecoModel->incluirEndereco($id_cliente, "Rua Teste 1", 100, "Bairro A", "São Paulo", "SP", "12345678");
        $this->enderecoModel->incluirEndereco($id_cliente, "Rua Teste 2", 200, "Bairro B", "Rio de Janeiro", "RJ", "87654321");

        // ✅ Verificar que os endereços foram adicionados
        $enderecos = $this->enderecoModel->listarPorCliente($id_cliente);
        $this->assertCount(2, $enderecos, "O cliente deveria ter 2 endereços.");

        // ✅ Excluir o cliente e verificar se os endereços também foram removidos
        $this->clienteModel->excluirCliente($id_cliente);
        $enderecosPosExclusao = $this->enderecoModel->listarPorCliente($id_cliente);
        $this->assertCount(0, $enderecosPosExclusao, "Os endereços do cliente deveriam ser removidos após exclusão.");
    }

}