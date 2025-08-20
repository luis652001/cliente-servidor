<?php
/**
 * Controlador de Autenticación
 * Sistema Cliente-Servidor - Arquitecturas 2025
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/jwt.php';

class AuthController {
    private $user;
    private $jwt;

    public function __construct($db) {
        $this->user = new User($db);
        $this->jwt = new JWTConfig();
    }

    // Registrar usuario
    public function register($data) {
        // Validar datos requeridos
        if(empty($data['nombre']) || empty($data['correo']) || empty($data['password'])) {
            return [
                'success' => false,
                'message' => 'Todos los campos son requeridos'
            ];
        }

        // Validar formato de email
        if(!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Formato de email inválido'
            ];
        }

        // Verificar si el email ya existe
        $this->user->correo = $data['correo'];
        if($this->user->emailExists()) {
            return [
                'success' => false,
                'message' => 'El email ya está registrado'
            ];
        }

        // Hash de la contraseña
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

        // Crear usuario
        $this->user->nombre = $data['nombre'];
        $this->user->correo = $data['correo'];
        $this->user->password_hash = $password_hash;

        if($this->user->create()) {
            return [
                'success' => true,
                'message' => 'Usuario registrado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al registrar usuario'
            ];
        }
    }

    // Login de usuario
    public function login($data) {
        // Validar datos requeridos
        if(empty($data['correo']) || empty($data['password'])) {
            return [
                'success' => false,
                'message' => 'Email y contraseña son requeridos'
            ];
        }

        // Verificar si el usuario existe
        $this->user->correo = $data['correo'];
        if(!$this->user->emailExists()) {
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }

        // Verificar contraseña
        if(!password_verify($data['password'], $this->user->password_hash)) {
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }

        // Generar token JWT
        $payload = [
            'user_id' => $this->user->id,
            'nombre' => $this->user->nombre,
            'correo' => $this->user->correo
        ];

        $token = $this->jwt->generateToken($payload);

        return [
            'success' => true,
            'message' => 'Login exitoso',
            'token' => $token,
            'user' => [
                'id' => $this->user->id,
                'nombre' => $this->user->nombre,
                'correo' => $this->user->correo
            ]
        ];
    }

    // Obtener perfil del usuario
    public function getProfile($user_id) {
        $this->user->id = $user_id;
        
        if($this->user->readOne()) {
            return [
                'success' => true,
                'user' => [
                    'id' => $this->user->id,
                    'nombre' => $this->user->nombre,
                    'correo' => $this->user->correo,
                    'creado_en' => $this->user->creado_en
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }
    }

    // Validar token JWT
    public function validateToken($token) {
        if(empty($token)) {
            return [
                'success' => false,
                'message' => 'Token no proporcionado'
            ];
        }

        $payload = $this->jwt->validateToken($token);
        
        if($payload) {
            return [
                'success' => true,
                'payload' => $payload
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Token inválido o expirado'
            ];
        }
    }
}
?>
