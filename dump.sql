-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS the_guardian;

-- Selecionando o banco de dados
USE the_guardian;

-- Criação da tabela `usuarios`
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,        
    email VARCHAR(255) NOT NULL UNIQUE, 
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela `clientes`
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    rg VARCHAR(20) NOT NULL,
    telefone VARCHAR(15) NOT NULL
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
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Inserir um usuário para login
INSERT INTO usuarios (usuario, senha, email ) VALUES ('admin', 'senha123', 'camila@gmail.com');