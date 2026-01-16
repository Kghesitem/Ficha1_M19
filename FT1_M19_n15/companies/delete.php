<?php
// companies/delete.php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

// Eliminar apenas se pertencer ao utilizador logado
$stmt = $pdo->prepare("DELETE FROM EmpresasFCT WHERE id_empresa = ? AND id_utilizador = ?");
if ($stmt->execute([$id, $_SESSION['user_id']])) {
    $back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: " . $back);
} else {
    die("Erro ao eliminar empresa.");
}
?>