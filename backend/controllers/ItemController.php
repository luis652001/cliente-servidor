<?php
/**
 * Controlador de Items
 * Sistema Cliente-Servidor - Arquitecturas 2025
 */

require_once __DIR__ . '/../models/Item.php';

class ItemController {
    private $item;

    public function __construct($db) {
        $this->item = new Item($db);
    }

    // Crear nuevo item
    public function create($data, $user_id) {
        // Validar datos requeridos
        if(empty($data['nombre']) || empty($data['descripcion'])) {
            return [
                'success' => false,
                'message' => 'Nombre y descripción son requeridos'
            ];
        }

        // Crear item
        $this->item->nombre = $data['nombre'];
        $this->item->descripcion = $data['descripcion'];
        $this->item->estado = isset($data['estado']) ? $data['estado'] : 'activo';
        $this->item->usuario_id = $user_id;

        if($this->item->create()) {
            return [
                'success' => true,
                'message' => 'Item creado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al crear item'
            ];
        }
    }

    // Obtener todos los items de un usuario
    public function getAllByUser($user_id) {
        $stmt = $this->item->readByUser($user_id);
        $items = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'estado' => $row['estado'],
                'creado_en' => $row['creado_en']
            ];
        }

        return [
            'success' => true,
            'items' => $items,
            'total' => count($items)
        ];
    }

    // Obtener un item específico
    public function getOne($item_id, $user_id) {
        $this->item->id = $item_id;
        
        if($this->item->readOne()) {
            // Verificar que el item pertenezca al usuario
            if($this->item->belongsToUser($user_id)) {
                return [
                    'success' => true,
                    'item' => [
                        'id' => $this->item->id,
                        'nombre' => $this->item->nombre,
                        'descripcion' => $this->item->descripcion,
                        'estado' => $this->item->estado,
                        'creado_en' => $this->item->creado_en
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No tienes permisos para ver este item'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Item no encontrado'
            ];
        }
    }

    // Actualizar item
    public function update($item_id, $data, $user_id) {
        // Verificar que el item pertenezca al usuario
        $this->item->id = $item_id;
        if(!$this->item->belongsToUser($user_id)) {
            return [
                'success' => false,
                'message' => 'No tienes permisos para modificar este item'
            ];
        }

        // Validar datos requeridos
        if(empty($data['nombre']) || empty($data['descripcion'])) {
            return [
                'success' => false,
                'message' => 'Nombre y descripción son requeridos'
            ];
        }

        // Actualizar item
        $this->item->nombre = $data['nombre'];
        $this->item->descripcion = $data['descripcion'];
        $this->item->estado = isset($data['estado']) ? $data['estado'] : 'activo';
        $this->item->usuario_id = $user_id;

        if($this->item->update()) {
            return [
                'success' => true,
                'message' => 'Item actualizado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar item'
            ];
        }
    }

    // Eliminar item
    public function delete($item_id, $user_id) {
        // Verificar que el item pertenezca al usuario
        $this->item->id = $item_id;
        if(!$this->item->belongsToUser($user_id)) {
            return [
                'success' => false,
                'message' => 'No tienes permisos para eliminar este item'
            ];
        }

        if($this->item->delete()) {
            return [
                'success' => true,
                'message' => 'Item eliminado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al eliminar item'
            ];
        }
    }

    // Cambiar estado del item
    public function changeStatus($item_id, $estado, $user_id) {
        // Verificar que el item pertenezca al usuario
        $this->item->id = $item_id;
        if(!$this->item->belongsToUser($user_id)) {
            return [
                'success' => false,
                'message' => 'No tienes permisos para modificar este item'
            ];
        }

        // Validar estado
        if(!in_array($estado, ['activo', 'inactivo'])) {
            return [
                'success' => false,
                'message' => 'Estado inválido'
            ];
        }

        // Actualizar estado
        $this->item->estado = $estado;
        $this->item->usuario_id = $user_id;

        if($this->item->update()) {
            return [
                'success' => true,
                'message' => 'Estado del item actualizado exitosamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al actualizar estado del item'
            ];
        }
    }
}
?>
