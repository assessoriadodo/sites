<?php
/**
 * Configuração do Banco de Dados
 * Vereador Douglas Souto - Dodô
 * Mucuri-BA
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'vereador_dodo');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configurações do Site
define('SITE_URL', 'http://localhost');
define('SITE_NAME', 'Vereador Douglas Souto - Dodô');
define('SITE_SLOGAN', 'Trabalho e Compromisso com Mucuri');

// Upload
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB

// Sessão
ini_set('session.cookie_httponly', 1);
session_start();

// Timezone
date_default_timezone_set('America/Bahia');

/**
 * Classe de Conexão com Banco de Dados
 */
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

/**
 * Funções Auxiliares
 */
function db() {
    return Database::getInstance()->getConnection();
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_logged']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['admin_nivel']) && $_SESSION['admin_nivel'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateSlug($string) {
    $string = strtolower(trim(preg_replace('/\s+/', '-', $string)));
    $string = preg_replace('/[^a-z0-9-]/', '', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function uploadFile($file, $directory = 'general') {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Erro no upload'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erro no upload: código ' . $file['error']];
    }
    
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'message' => 'Arquivo muito grande (máx 5MB)'];
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido'];
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $uploadPath = UPLOAD_DIR . $directory . '/';
    
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
        return ['success' => true, 'filename' => $directory . '/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Falha ao salvar arquivo'];
}

function getConfig($key) {
    static $configs = null;
    
    if ($configs === null) {
        $stmt = db()->query("SELECT chave, valor FROM configuracoes");
        $configs = [];
        while ($row = $stmt->fetch()) {
            $configs[$row['chave']] = $row['valor'];
        }
    }
    
    return isset($configs[$key]) ? $configs[$key] : '';
}

function logAction($acao, $tabela = null, $registroId = null, $detalhes = null) {
    if (!isLoggedIn()) return;
    
    $stmt = db()->prepare("INSERT INTO logs_auditoria (admin_id, acao, tabela_afetada, registro_id, detalhes, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['admin_id'],
        $acao,
        $tabela,
        $registroId,
        $detalhes,
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}

function formatDate($date) {
    if (!$date) return '';
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

function formatDateTime($date) {
    if (!$date) return '';
    $timestamp = strtotime($date);
    return date('d/m/Y H:i', $timestamp);
}

function getTipoLabel($tipo) {
    $labels = [
        'pedido_providencia' => 'Pedido de Providência',
        'indicacao' => 'Indicação',
        'projeto_lei' => 'Projeto de Lei',
        'requerimento' => 'Requerimento',
        'fiscalizacao' => 'Fiscalização',
        'outro' => 'Outro'
    ];
    return $labels[$tipo] ?? $tipo;
}

function getCategoriaLabel($categoria) {
    $labels = [
        'saude' => 'Saúde',
        'educacao' => 'Educacao',
        'infraestrutura' => 'Infraestrutura',
        'assistencia_social' => 'Assistência Social',
        'agricultura' => 'Agricultura',
        'obras_publicas' => 'Obras Públicas',
        'fiscalizacao_contratos' => 'Fiscalização de Contratos',
        'outros' => 'Outros'
    ];
    return $labels[$categoria] ?? $categoria;
}

function getStatusLabel($status) {
    $labels = [
        'rascunho' => 'Rascunho',
        'publicado' => 'Publicado',
        'protocolado' => 'Protocolado',
        'em_analise' => 'Em Análise',
        'aprovado' => 'Aprovado',
        'executado' => 'Executado',
        'arquivado' => 'Arquivado'
    ];
    return $labels[$status] ?? $status;
}
?>
