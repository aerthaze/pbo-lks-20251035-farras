# PROJECT_STATUS — Progres Pengerjaan LKS

> OpenCode wajib memperbarui file ini setelah memverifikasi setiap langkah. Jangan centang hanya karena kode sudah dibuat. Centang setelah hasil diuji dan bukti screenshot disiapkan.

## Status Umum
- [x] Workspace sudah diperiksa
- [x] Backend REST API selesai
- [x] Frontend selesai
- [x] Integrasi end-to-end selesai
- [x] 20 langkah LKS selesai (implementasi + screenshot 1–20)
- [x] Screenshot final tersedia
- [x] Repository GitHub final di-push ke `https://github.com/aerthaze/pbo-lks-20251035-farras`

## Checklist 20 Langkah
| No. | Langkah | Status | Bukti / Catatan |
|---:|---|---|---|
| 1 | Cek Lingkungan Pengembangan | [x] Selesai | `pertemuan-01/langkah-01.png` — PHP 8.5.10, Composer 2.10.3, MySQL 8.4.3 |
| 2 | Struktur Folder & Composer PSR-4 | [x] Selesai | `pertemuan-01/langkah-02.png` — `composer dump-autoload -o` berhasil; `vendor/autoload.php` ada; PSR-4 `App\\` → `src/` |
| 3 | Konfigurasi .env & Skema Database | [x] Selesai | `pertemuan-01/langkah-03.png` — `.env`, `.env.example`, `database/schema.sql`; tabel `books` + 2 data awal |
| 4 | Env Loader & Connection PDO | [x] Selesai | `pertemuan-01/langkah-04.png` — `test_connection.php` → koneksi berhasil, MySQL 8.4.3 |
| 5 | Model, Interface & Repository Book | [x] Selesai | `pertemuan-01/langkah-05.png` — `test_repository.php` → total 2 buku |
| 6 | BookService — Logika Bisnis | [x] Selesai | `pertemuan-02/langkah-06.png` — validasi `Judul wajib diisi` kode 422; buku Pulang dibuat (ID 3) |
| 7 | Core Framework: Request, Response, Router, Container | [x] Selesai | `pertemuan-02/langkah-07.png` — `test_router.php` → router dan container bekerja |
| 8 | BookController & Front Controller | [x] Selesai | `pertemuan-02/langkah-08.png` — `GET /api/books` → HTTP 200, data dari database |
| 9 | Uji CRUD Buku dengan cURL | [x] Selesai | `pertemuan-02/langkah-09.png` — POST 201, GET 200, PUT 200, DELETE 200 |
| 10 | Uji Validasi & Error Handling | [x] Selesai | `pertemuan-03/langkah-10.png` — ID 999 → 404; judul kosong → 422; endpoint asing → 404 |
| 11 | Uji dengan Postman | [x] Selesai (cURL) | `pertemuan-03/langkah-11.png` — Postman tidak terpasang; POST buku "Bumi" diuji via cURL → 201 |
| 12 | Skrip Uji Otomatis | [x] Selesai | `pertemuan-04/langkah-12.png` — `tests/api_test.sh` → 11 assertion lulus, 0 gagal |
| 13 | CRUD Kategori — Schema & Repository | [x] Selesai | `pertemuan-05/langkah-13.png` — tabel `categories` + 2 data awal; model, interface, repository |
| 14 | CRUD Kategori — Service, Controller & Route | [x] Selesai | `pertemuan-05/langkah-14.png` — `GET /api/categories` → HTTP 200, 2 kategori |
| 15 | Uji CRUD Kategori | [x] Selesai | `pertemuan-05/langkah-15.png` — POST 201, PUT 200, DELETE 200, nama kosong 422 |
| 16 | Aktifkan CORS di Backend | [x] Selesai | `pertemuan-06/langkah-16.png` — `OPTIONS /api/books` → HTTP 204 + header CORS |
| 17 | Setup Frontend & Konfigurasi API | [x] Selesai | `pertemuan-06/langkah-17.png` — `http://localhost:3000` → HTTP 200, `config.js` menunjuk API |
| 18 | Halaman Dashboard & Buku | [x] Selesai | `pertemuan-06/langkah-18.png` — dashboard 2 buku/2 kategori + tabel Buku dengan aksi Edit/Hapus |
| 19 | Halaman Kategori & Integrasi Penuh | [x] Selesai | `pertemuan-07/langkah-19.png` — tabel Kategori dengan aksi Edit/Hapus, data dari backend |
| 20 | Finalisasi & Push Repository | [x] Selesai | `pertemuan-07/langkah-20.png` — `git push -u origin main` + tag `v1.0-lks-final` terkirim ke GitHub |

## Screenshot Final
- [x] `screenshot-final/dashboard.png`
- [x] `screenshot-final/halaman-buku.png`
- [x] `screenshot-final/halaman-kategori.png`

## Environment
Isi setelah pemeriksaan, jangan menebak:
- OS: Windows NT 10.0.26300.0 (Windows 11)
- PHP: 8.5.10 (CLI, `C:\php-8.5.10\php.exe`; `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json` tersedia)
- Composer: 2.10.3
- MySQL/MariaDB: MySQL 8.4.3 (Laragon, `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe`; server berjalan di port 3306; `mysql` belum masuk PATH permanen)
- cURL: 8.21.0 (`curl.exe`)
- jq: 1.8.2 (terpasang via WinGet; salinan portable di `rest-api-perpustakaan/tools/jq.exe`)
- Postman/Insomnia: tidak terdeteksi (langkah 11 diuji dengan cURL)
- Browser: Google Chrome (`C:\Program Files\Google\Chrome\Application\chrome.exe`)

