/**
 * Aplicación JavaScript - Sistema Cliente-Servidor
 * Arquitecturas 2025
 */

// Configuración de la API
const API_BASE_URL = 'http://localhost/cliente-servidor/backend/api';
const API_ENDPOINTS = {
    test: '/test-endpoint.php',
    register: '/auth/register.php',
    login: '/auth/login.php',
    profile: '/auth/profile.php',
    items: '/items-simple.php'  // Temporalmente usando el endpoint simple
};

// Estado global de la aplicación
let currentUser = null;
let authToken = null;

// Elementos del DOM
const authSection = document.getElementById('auth-section');
const mainPanel = document.getElementById('main-panel');
const itemFormContainer = document.getElementById('item-form-container');
const itemsList = document.getElementById('items-list');
const notifications = document.getElementById('notifications');

// Inicialización de la aplicación
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay un token guardado
    const savedToken = localStorage.getItem('authToken');
    if (savedToken) {
        authToken = savedToken;
        // Verificar si el token sigue siendo válido
        validateTokenAndLoadUser();
    }
});

// ===== FUNCIONES DE AUTENTICACIÓN =====

/**
 * Muestra la pestaña especificada
 */
function showTab(tabName) {
    // Ocultar todas las pestañas
    document.querySelectorAll('.auth-form').forEach(form => {
        form.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar la pestaña seleccionada
    document.getElementById(tabName + '-form').classList.add('active');
    event.target.classList.add('active');
}

/**
 * Maneja el registro de usuarios
 */
async function handleRegister(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        nombre: formData.get('name'),
        correo: formData.get('email'),
        password: formData.get('password')
    };
    
    try {
        const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.register}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Usuario registrado exitosamente', 'success');
            // Cambiar a la pestaña de login
            showTab('login');
            event.target.reset();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Error de conexión', 'error');
        console.error('Error:', error);
    }
}

/**
 * Maneja el login de usuarios
 */
async function handleLogin(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        correo: formData.get('email'),
        password: formData.get('password')
    };
    
    try {
        const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.login}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Guardar token y datos del usuario
            authToken = result.token;
            currentUser = result.user;
            localStorage.setItem('authToken', authToken);
            localStorage.setItem('userData', JSON.stringify(currentUser));
            
            showNotification('Login exitoso', 'success');
            
            // Cambiar a la vista principal
            showMainPanel();
            loadUserItems();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Error de conexión', 'error');
        console.error('Error:', error);
    }
}

/**
 * Valida el token y carga la información del usuario
 */
async function validateTokenAndLoadUser() {
    try {
        const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.profile}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                currentUser = result.user;
                showMainPanel();
                loadUserItems();
            } else {
                // Token inválido, limpiar
                logout();
            }
        } else {
            logout();
        }
    } catch (error) {
        logout();
    }
}

/**
 * Cierra la sesión del usuario
 */
function logout() {
    authToken = null;
    currentUser = null;
    localStorage.removeItem('authToken');
    localStorage.removeItem('userData');
    
    hideMainPanel();
    showNotification('Sesión cerrada', 'info');
}

// ===== FUNCIONES DE INTERFAZ =====

/**
 * Muestra el panel principal
 */
function showMainPanel() {
    authSection.style.display = 'none';
    mainPanel.classList.remove('hidden');
    
    // Actualizar nombre del usuario
    document.getElementById('user-name').textContent = currentUser.nombre;
}

/**
 * Oculta el panel principal
 */
function hideMainPanel() {
    mainPanel.classList.add('hidden');
    authSection.style.display = 'block';
}

/**
 * Muestra el formulario para crear items
 */
function showCreateItemForm() {
    itemFormContainer.classList.remove('hidden');
    document.getElementById('item-form-title').textContent = 'Nuevo Item';
    document.getElementById('item-form').reset();
    document.getElementById('item-id').value = '';
}

/**
 * Oculta el formulario de items
 */
function hideItemForm() {
    itemFormContainer.classList.add('hidden');
}

/**
 * Muestra el formulario para editar items
 */
function showEditItemForm(item) {
    itemFormContainer.classList.remove('hidden');
    document.getElementById('item-form-title').textContent = 'Editar Item';
    document.getElementById('item-id').value = item.id;
    document.getElementById('item-name').value = item.nombre;
    document.getElementById('item-description').value = item.descripcion;
    document.getElementById('item-status').value = item.estado;
}

// ===== FUNCIONES DE ITEMS =====

/**
 * Carga los items del usuario
 */
async function loadUserItems() {
    try {
        const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.items}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            displayItems(result.items);
        } else {
            showNotification('Error al cargar items', 'error');
        }
    } catch (error) {
        showNotification('Error de conexión', 'error');
        console.error('Error:', error);
    }
}

/**
 * Muestra los items en la interfaz
 */
function displayItems(items) {
    if (items.length === 0) {
        itemsList.innerHTML = `
            <div class="text-center mt-20">
                <p>No tienes items aún. ¡Crea tu primer item!</p>
            </div>
        `;
        return;
    }
    
    itemsList.innerHTML = items.map(item => `
        <div class="item-card" data-id="${item.id}">
            <div class="item-header">
                <h3 class="item-title">${item.nombre}</h3>
                <span class="item-status ${item.estado}">${item.estado}</span>
            </div>
            <p class="item-description">${item.descripcion}</p>
            <div class="item-meta">
                <span>Creado: ${formatDate(item.creado_en)}</span>
                <div class="item-actions">
                    <button class="btn btn-secondary" onclick="showEditItemForm(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn btn-danger" onclick="deleteItem(${item.id})">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    <button class="btn btn-outline" onclick="toggleItemStatus(${item.id}, '${item.estado === 'activo' ? 'inactivo' : 'activo'}')">
                        ${item.estado === 'activo' ? 'Desactivar' : 'Activar'}
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

/**
 * Maneja la creación/edición de items
 */
async function handleItemSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const itemId = formData.get('id');
    const data = {
        nombre: formData.get('name'),
        descripcion: formData.get('description'),
        estado: formData.get('status')
    };
    
    try {
        const url = itemId ? 
            `${API_BASE_URL}${API_ENDPOINTS.items}?id=${itemId}` : 
            `${API_BASE_URL}${API_ENDPOINTS.items}`;
        
        const method = itemId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${authToken}`
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            hideItemForm();
            loadUserItems();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Error de conexión', 'error');
        console.error('Error:', error);
    }
}

/**
 * Elimina un item
 */
async function deleteItem(itemId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este item?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.items}?id=${itemId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            loadUserItems();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Error de conexión', 'error');
        console.error('Error:', error);
    }
}

/**
 * Cambia el estado de un item
 */
async function toggleItemStatus(itemId, newStatus) {
    try {
        const response = await fetch(`${API_BASE_URL}/status-simple.php?item_id=${itemId}&status=${newStatus}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            loadUserItems();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Error de conexión', 'error');
        console.error('Error:', error);
    }
}

// ===== FUNCIONES UTILITARIAS =====

/**
 * Muestra una notificación
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    notifications.appendChild(notification);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

/**
 * Formatea una fecha
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Función para probar la API
 */
async function testAPI() {
    try {
        const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.test}`);
        const result = await response.json();
        console.log('API Test:', result);
    } catch (error) {
        console.error('API Test Error:', error);
    }
}

// Ejecutar test de API al cargar
testAPI();
