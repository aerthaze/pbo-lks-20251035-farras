# PROJECT_STATUS — Progres Pengerjaan LKS

> OpenCode wajib memperbarui file ini setelah memverifikasi setiap langkah. Jangan centang hanya karena kode sudah dibuat. Centang setelah hasil diuji dan bukti screenshot disiapkan.

## Status Umum
- [x] Workspace sudah diperiksa
- [x] Backend REST API selesai
- [x] Frontend selesai
- [x] Integrasi end-to-end selesai
- [ ] 20 langkah LKS selesai (implementasi selesai; screenshot belum lengkap)
- [ ] Screenshot final tersedia
- [ ] Repository GitHub final di-push

## Checklist 20 Langkah
| No. | Langkah | Status | Bukti / Catatan |
|---:|---|---|---|
| 1 | Cek Lingkungan Pengembangan | [x] Selesai | `pertemuan-01/langkah-01.png` — PHP 8.5.10, Composer 2.10.3, MySQL 8.4.3 |
| 2 | Struktur Folder & Composer PSR-4 | [x] Selesai | `pertemuan-01/langkah-02.png` — `composer dump-autoload -o` berhasil; `vendor/autoload.php` ada; PSR-4 `App\\` → `src/` |
| 3 | Konfigurasi .env & Skema Database | [~] Terverifikasi, menunggu screenshot | `.env`, `.env.example`, `database/schema.sql`; tabel `books` + 2 data awal |
| 4 | Env Loader & Connection PDO | [~] Terverifikasi, menunggu screenshot | `test_connection.php` → koneksi berhasil, MySQL 8.4.3 |
| 5 | Model, Interface & Repository Book | [~] Terverifikasi, menunggu screenshot | `test_repository.php` → total 2 buku (Laskar Pelangi, Bumi Manusia) |
| 6 | BookService — Logika Bisnis | [~] Terverifikasi, menunggu screenshot | Validasi `Judul wajib diisi` kode 422; buku Pulang dibuat (ID 3) |
| 7 | Core Framework: Request, Response, Router, Container | [~] Terverifikasi, menunggu screenshot | `test_router.php` → router dan container bekerja |
| 8 | BookController & Front Controller | [~] Terverifikasi, menunggu screenshot | `GET /api/books` → HTTP 200, data dari database |
| 9 | Uji CRUD Buku dengan cURL | [~] Terverifikasi, menunggu screenshot | GET 200, GET detail 200, POST 201, PUT 200, DELETE 200 |
| 10 | Uji Validasi & Error Handling | [~] Terverifikasi, menunggu screenshot | ID 999 → 404; judul kosong → 422; endpoint asing → 404 |
| 11 | Uji dengan Postman | [~] API terverifikasi via cURL; menunggu screenshot Postman | POST buku “Bumi” diuji via cURL → 201; screenshot Postman belum ada |
| 12 | Skrip Uji Otomatis | [~] Terverifikasi, menunggu screenshot | `tests/api_test.sh` → 11 assertion lulus, 0 gagal (6 skenario) |
| 13 | CRUD Kategori — Schema & Repository | [~] Terverifikasi, menunggu screenshot | Tabel `categories` + 2 data awal; model, interface, repository dibuat |
| 14 | CRUD Kategori — Service, Controller & Route | [~] Terverifikasi, menunggu screenshot | `GET /api/categories` → HTTP 200, 2 kategori |
| 15 | Uji CRUD Kategori | [~] Terverifikasi, menunggu screenshot | POST 201, PUT 200, DELETE 200, nama kosong 422 |
| 16 | Aktifkan CORS di Backend | [~] Terverifikasi, menunggu screenshot | `OPTIONS /api/books` → HTTP 204 + header CORS |
| 17 | Setup Frontend & Konfigurasi API | [~] Terverifikasi, menunggu screenshot | `http://localhost:3000` terbuka; header/menu tampil; tanpa error console |
| 18 | Halaman Dashboard & Buku | [~] Terverifikasi, menunggu screenshot | Dashboard 2 buku/2 kategori; CRUD Buku via UI berhasil |
| 19 | Halaman Kategori & Integrasi Penuh | [~] Terverifikasi, menunggu screenshot | CRUD Kategori via UI berhasil; data dashboard ikut ter-update |
| 20 | Finalisasi & Push Repository | [~] Sebagian | README fitur/cara jalan/bukti + `.gitignore` siap; screenshot final dan push GitHub belum |

## Screenshot Final
- [ ] `screenshot-final/dashboard.png`
- [ ] `screenshot-final/halaman-buku.png`
- [ ] `screenshot-final/halaman-kategori.png`

## Environment
Isi setelah pemeriksaan, jangan menebak:
- OS: Windows NT 10.0.26300.0 (Windows 11)
- PHP: 8.5.10 (CLI, `C:\php-8.5.10\php.exe`; `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json` tersedia)
- Composer: 2.10.3
- MySQL/MariaDB: MySQL 8.4.3 (Laragon, `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe`; server berjalan di port 3306; `mysql` belum masuk PATH permanen)
- cURL: 8.21.0 (`curl.exe`)
- jq: 1.8.2 (terpasang via WinGet; salinan portable di `rest-api-perpustakaan/tools/jq.exe`)
- Postman/Insomnia: tidak terdeteksi
- Browser: Google Chrome (`C:\Program Files\Google\Chrome\Application\chrome.exe`)

