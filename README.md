# 🖥️ PRODUCTO 1: Arquitectura Cliente-Servidor

## 📋 Descripción
Sistema web completo implementado con arquitectura cliente-servidor tradicional, donde un servidor PHP centralizado maneja toda la lógica de negocio y persistencia de datos.

## 🏗️ Arquitectura del Sistema

### Diagrama de Arquitectura
```
┌─────────────────┐    HTTP/JSON    ┌─────────────────┐    SQL    ┌─────────────────┐
│   Cliente Web   │ ──────────────→ │  Servidor PHP   │ ────────→ │   MySQL BD      │
│  (HTML/CSS/JS)  │ ←────────────── │   (Backend)     │ ←──────── │                 │
└─────────────────┘                 └─────────────────┘           └─────────────────┘
```

### Componentes Principales
- **Cliente**: Interfaz web responsiva con HTML5, CSS3 y JavaScript
- **Servidor**: API REST en PHP con PDO para acceso a base de datos
- **Base de Datos**: MySQL con tablas para usuarios y elementos del sistema
- **Autenticación**: Sistema JWT para manejo de sesiones seguras

## 🛠️ Tecnologías Utilizadas
- **Backend**: PHP 8.0+ con PDO
- **Base de Datos**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Autenticación**: JWT (JSON Web Tokens)
- **Servidor Web**: Apache/Nginx
- **API**: REST con respuestas JSON

## 📁 Estructura de Archivos
```
producto-1-cliente-servidor/
├── 📁 backend/
│   ├── 📄 config/
│   │   ├── database.php          # Configuración de BD
│   │   └── jwt.php              # Configuración JWT
│   ├── 📄 controllers/
│   │   ├── AuthController.php    # Controlador de autenticación
│   │   └── ItemController.php    # Controlador de elementos
│   ├── 📄 models/
│   │   ├── User.php             # Modelo de usuario
│   │   └── Item.php             # Modelo de elemento
│   ├── 📄 middleware/
│   │   └── AuthMiddleware.php    # Middleware de autenticación
│   ├── 📄 api/
│   │   ├── auth.php             # Endpoints de autenticación
│   │   └── items.php            # Endpoints de elementos
│   └── 📄 index.php             # Punto de entrada principal
├── 📁 frontend/
│   ├── 📄 index.html            # Página principal
│   ├── 📄 css/
│   │   └── styles.css           # Estilos principales
│   └── 📄 js/
│       ├── auth.js              # Lógica de autenticación
│       └── items.js             # Lógica de elementos
├── 📁 database/
│   ├── 📄 schema.sql            # Esquema de base de datos
│   └── 📄 sample-data.sql       # Datos de ejemplo
├── 📄 .env.example              # Variables de entorno de ejemplo
├── 📄 .htaccess                 # Configuración Apache
└── 📖 README.md                 # Este archivo
```

## 🚀 Instalación y Configuración

### 1. Requisitos Previos
- PHP 8.0 o superior
- MySQL 8.0 o superior
- Servidor web (Apache/Nginx)
- Extensión PHP PDO MySQL habilitada

### 2. Configuración de Base de Datos
```sql
-- Crear base de datos
CREATE DATABASE cliente_servidor_db;
USE cliente_servidor_db;

-- Ejecutar el archivo database/schema.sql
```

### 3. Configuración del Proyecto
1. Copiar `.env.example` a `.env`
2. Configurar variables de base de datos:
   ```env
   DB_HOST=localhost
   DB_NAME=cliente_servidor_db
   DB_USER=tu_usuario
   DB_PASS=tu_password
   JWT_SECRET=tu_clave_secreta_muy_larga
   ```

### 4. Instalación
1. Colocar archivos en directorio del servidor web
2. Configurar permisos de escritura en carpetas necesarias
3. Acceder a `http://localhost/tu-proyecto/`

## 🔌 Endpoints de la API

### Autenticación
- `POST /api/auth/register` - Registro de usuario
- `POST /api/auth/login` - Inicio de sesión
- `POST /api/auth/logout` - Cierre de sesión

### Elementos (requieren autenticación)
- `GET /api/items` - Listar elementos
- `POST /api/items` - Crear elemento
- `PUT /api/items/{id}` - Actualizar elemento
- `DELETE /api/items/{id}` - Eliminar elemento

## 📊 Esquema de Base de Datos

### Tabla: usuarios
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabla: items
```sql
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    usuario_id INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
```

## 🧪 Pruebas del Sistema

### Flujo de Pruebas Recomendado
1. **Registro de Usuario**: Crear cuenta nueva
2. **Inicio de Sesión**: Autenticarse y obtener token JWT
3. **Crear Elemento**: Usar token para crear nuevo elemento
4. **Listar Elementos**: Obtener lista de elementos del usuario
5. **Actualizar Elemento**: Modificar elemento existente
6. **Eliminar Elemento**: Borrar elemento del sistema

### Herramientas de Prueba
- **Postman**: Para probar endpoints de la API
- **Navegador Web**: Para probar la interfaz de usuario
- **Consola del Navegador**: Para ver logs y errores

## 🔒 Seguridad Implementada
- **Hash de Contraseñas**: Bcrypt para encriptación segura
- **JWT**: Tokens de autenticación stateless
- **Validación de Entrada**: Sanitización de datos de entrada
- **CORS**: Configuración de políticas de origen cruzado
- **SQL Injection**: Prevención mediante PDO preparado

## 📱 Características del Frontend
- **Responsive Design**: Adaptable a diferentes dispositivos
- **Validación en Tiempo Real**: Feedback inmediato al usuario
- **Manejo de Estados**: Indicadores de carga y errores
- **Persistencia Local**: Almacenamiento de token en localStorage
- **Navegación Intuitiva**: Interfaz clara y fácil de usar

## 🚨 Solución de Problemas

### Error de Conexión a Base de Datos
- Verificar credenciales en archivo `.env`
- Confirmar que MySQL esté ejecutándose
- Verificar permisos del usuario de base de datos

### Error de Autenticación JWT
- Verificar que `JWT_SECRET` esté configurado
- Confirmar que el token no haya expirado
- Verificar formato del token en headers

### Problemas de Permisos
- Verificar permisos de escritura en carpetas de logs
- Confirmar permisos del servidor web en archivos

## 📈 Próximas Mejoras
- [ ] Implementar refresh tokens
- [ ] Agregar validación de email
- [ ] Implementar recuperación de contraseña
- [ ] Agregar paginación en listados
- [ ] Implementar búsqueda y filtros
- [ ] Agregar logs de auditoría

---

**Desarrollado por**: Equipo de Arquitecturas 2025  
**Versión**: 1.0.0  
**Última actualización**: 2025
