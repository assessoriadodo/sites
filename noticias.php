<?php
require_once 'includes/config.php';

$pageTitle = 'Notícias';

// Paginação e filtros
$pagina = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$porPagina = 9;
$offset = ($pagina - 1) * $porPagina;

$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$busca = isset($_GET['q']) ? trim($_GET['q']) : '';

// Query base
$where = ['n.status = ?'];
$params = ['publicado'];

if ($categoria) {
    $where[] = "c.id = ?";
    $params[] = $categoria;
}

if ($busca) {
    $where[] = "(n.titulo LIKE ? OR n.resumo LIKE ? OR n.conteudo LIKE ?)";
    $searchTerm = "%$busca%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$whereClause = implode(' AND ', $where);

// Total de notícias
$countStmt = db()->prepare("SELECT COUNT(*) FROM noticias n LEFT JOIN categorias c ON n.categoria_id = c.id WHERE $whereClause");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPaginas = ceil($total / $porPagina);

// Buscar notícias
$stmt = db()->prepare("
    SELECT n.*, c.nome as categoria_nome 
    FROM noticias n 
    LEFT JOIN categorias c ON n.categoria_id = c.id 
    WHERE $whereClause 
    ORDER BY n.publicado_em DESC 
    LIMIT $porPagina OFFSET $offset
");
$stmt->execute($params);
$noticias = $stmt->fetchAll();

// Categorias para filtro
$categorias = db()->query("SELECT id, nome, slug FROM categorias WHERE ativo = TRUE ORDER BY ordem, nome")->fetchAll();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); padding: 150px 0 80px; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 1rem;">Notícias</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">
            Acompanhe todas as novidades e atualizações do mandato
        </p>
    </div>
</section>

<!-- Search and Filters -->
<section class="section" style="padding-top: 3rem; background: var(--off-white);">
    <div class="container">
        <form method="GET" class="filters-form" style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; margin-bottom: 2rem;">
            <input type="text" name="q" placeholder="Buscar por palavra-chave..." value="<?= sanitize($busca) ?>" 
                   style="padding: 0.75rem 1.5rem; border: 2px solid var(--gray-light); border-radius: var(--radius-lg); font-family: inherit; min-width: 250px;">
            <select name="categoria" style="padding: 0.75rem 1.5rem; border: 2px solid var(--gray-light); border-radius: var(--radius-lg); font-family: inherit; min-width: 200px;">
                <option value="">Todas as Categorias</option>
                <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categoria == $cat['id'] ? 'selected' : '' ?>><?= sanitize($cat['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Buscar
            </button>
            <?php if ($busca || $categoria): ?>
            <a href="noticias.php" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Limpar
            </a>
            <?php endif; ?>
        </form>
        
        <!-- Results info -->
        <p style="text-align: center; color: var(--text-light); margin-bottom: 2rem;">
            <strong><?= $total ?></strong> notícia(s) encontrada(s)
        </p>
    </div>
</section>

<!-- News Grid -->
<section class="section">
    <div class="container">
        <?php if (count($noticias) > 0): ?>
        <div class="grid-3">
            <?php foreach ($noticias as $noticia): ?>
            <div class="card animate-on-scroll">
                <div class="card-image">
                    <img src="uploads/noticias/<?= $noticia['imagem_capa'] ?>" alt="<?= sanitize($noticia['titulo']) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22><rect fill=%22%232563eb%22 width=%22400%22 height=%22225%22/><text x=%22200%22 y=%22112.5%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2216%22>Notícia</text></svg>'">
                    <?php if ($noticia['categoria_nome']): ?>
                    <span class="card-badge"><?= sanitize($noticia['categoria_nome']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3 class="card-title"><?= sanitize($noticia['titulo']) ?></h3>
                    <p class="card-excerpt"><?= substr(strip_tags($noticia['resumo']), 0, 150) ?>...</p>
                    <div class="card-meta" style="justify-content: space-between; margin-bottom: 1rem;">
                        <span><i class="far fa-calendar"></i> <?= formatDate($noticia['publicado_em']) ?></span>
                        <span><i class="far fa-eye"></i> <?= $noticia['visualizacoes'] ?></span>
                    </div>
                    <a href="noticia.php?slug=<?= $noticia['slug'] ?>" class="btn btn-primary" style="width: 100%;">
                        Ler matéria completa
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPaginas > 1): ?>
        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem; flex-wrap: wrap;">
            <?php if ($pagina > 1): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['p' => $pagina - 1])) ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['p' => $i])) ?>" 
               class="btn <?= $i === $pagina ? 'btn-primary' : 'btn-secondary' ?>" 
               style="padding: 0.5rem 1rem; min-width: 40px;">
                <?= $i ?>
            </a>
            <?php endfor; ?>
            
            <?php if ($pagina < $totalPaginas): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['p' => $pagina + 1])) ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div style="text-align: center; padding: 4rem 0;">
            <div style="font-size: 4rem; color: var(--gray-light); margin-bottom: 1rem;">
                <i class="fas fa-newspaper"></i>
            </div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Nenhuma notícia encontrada</h3>
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
.filters-form input,
.filters-form select {
    cursor: text;
}
.filters-form input:focus,
.filters-form select:focus {
    outline: none;
    border-color: var(--primary-blue);
}
</style>

<?php include 'includes/footer.php'; ?>
