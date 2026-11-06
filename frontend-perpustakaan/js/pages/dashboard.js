import { bookService, categoryService } from '../api.js';
import { escapeHtml } from '../utils.js';

function statCard(label, value, colorClass) {
    return `
        <div class="rounded-lg ${colorClass} text-white shadow p-5">
            <p class="text-sm font-medium opacity-90">${escapeHtml(label)}</p>
            <p class="mt-2 text-3xl font-bold">${escapeHtml(value)}</p>
        </div>
    `;
}

export async function renderDashboard(container) {
    const [booksResponse, categoriesResponse] = await Promise.all([
        bookService.list(),
        categoryService.list(),
    ]);

    const books = booksResponse.data ?? [];
    const categories = categoriesResponse.data ?? [];
    const totalStok = books.reduce((total, book) => total + Number(book.stok ?? 0), 0);

    const latest = [...books]
        .sort((a, b) => Number(b.id) - Number(a.id))
        .slice(0, 5);

    const rows = latest.length
        ? latest.map((book) => `
            <tr class="border-t border-gray-100">
                <td class="px-6 py-3 text-gray-500">${escapeHtml(book.id)}</td>
                <td class="px-6 py-3 font-medium text-gray-800">${escapeHtml(book.judul)}</td>
                <td class="px-6 py-3 text-gray-600">${escapeHtml(book.penulis)}</td>
                <td class="px-6 py-3 text-gray-600">${escapeHtml(book.tahun)}</td>
                <td class="px-6 py-3 text-gray-600">${escapeHtml(book.stok)}</td>
            </tr>
        `).join('')
        : '<tr><td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada buku</td></tr>';

    container.innerHTML = `
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            ${statCard('Total Buku', books.length, 'bg-blue-600')}
            ${statCard('Kategori', categories.length, 'bg-orange-500')}
            ${statCard('Total Stok', totalStok, 'bg-emerald-600')}
        </div>

        <section class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Lima Buku Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-6 py-3 font-medium">ID</th>
                            <th class="px-6 py-3 font-medium">Judul</th>
                            <th class="px-6 py-3 font-medium">Penulis</th>
                            <th class="px-6 py-3 font-medium">Tahun</th>
                            <th class="px-6 py-3 font-medium">Stok</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        </section>
    `;
}
