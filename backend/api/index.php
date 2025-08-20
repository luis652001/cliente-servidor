<?php
/**
 * API Principal - Sistema Cliente-Servidor
 * Arquitecturas 2025
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
require_once '../controllers/AuthController.php';
require_once '../controllers/ItemController.php';
require_once '../middlewares/AuthMiddleware.php';

// Crear instancias
$database = new Database();
$db = $database->getConnection();
$authController = new AuthController($db);
$itemController = new ItemController($db);
$authMiddleware = new AuthMiddleware();

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/', '', $path);
$path_parts = explode('/', $path);

// Obtener datos del body
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Función para enviar respuesta JSON
function sendResponse($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// Función para manejar errores
function sendError($message, $status_code = 400) {
    sendResponse([
        'success' => false,
        'message' => $message
    ], $status_code);
}

// Enrutamiento de la API
try {
    // Ruta de prueba (cuando se accede a /api/ directamente)
    if (empty($path_parts[0]) || $path_parts[0] === '') {
        sendResponse([
            'success' => true,
            'message' => 'API funcionando correctamente',
            'endpoints' => [
                'auth' => ['POST /auth/register', 'POST /auth/login', 'GET /auth/profile'],
                'items' => ['GET /items', 'POST /items', 'PUT /items/{id}', 'DELETE /items/{id}'],
                'test' => 'GET /test.php'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    // Ruta de autenticación
    if ($path_parts[0] === 'auth') {
        switch ($method) {
            case 'POST':
                if ($path_parts[1] === 'register') {
                    $result = $authController->register($input);
                    sendResponse($result, $result['success'] ? 201 : 400);
                } 
                elseif ($path_parts[1] === 'login') {
                    $result = $authController->login($input);
                    sendResponse($result, $result['success'] ? 200 : 401);
                }
                else {
                    sendError('Endpoint de autenticación no válido', 404);
                }
                break;
                
            case 'GET':
                if ($path_parts[1] === 'profile') {
                    $authMiddleware->requireAuth(function($payload) use ($authController) {
                        $result = $authController->getProfile($payload['user_id']);
                        sendResponse($result, $result['success'] ? 200 : 404);
                    });
                }
                else {
                    sendError('Endpoint de perfil no válido', 404);
                }
                break;
                
            default:
                sendError('Método HTTP no permitido', 405);
        }
    }
    
    // Ruta de prueba
    elseif ($path_parts[0] === 'test') {
        sendResponse([
            'success' => true,
            'message' => 'Test endpoint funcionando',
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => PHP_VERSION
        ]);
    }
    
    // Ruta de items (requiere autenticación)
    elseif ($path_parts[0] === 'items') {
        $authMiddleware->requireAuth(function($payload) use ($itemController, $method, $path_parts, $input) {
            $user_id = $payload['user_id'];
            
            switch ($method) {
                case 'GET':
                    if (isset($path_parts[1])) {
                        // GET /items/{id}
                        $item_id = $path_parts[1];
                        $result = $itemController->getOne($item_id, $user_id);
                        sendResponse($result, $result['success'] ? 200 : 404);
                    } else {
                        // GET /items
                        $result = $itemController->getAllByUser($user_id);
                        sendResponse($result, 200);
                    }
                    break;
                    
                case 'POST':
                    // POST /items
                    $result = $itemController->create($input, $user_id);
                    sendResponse($result, $result['success'] ? 201 : 400);
                    break;
                    
                case 'PUT':
                    // PUT /items/{id}
                    if (isset($path_parts[1])) {
                        $item_id = $path_parts[1];
                        $result = $itemController->update($item_id, $input, $user_id);
                        sendResponse($result, $result['success'] ? 200 : 400);
                    } else {
                        sendError('ID de item requerido', 400);
                    }
                    break;
                    
                case 'DELETE':
                    // DELETE /items/{id}
                    if (isset($path_parts[1])) {
                        $item_id = $path_parts[1];
                        $result = $itemController->delete($item_id, $user_id);
                        sendResponse($result, $result['success'] ? 200 : 400);
                    } else {
                        sendError('ID de item requerido', 400);
                    }
                    break;
                    
                default:
                    sendError('Método HTTP no permitido', 405);
            }
        });
    }
    
    // Ruta de estado de items
    elseif ($path_parts[0] === 'status' && isset($path_parts[1]) && isset($path_parts[2])) {
        $authMiddleware->requireAuth(function($payload) use ($itemController, $path_parts) {
            $user_id = $payload['user_id'];
            $item_id = $path_parts[1];
            $estado = $path_parts[2];
            
            $result = $itemController->changeStatus($item_id, $estado, $user_id);
            sendResponse($result, $result['success'] ? 200 : 400);
        });
    }
    
    // Ruta no encontrada
    else {
        sendError('Endpoint no encontrado', 404);
    }
    
} catch (Exception $e) {
    sendError('Error interno del servidor: ' . $e->getMessage(), 500);
}
?>
