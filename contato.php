<?php
require_once 'includes/config.php';
$pageTitle = 'Contato';
$mensagemEnviada = false;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $telefone = sanitize($_POST['telefone'] ?? '');
    $bairro = sanitize($_POST['bairro'] ?? '');
    $assunto = sanitize($_POST['assunto'] ?? '');
    $mensagem = sanitize($_POST['mensagem'] ?? '');
    
    if (strlen($nome) < 3 || strlen($mensagem) < 10) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } else {
        $stmt = db()->prepare("INSERT INTO mensagens (nome, email, telefone, bairro_comunidade, assunto, mensagem) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nome, $email, $telefone, $bairro, $assunto, $mensagem])) {
            $mensagemEnviada = true;
            logAction('nova_mensagem_contato', 'mensagens');
        }
    }
}
include 'includes/header.php';
?>
<section class="page-header" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%); padding: 150px 0 80px; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; font-size: 3rem; margin-bottom: 1rem;">Fale com Dodô</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 700px; margin: 0 auto;">Envie sua demanda, sugestão ou denúncia. Sua voz é importante!</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid-2" style="align-items: start;">
            <div>
                <?php if ($mensagemEnviada): ?>
                <div style="background: #d1fae5; border: 1px solid #10b981; padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; color: #065f46;">
                    <i class="fas fa-check-circle" style="color: #10b981; margin-right: 0.5rem;"></i>
                    Mensagem enviada com sucesso! Em breve entraremos em contato.
                </div>
                <?php endif; ?>
                <?php if ($erro): ?>
                <div style="background: #fee2e2; border: 1px solid #ef4444; padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; color: #991b1b;">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444; margin-right: 0.5rem;"></i>
                    <?= $erro ?>
                </div>
                <?php endif; ?>
                <form id="contactForm" method="POST" style="background: var(--off-white); padding: 2rem; border-radius: var(--radius-xl);">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nome *</label>
                        <input type="text" name="nome" required style="width: 100%; padding: 0.875rem; border: 2px solid var(--gray-light); border-radius: var(--radius-md); font-family: inherit;">
                    </div>
                    <div class="grid-2" style="gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                            <input type="email" name="email" style="width: 100%; padding: 0.875rem; border: 2px solid var(--gray-light); border-radius: var(--radius-md); font-family: inherit;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Telefone</label>
                            <input type="tel" name="telefone" style="width: 100%; padding: 0.875rem; border: 2px solid var(--gray-light); border-radius: var(--radius-md); font-family: inherit;">
                        </div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bairro/Comunidade</label>
                        <input type="text" name="bairro" style="width: 100%; padding: 0.875rem; border: 2px solid var(--gray-light); border-radius: var(--radius-md); font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Assunto</label>
                        <input type="text" name="assunto" style="width: 100%; padding: 0.875rem; border: 2px solid var(--gray-light); border-radius: var(--radius-md); font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Mensagem *</label>
                        <textarea name="message" rows="5" required style="width: 100%; padding: 0.875rem; border: 2px solid var(--gray-light); border-radius: var(--radius-md); font-family: inherit; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Enviar Mensagem
                    </button>
                </form>
            </div>
            <div>
                <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; color: var(--primary-blue);">Informações de Contato</h3>
                    <ul class="contact-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary-blue); font-size: 1.25rem; margin-top: 0.25rem;"></i>
                            <div><strong>Endereço:</strong><br><?= sanitize(getConfig('endereco_ufficio')) ?></div>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <i class="fas fa-phone" style="color: var(--primary-blue); font-size: 1.25rem; margin-top: 0.25rem;"></i>
                            <div><strong>Telefone:</strong><br><a href="tel:<?= sanitize(getConfig('contato_telefone')) ?>"><?= sanitize(getConfig('contato_telefone')) ?></a></div>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <i class="fab fa-whatsapp" style="color: #25d366; font-size: 1.25rem; margin-top: 0.25rem;"></i>
                            <div><strong>WhatsApp:</strong><br><a href="https://wa.me/<?= sanitize(getConfig('contato_whatsapp')) ?>" target="_blank">Enviar mensagem</a></div>
                        </li>
                        <li style="display: flex; gap: 1rem; align-items: flex-start;">
                            <i class="fas fa-envelope" style="color: var(--primary-blue); font-size: 1.25rem; margin-top: 0.25rem;"></i>
                            <div><strong>Email:</strong><br><a href="mailto:<?= sanitize(getConfig('contato_email')) ?>"><?= sanitize(getConfig('contato_email')) ?></a></div>
                        </li>
                    </ul>
                </div>
                <div class="card" style="padding: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; color: var(--primary-blue);">Redes Sociais</h3>
                    <div class="footer-social" style="justify-content: flex-start;">
                        <?php if (getConfig('redes_instagram')): ?><a href="<?= sanitize(getConfig('redes_instagram')) ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                        <?php if (getConfig('redes_facebook')): ?><a href="<?= sanitize(getConfig('redes_facebook')) ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                        <?php if (getConfig('redes_youtube')): ?><a href="<?= sanitize(getConfig('redes_youtube')) ?>" target="_blank"><i class="fab fa-youtube"></i></a><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>.page-header{position:relative;overflow:hidden}.page-header::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');background-size:100px 100px;opacity:0.5}.page-header .container{position:relative;z-index:1}</style>
<?php include 'includes/footer.php'; ?>
