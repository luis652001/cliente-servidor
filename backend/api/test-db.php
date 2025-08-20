<?php
/**
 * Test de Base de Datos - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

try {
    // Incluir archivo de base de datos
    require_once '../config/database.php';
    
    // Crear instancia de base de datos
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo json_encode([
            'success' => true,
            'message' => 'Conexión a base de datos exitosa',
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => PHP_VERSION
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo conectar a la base de datos',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>


