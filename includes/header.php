<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= sanitize(getConfig('site_descricao')) ?>">
    <meta name="author" content="<?= sanitize(getConfig('vereador_nome')) ?>">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= getConfig('site_name') ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-name"><?= sanitize(getConfig('vereador_nome')) ?></span>
                        <span class="logo-nickname"><?= sanitize(getConfig('vereador_apelido')) ?></span>
                        <span class="logo-subtitle">Vereador de Mucuri-BA</span>
                    </div>
                </a>
                
                <nav class="nav-menu" id="navMenu">
                    <ul>
                        <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Início</a></li>
                        <li><a href="sobre.php" class="<?= basename($_SERVER['PHP_SELF']) == 'sobre.php' ? 'active' : '' ?>">Sobre Dodô</a></li>
                        <li><a href="atuacao.php" class="<?= basename($_SERVER['PHP_SELF']) == 'atuacao.php' ? 'active' : '' ?>">Atuação Parlamentar</a></li>
                        <li><a href="noticias.php" class="<?= basename($_SERVER['PHP_SELF']) == 'noticias.php' || basename($_SERVER['PHP_SELF']) == 'noticia.php' ? 'active' : '' ?>">Notícias</a></li>
                        <li><a href="galeria.php" class="<?= basename($_SERVER['PHP_SELF']) == 'galeria.php' ? 'active' : '' ?>">Galeria</a></li>
                        <li><a href="transparencia.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transparencia.php' ? 'active' : '' ?>">Transparência</a></li>
                        <li><a href="contato.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contato.php' ? 'active' : '' ?>">Contato</a></li>
                    </ul>
                </nav>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