## Langkah Aktif
- Nomor langkah: 20 (selesai)
- Nama langkah: Finalisasi & Push Repository
- Tujuan saat ini: Tidak ada; seluruh 20 langkah selesai dan repository sudah di GitHub.
- File yang akan dibuat/diubah: tidak ada.
- Cara verifikasi: `git remote -v`, `git ls-remote --heads origin`, `git ls-remote --tags origin`.
- Hasil verifikasi: `main` dan tag `v1.0-lks-final` ada di remote; seluruh screenshot 1–20 dan tiga screenshot final tersedia.
- Screenshot: 20 file `pertemuan-XX/langkah-YY.png` + 3 file `screenshot-final/*.png`.
- Kendala: identitas README masih placeholder (`[ISI NAMA]`, `[ISI NIM]`, `[ISI KELAS]`).

## Log Perubahan
| Tanggal | Langkah | Perubahan | Pengujian | Status |
|---|---|---|---|---|
| 2026-11-06 | 1 | Menyalin dokumen referensi, membuat folder pertemuan, memeriksa lingkungan | PHP 8.5.10; Composer 2.10.3; MySQL 8.4.3 | Selesai — screenshot tersedia |
| 2026-11-06 | 2 | Membuat struktur `rest-api-perpustakaan/` dan `composer.json` PSR-4 | `composer dump-autoload -o`; `vendor/autoload.php` ada | Selesai — screenshot tersedia |
| 2026-11-06 | 3 | Membuat `.env`, `.env.example`, `database/schema.sql`; membuat database dan tabel `books` | MySQL: 2 baris awal tampil | Selesai — screenshot tersedia |
| 2026-11-06 | 4 | Membuat `Env` dan `Connection` PDO Singleton | `php test_connection.php` → koneksi berhasil, MySQL 8.4.3 | Selesai — screenshot tersedia |
| 2026-11-06 | 5 | Membuat `Book`, `BookRepositoryInterface`, `MySQLBookRepository` | `php test_repository.php` → total 2 buku | Selesai — screenshot tersedia |
| 2026-11-06 | 6 | Membuat `BookService` dengan validasi 422 dan 404 | `php test_service.php` → 2 buku, validasi 422, Pulang ID 3 | Selesai — screenshot tersedia |
| 2026-11-06 | 7 | Membuat `Request`, `Response`, `Router`, `Container` | `php test_router.php` → router + container bekerja | Selesai — screenshot tersedia |
| 2026-11-06 | 8 | Membuat `BookController` dan `public/index.php` | `GET /api/books` → HTTP 200 | Selesai — screenshot tersedia |
| 2026-11-06 | 9 | Menguji CRUD Buku via cURL | POST 201, GET 200, PUT 200, DELETE 200 | Selesai — screenshot tersedia |
| 2026-11-06 | 10 | Menguji validasi dan error handling | 404, 422, 404 dengan pesan Indonesia | Selesai — screenshot tersedia |
| 2026-11-06 | 11 | Menguji POST buku "Bumi" via cURL (pengganti Postman) | POST → HTTP 201 | Selesai — screenshot cURL tersedia |
| 2026-11-06 | 12 | Membuat `tests/api_test.sh` + jq portable | 11 assertion lulus, 0 gagal | Selesai — screenshot tersedia |
| 2026-11-06 | 13 | Menambah tabel `categories`, model, interface, repository | 2 kategori awal tampil | Selesai — screenshot tersedia |
| 2026-11-06 | 14 | Membuat `CategoryService`, `CategoryController`, binding, route | `GET /api/categories` → HTTP 200 | Selesai — screenshot tersedia |
| 2026-11-06 | 15 | Menguji CRUD Kategori | POST 201, PUT 200, DELETE 200, validasi 422 | Selesai — screenshot tersedia |
| 2026-11-06 | 16 | Menambahkan header CORS dan preflight OPTIONS | `OPTIONS /api/books` → HTTP 204 + header CORS | Selesai — screenshot tersedia |
| 2026-11-06 | 17 | Membuat frontend Tailwind + `config.js`, `api.js`, `utils.js` | Halaman `localhost:3000` → HTTP 200 | Selesai — screenshot tersedia |
| 2026-11-06 | 18 | Membuat dashboard, halaman Buku, modal CRUD, router hash | Dashboard + tabel Buku tampil dengan data backend | Selesai — screenshot tersedia |
| 2026-11-06 | 19 | Membuat halaman Kategori + integrasi penuh | Tabel Kategori tampil dengan data backend | Selesai — screenshot tersedia |
| 2026-11-15 | 20 | Mengambil seluruh screenshot bukti, merapikan README/status, commit, tag, push ke GitHub | 20 screenshot langkah + 3 screenshot final; `git push -u origin main`; tag `v1.0-lks-final` | Selesai — repository publik tersedia |

## Aturan Pembaruan
1. Perbarui status hanya berdasarkan hasil aktual.
2. Catat command/test yang dijalankan dan hasilnya secara ringkas.
3. Jika gagal, tulis error dan langkah diagnosis. Jangan tandai selesai.
4. Screenshot hanya dianggap tersedia jika file benar-benar ada di workspace.
5. Jangan mengubah status langkah lain yang belum diverifikasi.
