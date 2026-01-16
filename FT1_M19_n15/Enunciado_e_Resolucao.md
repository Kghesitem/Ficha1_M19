# Exercício Prático: Aplicação de Gestão de Empresas FCT


**Módulo:** Programação Web (PHP)  
**Objetivo:** Desenvolver uma aplicação web para gestão de empresas de estágio (FCT) com autenticação.

### Resumo do Projeto
Este projeto consiste no desenvolvimento de uma aplicação web completa utilizando **PHP** e **MySQL** para a gestão de parceiros de estágio (FCT - Formação em Contexto de Trabalho). 

A plataforma permite que professores e coordenadores se registem e autentiquem no sistema para gerir a sua própria carteira de empresas parceiras. As principais funcionalidades incluem:
*   **Autenticação Segura:** Sistema de registo e login para proteção dos dados.
*   **Gestão de Empresas (CRUD):** Criação, listagem, edição e remoção de registos de empresas.
*   **Organização de Dados:** Armazenamento de detalhes vitais como Morada, CAE, Responsável na empresa e Professor orientador.
*   **Dashboard:** Uma visão geral intuitiva para navegação rápida entre as diferentes funcionalidades.

O objetivo é simplificar o processo administrativo de acompanhamento de estágios, centralizando a informação numa aplicação acessível e fácil de utilizar.

---

## Parte 1: Enunciado do Exercício

### Objetivo Geral
Desenvolver uma plataforma web que permita a utilizadores registados gerir uma lista de empresas parceiras para Formação em Contexto de Trabalho (Estágios).

### Requisitos Técnicos
*   Linguagem: **PHP**
*   Base de Dados: **MySQL**
*   Servidor: **WampServer** (ou XAMPP)
*   Frontend: **Bootstrap 5** (Opcional, mas recomendado para o layout)

### Fases de Desenvolvimento

#### Fase 1 – Preparação e Base de Dados
1.  Crie uma base de dados chamada `gestao_fct_db`.
2.  Crie duas tabelas:
    *   `Utilizadores` (id, nome, email, password)
    *   `EmpresasFCT` (id, nome, morada, cae, responsavel, professor, id_utilizador)
3.  Crie o ficheiro de ligação à base de dados (`config/database.php`).

#### Fase 2 – Autenticação
1.  Crie um sistema de **registo** onde o utilizador insere nome, email e password.
2.  Crie um sistema de **login** que inicie uma sessão PHP.
3.  Garanta que as passwords são guardadas de forma segura (hash).
4.  Crie o **logout**.

#### Fase 3 – Gestão de Empresas (CRUD)
1.  Crie uma página para **listar** todas as empresas inseridas pelo utilizador logado.
2.  Crie um formulário para **adicionar** novas empresas.
3.  Permita **editar** e **eliminar** empresas existentes.

---

## Parte 2: Resolução (Código Fonte)

### 1. Script da Base de Dados (SQL)
Execute este script no phpMyAdmin:

```sql
CREATE DATABASE IF NOT EXISTS gestao_fct_db;
USE gestao_fct_db;

CREATE TABLE Utilizadores (
    id_utilizador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    data_registo DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE EmpresasFCT (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    nome_empresa VARCHAR(150) NOT NULL,
    morada TEXT,
    CAE VARCHAR(10),
    responsavel VARCHAR(100),
    nome_professor VARCHAR(100),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_utilizador INT,
    FOREIGN KEY (id_utilizador) REFERENCES Utilizadores(id_utilizador)
);
```

### 2. Configuração (config/database.php)
```php
<?php
$host = 'localhost';
$db = 'gestao_fct_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
```

### 3. Registo de Utilizador (auth/register.php)
```php
<?php
require_once '../config/database.php';
// Lógica de processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO Utilizadores (nome, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $pass]);
    echo "Utilizador registado!";
}
?>
<!-- Formulário HTML omitido para brevidade -->
```

### 4. Login (auth/login.php)
```php
<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM Utilizadores WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id_utilizador'];
        header("Location: ../dashboard.php");
    } else {
        echo "Login inválido";
    }
}
?>
```

### 5. Listar Empresas (companies/index.php)
```php
<?php
session_start();
require_once '../config/database.php';

// Verificar login
if (!isset($_SESSION['user_id'])) header("Location: ../auth/login.php");

$stmt = $pdo->prepare("SELECT * FROM EmpresasFCT WHERE id_utilizador = ?");
$stmt->execute([$_SESSION['user_id']]);
$empresas = $stmt->fetchAll();
?>

<table>
    <tr><th>Nome</th><th>Ações</th></tr>
    <?php foreach($empresas as $emp): ?>
    <tr>
        <td><?= $emp['nome_empresa'] ?></td>
        <td>
            <a href="edit.php?id=<?= $emp['id_empresa'] ?>">Editar</a>
            <a href="delete.php?id=<?= $emp['id_empresa'] ?>">Apagar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
```

### 6. Criar Empresa (companies/create.php)
```php
<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    // ... outros campos ...
    $id_user = $_SESSION['user_id'];

    $sql = "INSERT INTO EmpresasFCT (nome_empresa, id_utilizador) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $id_user]);
    header("Location: index.php");
}
?>
<form method="post">
    Nome: <input type="text" name="nome">
    <button type="submit">Guardar</button>
</form>
```
