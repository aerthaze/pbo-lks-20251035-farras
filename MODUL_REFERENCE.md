# MODUL_REFERENCE — Panduan Teknis REST API PHP Modern

Dokumen ini merangkum konsep dan instruksi implementasi dari Modul Praktikum “Merancang dan Membangun REST API”. Untuk urutan tugas dan bukti penilaian, gunakan `LKS_REFERENCE.md`.

## 1. Tujuan dan Capaian
Mahasiswa diharapkan mampu:
- Memahami PBO: class, object, encapsulation, inheritance, polymorphism, abstraction.
- Menerapkan SOLID dan design pattern.
- Membangun aplikasi PHP dengan MVC, PSR-4, Composer.
- Merancang, membangun, dan menguji REST API yang aman dan terstruktur.
- Mengelola `.env` dan menguji dengan cURL/Postman.

## 2. Alat dan Versi
- PHP 8.0+
- Composer 2.x
- MySQL 5.7+ atau MariaDB 10.4+
- XAMPP/Laragon (opsional)
- Editor kode (VS Code)
- Postman/Insomnia
- cURL
- Browser modern (Chrome/Edge/Firefox)

## 3. Studi Kasus
REST API Perpustakaan untuk mengelola data buku melalui CRUD. Entitas lanjutan: Kategori.

## 4. Konsep Arsitektur

### PBO
- Encapsulation: menyembunyikan detail internal dan mengekspos interface publik.
- Inheritance: mewarisi sifat class induk.
- Polymorphism: satu interface, banyak implementasi.
- Abstraction: fokus pada apa yang dilakukan, bukan detail bagaimana caranya.

### PSR-4 dan Composer
Mapping namespace:
```json
{
  "autoload": {
    "psr-4": {
      "App\\\\": "src/"
    }
  }
}
```
Contoh `App\\Models\\Book` dipetakan ke `src/Models/Book.php`.

Jalankan:
```bash
composer dump-autoload -o
```

### SOLID
| Prinsip | Inti | Penerapan |
|---|---|---|
| SRP | Satu class satu tanggung jawab | Book untuk data; Repository untuk akses DB; Service untuk logika bisnis |
| OCP | Terbuka untuk ekstensi, tertutup untuk modifikasi | Implementasi repository baru dapat mengganti MySQL |
| LSP | Implementasi dapat saling menggantikan | MySQL repository dan in-memory repository mengikuti interface |
| ISP | Interface kecil dan spesifik | BookRepositoryInterface khusus operasi buku |
| DIP | Bergantung pada abstraksi | Service menerima interface, bukan repository konkret |

### Design Pattern
- Front Controller: satu pintu masuk request melalui `public/index.php`.
- Repository Pattern: abstraksi akses database melalui interface.
- Dependency Injection: dependensi diberikan dari luar, umumnya melalui constructor.
- Service Layer: logika bisnis dipisahkan dari controller dan repository.
- Simple Container/Service Locator: wadah untuk merangkai dependensi.
- Singleton pada Connection: satu instance PDO per request sesuai contoh modul.

### MVC
- Model: representasi data/aturan domain.
- View: response JSON untuk REST API.
- Controller: menjembatani request dengan model/service.

### REST
- Stateless.
- URL merepresentasikan resource, misalnya `/api/books`.
- HTTP method: GET, POST, PUT, DELETE.
- Response menggunakan JSON.

## 5. Struktur Backend

```text
rest-api-perpustakaan/
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── public/
│   └── index.php
├── src/
│   ├── Config/Env.php
│   ├── Database/Connection.php
│   ├── Core/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Router.php
│   │   └── Container.php
│   ├── Models/Book.php
│   ├── Contracts/BookRepositoryInterface.php
│   ├── Repositories/MySQLBookRepository.php
│   ├── Services/BookService.php
│   └── Controllers/BookController.php
├── database/schema.sql
└── tests/api_test.sh
```

`public/` adalah document root; `src/` menyimpan kode aplikasi; `database/` menyimpan skema; `tests/` menyimpan pengujian.

## 6. Konfigurasi

Contoh `.env.example`:
```dotenv
APP_NAME=Perpustakaan API
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Salin menjadi `.env`, lalu sesuaikan kredensial. Jangan commit `.env`.

Contoh `.gitignore`:
```gitignore
/vendor/
/.env
.DS_Store
*.log
```

## 7. Skema Database Buku

```sql
CREATE DATABASE IF NOT EXISTS perpustakaan
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE perpustakaan;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(255) NOT NULL,
    tahun INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO books (judul, penulis, tahun, stok) VALUES
('Laskar Pelangi', 'Andrea Hirata', 2005, 5),
('Bumi Manusia', 'Pramoedya Ananta Toer', 1980, 3);
```

## 8. Komponen Backend

### Env
`App\\Config\\Env` membaca file `.env`, melewati baris kosong/komentar, memisahkan `KEY=VALUE`, dan menyediakan `get($key, $default)`.

### Connection
`App\\Database\\Connection` membuat PDO MySQL dari konfigurasi Env. Opsi yang dicontohkan:
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`
- `PDO::ATTR_EMULATE_PREPARES => false`

### Book Model
Menyimpan `id`, `judul`, `penulis`, `tahun`, `stok`, menyediakan getter dan `toArray()`.

