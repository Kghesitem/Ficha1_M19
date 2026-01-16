<?php
// companies/edit.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
// Buscar dados atuais garantindo que pertence ao utilizador
$stmt = $pdo->prepare("SELECT * FROM EmpresasFCT WHERE id_empresa = ? AND id_utilizador = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
    die("Empresa não encontrada ou sem permissão.");
}
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome_empresa']);
    $morada = trim($_POST['morada']);
    $cae = trim($_POST['cae']);
    $responsavel = trim($_POST['responsavel']);
    $professor = trim($_POST['nome_professor']);

    if (empty($nome)) {
        $error = "O nome da empresa é obrigatório.";
    } else {
        $sql = "UPDATE EmpresasFCT SET nome_empresa=?, morada=?, CAE=?, responsavel=?, nome_professor=? WHERE id_empresa=? AND id_utilizador=?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nome, $morada, $cae, $responsavel, $professor, $id, $_SESSION['user_id']])) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Erro ao atualizar empresa.";
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h4 class="mb-0">Editar Empresa</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nome_empresa" class="form-label">Nome da Empresa *</label>
                        <input type="text" class="form-control" id="nome_empresa" name="nome_empresa"
                            value="<?php echo htmlspecialchars($company['nome_empresa']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="morada" class="form-label">Morada</label>
                        <textarea class="form-control" id="morada" name="morada"
                            rows="2"><?php echo htmlspecialchars($company['morada']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cae" class="form-label">CAE</label>
                            <input type="text" class="form-control" id="cae" name="cae"
                                value="<?php echo htmlspecialchars($company['CAE']); ?>">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="responsavel" class="form-label">Responsável na Empresa</label>
                            <input type="text" class="form-control" id="responsavel" name="responsavel"
                                value="<?php echo htmlspecialchars($company['responsavel']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nome_professor" class="form-label">Professor Acompanhante</label>
                        <input type="text" class="form-control" id="nome_professor" name="nome_professor"
                            value="<?php echo htmlspecialchars($company['nome_professor']); ?>">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="javascript:history.back()" class="btn btn-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Atualizar Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>