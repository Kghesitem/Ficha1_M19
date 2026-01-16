<?php
// perfil.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
include 'includes/header.php';

// Obter dados atualizados do utilizador
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id_utilizador = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Obter última empresa (opcional para o dashboard)
$empresas = $pdo->prepare("SELECT * FROM EmpresasFCT WHERE id_utilizador = ? ORDER BY data_criacao DESC LIMIT 1");
$empresas->execute([$_SESSION['user_id']]);
$companies = $empresas->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Perfil: <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            
        </div>
    </div>
</div>


<div class="row mt-4 d-flex gap-0 row-gap-3 justify-content-center">

    <div class="col-md-6">
        <div class="card text-center h-100 " >
            <div class="card-body">
                <h3 class="card-title">Ultima Empresa adicionada</h3>
                <p class="card-text">Adicione, edite ou elimine registos de empresas para estágio (FCT).</p>
                <a href="companies/index.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-list"></i> Ver Lista Completa de Empresas
            </a>
            </div>
        </div>
    </div>
    

    <?php if (count($companies) > 0): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="col-md-6">
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
    <?php endif; ?>


   <div class="row d-flex justify-content-center">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="card-title bi bi-person-circle"> Dados do Utilizador</h2>
                        <button id="btnEditProfile" class="btn btn-sm btn-outline-warning d-flex align-items-center gap-2">
                            <i class="bi bi-pencil"></i> <span>Editar Perfil</span>
                        </button>
                    </div>
                    
                    <form id="profileForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome de Utilizador:</label>
                                <input type="text" name="username" class="form-control" disabled 
                                       value="<?php echo htmlspecialchars($user_data['nome']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" disabled 
                                       value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="card-title bi bi-key-fill"> Segurança</h2>
                                <button type="button" id="btnChangePass" class="btn btn-sm btn-outline-info d-flex align-items-center gap-2">
                                    <i class="bi bi-key"></i><span>Mudar Password</span>
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password antiga:</label>
                                <input type="password" name="password_antiga" id="password_antiga" placeholder="***********" 
                                       disabled class="form-control pass-field">
                            </div>
                            <div class="col-md-6 mb-3">
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nova Password:</label>
                                <input type="password" name="password" id="password" placeholder="***********" 
                                       disabled class="form-control pass-field">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmar Nova Password:</label>
                                <input type="password" name="confirm_password" id="confirm_password" 
                                       placeholder="***********" disabled class="form-control pass-field">
                            </div>
                        </div>

                        <div id="responseMessage" class="mt-3"></div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" id="btnSave" style="display:none;">
                                Guardar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnEditProfile = document.getElementById('btnEditProfile');
    const btnChangePass = document.getElementById('btnChangePass');
    const btnSave = document.getElementById('btnSave');
    const profileForm = document.getElementById('profileForm');
    const responseMessage = document.getElementById('responseMessage');
    
    const passInputs = profileForm.querySelectorAll('.pass-field');
    const profileInputs = profileForm.querySelectorAll('input:not(.pass-field)');

    function checkVisibility() {
        const isEditingProfile = !profileInputs[0].disabled;
        const isEditingPass = !passInputs[0].disabled;
        btnSave.style.display = (isEditingProfile || isEditingPass) ? 'block' : 'none';
    }

    // Toggle Perfil
    btnEditProfile.addEventListener('click', (e) => {
        e.preventDefault();
        const isDisabled = profileInputs[0].disabled;
        profileInputs.forEach(input => input.disabled = !isDisabled);
        btnEditProfile.innerHTML = isDisabled ? '<i class="bi bi-x-circle"></i> <span>Cancelar</span>' : '<i class="bi bi-pencil"></i> <span>Editar Perfil</span>';
        btnEditProfile.classList.toggle('btn-outline-warning');
        btnEditProfile.classList.toggle('btn-outline-danger');
        checkVisibility();
    });

    // Toggle Password
    btnChangePass.addEventListener('click', (e) => {
        e.preventDefault();
        const isDisabled = passInputs[0].disabled;
        passInputs.forEach(input => {
            input.disabled = !isDisabled;
            if (!isDisabled) input.value = "";
        });
        btnChangePass.innerHTML = isDisabled ? '<i class="bi bi-x-circle"></i> <span>Cancelar</span>' : '<i class="bi bi-key"></i> <span>Mudar Password</span>';
        btnChangePass.classList.toggle('btn-outline-info');
        btnChangePass.classList.toggle('btn-outline-danger');
        checkVisibility();
    });

    // Submissão AJAX
    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isProfileActive = !profileInputs[0].disabled;
        const isPassActive = !passInputs[0].disabled;

        // Ativar campos temporariamente para o FormData capturar os valores
        const allInputs = profileForm.querySelectorAll('input');
        allInputs.forEach(input => input.disabled = false);

        const formData = new FormData(profileForm);
        formData.append('profile_active', isProfileActive);
        formData.append('password_active', isPassActive);

        btnSave.disabled = true;
        btnSave.textContent = 'A processar...';

        fetch('auth/atualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                responseMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => location.reload(), 1500);
            } else {
                responseMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                btnSave.disabled = false;
                btnSave.textContent = 'Guardar Alterações';
                // Resetar estado visual caso falhe
                if(!isProfileActive) profileInputs.forEach(i => i.disabled = true);
                if(!isPassActive) passInputs.forEach(i => i.disabled = true);
            }
        })
        .catch(err => {
            responseMessage.innerHTML = '<div class="alert alert-danger">Erro de ligação ao servidor.</div>';
            btnSave.disabled = false;
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>