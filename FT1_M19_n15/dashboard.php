<?php
// dashboard.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
include 'includes/header.php';

// Obter contagem de empresas do utilizador
$stmt = $pdo->prepare("SELECT COUNT(*) FROM EmpresasFCT WHERE id_utilizador = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_empresas = $stmt->fetchColumn();

$empresas = $pdo->prepare("SELECT * FROM EmpresasFCT WHERE id_utilizador = ? ORDER BY data_criacao DESC limit 11");
$empresas->execute([$_SESSION['user_id']]);
$companies = $empresas->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="companies/create.php" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle"></i> Nova Empresa
            </a>
            <a href="companies/index.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-list"></i> Ver Lista
            </a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <div class="alert alert-success h-100 "style="right: 5px;background: #0D6EFD;
background: linear-gradient(210deg,rgba(13, 110, 253, 1) 0%, rgba(87, 199, 133, 1) 50%, rgba(209, 231, 221, 1) 100%);">
            <h4 class="alert-heading">Bem-vindo,
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            </h4>
            <p>Este é o seu painel de controlo. Aqui pode gerir todas as empresas de FCT associadas à sua conta.</p>
            <hr>
            <p class="mb-0">Atualmente tem <strong>
                    <?php echo $total_empresas; ?>
                </strong> empresa(s) registada(s).</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100" style="left: 5px;">
            <div class="card-body">
                <h5 class="card-title">Gerir Empresas</h5>
                <p class="card-text">Adicione, edite ou elimine registos de empresas para estágio (FCT).</p>
                <a href="companies/index.php" class="btn btn-primary">Ir para Empresas</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 d-flex gap-0 row-gap-3">

    <div class="col-md-4">
        <div class="card text-center h-100 " >
            <div class="card-body">
                <h5 class="card-title">Ultimas Empresas adicionadas</h5>
                <p class="card-text">Adicione, edite ou elimine registos de empresas para estágio (FCT).</p>
               <!-- <a href="companies/index.php" class="btn btn-primary">Ir para Empresas</a>-->
                
            </div>
        </div>
    </div>
    <!-- Mais cards podem ser adicionados aqui futuramente -->

    <?php if (count($companies) > 0): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="col-md-4">
                        <div class="card text-center h-100 ">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($company['nome_empresa']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($company['responsavel']); ?></p>
                                <tr>
                                    <td class="text-end">
                                        <a href="companies/view.php?id=<?php echo $company['id_empresa']; ?>" class="btn btn-sm btn-info text-white" 
                                            title="Ver Detalhes"><i class="bi bi-eye"></i></a>
                                        <a href="companies/edit.php?id=<?php echo $company['id_empresa']; ?>"
                                            class="btn btn-sm btn-warning text-white" title="Editar"><i class="bi bi-pencil"></i></a>
                                        <a href="companies/delete.php?id=<?php echo $company['id_empresa']; ?>" class="btn btn-sm btn-danger"
                                            title="Eliminar" onclick="return confirm('Tem a certeza que deseja eliminar esta empresa?');"><i
                                            class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
    <?php else: ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>