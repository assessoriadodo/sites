-- =====================================================
-- BANCO DE DADOS - VEREADOR DOUGLAS SOUTO (DODÔ)
-- Mucuri-BA
-- =====================================================

CREATE DATABASE IF NOT EXISTS vereador_dodo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vereador_dodo;

-- Tabela de Administradores
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin', 'editor') DEFAULT 'editor',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir admin padrão (senha: admin123)
INSERT INTO admins (nome, email, senha, nivel) VALUES 
('Administrador', 'admin@vereadordodo.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Tabela de Categorias de Notícias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    descricao TEXT,
    ordem INT DEFAULT 0,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Notícias
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    subtitulo VARCHAR(300),
    slug VARCHAR(200) UNIQUE NOT NULL,
    resumo TEXT,
    conteudo LONGTEXT NOT NULL,
    categoria_id INT,
    imagem_capa VARCHAR(255),
    autor_id INT,
    visualizacoes INT DEFAULT 0,
    status ENUM('rascunho', 'publicado') DEFAULT 'rascunho',
    publicado_em TIMESTAMP NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (autor_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_publicado (publicado_em),
    INDEX idx_categoria (categoria_id)
);

-- Tabela de Galeria de Fotos da Notícia
CREATE TABLE IF NOT EXISTS noticia_galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    legenda VARCHAR(255),
    ordem INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE
);

-- Tabela de Ações Parlamentares
CREATE TABLE IF NOT EXISTS acoes_parlamentares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    tipo ENUM('pedido_providencia', 'indicacao', 'projeto_lei', 'requerimento', 'fiscalizacao', 'outro') NOT NULL,
    categoria ENUM('saude', 'educacao', 'infraestrutura', 'assistencia_social', 'agricultura', 'obras_publicas', 'fiscalizacao_contratos', 'outros') NOT NULL,
    resumo TEXT,
    descricao LONGTEXT,
    numero_protocolo VARCHAR(50),
    data_acao DATE,
    imagem VARCHAR(255),
    documento_pdf VARCHAR(255),
    status ENUM('protocolado', 'em_analise', 'aprovado', 'executado', 'arquivado') DEFAULT 'protocolado',
    destaque BOOLEAN DEFAULT FALSE,
    visualizacoes INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo),
    INDEX idx_categoria (categoria),
    INDEX idx_status (status),
    INDEX idx_destaque (destaque)
);

-- Tabela de Galeria de Fotos Geral
CREATE TABLE IF NOT EXISTS galeria_fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200),
    descricao TEXT,
    imagem VARCHAR(255) NOT NULL,
    categoria VARCHAR(100),
    data_foto DATE,
    destaque BOOLEAN DEFAULT FALSE,
    ordem INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_destaque (destaque),
    INDEX idx_categoria (categoria)
);

-- Tabela de Mensagens de Contato
CREATE TABLE IF NOT EXISTS mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20),
    bairro_comunidade VARCHAR(100),
    assunto VARCHAR(200),
    mensagem TEXT NOT NULL,
    lido BOOLEAN DEFAULT FALSE,
    respondido BOOLEAN DEFAULT FALSE,
    resposta TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_lido (lido),
    INDEX idx_criado (criado_em)
);

-- Tabela de Configurações do Site
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    tipo ENUM('texto', 'textarea', 'imagem', 'boolean', 'json') DEFAULT 'texto',
    descricao VARCHAR(255),
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir configurações padrão
INSERT INTO configuracoes (chave, valor, tipo, descricao) VALUES
('site_nome', 'Vereador Douglas Souto - Dodô', 'texto', 'Nome do site'),
('site_slogan', 'Trabalho e Compromisso com Mucuri', 'texto', 'Slogan do site'),
('site_descricao', 'Portal oficial do mandato do vereador Douglas Souto (Dodô) de Mucuri-BA', 'textarea', 'Descrição do site'),
('vereador_nome', 'Douglas Souto', 'texto', 'Nome do vereador'),
('vereador_apelido', 'Dodô', 'texto', 'Apelido do vereador'),
('vereador_biografia', 'Biografia do vereador será inserida aqui.', 'textarea', 'Biografia completa'),
('contato_email', 'contato@vereadordodo.com.br', 'texto', 'Email de contato'),
('contato_telefone', '(73) 99999-9999', 'texto', 'Telefone/WhatsApp'),
('contato_whatsapp', '5573999999999', 'texto', 'Número WhatsApp para API'),
('redes_instagram', 'https://instagram.com/vereadordodo', 'texto', 'Instagram'),
('redes_facebook', 'https://facebook.com/vereadordodo', 'texto', 'Facebook'),
('redes_youtube', 'https://youtube.com/@vereadordodo', 'texto', 'YouTube'),
('endereco_ufficio', 'Rua Principal, 123 - Centro, Mucuri-BA', 'texto', 'Endereço do gabinete');

-- Tabela de Logs de Auditoria
CREATE TABLE IF NOT EXISTS logs_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    acao VARCHAR(100) NOT NULL,
    tabela_afetada VARCHAR(100),
    registro_id INT,
    detalhes TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_admin (admin_id),
    INDEX idx_acao (acao),
    INDEX idx_criado (criado_em)
);

-- View para estatísticas
CREATE OR REPLACE VIEW vw_estatisticas AS
SELECT 
    (SELECT COUNT(*) FROM noticias WHERE status = 'publicado') as total_noticias,
    (SELECT COUNT(*) FROM acoes_parlamentares) as total_acoes,
    (SELECT COUNT(*) FROM galeria_fotos) as total_fotos,
    (SELECT COUNT(*) FROM mensagens WHERE lido = FALSE) as mensagens_nao_lidas,
    (SELECT COUNT(*) FROM admins) as total_usuarios;