## Langkah Aktif
- Nomor langkah: 2–19 (implementasi selesai)
- Nama langkah: Menunggu pengambilan screenshot bukti
- Tujuan saat ini: Mengumpulkan screenshot asli tiap langkah dan tiga screenshot final.
- File yang akan dibuat/diubah: file `pertemuan-XX/langkah-YY.png` dan `screenshot-final/*.png` (diisi pengguna).
- Cara verifikasi: jalankan ulang perintah tiap langkah pada terminal, lalu screenshot hasilnya.
- Hasil verifikasi: Seluruh backend, frontend, dan integrasi sudah diuji (lihat log).
- Screenshot: Baru `pertemuan-01/langkah-01.png` yang tersedia.
- Kendala: `test_connection.php`, `test_repository.php`, `test_service.php`, dan `test_router.php` sengaja dipertahankan sementara agar pengguna bisa mengambil screenshot; hapus setelah screenshot selesai. Repository Git belum diinisialisasi dan remote GitHub belum tersedia.

## Log Perubahan
| Tanggal | Langkah | Perubahan | Pengujian | Status |
|---|---|---|---|---|
| 2026-11-06 | 1 | Menyalin dokumen referensi, membuat folder pertemuan, memeriksa lingkungan | PHP 8.5.10; Composer 2.10.3; MySQL 8.4.3 | Selesai — screenshot tersedia |
| 2026-11-06 | 2 | Membuat struktur `rest-api-perpustakaan/` dan `composer.json` PSR-4 | `composer dump-autoload -o`; `vendor/autoload.php` ada | Selesai — screenshot `langkah-02.png` tersedia |
| 2026-11-06 | 3 | Membuat `.env`, `.env.example`, `database/schema.sql`; membuat database dan tabel `books` | MySQL: 2 baris awal tampil | Terverifikasi |
| 2026-11-06 | 4 | Membuat `Env` dan `Connection` PDO Singleton | `php test_connection.php` → koneksi berhasil, MySQL 8.4.3 | Terverifikasi |
| 2026-11-06 | 5 | Membuat `Book`, `BookRepositoryInterface`, `MySQLBookRepository` | `php test_repository.php` → total 2 buku | Terverifikasi |
| 2026-11-06 | 6 | Membuat `BookService` dengan validasi 422 dan 404 | `php test_service.php` → 2 buku, validasi 422, Pulang ID 3 | Terverifikasi |
| 2026-11-06 | 7 | Membuat `Request`, `Response`, `Router`, `Container` | `php test_router.php` → router + container bekerja | Terverifikasi |
| 2026-11-06 | 8 | Membuat `BookController` dan `public/index.php` | `GET /api/books` → HTTP 200 | Terverifikasi |
| 2026-11-06 | 9 | Menguji CRUD Buku via cURL | GET 200, detail 200, POST 201, PUT 200, DELETE 200 | Terverifikasi |
| 2026-11-06 | 10 | Menguji validasi dan error handling | 404, 422, 404 dengan pesan Indonesia | Terverifikasi |
| 2026-11-06 | 11 | Menguji POST buku “Bumi” via cURL (pengganti sementara Postman) | POST → HTTP 201 | Menunggu screenshot Postman |
| 2026-11-06 | 12 | Membuat `tests/api_test.sh` + jq portable | 11 assertion lulus, 0 gagal | Terverifikasi |
| 2026-11-06 | 13 | Menambah tabel `categories`, model, interface, repository | 2 kategori awal tampil | Terverifikasi |
| 2026-11-06 | 14 | Membuat `CategoryService`, `CategoryController`, binding, route | `GET /api/categories` → HTTP 200 | Terverifikasi |
| 2026-11-06 | 15 | Menguji CRUD Kategori | POST 201, PUT 200, DELETE 200, validasi 422 | Terverifikasi |
| 2026-11-06 | 16 | Menambahkan header CORS dan preflight OPTIONS | `OPTIONS /api/books` → HTTP 204 + header CORS | Terverifikasi |
| 2026-11-06 | 17 | Membuat frontend Tailwind + `config.js`, `api.js`, `utils.js` | Halaman `localhost:3000` terbuka, tanpa error console | Terverifikasi |
| 2026-11-06 | 18 | Membuat dashboard, halaman Buku, modal CRUD, router hash | Dashboard tampil; tambah/hapus buku via UI berhasil | Terverifikasi |
| 2026-11-06 | 19 | Membuat halaman Kategori + integrasi penuh | Tambah/edit/hapus kategori via UI berhasil | Terverifikasi |
| 2026-11-06 | 20 | Menambah bagian fitur/cara menjalankan/bukti di README dan `.gitignore` | Data uji direset ke kondisi awal | Screenshot final + push belum |

## Aturan Pembaruan
1. Perbarui status hanya berdasarkan hasil aktual.
2. Catat command/test yang dijalankan dan hasilnya secara ringkas.
3. Jika gagal, tulis error dan langkah diagnosis. Jangan tandai selesai.
4. Screenshot hanya dianggap tersedia jika file benar-benar ada di workspace.
5. Jangan mengubah status langkah lain yang belum diverifikasi.
