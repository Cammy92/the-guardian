# 🛡 The Guardian - Sistema de Gestão de Clientes

## 🚀 Descrição
O **The Guardian** é um sistema de gerenciamento de clientes e endereços, desenvolvido em **PHP** com **MySQL**.  
Ele inclui funcionalidades como **inclusão, edição, exclusão e listagem de clientes**, além de **validações** e **proteção contra SQL Injection**.

---

## 📦 Tecnologias Utilizadas
- 🐘 **PHP 8+**
- 🛢 **MySQL**
- 🔧 **Composer**
- 🎨 **CSS Responsivo**
- 🧪 **PHPUnit para Testes Automatizados**

---

## ⚙️ Configuração do Projeto

### 📌 **1. Clone o repositório**
git clone https://github.com/Cammy92/the-guardian.git

---

### 📌 **2. Instale as Dependências**
composer install

---

### 📌 3. Configure o banco de dados
1️⃣ Crie um banco de dados the_guardian no MySQL.

2️⃣ Importe o arquivo dump.sql localizado na pasta do projeto:
mysql -u root -p the_guardian < dump.sql

---


🔑 4. Hashear a senha dos usuários
Após criar o banco de dados e importar dump.sql, é necessário rodar um script para hashear a senha dos usuários criados sem criptografia.

⚠️ Importante: Esse script só precisa ser executado uma vez para inicializar os usuários. Se o sistema já estiver rodando com login funcional, não é necessário executar novamente.
Execute o seguinte comando:
```sh
php script.php

📌 Isso atualizará a senha dos usuários no banco de dados, garantindo autenticação segura com hash.
💡 Após rodar o script, os usuários podem alterar suas senhas diretamente no sistema sem necessidade de rodar esse comando novamente.

📌 Agora, edite o arquivo .env com suas credenciais:

DB_HOST=localhost  
DB_NAME=the_guardian  
DB_USER=root  
DB_PASS=


---

### 📌 4. Rode os testes
php vendor/bin/phpunit tests/

---

✅ **Agora, o sistema está pronto para rodar!** 🚀  

## 🛠 Funcionalidades
✔ Cadastro de Clientes  
✔ Edição de Clientes  
✔ Exclusão de Clientes  
✔ Listagem com Paginação  
✔ Gerenciamento de Endereços  
✔ Proteção contra SQL Injection  
✔ Testes Automatizados  

## 🤝 Contribuição
Se quiser contribuir, abra um **Pull Request** ou reporte problemas na aba **Issues**. 💪🔥

📌 **Licença:** MIT  
📌 **Desenvolvido por:** Camila Ramos Araújo
