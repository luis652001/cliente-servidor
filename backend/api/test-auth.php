<?php
/**
 * Test de AuthController - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

try {
    // Incluir archivos necesarios
    require_once '../config/database.php';
    require_once '../controllers/AuthController.php';
    
    // Crear instancias
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('No se pudo conectar a la base de datos');
    }
    
    $authController = new AuthController($db);
    
    echo json_encode([
        'success' => true,
        'message' => 'AuthController cargado correctamente',
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION,
        'database_connected' => true
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s'),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>


