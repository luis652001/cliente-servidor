<?php
/**
 * Endpoint Simple de Items - Sistema Cliente-Servidor
 * Versión sin autenticación para pruebas
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
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
    
    // Obtener método HTTP
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Obtener datos del body
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }
    
    // Para pruebas, usar usuario ID 1
    $user_id = 1;
    
    switch ($method) {
        case 'GET':
            // GET /items - Obtener todos los items del usuario
            $result = $itemController->getAllByUser($user_id);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
            
        case 'POST':
            // POST /items - Crear nuevo item
            $result = $itemController->create($input, $user_id);
            http_response_code($result['success'] ? 201 : 400);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
            
        case 'PUT':
            // PUT /items/{id} - Actualizar item
            $item_id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$item_id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de item requerido'
                ]);
                exit();
            }
            $result = $itemController->update($item_id, $input, $user_id);
            http_response_code($result['success'] ? 200 : 400);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
            
        case 'DELETE':
            // DELETE /items/{id} - Eliminar item
            $item_id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$item_id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de item requerido'
                ]);
                exit();
            }
            $result = $itemController->delete($item_id, $user_id);
            http_response_code($result['success'] ? 200 : 400);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método HTTP no permitido'
            ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>


