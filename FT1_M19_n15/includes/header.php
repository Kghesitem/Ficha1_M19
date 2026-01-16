<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Define base URL for absolute paths if needed, or use relative paths carefully
$base_url = '/FT1_M19_n15';
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão FCT</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }
    </style>
</head>
    <?php
        $pagina_atual = $_SERVER['REQUEST_NAME'] ?? $_SERVER['PHP_SELF'];
    ?>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo $base_url; ?>/index.php">
                <i class="bi bi-briefcase-fill"></i> Gestão FCT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($pagina_atual, 'dashboard.php') !== false) ? 'active' : ''; ?>" 
                            href="<?php echo $base_url; ?>/dashboard.php">
                            Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($pagina_atual, 'companies/index.php') !== false) ? 'active' : ''; ?>" 
                            href="<?php echo $base_url; ?>/companies/index.php">
                            Empresas
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Olà, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo $base_url; ?>/perfil.php">Perfil</a></li>
                                <li><a class="dropdown-item" href="<?php echo $base_url; ?>/auth/logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($pagina_atual, 'login.php') !== false) ? 'active' : ''; ?>" 
                            href="<?php echo $base_url; ?>/auth/login.php">
                            Login
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($pagina_atual, 'register.php') !== false) ? 'active' : ''; ?>" 
                            href="<?php echo $base_url; ?>/auth/register.php">
                            Registo
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content py-4">