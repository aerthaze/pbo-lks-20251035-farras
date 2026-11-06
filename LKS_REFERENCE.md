# LKS_REFERENCE — Instruksi 20 Langkah Praktikum PBO

Dokumen ini merangkum instruksi Lembar Kerja Mahasiswa (LKS) **Merancang & Membangun REST API**. Gunakan ini sebagai referensi tugas dan bukti yang harus dikumpulkan. Detail implementasi teknis tersedia di `MODUL_REFERENCE.md`.

## Informasi LKS
- Program Studi: Sistem Informasi, FMIPA, Universitas Tanjungpura
- Mata Kuliah: Pemrograman Berbasis Objek (PBO), 3 SKS
- Dosen Pengampu: Syahru Rahmayuda, M.Kom
- Capaian: REST API PHP modern (PSR-4 + Composer), SOLID, Repository/DI/Front Controller, MVC + MySQL, frontend terpisah Tailwind CSS, portofolio GitHub.
- Pengumpulan: LKS terisi (PDF) dan link repository GitHub.
- Pola tiap langkah: baca tujuan → jalankan perintah/tulis kode → verifikasi → screenshot → upload ke GitHub.

## Setup Repository dan Folder Bukti
Nama repository: `pbo-lks-nim-nama` (sesuaikan NIM dan nama).

Folder bukti:
```text
pbo-lks-nim-nama/
├── README.md
├── pertemuan-01/
├── pertemuan-02/
├── pertemuan-03/
├── pertemuan-04/
├── pertemuan-05/
├── pertemuan-06/
└── pertemuan-07/
```

Pemetaan screenshot:
| Pertemuan | Langkah | Nama file |
|---|---:|---|
| 1 | 1–5 | `pertemuan-01/langkah-01.png` s.d. `langkah-05.png` |
| 2 | 6–9 | `pertemuan-02/langkah-06.png` s.d. `langkah-09.png` |
| 3 | 10–11 | `pertemuan-03/langkah-10.png` s.d. `langkah-11.png` |
| 4 | 12 | `pertemuan-04/langkah-12.png` |
| 5 | 13–15 | `pertemuan-05/langkah-13.png` s.d. `langkah-15.png` |
| 6 | 16–18 | `pertemuan-06/langkah-16.png` s.d. `langkah-18.png` |
| 7 | 19–20 | `pertemuan-07/langkah-19.png` s.d. `langkah-20.png` |

Commit per langkah mengikuti pola:
```bash
git add pertemuan-XX/langkah-YY.png
git commit -m "Pertemuan XX - Langkah YY: Judul Singkat"
git push origin main
```

---

## Pertemuan 1 — Setup & Database

### Langkah 1 — Cek Lingkungan Pengembangan
**Tujuan:** Memastikan PHP 8+, Composer, dan MySQL dapat dijalankan dari terminal.

Jalankan:
```bash
php -v
composer -V
mysql --version
```
**Verifikasi:** PHP 8.x, Composer 2.x, dan MySQL/MariaDB terdeteksi.

**Screenshot:** Terminal setelah menjalankan ketiga perintah.  
**Simpan sebagai:** `pertemuan-01/langkah-01.png`  
**Commit:** `Pertemuan 1 - Langkah 1: Cek lingkungan`

### Langkah 2 — Struktur Folder & Composer PSR-4
**Tujuan:** Membuat folder proyek dan mengonfigurasi Composer dengan PSR-4.

Jalankan:
```bash
mkdir rest-api-perpustakaan
cd rest-api-perpustakaan
```
Buat `composer.json` dengan mapping namespace `App\\` ke `src/`, sesuai modul. Lalu:
```bash
composer dump-autoload -o
```
**Verifikasi:** Folder `vendor/` dan file `vendor/autoload.php` terbentuk; `composer.json` berisi mapping PSR-4 yang benar.

