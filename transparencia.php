<?php
require_once 'includes/config.php';
$pageTitle = 'Transparência';

$stmt = db()->query("SELECT * FROM acoes_parlamentares WHERE documento_pdf IS NOT NULL AND documento_pdf != '' ORDER BY criado_em DESC");
$documentos = $stmt->fetchAll();
include 'includes/header.php';
?>
<section class="page-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); padding: 150px 0 80px; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 1rem;">Transparência Pública</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">Acesso a documentos, pedidos e fiscalizações do mandato</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid-2" style="align-items: start;">
            <div>
                <div class="card" style="padding: 2rem;">
                    <h3 style="margin-bottom: 1rem; color: var(--primary-blue);"><i class="fas fa-search"></i> Atuação Fiscalizatória</h3>
                    <p style="color: var(--text-light); line-height: 1.8; margin-bottom: 1.5rem;">
                        O vereador Douglas Souto (Dodô) atua com firmeza na fiscalização dos recursos públicos municipais, 
                        acompanhando contratos, obras e serviços prestados à população de Mucuri.
                    </p>
                    <ul style="display: flex; flex-direction: column; gap: 1rem; color: var(--text-medium);">
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;"><i class="fas fa-check-circle" style="color: var(--success); margin-top: 0.25rem;"></i><div><strong>Acompanhamento de Contratos:</strong> Fiscalização de contratos públicos para garantir o melhor uso dos recursos.</div></li>
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;"><i class="fas fa-check-circle" style="color: var(--success); margin-top: 0.25rem;"></i><div><strong>Vistoria de Obras:</strong> Visitas técnicas para verificar andamento e qualidade das obras públicas.</div></li>
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;"><i class="fas fa-check-circle" style="color: var(--success); margin-top: 0.25rem;"></i><div><strong>Pedidos de Informação:</strong> Requerimentos oficiais para transparência da gestão municipal.</div></li>
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;"><i class="fas fa-check-circle" style="color: var(--success); margin-top: 0.25rem;"></i><div><strong>Denúncias e Apurações:</strong> Encaminhamento de denúncias aos órgãos competentes.</div></li>
                    </ul>
                </div>
            </div>
            <div>
                <div class="card" style="padding: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; color: var(--primary-blue);"><i class="fas fa-file-pdf"></i> Documentos Disponíveis</h3>
                    <?php if (count($documentos) > 0): ?>
                    <ul style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php foreach ($documentos as $doc): ?>
                        <li style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--off-white); border-radius: var(--radius-md);">
                            <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.5rem;"></i>
                            <div style="flex: 1;">
                                <strong style="display: block; margin-bottom: 0.25rem;"><?= sanitize($doc['titulo']) ?></strong>
                                <span style="font-size: 0.8rem; color: var(--text-light);"><?= getTipoLabel($doc['tipo']) ?> - <?= formatDate($doc['data_acao']) ?></span>
                            </div>
                            <a href="uploads/documentos/<?= $doc['documento_pdf'] ?>" target="_blank" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;"><i class="fas fa-download"></i></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p style="color: var(--text-light); text-align: center; padding: 2rem 0;">Nenhum documento disponível no momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<style>.page-header{position:relative;overflow:hidden}.page-header::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');background-size:100px 100px;opacity:0.5}.page-header .container{position:relative;z-index:1}</style>
<?php include 'includes/footer.php'; ?>
