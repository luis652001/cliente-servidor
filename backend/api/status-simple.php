<?php
/**
 * Endpoint Simple para Cambiar Estado de Items - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo permitir GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit();
}

// Incluir archivos necesarios
require_once '../config/database.php';
require_once '../controllers/ItemController.php';

try {
    // Crear instancias
    $database = new Database();
    $db = $database->getConnection();
    $itemController = new ItemController($db);
    
    // Obtener parámetros
    $item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    
    // Validar parámetros
    if (!$item_id || !$status) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID de item y estado son requeridos'
        ]);
        exit();
    }
    
    // Para pruebas, usar usuario ID 1
    $user_id = 1;
    
    // Cambiar estado del item
    $result = $itemController->changeStatus($item_id, $status, $user_id);
    
    // Enviar respuesta
    http_response_code($result['success'] ? 200 : 400);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>