**Screenshot:** Terminal setelah Composer dijalankan dan isi folder yang menampilkan `vendor/`.  
**Simpan sebagai:** `pertemuan-01/langkah-02.png`  
**Commit:** `Pertemuan 1 - Langkah 2: Struktur folder + PSR-4`

### Langkah 3 — Konfigurasi .env & Skema Database
**Tujuan:** Membuat konfigurasi `.env` dan menginisialisasi database MySQL.

Jalankan:
```bash
cp .env.example .env
```
Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan MySQL lokal. Jalankan skema:
```bash
mysql -u root -p < database/schema.sql
```
Verifikasi:
```bash
mysql -u root -p -e "USE perpustakaan; SHOW TABLES; SELECT * FROM books;"
```
**Verifikasi:** Database `perpustakaan`, tabel `books`, dan dua data awal:
- Laskar Pelangi — Andrea Hirata — 2005 — stok 5
- Bumi Manusia — Pramoedya Ananta Toer — 1980 — stok 3

**Screenshot:** Hasil query dengan tabel `books` dan dua baris data.  
**Simpan sebagai:** `pertemuan-01/langkah-03.png`  
**Keamanan:** Jangan commit `.env`; hanya `.env.example` yang boleh dibagikan.  
**Commit:** `Pertemuan 1 - Langkah 3: .env + schema database`

### Langkah 4 — Env Loader & Connection PDO
**Tujuan:** Membuat class `Env` untuk membaca `.env` dan `Connection` sebagai PDO Singleton, sesuai SRP.

Buat:
- `src/Config/Env.php`
- `src/Database/Connection.php`

Buat sementara `test_connection.php` di root untuk memuat autoload, menjalankan `Env::load(__DIR__ . '/.env')`, memanggil `Connection::get()`, lalu menampilkan pesan koneksi berhasil dan versi MySQL.

Jalankan:
```bash
php test_connection.php
```
**Verifikasi:** Output menyatakan koneksi database berhasil dan menampilkan versi MySQL. Hapus `test_connection.php` setelah verifikasi.

**Screenshot:** Output `php test_connection.php`.  
**Simpan sebagai:** `pertemuan-01/langkah-04.png`  
**Commit:** `Pertemuan 1 - Langkah 4: Env loader + Connection PDO`

### Langkah 5 — Model, Interface & Repository Book
**Tujuan:** Membuat entity `Book`, kontrak repository (DIP), dan implementasi MySQL.

Buat:
- `src/Models/Book.php` — entity, property/getter/`toArray`
- `src/Contracts/BookRepositoryInterface.php` — kontrak akses data, lima method
- `src/Repositories/MySQLBookRepository.php` — implementasi PDO

Buat sementara `test_repository.php`: load autoload dan `.env`, buat koneksi, instansiasi repository, panggil `findAll()`, tampilkan jumlah dan judul/penulis buku.

Jalankan:
```bash
php test_repository.php
```
**Verifikasi:** Total buku 2; menampilkan Bumi Manusia dan Laskar Pelangi. Class Book hanya data + getter; repository mengimplementasikan interface.

**Screenshot:** Output `php test_repository.php`.  
**Simpan sebagai:** `pertemuan-01/langkah-05.png`  
**Commit:** `Pertemuan 1 - Langkah 5: Model + Interface + Repository Book`

---

## Pertemuan 2 — Core OOP

### Langkah 6 — BookService: Logika Bisnis
**Tujuan:** Membuat `BookService` untuk validasi dan koordinasi repository, terpisah dari HTTP dan SQL.

Buat `src/Services/BookService.php`. Service harus menerima `BookRepositoryInterface` melalui constructor (DIP), menyediakan operasi list/detail/create/update/delete, dan melakukan validasi input.

Verifikasi menggunakan skrip sementara `test_service.php`:
1. Uji list buku.
2. Uji input judul kosong dan pastikan validasi menolak.
3. Buat buku valid: Pulang, Leila S. Chudori, 2012, stok 4.

