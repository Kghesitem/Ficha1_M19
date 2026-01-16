<?php
// index.php
session_start();
// Se o utilizador já estiver logado, encaminhar para o dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
include 'includes/header.php';
?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm border text-center">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold text-primary">Gestão de Empresas para FCT</h1>
        <p class="col-md-8 fs-4 mx-auto">Bem-vindo à plataforma de gestão de estágios e empresas. Esta aplicação permite
            controlar as empresas parceiras para a Formação em Contexto de Trabalho.</p>

        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-5">
            <a href="auth/login.php" class="btn btn-primary btn-lg px-4 gap-3">Entrar / Login</a>
            <a href="auth/register.php" class="btn btn-outline-secondary btn-lg px-4">Registar Conta</a>
        </div>
    </div>
</div>

<div class="row align-items-md-stretch">
    <div class="col-md-6">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
            <h2>Organização</h2>
            <p>Mantenha uma base de dados atualizada com todas as empresas parceiras, incluindo contactos, moradas e
                áreas de atividade (CAE).</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="h-100 p-5 bg-light border rounded-3">
            <h2>Acompanhamento</h2>
            <p>Registe e visualize rapidamente os professores responsáveis e os alunos associados a cada empresa de
                estágio.</p>
        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>