<?php
/**
 * Archivo de prueba - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Respuesta de prueba
echo json_encode([
    'success' => true,
    'message' => 'Backend funcionando correctamente',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
]);
?>


