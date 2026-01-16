<?php
// auth/register.php
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validação básica
    if (empty($nome) || empty($email) || empty($password)) {
        $error = "Por favor, preencha todos os campos.";
    } 
    elseif(strlen($password) < 8){
        $error = "A palavra-passe tem de ser maior que 8";
    }
    elseif ($password !== $confirm_password) {
        $error = "As palavras-passe não coincidem.";
    } else {
        // Verificar se email já existe
        $stmt = $pdo->prepare("SELECT id_utilizador FROM Utilizadores WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Este email já está registado.";
        } else {
            // Hash da password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Inserir na BD
            $sql = "INSERT INTO Utilizadores (nome, email, password) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$nome, $email, $hashed_password])) {
                $success = "Registo efetuado com sucesso! Pode agora fazer login.";
            } else {
                $error = "Erro ao registar utilizador.";
            }
        }
    }
}
include '../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registo de Novo Utilizador</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                        <a href="login.php" class="alert-link">Clique aqui para entrar</a>.
                    </div>
                <?php else: ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Palavra-passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Palavra-passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registar</button>
                    </form>

                <?php endif; ?>
            </div>
            <div class="card-footer text-center">
                Já tem conta? <a href="login.php">Faça login aqui</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>