Jalankan:
```bash
php test_service.php
```
**Verifikasi:** Daftar buku berjumlah 2, validasi menampilkan `Judul wajib diisi`, dan buku Pulang berhasil dibuat (contoh ID 3). Error validasi menggunakan kode 422.

**Screenshot:** Output `php test_service.php`.  
**Simpan sebagai:** `pertemuan-02/langkah-06.png`  
**Commit:** `Pertemuan 2 - Langkah 6: BookService + validasi`

### Langkah 7 — Core Framework: Request, Response, Router, Container
**Tujuan:** Membuat empat komponen inti framework mini.

Buat:
- `src/Core/Request.php` — parsing HTTP request
- `src/Core/Response.php` — response JSON
- `src/Core/Router.php` — pemetaan URL ke handler
- `src/Core/Container.php` — Dependency Injection Container

Verifikasi Router dengan `test_router.php`: simulasikan `REQUEST_URI=/api/hello` dan `REQUEST_METHOD=GET`, daftarkan route callback, lalu dispatch request.

Jalankan:
```bash
php test_router.php
```
**Verifikasi:** Callback terpanggil dan menampilkan `Router bekerja! Endpoint /api/hello terpanggil.`; Container mendukung `bind()` dan `make()`.

**Screenshot:** Output `php test_router.php`.  
**Simpan sebagai:** `pertemuan-02/langkah-07.png`  
**Commit:** `Pertemuan 2 - Langkah 7: Core Request/Response/Router/Container`

### Langkah 8 — BookController & Front Controller
**Tujuan:** Membuat `BookController` dan `public/index.php` sebagai Front Controller/Composition Root.

Buat:
- `src/Controllers/BookController.php` — method `index`, `show`, `store`, `update`, `destroy`
- `public/index.php` — load autoload dan `.env`, binding Container, route, dispatch

Jalankan:
```bash
php -S localhost:8000 -t public
```
Buka:
```text
http://localhost:8000/api/books
```
**Verifikasi:** Server berjalan, endpoint mengembalikan response JSON berisi daftar buku dari database, HTTP 200.

**Screenshot:** Browser menampilkan response JSON `/api/books`.  
**Simpan sebagai:** `pertemuan-02/langkah-08.png`  
**Commit:** `Pertemuan 2 - Langkah 8: Controller + Front Controller berjalan`

### Langkah 9 — Uji CRUD Buku dengan cURL
**Tujuan:** Menguji seluruh endpoint CRUD Buku menggunakan cURL.

Jalankan satu per satu:
```bash
curl http://localhost:8000/api/books
curl http://localhost:8000/api/books/1

curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{"judul":"Pulang","penulis":"Leila S. Chudori","tahun":2012,"stok":4}'

curl -X PUT http://localhost:8000/api/books/1 \
  -H "Content-Type: application/json" \
  -d '{"judul":"Laskar Pelangi Revisi","penulis":"Andrea Hirata","tahun":2008,"stok":10}'

curl -X DELETE http://localhost:8000/api/books/3
```
**Verifikasi:** GET daftar 200, GET detail 200, POST 201, PUT 200, DELETE 200.

**Screenshot:** Terminal menampilkan lima response JSON.  
**Simpan sebagai:** `pertemuan-02/langkah-09.png`  
**Commit:** `Pertemuan 2 - Langkah 9: Uji CRUD lengkap dengan cURL`

---

## Pertemuan 3 — Framework & Routing / Pengujian API

### Langkah 10 — Uji Validasi & Error Handling
**Tujuan:** Memastikan API memberi status 404 dan 422 dengan pesan jelas.

Jalankan:
```bash
curl http://localhost:8000/api/books/999

curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{"judul":""}'

curl http://localhost:8000/api/unknown
```
**Verifikasi:**
- Buku ID 999 tidak ada → 404.
- Judul kosong → 422.
- Endpoint tidak dikenal → 404.
- Pesan error jelas dalam bahasa Indonesia.

