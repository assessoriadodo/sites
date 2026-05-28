<?php
require_once '../includes/config.php';
logAction('logout', 'admins', $_SESSION['admin_id'] ?? null);
session_destroy();
header('Location: login.php');
exit;
