<?php
require_once __DIR__ . '/config.php';
start_app_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token()) {
    http_response_code(400);
    exit('Solicitud no válida.');
}

$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
