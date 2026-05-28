<?php
require_once 'includes/config.php';

$pageTitle = 'Sobre Dodô';

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); padding: 150px 0 80px; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 1rem;">Sobre <?= sanitize(getConfig('vereador_apelido')) ?></h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">
            Conheça a história, trajetória e compromissos do vereador Douglas Souto
        </p>
    </div>
</section>

<!-- Biography Section -->
<section class="section">
    <div class="container">
        <div class="about-content">
            <div class="about-image animate-on-scroll">
                <div class="about-image-container">
                    <img src="images/dodo-biografia.jpg" alt="<?= sanitize(getConfig('vereador_nome')) ?>" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 500%22><rect fill=%22%23f8fafc%22 width=%22400%22 height=%22500%22/><text x=%22200%22 y=%22250%22 text-anchor=%22middle%22 fill=%22%231e3a8a%22 font-size=%2224%22>Foto do Vereador</text></svg>'">
                </div>
            </div>
            <div class="about-text animate-on-scroll">
                <h3>Trajetória de Vida e Compromisso com Mucuri</h3>
                <p>
                    <?= nl2br(sanitize(getConfig('vereador_biografia'))) ?>
                </p>
                <p>
                    Nascido e criado em Mucuri, Dodô sempre teve uma ligação profunda 
                    com nossa terra e nosso povo. Sua trajetória é marcada pelo trabalho 
                    honesto, pela dedicação às causas sociais e pelo compromisso inabalável 
                    com o desenvolvimento do nosso município.
                </p>
                <p>
                    Como vereador, tem atuado com firmeza na fiscalização dos recursos 
                    públicos, buscando garantir que cada real seja investido em benefício 
                    da população mucuriense.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section" style="background: var(--off-white);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Valores e Princípios</h2>
            <p class="section-subtitle">
                Os pilares que norteiam o trabalho do vereador Dodô
            </p>
        </div>
        <div class="grid-4">
            <div class="stat-card animate-on-scroll" style="text-align: left;">
                <div class="stat-icon" style="margin: 0 0 1.5rem 0;">
                    <i class="fas fa-search"></i>
                </div>
                <h4 style="margin-bottom: 1rem; color: var(--primary-blue);">Transparência</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Compromisso com a prestação de contas e acesso à informação pública 
                    para todos os cidadãos.
                </p>
            </div>
            <div class="stat-card animate-on-scroll" style="text-align: left;">
                <div class="stat-icon" style="margin: 0 0 1.5rem 0;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 style="margin-bottom: 1rem; color: var(--primary-blue);">Fiscalização</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Acompanhamento rigoroso das ações do executivo e aplicação dos 
                    recursos públicos.
                </p>
            </div>
            <div class="stat-card animate-on-scroll" style="text-align: left;">
                <div class="stat-icon" style="margin: 0 0 1.5rem 0;">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h4 style="margin-bottom: 1rem; color: var(--primary-blue);">Compromisso Social</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Dedicação às causas sociais e defesa dos mais vulneráveis da 
                    nossa sociedade.
                </p>
            </div>
            <div class="stat-card animate-on-scroll" style="text-align: left;">
                <div class="stat-icon" style="margin: 0 0 1.5rem 0;">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h4 style="margin-bottom: 1rem; color: var(--primary-blue);">Ética</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Conduta íntegra e responsável no exercício do mandato parlamentar.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Communities Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Atuação em Mucuri e Itabatã</h2>
            <p class="section-subtitle">
                Presença constante em todas as comunidades do município
            </p>
        </div>
        <div class="grid-3">
            <div class="card animate-on-scroll">
                <div class="card-content" style="text-align: center;">
                    <div class="stat-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 style="margin-bottom: 1rem;">Mucuri - Sede</h4>
                    <p style="color: var(--text-light);">
                        Atendimento constante no gabinete e presença ativa nos bairros 
                        da cidade, ouvindo demandas e buscando soluções.
                    </p>
                </div>
            </div>
            <div class="card animate-on-scroll">
                <div class="card-content" style="text-align: center;">
                    <div class="stat-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-home"></i>
                    </div>
                    <h4 style="margin-bottom: 1rem;">Itabatã</h4>
                    <p style="color: var(--text-light);">
                        Visitas regulares ao distrito, acompanhando obras, serviços 
                        públicos e necessidades da comunidade itabataense.
                    </p>
                </div>
            </div>
            <div class="card animate-on-scroll">
                <div class="card-content" style="text-align: center;">
                    <div class="stat-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <h4 style="margin-bottom: 1rem;">Zona Rural</h4>
                    <p style="color: var(--text-light);">
                        Apoio aos produtores rurais, busca por melhorias nas estradas 
                        vicinais e infraestrutura do campo.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); color: white; text-align: center;">
    <div class="container">
        <h2 style="color: white; font-size: 2.5rem; margin-bottom: 1.5rem;">
            Quer conhecer mais sobre meu trabalho?
        </h2>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto 2rem;">
            Acompanhe minhas ações parlamentares, notícias e fiscalizações
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="atuacao.php" class="btn btn-secondary">
                <i class="fas fa-briefcase"></i>
                Ver Atuação Parlamentar
            </a>
            <a href="noticias.php" class="btn btn-outline">
                <i class="fas fa-newspaper"></i>
                Acompanhar Notícias
            </a>
        </div>
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
</style>

<?php include 'includes/footer.php'; ?>
