<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha email e senha.';
    } else {
        $stmt = db()->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($senha, $admin['senha'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_nivel'] = $admin['nivel'];
            $_SESSION['admin_logged'] = true;
            
            logAction('login_sucesso', 'admins', $admin['id']);
            redirect('index.php');
        } else {
            $erro = 'Email ou senha incorretos.';
            logAction('login_falhou', 'admins', null, "Email: $email");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Administrativo</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { background: white; padding: 3rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); width: 100%; max-width: 400px; }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-logo { width: 80px; height: 80px; background: linear-gradient(135deg, #1e3a8a, #2563eb); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; margin: 0 auto 1rem; }
        .login-header h1 { color: #1e3a8a; font-size: 1.5rem; margin-bottom: 0.5rem; }
        .login-header p { color: #64748b; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; }
        .form-group input { width: 100%; padding: 0.875rem; border: 2px solid #e2e8f0; border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; transition: all 0.3s; }
        .form-group input:focus { outline: none; border-color: #1e3a8a; }
        .btn-login { width: 100%; padding: 1rem; font-size: 1rem; }
        .alert-error { background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo"><i class="fas fa-landmark"></i></div>
            <h1>Painel Admin</h1>
            <p>Vereador Douglas Souto - Dodô</p>
        </div>
        <?php if ($erro): ?>
        <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?= $erro ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Senha</label>
                <input type="password" name="senha" required placeholder="Sua senha">
            </div>
            <button type="submit" class="btn btn-primary btn-login"><i class="fas fa-sign-in-alt"></i> Entrar</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem; color: #64748b; font-size: 0.875rem;">
            <i class="fas fa-shield-alt"></i> Acesso restrito à equipe do mandato
        </p>
    </div>
</body>
</html>