**Screenshot:** Tiga response JSON dengan status 404, 422, dan 404.  
**Simpan sebagai:** `pertemuan-03/langkah-10.png`  
**Commit:** `Pertemuan 3 - Langkah 10: Uji validasi & error handling`

### Langkah 11 — Uji dengan Postman
**Tujuan:** Menguji API melalui Postman sebagai bukti alternatif dari cURL.

1. Buka Postman → New → HTTP Request.
2. Method `POST`, URL `http://localhost:8000/api/books`.
3. Body → raw → JSON.
4. Gunakan body:
```json
{
  "judul": "Bumi",
  "penulis": "Tere Liye",
  "tahun": 2014,
  "stok": 2
}
```
5. Klik Send.

**Verifikasi:** Status `201 Created`, response JSON berisi buku baru.

**Screenshot:** Jendela Postman memperlihatkan URL, method, body, dan response 201.  
**Simpan sebagai:** `pertemuan-03/langkah-11.png`  
**Commit:** `Pertemuan 3 - Langkah 11: Uji API dengan Postman`

---

## Pertemuan 4 — Testing Backend

### Langkah 12 — Skrip Uji Otomatis
**Tujuan:** Membuat skrip `tests/api_test.sh` untuk menguji alur CRUD otomatis menggunakan cURL + jq.

Buat `tests/api_test.sh`, lalu jalankan:
```bash
chmod +x tests/api_test.sh
./tests/api_test.sh
```
Skrip menguji enam skenario:
1. GET semua buku.
2. POST buku baru.
3. GET buku berdasarkan ID hasil POST.
4. PUT mengubah buku.
5. DELETE buku.
6. Validasi error untuk judul kosong.

**Verifikasi:** Enam pengujian berjalan tanpa error dan response sesuai harapan.

**Screenshot:** Terminal setelah menjalankan skrip dan semua test lulus.  
**Simpan sebagai:** `pertemuan-04/langkah-12.png`  
**Commit:** `Pertemuan 4 - Langkah 12: Skrip uji otomatis api_test.sh`

---

## Pertemuan 5 — CRUD Kategori

### Langkah 13 — CRUD Kategori: Schema & Repository
**Tujuan:** Menambah tabel `categories`, Model, Interface, dan Repository kategori mengikuti pola Book.

Buat:
- `src/Models/Category.php`
- `src/Contracts/CategoryRepositoryInterface.php`
- `src/Repositories/MySQLCategoryRepository.php`

Tambahkan tabel kategori pada `database/schema.sql`:
```sql
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO categories (nama, deskripsi) VALUES
('Fiksi', 'Buku-buku fiksi dan novel'),
('Non-Fiksi', 'Buku non-fiksi, sains, sejarah');
```
Jalankan ulang skema:
```bash
mysql -u root -p < database/schema.sql
```
Verifikasi:
```bash
mysql -u root -p -e "USE perpustakaan; SELECT * FROM categories;"
```
**Verifikasi:** Tabel categories ada, dua data awal tersimpan, dan tiga file Category dibuat.

**Screenshot:** Query tabel categories yang menampilkan dua kategori awal.  
**Simpan sebagai:** `pertemuan-05/langkah-13.png`  
**Commit:** `Pertemuan 5 - Langkah 13: Schema + Model + Repository Category`

### Langkah 14 — CRUD Kategori: Service, Controller & Route
**Tujuan:** Menambah Service, Controller, binding DI, dan route kategori.

Buat:
- `src/Services/CategoryService.php`
- `src/Controllers/CategoryController.php`

Edit `public/index.php` untuk binding dan lima route kategori. Restart server:
```bash
php -S localhost:8000 -t public
```
Uji:
```bash
curl http://localhost:8000/api/categories
```
**Verifikasi:** GET `/api/categories` menghasilkan 200 dan daftar kategori; lima route kategori terdaftar; endpoint Book tetap berjalan.

