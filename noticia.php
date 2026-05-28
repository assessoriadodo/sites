<?php
require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) redirect('noticias.php');

$stmt = db()->prepare("SELECT n.*, c.nome as categoria_nome FROM noticias n LEFT JOIN categorias c ON n.categoria_id = c.id WHERE n.slug = ? AND n.status = 'publicado'");
$stmt->execute([$slug]);
$noticia = $stmt->fetch();

if (!$noticia) redirect('noticias.php');

// Atualizar visualizações
db()->prepare("UPDATE noticias SET visualizacoes = visualizacoes + 1 WHERE id = ?")->execute([$noticia['id']]);

// Buscar galeria
$stmt = db()->prepare("SELECT * FROM noticia_galeria WHERE noticia_id = ? ORDER BY ordem ASC");
$stmt->execute([$noticia['id']]);
$galeria = $stmt->fetchAll();

$pageTitle = sanitize($noticia['titulo']);
include 'includes/header.php';
?>
<article style="max-width: 900px; margin: 0 auto; padding: 2rem;">
    <header style="text-align: center; margin-bottom: 2rem; padding-top: 4rem;">
        <?php if ($noticia['categoria_nome']): ?>
        <span class="card-category" style="font-size: 1rem;"><?= sanitize($noticia['categoria_nome']) ?></span>
        <?php endif; ?>
        <h1 style="font-size: 2.5rem; color: var(--primary-blue); margin: 1rem 0; line-height: 1.3;"><?= sanitize($noticia['titulo']) ?></h1>
        <?php if ($noticia['subtitulo']): ?>
        <p style="font-size: 1.25rem; color: var(--text-light); margin-bottom: 1.5rem;"><?= sanitize($noticia['subtitulo']) ?></p>
        <?php endif; ?>
        <div style="display: flex; justify-content: center; gap: 2rem; color: var(--text-light); font-size: 0.9rem;">
            <span><i class="far fa-calendar"></i> <?= formatDateTime($noticia['publicado_em']) ?></span>
            <span><i class="far fa-eye"></i> <?= $noticia['visualizacoes'] ?> visualizações</span>
        </div>
    </header>
    <?php if ($noticia['imagem_capa']): ?>
    <figure style="margin-bottom: 2rem;">
        <img src="uploads/noticias/<?= $noticia['imagem_capa'] ?>" alt="<?= sanitize($noticia['titulo']) ?>" style="width: 100%; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg);">
    </figure>
    <?php endif; ?>
    <div class="content" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-medium); margin-bottom: 3rem;">
        <?= nl2br($noticia['conteudo']) ?>
    </div>
    <?php if (count($galeria) > 0): ?>
    <section style="margin-bottom: 3rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--primary-blue);">Galeria de Fotos</h3>
        <div class="grid-4">
            <?php foreach ($galeria as $foto): ?>
            <div class="card" style="cursor: pointer;" onclick="openLightbox('uploads/noticias/<?= $foto['imagem'] ?>', '<?= sanitize($foto['legenda'] ?: '') ?>')">
                <div class="card-image"><img src="uploads/noticias/<?= $foto['imagem'] ?>" alt="<?= sanitize($foto['legenda'] ?: 'Foto') ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22><rect fill=%22%23e2e8f0%22 width=%22400%22 height=%22300%22/><text x=%22200%22 y=%22150%22 text-anchor=%22middle%22 fill=%22%2364748b%22 font-size=%2216%22>Foto</text></svg>'"></div>
                <?php if ($foto['legenda']): ?><p style="padding: 0.75rem; font-size: 0.85rem; color: var(--text-light); text-align: center;"><?= sanitize($foto['legenda']) ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    <footer style="border-top: 1px solid var(--gray-light); padding-top: 2rem;">
        <h4 style="margin-bottom: 1rem;">Compartilhar</h4>
        <div style="display: flex; gap: 1rem;">
            <a href="https://wa.me/?text=<?= urlencode(SITE_URL . '/noticia.php?slug=' . $noticia['slug']) ?>" target="_blank" class="btn btn-secondary"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            <a href="https://facebook.com/sharer.php?u=<?= urlencode(SITE_URL . '/noticia.php?slug=' . $noticia['slug']) ?>" target="_blank" class="btn btn-secondary"><i class="fab fa-facebook"></i> Facebook</a>
        </div>
    </footer>
</article>
<div style="text-align: center; margin: 3rem 0;"><a href="noticias.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Voltar às notícias</a></div>
<?php include 'includes/footer.php'; ?>
