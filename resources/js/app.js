/**
 * MyKlinik911 — Global JavaScript
 */

// CSRF setup for all fetch requests
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.csrfToken = csrfToken.content;
}

/**
 * Show a toast notification.
 */
window.toast = function(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    const t = document.createElement('div');
    t.className = `px-4 py-3 rounded-xl shadow-lg text-sm font-medium transition-all transform translate-y-2 opacity-0 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-gray-800 text-white'
    }`;
    t.textContent = message;
    container.appendChild(t);

    requestAnimationFrame(() => {
        t.classList.remove('translate-y-2', 'opacity-0');
    });

    setTimeout(() => {
        t.classList.add('opacity-0');
        setTimeout(() => t.remove(), 300);
    }, 2500);
};

// App logic goes here
