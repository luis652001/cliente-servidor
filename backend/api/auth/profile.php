<?php
/**
 * Endpoint para Obtener Perfil de Usuario - Sistema Cliente-Servidor
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
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
require_once '../../config/database.php';
require_once '../../controllers/AuthController.php';

try {
    // Obtener el token del header Authorization
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    
    if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Token de autorización requerido'
        ]);
        exit();
    }
    
    $token = $matches[1];
    
    // Crear instancias
    $database = new Database();
    $db = $database->getConnection();
    $authController = new AuthController($db);
    
    // Validar token y obtener usuario
    $result = $authController->validateToken($token);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'user' => $result['user']
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => $result['message']
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