**Screenshot:** Response JSON `/api/categories` di browser/cURL.  
**Simpan sebagai:** `pertemuan-05/langkah-14.png`  
**Commit:** `Pertemuan 5 - Langkah 14: Service + Controller + Route Kategori`

### Langkah 15 — Uji CRUD Kategori
**Tujuan:** Menguji POST, PUT, DELETE, dan validasi kategori.

```bash
curl -X POST http://localhost:8000/api/categories \
  -H "Content-Type: application/json" \
  -d '{"nama":"Sastra","deskripsi":"Buku sastra klasik dan modern"}'

curl -X PUT http://localhost:8000/api/categories/1 \
  -H "Content-Type: application/json" \
  -d '{"nama":"Fiksi & Sastra","deskripsi":"Buku fiksi, novel, dan sastra"}'

curl -X DELETE http://localhost:8000/api/categories/2

curl -X POST http://localhost:8000/api/categories \
  -H "Content-Type: application/json" \
  -d '{"nama":""}'
```
**Verifikasi:** POST 201, PUT 200, DELETE 200, nama kosong 422.

**Screenshot:** Empat response JSON untuk POST, PUT, DELETE, dan validasi.  
**Simpan sebagai:** `pertemuan-05/langkah-15.png`  
**Commit:** `Pertemuan 5 - Langkah 15: Uji CRUD Kategori lengkap`

---

## Pertemuan 6 — Frontend

### Langkah 16 — Aktifkan CORS di Backend
**Tujuan:** Mengizinkan frontend port 3000 mengakses backend port 8000.

Edit `public/index.php`. Tambahkan header CORS di bagian paling atas, sebelum `require_once`:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
```
Verifikasi:
```bash
curl -i -X OPTIONS http://localhost:8000/api/books
```
**Verifikasi:** HTTP 204 dan header CORS yang ditentukan muncul.

**Screenshot:** Response header CORS dari perintah cURL.  
**Simpan sebagai:** `pertemuan-06/langkah-16.png`  
**Commit:** `Pertemuan 6 - Langkah 16: Aktifkan CORS backend`

### Langkah 17 — Setup Frontend & Konfigurasi API
**Tujuan:** Membuat folder frontend dan file dasar.

Buat struktur:
```text
frontend-perpustakaan/
├── index.html
├── css/custom.css
└── js/
    ├── config.js
    ├── api.js
    ├── utils.js
    └── pages/
```
File:
- `index.html` — layout menggunakan Tailwind CDN.
- `css/custom.css` — styling tambahan.
- `js/config.js` — BASE_URL API.
- `js/api.js` — ApiClient dan services.
- `js/utils.js` — `escapeHtml`, `showToast`.

Jalankan:
```bash
php -S localhost:3000
```
Buka `http://localhost:3000`.

**Verifikasi:** Halaman terbuka dengan header biru “Perpustakaan”, menu Dashboard/Buku/Kategori, dan area “Memuat aplikasi...”; tidak ada error console.

**Screenshot:** Browser menampilkan header dan menu.  
**Simpan sebagai:** `pertemuan-06/langkah-17.png`  
**Commit:** `Pertemuan 6 - Langkah 17: Setup frontend + konfigurasi API`

### Langkah 18 — Halaman Dashboard & Buku
**Tujuan:** Membuat dashboard, halaman Buku, dan router frontend.

Buat:
- `js/pages/dashboard.js` — kartu statistik dan buku terbaru.
- `js/pages/books.js` — tabel dan modal CRUD buku.
- `js/app.js` — router hash.

**Verifikasi Dashboard:** Kartu Total Buku (biru), Kategori (oranye), dan daftar lima buku terbaru.  
**Verifikasi Buku:** Tabel kolom ID, Judul, Penulis, Tahun, Stok, Aksi; tombol “+ Tambah Buku” membuka modal dan submit berhasil menambah data.

