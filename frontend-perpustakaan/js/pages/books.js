import { bookService } from '../api.js';
import { escapeHtml, showToast } from '../utils.js';

export async function renderBooks(container) {
    container.innerHTML = `
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buku</h1>
            <button id="add-book" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm font-medium">
                + Tambah Buku
            </button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Judul</th>
                        <th class="px-4 py-3 font-medium">Penulis</th>
                        <th class="px-4 py-3 font-medium">Tahun</th>
                        <th class="px-4 py-3 font-medium">Stok</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="book-rows">
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

        <div id="book-modal" class="hidden fixed inset-0 z-40 bg-black/40 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-md rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h2 id="book-modal-title" class="font-semibold text-gray-800">Tambah Buku</h2>
                        <button id="close-book-modal" type="button" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>
                    <form id="book-form" class="space-y-4 px-6 py-4">
                        <input type="hidden" id="book-id">
                        <div>
                            <label for="book-judul" class="mb-1 block text-sm font-medium text-gray-700">Judul</label>
                            <input id="book-judul" type="text" required class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="book-penulis" class="mb-1 block text-sm font-medium text-gray-700">Penulis</label>
                            <input id="book-penulis" type="text" required class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="book-tahun" class="mb-1 block text-sm font-medium text-gray-700">Tahun</label>
                                <input id="book-tahun" type="number" min="1" required class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="book-stok" class="mb-1 block text-sm font-medium text-gray-700">Stok</label>
                                <input id="book-stok" type="number" min="0" required class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button id="cancel-book" type="button" class="rounded border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit" class="rounded bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    const rows = container.querySelector('#book-rows');
    const modal = container.querySelector('#book-modal');
    const modalTitle = container.querySelector('#book-modal-title');
    const form = container.querySelector('#book-form');
    const idInput = container.querySelector('#book-id');
    const judulInput = container.querySelector('#book-judul');
    const penulisInput = container.querySelector('#book-penulis');
    const tahunInput = container.querySelector('#book-tahun');
    const stokInput = container.querySelector('#book-stok');

    let books = [];

    function openModal(book = null) {
        idInput.value = book ? book.id : '';
        judulInput.value = book ? book.judul : '';
        penulisInput.value = book ? book.penulis : '';
        tahunInput.value = book ? book.tahun : '';
        stokInput.value = book ? book.stok : '';
        modalTitle.textContent = book ? 'Edit Buku' : 'Tambah Buku';
        modal.classList.remove('hidden');
        judulInput.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        form.reset();
        idInput.value = '';
    }

    async function load() {
        const response = await bookService.list();
        books = response.data ?? [];

        rows.innerHTML = books.length
            ? books.map((book) => `
                <tr class="border-t border-gray-100">
                    <td class="px-4 py-3 text-gray-500">${escapeHtml(book.id)}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">${escapeHtml(book.judul)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(book.penulis)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(book.tahun)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(book.stok)}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <button data-action="edit" data-id="${book.id}" class="rounded bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800 hover:bg-amber-200">Edit</button>
                        <button data-action="delete" data-id="${book.id}" class="rounded bg-red-100 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-200">Hapus</button>
                    </td>
                </tr>
            `).join('')
            : '<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada buku</td></tr>';
    }

    container.querySelector('#add-book').addEventListener('click', () => openModal());
    container.querySelector('#close-book-modal').addEventListener('click', closeModal);
    container.querySelector('#cancel-book').addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = idInput.value;
        const payload = {
            judul: judulInput.value.trim(),
            penulis: penulisInput.value.trim(),
            tahun: Number(tahunInput.value),
            stok: Number(stokInput.value),
        };

        try {
            if (id) {
                await bookService.update(id, payload);
                showToast('Buku berhasil diperbarui');
            } else {
                await bookService.create(payload);
                showToast('Buku berhasil ditambahkan');
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
        const book = books.find((item) => Number(item.id) === id);

        if (button.dataset.action === 'edit' && book) {
            openModal(book);
            return;
        }

        if (button.dataset.action === 'delete' && book) {
            if (!window.confirm(`Hapus buku "${book.judul}"?`)) {
                return;
            }

            try {
                await bookService.remove(id);
                showToast('Buku berhasil dihapus');
                await load();
            } catch (error) {
                showToast(error.message, 'error');
            }
        }
    });

    await load();
}
