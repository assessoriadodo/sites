<?php
require_once 'includes/config.php';

$pageTitle = 'Atuação Parlamentar';

// Filtros
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Query base
$where = [];
$params = [];

if ($categoria) {
    $where[] = "categoria = ?";
    $params[] = $categoria;
}

if ($tipo) {
    $where[] = "tipo = ?";
    $params[] = $tipo;
}

$whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = db()->prepare("SELECT * FROM acoes_parlamentares $whereClause ORDER BY criado_em DESC");
$stmt->execute($params);
$acoes = $stmt->fetchAll();

// Buscar todas as categorias e tipos únicos
$categoriasDisponiveis = db()->query("SELECT DISTINCT categoria FROM acoes_parlamentares ORDER BY categoria")->fetchAll(PDO::FETCH_COLUMN);
$tiposDisponiveis = db()->query("SELECT DISTINCT tipo FROM acoes_parlamentares ORDER BY tipo")->fetchAll(PDO::FETCH_COLUMN);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); padding: 150px 0 80px; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 1rem;">Atuação Parlamentar</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">
            Acompanhe pedidos, projetos, indicações e fiscalizações do mandato
        </p>
    </div>
</section>

<!-- Filters -->
<section class="section" style="padding-top: 3rem; background: var(--off-white);">
    <div class="container">
        <form method="GET" class="filters-form" style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; margin-bottom: 2rem;">
            <select name="categoria" style="padding: 0.75rem 1.5rem; border: 2px solid var(--gray-light); border-radius: var(--radius-lg); font-family: inherit; min-width: 200px;">
                <option value="">Todas as Categorias</option>
                <?php foreach ($categoriasDisponiveis as $cat): ?>
                <option value="<?= $cat ?>" <?= $categoria === $cat ? 'selected' : '' ?>><?= getCategoriaLabel($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="tipo" style="padding: 0.75rem 1.5rem; border: 2px solid var(--gray-light); border-radius: var(--radius-lg); font-family: inherit; min-width: 200px;">
                <option value="">Todos os Tipos</option>
                <?php foreach ($tiposDisponiveis as $tip): ?>
                <option value="<?= $tip ?>" <?= $tipo === $tip ? 'selected' : '' ?>><?= getTipoLabel($tip) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
                Filtrar
            </button>
            <?php if ($categoria || $tipo): ?>
            <a href="atuacao.php" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Limpar
            </a>
            <?php endif; ?>
        </form>
        
        <!-- Stats -->
        <div class="stats-grid" style="margin-bottom: 3rem;">
            <div class="stat-card">
                <div class="stat-number" style="font-size: 2rem;"><?= count($acoes) ?></div>
                <div class="stat-label">Ações Encontradas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="font-size: 2rem;"><?= count(array_filter($acoes, fn($a) => $a['destaque'])) ?></div>
                <div class="stat-label">Em Destaque</div>
            </div>
        </div>
    </div>
</section>

<!-- Actions Grid -->
<section class="section">
    <div class="container">
        <?php if (count($acoes) > 0): ?>
        <div class="grid-3">
            <?php foreach ($acoes as $acao): ?>
            <div class="card animate-on-scroll" data-category="<?= $acao['categoria'] ?>">
                <div class="card-image">
                    <img src="uploads/acoes/<?= $acao['imagem'] ?>" alt="<?= sanitize($acao['titulo']) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22><rect fill=%22%231e3a8a%22 width=%22400%22 height=%22225%22/><text x=%22200%22 y=%22112.5%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2216%22>Ação Parlamentar</text></svg>'">
                    <span class="card-badge"><?= getTipoLabel($acao['tipo']) ?></span>
                    <?php if ($acao['destaque']): ?>
                    <span class="card-badge" style="top: auto; bottom: 1rem; left: 1rem; background: var(--accent-gold); color: var(--primary-blue);">
                        <i class="fas fa-star"></i> Destaque
                    </span>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <span class="card-category"><?= getCategoriaLabel($acao['categoria']) ?></span>
                    <h3 class="card-title"><?= sanitize($acao['titulo']) ?></h3>
                    <p class="card-excerpt"><?= substr(strip_tags($acao['resumo']), 0, 120) ?>...</p>
                    <div class="card-meta" style="justify-content: space-between; margin-bottom: 1rem;">
                        <span><i class="far fa-calendar"></i> <?= formatDate($acao['data_acao']) ?></span>
                        <span style="background: var(--off-white); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.75rem;">
                            <?= getStatusLabel($acao['status']) ?>
                        </span>
                    </div>
                    <?php if ($acao['numero_protocolo']): ?>
                    <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">
                        <i class="fas fa-file-signature"></i> Protocolo: <?= sanitize($acao['numero_protocolo']) ?>
                    </p>
                    <?php endif; ?>
                    <a href="atuacao.php?id=<?= $acao['id'] ?>" class="btn btn-primary" style="width: 100%;">
                        Ver detalhes
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 4rem 0;">
            <div style="font-size: 4rem; color: var(--gray-light); margin-bottom: 1rem;">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Nenhuma ação encontrada</h3>
            <p style="color: var(--text-light);">Tente ajustar os filtros ou volte mais tarde.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.page-header {
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');
    background-size: 100px 100px;
    opacity: 0.5;
}
.page-header .container {
    position: relative;
    z-index: 1;
}
.filters-form select {
    cursor: pointer;
}
.filters-form select:focus {
    outline: none;
    border-color: var(--primary-blue);
}
</style>

<?php include 'includes/footer.php'; ?>