**Screenshot:** Dashboard dan halaman Buku.  
**Simpan sebagai:** `pertemuan-06/langkah-18.png`  
**Commit:** `Pertemuan 6 - Langkah 18: Halaman Dashboard + Buku berjalan`

---

## Pertemuan 7 — Integrasi & Finalisasi

### Langkah 19 — Halaman Kategori & Integrasi Penuh
**Tujuan:** Membuat halaman Kategori dan menguji backend + frontend secara end-to-end.

Buat `js/pages/categories.js` untuk tabel dan modal CRUD kategori.

Jalankan dua server bersamaan:

Terminal 1 — backend:
```bash
cd rest-api-perpustakaan
php -S localhost:8000 -t public
```

Terminal 2 — frontend:
```bash
cd frontend-perpustakaan
php -S localhost:3000
```
Buka `http://localhost:3000`.

**Verifikasi:** Menu Kategori menampilkan data dari API; tambah/edit/hapus kategori berhasil; toast muncul di kanan atas; data dashboard ter-update otomatis.

**Screenshot:** Dua terminal (backend + frontend) dan browser pada halaman Kategori.  
**Simpan sebagai:** `pertemuan-07/langkah-19.png`  
**Commit:** `Pertemuan 7 - Langkah 19: Halaman Kategori + integrasi penuh`

### Langkah 20 — Finalisasi & Push Repository
**Tujuan:** Merapikan repository GitHub dan dokumentasi akhir.

Aktivitas:
1. Jalankan aplikasi final dan ambil tiga screenshot: Dashboard, Halaman Buku, Halaman Kategori.
2. Simpan di `screenshot-final/dashboard.png`, `screenshot-final/halaman-buku.png`, dan `screenshot-final/halaman-kategori.png`.
3. Update README repository dengan identitas, daftar fitur, cara menjalankan backend/frontend, dan link screenshot tiap pertemuan.
4. Tambahkan `.gitignore` untuk mengecualikan `vendor/` dan `.env`.
5. Push final dengan commit dan tag.

Perintah final:
```bash
git add .
git commit -m "FINAL: LKS PBO selesai — 20 langkah + 3 screenshot aplikasi"
git tag v1.0-lks-final
git push origin main --tags
```
Screenshot repository utama yang menampilkan README dan folder pertemuan 01–07 disimpan sebagai `pertemuan-07/langkah-20.png`, kemudian commit dan push.

**Checklist:** Semua folder pertemuan berisi screenshot; README lengkap; tiga screenshot final tersimpan; `.gitignore` benar; commit final dan tag sudah di-push. Kirim link repository ke dosen melalui LMS/email.

---

## Rubrik Penilaian
| Aspek | Bobot |
|---|---:|
| Kelengkapan LKS (20 langkah terisi) | 25% |
| Kualitas screenshot (jelas, tidak blur) | 15% |
| REST API berjalan (CRUD Book & Category) | 20% |
| Frontend integrasi (Dashboard, Buku, Kategori) | 20% |
| Struktur GitHub rapi & README lengkap | 10% |
| Penerapan SOLID, PSR-4, Design Pattern | 10% |
| **Total** | **100%** |

## Catatan Akurasi
- Nomor pertemuan dan langkah mengikuti LKS.
- Detail kode yang tidak ditulis lengkap di LKS harus dirujuk ke `MODUL_REFERENCE.md`.
- Jika contoh output memuat ID tertentu (misalnya ID 3, 4, atau 5), anggap sebagai contoh hasil pada kondisi database di dokumen, bukan ID yang wajib sama pada setiap komputer.
- Relasi `books.category_id`, filter buku berdasarkan kategori, dan endpoint tambahan yang disebut sebagai latihan/opsional di modul bukan persyaratan wajib LKS kecuali pengguna/dosen meminta.
