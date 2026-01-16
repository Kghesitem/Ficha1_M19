<?php
// config/database.php

$host = 'localhost';
$db_name = 'gestao_fct_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na ligação à base de dados: " . $e->getMessage());
}
?>
