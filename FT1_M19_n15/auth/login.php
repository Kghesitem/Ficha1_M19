<?php
// auth/login.php
session_start();
require_once '../config/database.php';

// Se já estiver logado, redirecionar para dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare("SELECT id_utilizador, nome, password FROM Utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login válido
            $_SESSION['user_id'] = $user['id_utilizador'];
            $_SESSION['user_name'] = $user['nome'];

            header("Location: ../dashboard.php");
            exit;
        } else {
            $error = "Email ou palavra-passe incorretos.";
        }
    }
}

include '../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Palavra-passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    <div class="alert alert-info mt-3 small">
                        Nota: Certifique-se que executou o script da base de dados antes de tentar o login.
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Não tem conta? <a href="register.php">Registe-se aqui</a>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>