<?php
/**
 * Test de Login - Sistema Cliente-Servidor
 * Funciona con GET para pruebas
 */

// Configuración de CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

try {
    // Incluir archivos necesarios
    require_once '../../config/database.php';
    require_once '../../controllers/AuthController.php';
    
    // Crear instancias
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('No se pudo conectar a la base de datos');
    }
    
    $authController = new AuthController($db);
    
    // Simular datos de login para pruebas
    $testData = [
        'correo' => 'test@test.com',
        'password' => '123456'
    ];
    
    echo json_encode([
        'debug' => 'Iniciando test de login',
        'step' => '1 - Archivos incluidos correctamente',
        'step2' => '2 - Database conectado',
        'step3' => '3 - AuthController creado',
        'step4' => '4 - Datos de prueba preparados',
        'test_data' => $testData
    ]);
    
    // Intentar hacer login con datos de prueba
    $result = $authController->login($testData);
    
    echo json_encode([
        'debug' => 'Login ejecutado',
        'step' => '5 - Resultado del login',
        'result' => $result
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>


