<?php
// companies/view.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM EmpresasFCT WHERE id_empresa = ? AND id_utilizador = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
    die("Empresa não encontrada.");
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <?php echo htmlspecialchars($company['nome_empresa']); ?>
                </h4>
                <span class="badge bg-light text-dark">ID:
                    <?php echo $company['id_empresa']; ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold text-muted">Morada:</div>
                    <div class="col-sm-9">
                        <?php echo nl2br(htmlspecialchars($company['morada'])); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold text-muted">CAE:</div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($company['CAE']); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold text-muted">Responsável:</div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($company['responsavel']); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold text-muted">Prof. Acompanhante:</div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($company['nome_professor']); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold text-muted">Data Criação:</div>
                    <div class="col-sm-9">
                        <?php echo date('d/m/Y H:i', strtotime($company['data_criacao'])); ?>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                <a href="edit.php?id=<?php echo $company['id_empresa']; ?>" class="btn btn-warning">Editar</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>