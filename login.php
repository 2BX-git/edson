<?php
session_start();
require_once 'config.php';
require_once 'auth.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    global $usuarios;

    if (isset($usuarios[$usuario]) && password_verify($senha, $usuarios[$usuario]['senha'])) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['usuario_tipo'] = $usuarios[$usuario]['tipo'];
        header("Location: crm.php");
        exit();
    } else {
        $msg = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; margin: auto; box-shadow: 0 0.15rem 1.25rem rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="card login-card shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Login - CRM</h3>
            <?php if ($msg): ?>
                <div class="alert alert-danger"><?= h($msg) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Usuário</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
