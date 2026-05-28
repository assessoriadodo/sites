<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> - Painel Administrativo</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root { --sidebar-width: 260px; }
        body { display: flex; min-height: 100vh; background: #f1f5f9; }
        .sidebar { width: var(--sidebar-width); background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%); color: white; position: fixed; left: 0; top: 0; bottom: 0; overflow-y: auto; z-index: 1000; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-logo { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; }
        .sidebar-logo i { font-size: 1.75rem; color: #fbbf24; }
        .sidebar-logo span { font-weight: 700; font-size: 1.1rem; }
        .sidebar-user { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.5rem; background: rgba(255,255,255,0.1); margin-top: 1rem; border-radius: var(--radius-md); }
        .sidebar-user-avatar { width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .sidebar-user-info { flex: 1; }
        .sidebar-user-name { font-weight: 600; font-size: 0.9rem; }
        .sidebar-user-role { font-size: 0.75rem; opacity: 0.8; }
        .sidebar-menu { padding: 1rem 0; }
        .sidebar-menu a { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.85); transition: all 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255,255,255,0.15); color: white; }
        .sidebar-menu a i { width: 20px; text-align: center; }
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 2rem; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0; }
        .logout-btn { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 0.5rem 1rem; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.5rem; }
        .logout-btn:hover { background: #ef4444; color: white; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); transition: transform 0.3s; } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><i class="fas fa-landmark"></i><span>Painel Admin</span></div>
            <p style="font-size: 0.8rem; opacity: 0.8;">Vereador Douglas Souto</p>
        </div>
        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><i class="fas fa-user"></i></div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?= sanitize($_SESSION['admin_nome']) ?></div>
                <div class="sidebar-user-role"><?= ucfirst($_SESSION['admin_nivel']) ?></div>
            </div>
        </div>
        <nav class="sidebar-menu">
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a>
            <a href="noticias.php" class="<?= basename($_SERVER['PHP_SELF']) == 'noticias.php' ? 'active' : '' ?>"><i class="fas fa-newspaper"></i> Notícias</a>
            <a href="acoes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'acoes.php' ? 'active' : '' ?>"><i class="fas fa-gavel"></i> Ações Parlamentares</a>
            <a href="galeria.php" class="<?= basename($_SERVER['PHP_SELF']) == 'galeria.php' ? 'active' : '' ?>"><i class="fas fa-images"></i> Galeria de Fotos</a>
            <a href="mensagens.php" class="<?= basename($_SERVER['PHP_SELF']) == 'mensagens.php' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Mensagens <span id="msgCount" style="margin-left: auto; background: #ef4444; padding: 0.125rem 0.5rem; border-radius: 10px; font-size: 0.75rem;"><?php echo db()->query("SELECT COUNT(*) FROM mensagens WHERE lido = FALSE")->fetchColumn(); ?></span></a>
            <a href="configuracoes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'configuracoes.php' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Configurações</a>
            <a href="../" target="_blank"><i class="fas fa-external-link-alt"></i> Ver Site</a>
            <a href="logout.php" style="color: #fca5a5;"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </nav>
    </aside>
    <div class="main-content">
