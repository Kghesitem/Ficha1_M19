CREATE DATABASE IF NOT EXISTS gestao_fct_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestao_fct_db;

CREATE TABLE IF NOT EXISTS Utilizadores (
    id_utilizador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    data_registo DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS EmpresasFCT (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    nome_empresa VARCHAR(150) NOT NULL,
    morada TEXT,
    CAE VARCHAR(10),
    responsavel VARCHAR(100),
    nome_professor VARCHAR(100),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_utilizador INT,
    FOREIGN KEY (id_utilizador) REFERENCES Utilizadores(id_utilizador) ON DELETE SET NULL
);
