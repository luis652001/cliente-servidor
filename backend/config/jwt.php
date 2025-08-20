<?php
/**
 * Configuración JWT Simplificada
 * Sistema Cliente-Servidor - Arquitecturas 2025
 */

class JWTConfig {
    private $secret_key = 'tu_clave_secreta_muy_larga_y_segura_2025';

    public function generateToken($payload) {
        // Crear un token simple basado en base64
        $data = json_encode($payload);
        $signature = hash_hmac('sha256', $data, $this->secret_key);
        
        return base64_encode($data) . '.' . $signature;
    }

    public function validateToken($token) {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 2) {
                return false;
            }

            $data = base64_decode($parts[0]);
            $signature = $parts[1];

            $expectedSignature = hash_hmac('sha256', $data, $this->secret_key);

            if ($signature !== $expectedSignature) {
                return false;
            }

            $payload = json_decode($data, true);
            return $payload;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
