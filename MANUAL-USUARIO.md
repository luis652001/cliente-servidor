# 🚀 MANUAL DE USUARIO - PROYECTO CLIENTE-SERVIDOR

## 📋 ÍNDICE
1. [Descripción del Proyecto](#descripción-del-proyecto)
2. [Requisitos del Sistema](#requisitos-del-sistema)
3. [Instalación y Configuración](#instalación-y-configuración)
4. [Uso del Sistema](#uso-del-sistema)
5. [Funcionalidades](#funcionalidades)
6. [Solución de Problemas](#solución-de-problemas)
7. [Estructura del Proyecto](#estructura-del-proyecto)

---

## 🎯 DESCRIPCIÓN DEL PROYECTO

El **Proyecto Cliente-Servidor** es una aplicación web completa que implementa una arquitectura tradicional cliente-servidor con:

- **Frontend**: Interfaz web en JavaScript/HTML/CSS
- **Backend**: API REST en PHP con PDO y MySQL
- **Base de Datos**: MySQL con autenticación JWT
- **Funcionalidades**: Registro, login, gestión de items y perfiles de usuario

---

## 💻 REQUISITOS DEL SISTEMA

### Software Requerido:
- **XAMPP** (versión 8.0 o superior)
- **Apache** (puerto 80)
- **MySQL** (puerto 3306)
- **PHP** (versión 8.0 o superior)
- **Navegador web** (Chrome, Firefox, Edge)

### Hardware Mínimo:
- **RAM**: 4GB
- **Espacio en disco**: 2GB libres
- **Procesador**: Intel/AMD de 2GHz o superior

---

## ⚙️ INSTALACIÓN Y CONFIGURACIÓN

### 1. Preparar XAMPP
```
1. Descargar e instalar XAMPP desde: https://www.apachefriends.org/
2. Iniciar XAMPP Control Panel
3. Iniciar Apache (Start)
4. Iniciar MySQL (Start)
5. Verificar que ambos servicios estén en verde
```

### 2. Ubicación del Proyecto
```
El proyecto debe estar en: C:\xampp\htdocs\cliente-servidor\
```

### 3. Base de Datos
```
1. Abrir phpMyAdmin: http://localhost/phpmyadmin
2. Crear nueva base de datos: cliente_servidor_db
3. Importar el archivo: backend/database/schema.sql
4. Verificar que las tablas se crearon correctamente
```

### 4. Configuración de Archivos
```
Verificar que existan estos archivos:
✅ backend/config/database.php
✅ backend/config/jwt.php
✅ backend/api/.htaccess
✅ frontend/index.html
✅ frontend/app.js
```

---

## 🎮 USO DEL SISTEMA

### 1. Acceso al Sistema
```
URL: http://localhost/cliente-servidor/frontend/
```

### 2. Pantalla de Login
- **Email**: `demo@cliente-servidor.com`
- **Password**: `password`

### 3. Funciones Disponibles
- ✅ **Registro de usuarios**
- ✅ **Inicio de sesión**
- ✅ **Gestión de items**
- ✅ **Perfil de usuario**
- ✅ **Cerrar sesión**

---

## 🔧 FUNCIONALIDADES

### 🔐 AUTENTICACIÓN

#### Registro de Usuario
```
1. Hacer clic en "Crear Cuenta"
2. Completar formulario:
   - Nombre completo
   - Email válido
   - Password (mínimo 6 caracteres)
3. Hacer clic en "Crear Cuenta"
4. Confirmar mensaje de éxito
```

#### Inicio de Sesión
```
1. Ingresar email y password
2. Hacer clic en "Iniciar Sesión"
3. Verificar mensaje de login exitoso
4. Acceder al dashboard principal
```

### 📊 GESTIÓN DE ITEMS

#### Crear Item
```
1. En el dashboard, ir a "Gestión de Items"
2. Completar formulario:
   - Nombre del item
   - Descripción
   - Estado (activo/inactivo)
3. Hacer clic en "Crear Item"
4. Verificar que aparece en la lista
```

#### Ver Items
```
1. Los items se cargan automáticamente
2. Usar "Actualizar Lista" para refrescar
3. Cada item muestra:
   - Nombre
   - Descripción
   - Estado
   - Fecha de creación
```

#### Eliminar Item
```
1. Hacer clic en el botón "×" del item
2. Confirmar eliminación
3. El item desaparece de la lista
```

### 👤 PERFIL DE USUARIO

#### Ver Información
```
- Nombre completo
- Email
- ID de usuario
- Fecha de registro
```

#### Cerrar Sesión
```
1. Hacer clic en "Cerrar Sesión"
2. Volver a la pantalla de login
```

---

## 🚨 SOLUCIÓN DE PROBLEMAS

### Error: "Error de conexión"
```
✅ Verificar que XAMPP esté ejecutándose
✅ Verificar que Apache y MySQL estén activos
✅ Verificar URL: http://localhost/cliente-servidor/
✅ Limpiar caché del navegador (Ctrl+F5)
```

### Error: "Unexpected token '<'"
```
✅ Verificar que la API esté funcionando
✅ Probar endpoint: http://localhost/cliente-servidor/backend/api/test
✅ Verificar archivos .htaccess
✅ Revisar consola del navegador (F12)
```

### Error: "404 Not Found"
```
✅ Verificar ruta del proyecto
✅ Verificar archivo .htaccess
✅ Verificar permisos de archivos
✅ Reiniciar Apache
```

### Base de Datos no Conecta
```
✅ Verificar que MySQL esté ejecutándose
✅ Verificar credenciales en config/database.php
✅ Verificar que la base de datos existe
✅ Probar conexión en phpMyAdmin
```

---

## 📁 ESTRUCTURA DEL PROYECTO

```
cliente-servidor/
├── backend/
│   ├── api/
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   ├── register.php
│   │   │   └── profile.php
│   │   ├── items.php
│   │   ├── items-simple.php
│   │   ├── status-simple.php
│   │   ├── index.php
│   │   └── .htaccess
│   ├── config/
│   │   ├── database.php
│   │   └── jwt.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   └── ItemController.php
│   ├── models/
│   │   ├── User.php
│   │   └── Item.php
│   └── database/
│       └── schema.sql
├── frontend/
│   ├── index.html
│   └── app.js
└── MANUAL-USUARIO.md
```

---

## 🔍 COMANDOS ÚTILES

### Verificar Servicios XAMPP
```
1. Abrir XAMPP Control Panel
2. Verificar estado de Apache (puerto 80)
3. Verificar estado de MySQL (puerto 3306)
4. Verificar logs si hay errores
```

### Limpiar Caché del Navegador
```
Chrome/Firefox: Ctrl + Shift + R
Edge: Ctrl + F5
```

### Abrir Herramientas de Desarrollador
```
F12 o Ctrl + Shift + I
```

---

## 📞 SOPORTE TÉCNICO

### Archivos de Log
```
XAMPP Logs: C:\xampp\apache\logs\
PHP Errors: C:\xampp\php\logs\
```

### Verificar Errores
```
1. Consola del navegador (F12)
2. Logs de Apache
3. Logs de PHP
4. Logs de MySQL
```

---

## 🎉 ¡FELICITACIONES!

Has configurado exitosamente el **Proyecto Cliente-Servidor**. 

### Próximos Pasos:
1. ✅ **Probar todas las funcionalidades**
2. ✅ **Crear varios usuarios de prueba**
3. ✅ **Gestionar items de ejemplo**
4. ✅ **Explorar el código para aprender**

### Recursos Adicionales:
- 📖 **Documentación PHP**: https://www.php.net/
- 📖 **Documentación MySQL**: https://dev.mysql.com/doc/
- 📖 **Documentación JavaScript**: https://developer.mozilla.org/es/docs/Web/JavaScript

---

 
**👨‍💻 Desarrollado por**: Sistema de Microservicios - Arquitecturas 2025  
**📧 Contacto**: Soporte técnico del proyecto


