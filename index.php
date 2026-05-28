<?php
require_once 'includes/config.php';

$pageTitle = 'Início';

// Buscar estatísticas
$stmt = db()->query("SELECT * FROM vw_estatisticas");
$stats = $stmt->fetch();

// Buscar notícias recentes
$stmt = db()->query("
    SELECT n.*, c.nome as categoria_nome 
    FROM noticias n 
    LEFT JOIN categorias c ON n.categoria_id = c.id 
    WHERE n.status = 'publicado' 
    ORDER BY n.publicado_em DESC 
    LIMIT 6
");
$noticiasRecentes = $stmt->fetchAll();

// Buscar ações em destaque
$stmt = db()->query("
    SELECT * FROM acoes_parlamentares 
    WHERE destaque = TRUE 
    ORDER BY criado_em DESC 
    LIMIT 4
");
$acoesDestaque = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge">
                    <i class="fas fa-star"></i> Vereador de Mucuri-BA
                </span>
                <h1 class="hero-title">
                    Douglas Souto<br>
                    <span><?= sanitize(getConfig('vereador_apelido')) ?></span>
                </h1>
                <p class="hero-description">
                    Trabalhando com transparência, responsabilidade e compromisso 
                    pelo desenvolvimento de Mucuri e Itabatã. Fiscalização séria 
                    e defesa dos interesses do nosso povo.
                </p>
                <div class="hero-buttons">
                    <a href="atuacao.php" class="btn btn-primary">
                        <i class="fas fa-briefcase"></i>
                        Conheça meu trabalho
                    </a>
                    <a href="noticias.php" class="btn btn-outline">
                        <i class="fas fa-newspaper"></i>
                        Acompanhe as notícias
                    </a>
                    <a href="contato.php" class="btn btn-secondary" style="background: white; color: var(--primary-blue);">
                        <i class="fas fa-comment-dots"></i>
                        Fale com Dodô
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-image-container">
                    <img src="images/dodo-hero.jpg" alt="Vereador Douglas Souto - Dodô" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 500%22><rect fill=%22%231e3a8a%22 width=%22400%22 height=%22500%22/><text x=%22200%22 y=%22250%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2224%22>Foto do Vereador</text></svg>'">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-number" data-count="<?= $stats['total_noticias'] ?? 0 ?>"><?= ($stats['total_noticias'] ?? 0) ?>+</div>
                <div class="stat-label">Notícias Publicadas</div>
            </div>
            <div class="stat-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="stat-number" data-count="<?= $stats['total_acoes'] ?? 0 ?>"><?= ($stats['total_acoes'] ?? 0) ?>+</div>
                <div class="stat-label">Ações Parlamentares</div>
            </div>
            <div class="stat-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-number" data-count="<?= $stats['total_fotos'] ?? 0 ?>"><?= ($stats['total_fotos'] ?? 0) ?>+</div>
                <div class="stat-label">Fotos no Acervo</div>
            </div>
            <div class="stat-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number" data-count="1000">1000+</div>
                <div class="stat-label">Pessoas Atendidas</div>
            </div>
        </div>
    </div>
</section>

<!-- Sobre Section Preview -->
<section class="section about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-image animate-on-scroll">
                <div class="about-image-container">
                    <img src="images/dodo-sobre.jpg" alt="Sobre Dodô" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 500%22><rect fill=%22%23f8fafc%22 width=%22400%22 height=%22500%22/><text x=%22200%22 y=%22250%22 text-anchor=%22middle%22 fill=%22%231e3a8a%22 font-size=%2224%22>Foto Institucional</text></svg>'">
                </div>
            </div>
            <div class="about-text animate-on-scroll">
                <h3>Sobre <?= sanitize(getConfig('vereador_apelido')) ?></h3>
                <p>
                    <?= substr(sanitize(getConfig('vereador_biografia')), 0, 500) ?>...
                </p>
                <p>
                    Comprometido com o trabalho sério e transparente, Dodô tem sido 
                    uma voz ativa na defesa dos interesses de Mucuri, fiscalizando 
                    contratos, buscando recursos e garantindo que as necessidades 
                    da população sejam atendidas.
                </p>
                <div class="about-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="highlight-text">Transparência</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="highlight-text">Fiscalização</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <span class="highlight-text">Compromisso</span>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="highlight-text">Trabalho Social</span>
                    </div>
                </div>
                <a href="sobre.php" class="btn btn-primary" style="margin-top: 2rem;">
                    <i class="fas fa-user"></i>
                    Conhecer história completa
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Ações em Destaque -->
<?php if (count($acoesDestaque) > 0): ?>
<section class="section" style="background: var(--off-white);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Ações em Destaque</h2>
            <p class="section-subtitle">
                Confira as principais ações, fiscalizações e projetos do mandato
            </p>
        </div>
        <div class="grid-4">
            <?php foreach ($acoesDestaque as $acao): ?>
            <div class="card animate-on-scroll" data-category="<?= $acao['categoria'] ?>">
                <div class="card-image">
                    <img src="uploads/acoes/<?= $acao['imagem'] ?>" alt="<?= sanitize($acao['titulo']) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22><rect fill=%22%231e3a8a%22 width=%22400%22 height=%22225%22/><text x=%22200%22 y=%22112.5%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2216%22>Ação Parlamentar</text></svg>'">
                    <span class="card-badge"><?= getTipoLabel($acao['tipo']) ?></span>
                </div>
                <div class="card-content">
                    <span class="card-category"><?= getCategoriaLabel($acao['categoria']) ?></span>
                    <h3 class="card-title"><?= sanitize($acao['titulo']) ?></h3>
                    <p class="card-excerpt"><?= substr(sanitize($acao['resumo']), 0, 100) ?>...</p>
                    <div class="card-meta">
                        <span><i class="far fa-calendar"></i> <?= formatDate($acao['data_acao']) ?></span>
                        <span><i class="far fa-eye"></i> <?= $acao['visualizacoes'] ?></span>
                    </div>
                    <a href="atuacao.php?id=<?= $acao['id'] ?>" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                        Saiba mais
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="atuacao.php" class="btn btn-secondary">
                <i class="fas fa-list"></i>
                Ver todas as ações
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Notícias Recentes -->
<?php if (count($noticiasRecentes) > 0): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Últimas Notícias</h2>
            <p class="section-subtitle">
                Acompanhe as novidades e atualizações do mandato
            </p>
        </div>
        <div class="grid-3">
            <?php foreach ($noticiasRecentes as $noticia): ?>
            <div class="card animate-on-scroll">
                <div class="card-image">
                    <img src="uploads/noticias/<?= $noticia['imagem_capa'] ?>" alt="<?= sanitize($noticia['titulo']) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22><rect fill=%22%232563eb%22 width=%22400%22 height=%22225%22/><text x=%22200%22 y=%22112.5%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2216%22>Notícia</text></svg>'">
                    <?php if ($noticia['categoria_nome']): ?>
                    <span class="card-badge"><?= sanitize($noticia['categoria_nome']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3 class="card-title"><?= sanitize($noticia['titulo']) ?></h3>
                    <p class="card-excerpt"><?= substr(strip_tags($noticia['resumo']), 0, 120) ?>...</p>
                    <div class="card-meta">
                        <span><i class="far fa-calendar"></i> <?= formatDate($noticia['publicado_em']) ?></span>
                        <span><i class="far fa-eye"></i> <?= $noticia['visualizacoes'] ?></span>
                    </div>
                    <a href="noticia.php?slug=<?= $noticia['slug'] ?>" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                        Ler matéria completa
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="noticias.php" class="btn btn-secondary">
                <i class="fas fa-newspaper"></i>
                Ver todas as notícias
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); color: white; text-align: center;">
    <div class="container">
        <h2 style="color: white; font-size: 2.5rem; margin-bottom: 1.5rem;">
            Tem uma demanda ou sugestão?
        </h2>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto 2rem;">
            Seu pedido é muito importante. Entre em contato e vamos trabalhar juntos 
            pelo desenvolvimento de Mucuri.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="https://wa.me/<?= sanitize(getConfig('contato_whatsapp')) ?>" 
               class="btn btn-secondary" 
               target="_blank" 
               rel="noopener noreferrer">
                <i class="fab fa-whatsapp"></i>
                Enviar WhatsApp
            </a>
            <a href="contato.php" class="btn btn-outline">
                <i class="fas fa-envelope"></i>
                Enviar mensagem
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
