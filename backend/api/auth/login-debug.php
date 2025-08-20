<?php
/**
 * Endpoint de Login con Debug - Sistema Cliente-Servidor
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

echo json_encode([
    'debug' => 'Iniciando proceso de login',
    'step' => '1 - Headers configurados'
]);

try {
    echo json_encode([
        'debug' => 'Incluyendo archivos',
        'step' => '2 - Incluyendo database.php'
    ]);
    
    // Incluir archivos necesarios
    require_once '../../config/database.php';
    
    echo json_encode([
        'debug' => 'Database.php incluido',
        'step' => '3 - Incluyendo AuthController.php'
    ]);
    
    require_once '../../controllers/AuthController.php';
    
    echo json_encode([
        'debug' => 'AuthController.php incluido',
        'step' => '4 - Creando instancia de Database'
    ]);
    
    // Crear instancias
    $database = new Database();
    
    echo json_encode([
        'debug' => 'Database instanciado',
        'step' => '5 - Obteniendo conexión'
    ]);
    
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('No se pudo conectar a la base de datos');
    }
    
    echo json_encode([
        'debug' => 'Conexión exitosa',
        'step' => '6 - Creando AuthController'
    ]);
    
    $authController = new AuthController($db);
    
    echo json_encode([
        'debug' => 'AuthController creado',
        'step' => '7 - Obteniendo datos del body'
    ]);
    
    // Obtener datos del body
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }
    
    echo json_encode([
        'debug' => 'Datos obtenidos',
        'step' => '8 - Validando datos',
        'input' => $input
    ]);
    
    // Validar datos requeridos
    if(empty($input['correo']) || empty($input['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Email y contraseña son requeridos'
        ]);
        exit();
    }
    
    echo json_encode([
        'debug' => 'Datos válidos',
        'step' => '9 - Ejecutando login'
    ]);
    
    // Hacer login
    $result = $authController->login($input);
    
    echo json_encode([
        'debug' => 'Login ejecutado',
        'step' => '10 - Enviando respuesta',
        'result' => $result
    ]);
    
    // Enviar respuesta
    http_response_code($result['success'] ? 200 : 401);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>


