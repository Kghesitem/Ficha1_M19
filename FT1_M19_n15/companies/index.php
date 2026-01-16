<?php
// companies/index.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
include '../includes/header.php';

// 1. Capturar parâmetros da URL
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'data_criacao';
$dir = isset($_GET['dir']) && strtoupper($_GET['dir']) === 'ASC' ? 'ASC' : 'DESC';

// Lista de colunas permitidas para evitar SQL Injection no ORDER BY
$allowed_sort = ['nome_empresa', 'responsavel', 'nome_professor', 'CAE', 'data_criacao'];
if (!in_array($sort, $allowed_sort)) {
    $sort = 'data_criacao';
}

// 2. Construir a Query Base
$sql = "SELECT * FROM EmpresasFCT WHERE id_utilizador = :user_id";
$params = [':user_id' => $_SESSION['user_id']];

// 3. Adicionar Filtro de Pesquisa (se existir)
if (!empty($q)) {
    $sql .= " AND (
                nome_empresa LIKE :q OR 
                responsavel LIKE :q OR 
                nome_professor LIKE :q OR 
                CAE LIKE :q
            )";
    $params[':q'] = "%$q%";
}

// 4. Adicionar Ordenação
$sql .= " ORDER BY $sort $dir";

// 5. Executar
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Minhas Empresas FCT</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Adicionar Nova
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4 border-0">
    <div class="card-body bg-light rounded">
        <form method="get" action="index.php" class="row g-2 justify-content-center">
            
            <div class="col-12 col-md-7 col-lg-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="q" 
                        class="form-control border-start-0 ps-0" 
                        placeholder="Pesquisar por nome, responsável, professor ou CAE..." 
                        value="<?php echo htmlspecialchars($q); ?>"
                    >
                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-4 d-flex gap-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-funnel me-1"></i> Pesquisar
                </button>
                <a class="btn btn-outline-secondary px-3" href="index.php" title="Limpar">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>

            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
            <input type="hidden" name="dir" value="<?php echo htmlspecialchars($dir); ?>">
        </form>
    </div>
</div>

<?php if (!empty($q)): ?>
    <div class="mb-3">
        <small class="text-muted">
            <i class="bi bi-filter-left"></i> 
            Resultados para: <span class="badge bg-secondary"><?php echo htmlspecialchars($q); ?></span>
        </small>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nome Empresa</th>
                <th>Responsável</th>
                <th>Professor</th>
                <th>CAE</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($companies) > 0): ?>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($company['nome_empresa']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($company['responsavel']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($company['nome_professor']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($company['CAE']); ?>
                        </td>
                        <td class="text-end">
                            <a href="view.php?id=<?php echo $company['id_empresa']; ?>" class="btn btn-sm btn-info text-white"
                                title="Ver Detalhes"><i class="bi bi-eye"></i></a>
                            <a href="edit.php?id=<?php echo $company['id_empresa']; ?>"
                                class="btn btn-sm btn-warning text-white" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="delete.php?id=<?php echo $company['id_empresa']; ?>" class="btn btn-sm btn-danger"
                                title="Eliminar" onclick="return confirm('Tem a certeza que deseja eliminar esta empresa?');"><i
                                    class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <p class="text-muted">Ainda não registou nenhuma empresa.</p>
                        <a href="create.php" class="btn btn-sm btn-outline-primary">Começar agora</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>