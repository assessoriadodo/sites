    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-landmark"></i>
                        <span><?= sanitize(getConfig('vereador_nome')) ?></span>
                    </div>
                    <p class="footer-slogan"><?= sanitize(getConfig('site_slogan')) ?></p>
                    <p class="footer-description">
                        Trabalhando com transparência, responsabilidade e compromisso 
                        pelo desenvolvimento de Mucuri e Itabatã.
                    </p>
                    <div class="footer-social">
                        <?php if (getConfig('redes_instagram')): ?>
                        <a href="<?= sanitize(getConfig('redes_instagram')) ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (getConfig('redes_facebook')): ?>
                        <a href="<?= sanitize(getConfig('redes_facebook')) ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (getConfig('redes_youtube')): ?>
                        <a href="<?= sanitize(getConfig('redes_youtube')) ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Links Rápidos</h4>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Início</a></li>
                        <li><a href="sobre.php"><i class="fas fa-chevron-right"></i> Sobre Dodô</a></li>
                        <li><a href="atuacao.php"><i class="fas fa-chevron-right"></i> Atuação Parlamentar</a></li>
                        <li><a href="noticias.php"><i class="fas fa-chevron-right"></i> Notícias</a></li>
                        <li><a href="galeria.php"><i class="fas fa-chevron-right"></i> Galeria de Fotos</a></li>
                        <li><a href="transparencia.php"><i class="fas fa-chevron-right"></i> Transparência</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contato</h4>
                    <ul class="contact-list">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <?= sanitize(getConfig('endereco_ufficio')) ?>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <a href="tel:<?= sanitize(getConfig('contato_telefone')) ?>">
                                <?= sanitize(getConfig('contato_telefone')) ?>
                            </a>
                        </li>
                        <li>
                            <i class="fab fa-whatsapp"></i>
                            <a href="https://wa.me/<?= sanitize(getConfig('contato_whatsapp')) ?>" target="_blank" rel="noopener noreferrer">
                                Enviar mensagem no WhatsApp
                            </a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?= sanitize(getConfig('contato_email')) ?>">
                                <?= sanitize(getConfig('contato_email')) ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= sanitize(getConfig('site_name')) ?>. Todos os direitos reservados.</p>
                <p>Desenvolvido com compromisso e transparência.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/<?= sanitize(getConfig('contato_whatsapp')) ?>" 
       class="whatsapp-float" 
       target="_blank" 
       rel="noopener noreferrer"
       title="Fale conosco no WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>
