<?php
/**
 * Middleware de Autenticación
 * Sistema Cliente-Servidor - Arquitecturas 2025
 */

require_once '../config/jwt.php';

class AuthMiddleware {
    private $jwt;

    public function __construct() {
        $this->jwt = new JWTConfig();
    }

    /**
     * Verifica si el token JWT es válido
     * @return array|false Retorna el payload del token si es válido, false si no
     */
    public function authenticate() {
        // Obtener el token del header Authorization
        $headers = getallheaders();
        $auth_header = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        // Verificar formato del header
        if(empty($auth_header) || !preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
            return false;
        }

        $token = $matches[1];
        
        // Validar el token
        $payload = $this->jwt->validateToken($token);
        
        if($payload) {
            return $payload;
        }
        
        return false;
    }

    /**
     * Middleware para proteger rutas
     * @param callable $callback Función a ejecutar si la autenticación es exitosa
     * @return void
     */
    public function requireAuth($callback) {
        $payload = $this->authenticate();
        
        if($payload) {
            // Token válido, ejecutar callback con el payload
            call_user_func($callback, $payload);
        } else {
            // Token inválido o no proporcionado
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Token de autenticación requerido o inválido'
            ]);
            exit;
        }
    }

    /**
     * Middleware opcional para obtener información del usuario si está autenticado
     * @param callable $callback Función a ejecutar
     * @return void
     */
    public function optionalAuth($callback) {
        $payload = $this->authenticate();
        
        if($payload) {
            // Usuario autenticado
            call_user_func($callback, $payload);
        } else {
            // Usuario no autenticado, ejecutar sin payload
            call_user_func($callback, null);
        }
    }

    /**
     * Verifica si el usuario tiene permisos para acceder a un recurso específico
     * @param int $resource_user_id ID del usuario propietario del recurso
     * @param int $current_user_id ID del usuario actual
     * @return bool
     */
    public function checkPermission($resource_user_id, $current_user_id) {
        return $resource_user_id == $current_user_id;
    }

    /**
     * Middleware para verificar permisos de recurso
     * @param int $resource_user_id ID del usuario propietario del recurso
     * @param callable $callback Función a ejecutar si tiene permisos
     * @return void
     */
    public function requireResourcePermission($resource_user_id, $callback) {
        $payload = $this->authenticate();
        
        if($payload && $this->checkPermission($resource_user_id, $payload['user_id'])) {
            // Usuario autenticado y con permisos
            call_user_func($callback, $payload);
        } else {
            // Sin permisos
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso'
            ]);
            exit;
        }
    }

    /**
     * Obtiene el ID del usuario actual desde el token
     * @return int|null
     */
    public function getCurrentUserId() {
        $payload = $this->authenticate();
        return $payload ? $payload['user_id'] : null;
    }

    /**
     * Obtiene el nombre del usuario actual desde el token
     * @return string|null
     */
    public function getCurrentUserName() {
        $payload = $this->authenticate();
        return $payload ? $payload['nombre'] : null;
    }

    /**
     * Obtiene el email del usuario actual desde el token
     * @return string|null
     */
    public function getCurrentUserEmail() {
        $payload = $this->authenticate();
        return $payload ? $payload['correo'] : null;
    }
}
?>
