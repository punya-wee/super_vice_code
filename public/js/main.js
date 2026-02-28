/**
 * Main JavaScript for Agri Management System
 */

// Check if user is logged in
function checkAuth() {
    // Check localStorage or session for auth token
    const token = localStorage.getItem('authToken');
    return !!token;
}

// Login function
function login(email, password) {
    return fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('authToken', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            return true;
        }
        return false;
    })
    .catch(error => {
        console.error('Login error:', error);
        return false;
    });
}

// Logout function
function logout() {
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
    window.location.href = '/login';
}

// Get current user
function getCurrentUser() {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
}

// Create notification/toast
function showNotification(message, type = 'success', duration = 3000) {
    const container = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icons = {
        success: '✓',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    
    toast.innerHTML = `
        <span class="toast-icon">${icons[type] || icons.info}</span>
        <span class="toast-message">${message}</span>
        <button class="toast-close" aria-label="Close notification">×</button>
    `;
    
    toast.querySelector('.toast-close').addEventListener('click', () => {
        toast.remove();
    });
    
    container.appendChild(toast);
    
    if (duration > 0) {
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, duration);
    }
    
    return toast;
}

// Create toast container if it doesn't exist
function createToastContainer() {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        document.body.appendChild(container);
    }
    return container;
}

// API call helper
async function apiCall(endpoint, options = {}) {
    const defaults = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    };
    
    const config = { ...defaults, ...options };
    
    try {
        const response = await fetch(endpoint, config);
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Format date
function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('th-TH', options);
}

// Format currency (Thai Baht)
function formatCurrency(amount) {
    return new Intl.NumberFormat('th-TH', {
        style: 'currency',
        currency: 'THB'
    }).format(amount);
}

// Validate email
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Debounce function
function debounce(func, delay = 300) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

// Loading spinner
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'loading-spinner';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    loader.innerHTML = `
        <div style="color: white; font-size: 24px;">⏳ กำลังโหลด...</div>
    `;
    document.body.appendChild(loader);
    return loader;
}

function hideLoading() {
    const loader = document.getElementById('loading-spinner');
    if (loader) {
        loader.remove();
    }
}

// Confirm dialog
function confirmAction(message) {
    return new Promise((resolve) => {
        if (confirm(message)) {
            resolve(true);
        } else {
            resolve(false);
        }
    });
}

// Export functions for use in other files
window.agriApp = {
    show: showNotification,
    api: apiCall,
    logout: logout,
    getCurrentUser: getCurrentUser,
    checkAuth: checkAuth,
    formatDate: formatDate,
    formatCurrency: formatCurrency,
    validateEmail: validateEmail,
    showLoading: showLoading,
    hideLoading: hideLoading,
    confirmAction: confirmAction
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to all fetch requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) {
        // Token will be added in apiCall function
    }
    
    // Check authentication on protected pages
    const protectedPages = ['/dashboard', '/hub', '/workspace'];
    if (protectedPages.some(page => window.location.pathname.includes(page))) {
        if (!checkAuth()) {
            window.location.href = '/login';
        }
    }
});
