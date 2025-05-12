-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS the_guardian;

-- Selecionando o banco de dados
USE the_guardian;

-- Criação da tabela `usuarios`
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,        -- Senha armazenada normalmente
    email VARCHAR(255) NOT NULL UNIQUE, -- E-mail para login
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Data de criação
);

-- Criação da tabela `clientes`
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL, -- Chave estrangeira para a tabela `usuarios`
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    rg VARCHAR(20) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    relacionamento_especial BOOLEAN DEFAULT FALSE, -- Flag para identificar se tem relacionamento especial
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Criação da tabela `enderecos`
CREATE TABLE IF NOT EXISTS enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL, -- Chave estrangeira para a tabela `clientes`
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(255),
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id)
);
