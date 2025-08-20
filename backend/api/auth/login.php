<?php
/**
 * Endpoint de Login - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit();
}

// Incluir archivos necesarios
require_once '../../config/database.php';
require_once '../../controllers/AuthController.php';

try {
    // Crear instancias
    $database = new Database();
    $db = $database->getConnection();
    $authController = new AuthController($db);
    
    // Obtener datos del body
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }
    
    // Validar datos requeridos
    if(empty($input['correo']) || empty($input['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Email y contraseña son requeridos'
        ]);
        exit();
    }
    
    // Hacer login
    $result = $authController->login($input);
    
    // Enviar respuesta
    http_response_code($result['success'] ? 200 : 401);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
}
?>


