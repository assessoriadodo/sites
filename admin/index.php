<?php
require_once '../includes/config.php';
requireLogin();

$pageTitle = 'Dashboard';
$stats = db()->query("SELECT * FROM vw_estatisticas")->fetch();
include 'header.php';
?>
<div class="dashboard-content">
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
        <p>Painel de controle do mandato</p>
    </div>
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #1e3a8a, #2563eb); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);"><i class="fas fa-newspaper"></i></div>
            <div class="stat-number" style="color: white;"><?= $stats['total_noticias'] ?></div>
            <div class="stat-label" style="color: rgba(255,255,255,0.9);">Notícias</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #059669, #10b981); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);"><i class="fas fa-gavel"></i></div>
            <div class="stat-number" style="color: white;"><?= $stats['total_acoes'] ?></div>
            <div class="stat-label" style="color: rgba(255,255,255,0.9);">Ações</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #7c3aed, #8b5cf6); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);"><i class="fas fa-images"></i></div>
            <div class="stat-number" style="color: white;"><?= $stats['total_fotos'] ?></div>
            <div class="stat-label" style="color: rgba(255,255,255,0.9);">Fotos</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #dc2626, #ef4444); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);"><i class="fas fa-envelope"></i></div>
            <div class="stat-number" style="color: white;"><?= $stats['mensagens_nao_lidas'] ?></div>
            <div class="stat-label" style="color: rgba(255,255,255,0.9);">Mensagens</div>
        </div>
    </div>
    <div class="grid-2" style="gap: 2rem;">
        <div class="card" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 1rem;"><i class="fas fa-bolt"></i> Acesso Rápido</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <a href="noticias.php" class="btn btn-primary" style="justify-content: center;"><i class="fas fa-plus"></i> Nova Notícia</a>
                <a href="acoes.php" class="btn btn-primary" style="justify-content: center;"><i class="fas fa-plus"></i> Nova Ação</a>
                <a href="galeria.php" class="btn btn-secondary" style="justify-content: center;"><i class="fas fa-image"></i> Galeria</a>
                <a href="mensagens.php" class="btn btn-secondary" style="justify-content: center;"><i class="fas fa-inbox"></i> Mensagens</a>
            </div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 1rem;"><i class="fas fa-info-circle"></i> Informações</h3>
            <ul style="display: flex; flex-direction: column; gap: 0.75rem; color: var(--text-light);">
                <li><i class="fas fa-check" style="color: var(--success);"></i> Site institucional responsivo</li>
                <li><i class="fas fa-check" style="color: var(--success);"></i> Painel administrativo seguro</li>
                <li><i class="fas fa-check" style="color: var(--success);"></i> Gestão completa de conteúdo</li>
                <li><i class="fas fa-check" style="color: var(--success);"></i> Upload de imagens e documentos</li>
            </ul>
        </div>
    </div>
</div>
<style>
.dashboard-content { padding: 2rem; }
.dashboard-header { margin-bottom: 2rem; }
.dashboard-header h1 { color: var(--primary-blue); font-size: 2rem; margin-bottom: 0.5rem; }
.dashboard-header p { color: var(--text-light); }
</style>
<?php include 'footer.php'; ?>
