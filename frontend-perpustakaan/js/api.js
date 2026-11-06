import { API_BASE_URL } from './config.js';

class ApiClient {
    async request(path, options = {}) {
        const response = await fetch(`${API_BASE_URL}${path}`, {
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            ...options,
        });

        let payload;

        try {
            payload = await response.json();
        } catch {
            payload = { success: false, message: `Response bukan JSON (HTTP ${response.status})` };
        }

        if (!response.ok) {
            throw new Error(payload.message || `Permintaan gagal (HTTP ${response.status})`);
        }

        return payload;
    }

    get(path) {
        return this.request(path);
    }

    post(path, body) {
        return this.request(path, {
            method: 'POST',
            body: JSON.stringify(body),
        });
    }

    put(path, body) {
        return this.request(path, {
            method: 'PUT',
            body: JSON.stringify(body),
        });
    }

    delete(path) {
        return this.request(path, { method: 'DELETE' });
    }
}

export const api = new ApiClient();

export const bookService = {
    list: () => api.get('/books'),
    get: (id) => api.get(`/books/${id}`),
    create: (data) => api.post('/books', data),
    update: (id, data) => api.put(`/books/${id}`, data),
    remove: (id) => api.delete(`/books/${id}`),
};

export const categoryService = {
    list: () => api.get('/categories'),
    get: (id) => api.get(`/categories/${id}`),
    create: (data) => api.post('/categories', data),
    update: (id, data) => api.put(`/categories/${id}`, data),
    remove: (id) => api.delete(`/categories/${id}`),
};
