import { categoryService } from '../api.js';
import { escapeHtml, showToast } from '../utils.js';

export async function renderCategories(container) {
    container.innerHTML = `
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kategori</h1>
            <button id="add-category" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-medium">
                + Tambah Kategori
            </button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Deskripsi</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="category-rows">
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

        <div id="category-modal" class="hidden fixed inset-0 z-40 bg-black/40 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-md rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h2 id="category-modal-title" class="font-semibold text-gray-800">Tambah Kategori</h2>
                        <button id="close-category-modal" type="button" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>
                    <form id="category-form" class="space-y-4 px-6 py-4">
                        <input type="hidden" id="category-id">
                        <div>
                            <label for="category-nama" class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
                            <input id="category-nama" type="text" required class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="category-deskripsi" class="mb-1 block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="category-deskripsi" rows="3" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"></textarea>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button id="cancel-category" type="button" class="rounded border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit" class="rounded bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    const rows = container.querySelector('#category-rows');
    const modal = container.querySelector('#category-modal');
    const modalTitle = container.querySelector('#category-modal-title');
    const form = container.querySelector('#category-form');
    const idInput = container.querySelector('#category-id');
    const namaInput = container.querySelector('#category-nama');
    const deskripsiInput = container.querySelector('#category-deskripsi');

    let categories = [];

    function openModal(category = null) {
        idInput.value = category ? category.id : '';
        namaInput.value = category ? category.nama : '';
        deskripsiInput.value = category ? (category.deskripsi ?? '') : '';
        modalTitle.textContent = category ? 'Edit Kategori' : 'Tambah Kategori';
        modal.classList.remove('hidden');
        namaInput.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        form.reset();
        idInput.value = '';
    }

    async function load() {
        const response = await categoryService.list();
        categories = response.data ?? [];

        rows.innerHTML = categories.length
            ? categories.map((category) => `
                <tr class="border-t border-gray-100">
                    <td class="px-4 py-3 text-gray-500">${escapeHtml(category.id)}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">${escapeHtml(category.nama)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(category.deskripsi ?? '-')}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <button data-action="edit" data-id="${category.id}" class="rounded bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800 hover:bg-amber-200">Edit</button>
                        <button data-action="delete" data-id="${category.id}" class="rounded bg-red-100 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Hapus</button>
                    </td>
                </tr>
            `).join('')
            : '<tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada kategori</td></tr>';
    }

    container.querySelector('#add-category').addEventListener('click', () => openModal());
    container.querySelector('#close-category-modal').addEventListener('click', closeModal);
    container.querySelector('#cancel-category').addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = idInput.value;
        const payload = {
            nama: namaInput.value.trim(),
            deskripsi: deskripsiInput.value.trim(),
        };

        try {
            if (id) {
                await categoryService.update(id, payload);
                showToast('Kategori berhasil diperbarui');
            } else {
                await categoryService.create(payload);
                showToast('Kategori berhasil ditambahkan');
            }

            closeModal();
            await load();
        } catch (error) {
            showToast(error.message, 'error');
        }
    });

    rows.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action]');

        if (!button) {
            return;
        }

        const id = Number(button.dataset.id);
        const category = categories.find((item) => Number(item.id) === id);

        if (button.dataset.action === 'edit' && category) {
            openModal(category);
            return;
        }

        if (button.dataset.action === 'delete' && category) {
            if (!window.confirm(`Hapus kategori "${category.nama}"?`)) {
                return;
            }

            try {
                await categoryService.remove(id);
                showToast('Kategori berhasil dihapus');
                await load();
            } catch (error) {
                showToast(error.message, 'error');
            }
        }
    });

    await load();
}