### BookRepositoryInterface
Kontrak lima method:
- `findAll(): array`
- `findById(int $id): ?Book`
- `save(Book $book): int`
- `update(Book $book): bool`
- `delete(int $id): bool`

### MySQLBookRepository
Implementasi PDO untuk query SELECT/INSERT/UPDATE/DELETE. Gunakan prepared statements dan konversi row database menjadi object Book.

### BookService
Logika bisnis:
- `list()`
- `detail($id)`
- `create($data)`
- `update($id, $data)`
- `delete($id)`
- validasi judul, penulis, tahun, stok.
Contoh status exception: 404 untuk data tidak ditemukan dan 422 untuk input tidak valid.

### Core
- Request: method, URI, JSON body, query parameter.
- Response: JSON, success envelope, error envelope, HTTP status.
- Router: pemetaan method + path ke handler.
- Container: binding dan resolusi dependency.

### BookController dan Front Controller
Controller menangani method HTTP dan memanggil Service. `public/index.php` memuat autoload dan `.env`, membuat Container, mengikat PDO/repository/service/controller, mendaftarkan route, lalu dispatch request.

## 9. Endpoint Buku

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/books` | Daftar buku |
| GET | `/api/books/{id}` | Detail buku |
| POST | `/api/books` | Tambah buku |
| PUT | `/api/books/{id}` | Ubah buku |
| DELETE | `/api/books/{id}` | Hapus buku |

Contoh payload:
```json
{
  "judul": "Pulang",
  "penulis": "Leila S. Chudori",
  "tahun": 2012,
  "stok": 4
}
```

Envelope response sukses:
```json
{
  "success": true,
  "message": "Daftar buku",
  "data": []
}
```

Envelope error:
```json
{
  "success": false,
  "message": "Pesan kesalahan"
}
```

## 10. Pengujian Backend
- Jalankan backend: `php -S localhost:8000 -t public`
- GET semua buku: `curl http://localhost:8000/api/books`
- Uji GET detail, POST, PUT, DELETE.
- Uji data tidak ada (404), validasi input (422), dan endpoint tidak dikenal (404).
- Uji POST dari Postman dengan Body → raw → JSON.
- Skrip otomatis `tests/api_test.sh` menggunakan cURL dan jq.

## 11. CRUD Kategori
Endpoint:
| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/categories` | Daftar kategori |
| GET | `/api/categories/{id}` | Detail kategori |
| POST | `/api/categories` | Tambah kategori |
| PUT | `/api/categories/{id}` | Ubah kategori |
| DELETE | `/api/categories/{id}` | Hapus kategori |

Tabel:
```sql
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```
Pola file mengikuti Buku:
- `Category.php`
- `CategoryRepositoryInterface.php`
- `MySQLCategoryRepository.php`
- `CategoryService.php`
- `CategoryController.php`

Tambahkan binding DI dan route kategori di `public/index.php`. Modul juga menyebut latihan opsional: validasi `category_id`, endpoint `/api/categories/{id}/books`, dan filter `/api/books?category_id=1`. Relasi buku-kategori disebut opsional, bukan kewajiban inti.

## 12. Frontend dan CORS

### CORS
Tambahkan header CORS di awal `public/index.php` sebelum autoload:
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
Verifikasi dengan:
```bash
curl -i -X OPTIONS http://localhost:8000/api/books
```

### Frontend
```text
frontend-perpustakaan/
├── index.html
├── css/custom.css
└── js/
    ├── config.js
    ├── api.js
    ├── utils.js
    ├── app.js
    └── pages/
        ├── dashboard.js
        ├── books.js
        └── categories.js
```

Fungsi file:
- `index.html`: layout dan Tailwind CDN.
- `custom.css`: styling tambahan.
- `config.js`: BASE_URL API.
- `api.js`: ApiClient dan services untuk request.
- `utils.js`: `escapeHtml`, `showToast`.
- `dashboard.js`: statistik dan buku terbaru.
- `books.js`: tabel dan modal CRUD Buku.
- `categories.js`: tabel dan modal CRUD Kategori.
- `app.js`: hash router.

Jalankan frontend dari foldernya:
```bash
php -S localhost:3000
```
Backend tetap berjalan di port 8000. Akses frontend melalui `http://localhost:3000`.

## 13. Troubleshooting dari Modul
| Error | Penyebab umum | Solusi |
|---|---|---|
| Class not found | Autoload belum dibuat | `composer dump-autoload -o` |
| Koneksi DB gagal | Kredensial `.env` salah / MySQL mati | Periksa `.env` dan service MySQL |
| 404 endpoint | URL salah atau route belum terdaftar | Periksa route di `public/index.php` |
| 405 method | HTTP method tidak sesuai | Gunakan GET/POST/PUT/DELETE sesuai route |
| `.env` tidak ditemukan | Belum dibuat dari template | Salin `.env.example` menjadi `.env` |
| Port 8000 terpakai | Server lain berjalan | Hentikan server atau gunakan port lain |

## 14. Batasan dan Kehati-hatian
- Jangan commit `.env` atau `vendor/`.
- Gunakan prepared statements.
- Jangan mengarang output sukses. Jalankan pengujian.
- Jangan menganggap ID contoh pada modul harus sama pada database lokal.
- Fitur latihan/opsional tidak boleh diperlakukan sebagai syarat wajib tanpa instruksi tambahan.
