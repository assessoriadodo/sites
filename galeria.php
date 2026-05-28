<?php
require_once 'includes/config.php';
$pageTitle = 'Galeria de Fotos';

$categoria = isset($_GET['cat']) ? $_GET['cat'] : '';
$where = $categoria ? "WHERE categoria = ?" : "";
$params = $categoria ? [$categoria] : [];

$stmt = db()->prepare("SELECT * FROM galeria_fotos $where ORDER BY data_foto DESC, ordem ASC");
$stmt->execute($params);
$fotos = $stmt->fetchAll();

$categorias = db()->query("SELECT DISTINCT categoria FROM galeria_fotos WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria")->fetchAll(PDO::FETCH_COLUMN);
include 'includes/header.php';
?>
<section class="page-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); padding: 150px 0 80px; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 1rem;">Galeria de Fotos</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">Confira momentos importantes do mandato</p>
    </div>
</section>
<section class="section" style="padding-top: 3rem; background: var(--off-white);">
    <div class="container">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center; margin-bottom: 2rem;">
            <a href="galeria.php" class="btn <?= !$categoria ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 0.5rem 1rem;">Todas</a>
            <?php foreach ($categorias as $cat): ?>
            <a href="?cat=<?= urlencode($cat) ?>" class="btn <?= $categoria === $cat ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 0.5rem 1rem;"><?= sanitize($cat) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <?php if (count($fotos) > 0): ?>
        <div class="grid-4">
            <?php foreach ($fotos as $foto): ?>
            <div class="card animate-on-scroll" style="cursor: pointer;" data-lightbox onclick="openLightbox('uploads/galeria/<?= $foto['imagem'] ?>', '<?= sanitize($foto['titulo'] ?: '') ?>')">
                <div class="card-image">
                    <img src="uploads/galeria/<?= $foto['imagem'] ?>" alt="<?= sanitize($foto['titulo'] ?: 'Foto') ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22><rect fill=%22%23e2e8f0%22 width=%22400%22 height=%22300%22/><text x=%22200%22 y=%22150%22 text-anchor=%22middle%22 fill=%22%2364748b%22 font-size=%2216%22>Foto</text></svg>'">
                    <?php if ($foto['destaque']): ?>
                    <span class="card-badge"><i class="fas fa-star"></i> Destaque</span>
                    <?php endif; ?>
                </div>
                <?php if ($foto['titulo'] || $foto['descricao']): ?>
                <div class="card-content">
                    <?php if ($foto['titulo']): ?><h4 style="margin-bottom: 0.5rem; font-size: 1rem;"><?= sanitize($foto['titulo']) ?></h4><?php endif; ?>
                    <?php if ($foto['descricao']): ?><p style="font-size: 0.85rem; color: var(--text-light);"><?= substr(sanitize($foto['descricao']), 0, 80) ?>...</p><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 4rem 0;">
            <div style="font-size: 4rem; color: var(--gray-light); margin-bottom: 1rem;"><i class="fas fa-images"></i></div>
            <h3 style="color: var(--text-dark); margin-bottom: 0.5rem;">Nenhuma foto encontrada</h3>
        </div>
        <?php endif; ?>
    </div>
</section>
<style>.page-header{position:relative;overflow:hidden}.page-header::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');background-size:100px 100px;opacity:0.5}.page-header .container{position:relative;z-index:1}</style>
<?php include 'includes/footer.php'; ?>
