import { renderDashboard } from './pages/dashboard.js';
import { renderBooks } from './pages/books.js';
import { renderCategories } from './pages/categories.js';
import { escapeHtml, showToast } from './utils.js';

const routes = {
    '/dashboard': renderDashboard,
    '/books': renderBooks,
    '/categories': renderCategories,
};

function currentRoute() {
    const hash = window.location.hash.replace(/^#/, '');
    return routes[hash] ? hash : '/dashboard';
}

function updateNavigation(route) {
    document.querySelectorAll('[data-nav]').forEach((link) => {
        const target = link.getAttribute('href');
        link.classList.toggle('active', target === `#${route}`);
    });
}

async function router() {
    const route = currentRoute();
    const app = document.getElementById('app');

    updateNavigation(route);
    app.innerHTML = '<div class="text-gray-500">Memuat...</div>';

    try {
        await routes[route](app);
    } catch (error) {
        app.innerHTML = `
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                ${escapeHtml(error.message)}
            </div>
        `;
        showToast(error.message, 'error');
    }
}

window.addEventListener('hashchange', router);
window.addEventListener('DOMContentLoaded', router);
router();
