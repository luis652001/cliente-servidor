<?php
/**
 * Endpoint de Test - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Respuesta de prueba
echo json_encode([
    'success' => true,
    'message' => 'Test endpoint funcionando desde archivo específico',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'method' => $_SERVER['REQUEST_METHOD']
]);
?>


