<?php
// 1. Iniciar sessão e buffer
session_start();
ob_start(); 

// 2. Configurações de cabeçalho
header('Content-Type: application/json');
ini_set('display_errors', 0); 

// 3. Importar base de dados (Caminho correto para quem está dentro da pasta auth)
try {
    require_once '../config/database.php'; 
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Erro ao ligar à base de dados.']);
    exit;
}

$response = ['success' => false, 'message' => 'Nenhuma alteração efetuada.'];

// 4. Verificar login
if (!isset($_SESSION['user_id'])) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Sessão expirada.']);
    exit;
}

$id_utilizador = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $profile_active = (isset($_POST['profile_active']) && $_POST['profile_active'] === 'true');
        $password_active = (isset($_POST['password_active']) && $_POST['password_active'] === 'true');

        // Atualizar Nome e Email
        if ($profile_active) {
            $nome = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($nome) || empty($email)) {
                ob_clean();
                echo json_encode(['success' => false, 'message' => 'Nome e Email são obrigatórios.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, email = ? WHERE id_utilizador = ?");
            $stmt->execute([$nome, $email, $id_utilizador]);
            
            // Atualizar nome na sessão para refletir no header/perfil imediatamente
            $_SESSION['user_name'] = $nome;
            $response = ['success' => true, 'message' => 'Perfil atualizado com sucesso!'];
        }

        // Atualizar Password
        // Atualizar Password
if ($password_active) {
    $password_antiga = $_POST['password_antiga'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $stmt_check = $pdo->prepare("SELECT password FROM utilizadores WHERE id_utilizador = ?");
    $stmt_check->execute([$id_utilizador]);
    $user = $stmt_check->fetch();
    if (!$user || !password_verify($password_antiga, $user['password'])) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'A password antiga está incorreta.']);
        exit;
    }
    if (empty($password) || $password !== $confirm) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'As novas passwords não coincidem.']);
        exit;
    }
    if (strlen($password) < 8) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'A password deve ter pelo menos 8 caracteres.']);
        exit;
    }
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE utilizadores SET password = ? WHERE id_utilizador = ?");
    $stmt->execute([$hashed, $id_utilizador]);
    $_SESSION['password'] = $hashed; 
    $response = ['success' => true, 'message' => 'Dados atualizados com sucesso!'];
}
        ob_clean();
        echo json_encode($response);

    } catch (Exception $e) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
    exit;
}