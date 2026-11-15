# PBO LKS — Portofolio Praktikum REST API

Repository: https://github.com/aerthaze/pbo-lks-20251035-farras

> **Instruksi utama untuk OpenCode.** Baca file ini sebelum melakukan perubahan apa pun. Detail tugas per langkah ada di `LKS_REFERENCE.md`, sedangkan progres aktual dicatat di `PROJECT_STATUS.md`.

## Identitas
- Nama: Muhammad Farras Haidar Revanezka
- NIM: H1101251035
- Kelas: B
- Program Studi: Sistem Informasi
- Fakultas: FMIPA
- Universitas: Universitas Tanjungpura
- Mata Kuliah: Pemrograman Berbasis Objek (PBO)
- Dosen Pengampu: Syahru Rahmayuda, M.Kom

## Tujuan Proyek
Membangun REST API Perpustakaan menggunakan PHP modern, Composer, PSR-4, OOP, SOLID, MVC, MySQL, dan testing. Proyek juga mencakup frontend terpisah menggunakan Tailwind CSS dan JavaScript untuk mengelola Buku dan Kategori.

## Sumber Acuan
1. `LKS_REFERENCE.md` adalah transkripsi terstruktur instruksi LKS 20 langkah. Ikuti urutan, nama langkah, bukti screenshot, dan checklist di dalamnya.
2. `MODUL_REFERENCE.md` berisi ringkasan teknis dari modul praktikum. Gunakan sebagai panduan implementasi kode.
3. Jika ada perbedaan antara ringkasan dan instruksi spesifik LKS, **utamakan instruksi LKS**. Jangan mengarang persyaratan yang tidak tercantum.
4. Jika informasi yang diperlukan tidak ditemukan di kedua referensi, tandai sebagai `PERLU KONFIRMASI`, jelaskan kekurangannya, dan jangan menganggapnya sebagai instruksi dosen.

## Aturan Kerja OpenCode
- Sebelum mulai, baca `README.md`, `LKS_REFERENCE.md`, `MODUL_REFERENCE.md`, dan `PROJECT_STATUS.md`.
- Periksa seluruh isi workspace dan git status sebelum membuat, menghapus, atau mengubah file.
- Jangan menimpa pekerjaan yang sudah ada. Pertahankan kode dan struktur yang masih benar.
- Kerjakan **satu langkah LKS dalam satu waktu**, sesuai urutan 1 sampai 20.
- Jangan melanjutkan ke langkah berikutnya sebelum pengguna menyetujui hasil langkah saat ini.
- Sebelum menulis kode, jelaskan singkat file yang akan dibuat/diubah dan tujuannya.
- Implementasikan sesuai arsitektur modul: PSR-4, OOP, SOLID, Repository, Service Layer, Dependency Injection, Front Controller, MVC, PDO, dan MySQL.
- Jangan menambah framework atau dependency yang tidak diminta tanpa alasan kuat dan persetujuan pengguna.
- Setelah implementasi, jalankan pemeriksaan/test yang relevan. Jangan menyatakan berhasil jika belum benar-benar diuji.
- Jangan menghapus database, data, file, atau konfigurasi pengguna tanpa izin.
- Jangan menampilkan atau menyalin rahasia dari `.env`.
- Jangan pernah commit `.env`, `vendor/`, atau file sensitif.
- Jika perintah terminal berisiko mengubah data, jelaskan dampaknya dahulu dan minta persetujuan.
- Jika lingkungan tidak memiliki PHP, Composer, MySQL, cURL, jq, atau Postman, laporkan keterbatasannya. Jangan memalsukan hasil pengujian.
- Jangan membuat screenshot palsu. Beri tahu pengguna apa yang perlu di-screenshot dan nama file yang tepat.

## Teknologi dan Persyaratan
- PHP 8.0+
- Composer 2.x
- MySQL 5.7+ atau MariaDB 10.4+
- PDO MySQL
- PSR-4 autoload (`App\\` → `src/`)
- HTML, Tailwind CSS CDN, CSS tambahan, JavaScript modular/ES Modules, Fetch API
- cURL dan jq untuk skrip uji; Postman/Insomnia untuk pengujian manual
- Browser modern

## Struktur Proyek yang Diharapkan

```text
pbo-lks-nim-nama/
├── README.md
├── LKS_REFERENCE.md
├── MODUL_REFERENCE.md
├── PROJECT_STATUS.md
├── .gitignore
├── rest-api-perpustakaan/
│   ├── .env                 # lokal saja, jangan commit
│   ├── .env.example
│   ├── composer.json
│   ├── public/
│   │   └── index.php
│   ├── src/
│   │   ├── Config/Env.php
│   │   ├── Database/Connection.php
│   │   ├── Core/
│   │   │   ├── Request.php
│   │   │   ├── Response.php
│   │   │   ├── Router.php
│   │   │   └── Container.php
│   │   ├── Models/
│   │   ├── Contracts/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   └── Controllers/
│   ├── database/schema.sql
│   └── tests/api_test.sh
├── frontend-perpustakaan/
│   ├── index.html
│   ├── css/custom.css
│   └── js/
│       ├── config.js
│       ├── api.js
│       ├── utils.js
│       ├── app.js
│       └── pages/
│           ├── dashboard.js
│           ├── books.js
│           └── categories.js
├── pertemuan-01/
├── pertemuan-02/
├── pertemuan-03/
├── pertemuan-04/
├── pertemuan-05/
├── pertemuan-06/
├── pertemuan-07/
└── screenshot-final/
    ├── dashboard.png
    ├── halaman-buku.png
    └── halaman-kategori.png
```

Struktur di atas adalah target organisasi. Jangan memindahkan proyek yang sudah ada tanpa memeriksa workspace dan meminta persetujuan.

## Perintah Dasar

Backend:
```bash
cd rest-api-perpustakaan
composer dump-autoload -o
cp .env.example .env
# Sesuaikan kredensial database di .env
mysql -u root -p < database/schema.sql
php -S localhost:8000 -t public
```

Frontend, pada terminal terpisah:
```bash
cd frontend-perpustakaan
php -S localhost:3000
```

Buka `http://localhost:3000`.

Untuk Windows, jika `cp` tidak tersedia:
- CMD: `copy .env.example .env`
- PowerShell: `Copy-Item .env.example .env`

## Endpoint yang Harus Tersedia

### Buku
| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/books` | Daftar buku |
| GET | `/api/books/{id}` | Detail buku |
| POST | `/api/books` | Tambah buku |
| PUT | `/api/books/{id}` | Ubah buku |
| DELETE | `/api/books/{id}` | Hapus buku |

### Kategori
| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/categories` | Daftar kategori |
| GET | `/api/categories/{id}` | Detail kategori |
| POST | `/api/categories` | Tambah kategori |
| PUT | `/api/categories/{id}` | Ubah kategori |
| DELETE | `/api/categories/{id}` | Hapus kategori |

## Format Screenshot dan Git
Nama file harus mengikuti `LKS_REFERENCE.md`, misalnya `pertemuan-01/langkah-01.png`. Setiap langkah yang selesai:
1. Verifikasi hasil.
2. Minta pengguna mengambil screenshot asli.
3. Pastikan file screenshot tersimpan pada folder dan nama yang ditentukan.
4. Commit screenshot dan perubahan terkait jika pengguna mengizinkan.

Contoh:
```bash
git add pertemuan-01/langkah-01.png
git commit -m "Pertemuan 1 - Langkah 1: Cek lingkungan"
git push origin main
```

## Fitur Aplikasi

### Backend REST API
- CRUD Buku: `GET/POST/PUT/DELETE /api/books` dan `GET /api/books/{id}`
- CRUD Kategori: `GET/POST/PUT/DELETE /api/categories` dan `GET /api/categories/{id}`
- Validasi input dengan status `422` dan data tidak ditemukan dengan status `404`
- Response JSON dengan envelope `success`, `message`, dan `data`
- CORS aktif untuk frontend di port `3000`

### Frontend
- Dashboard: total buku, total kategori, total stok, dan lima buku terbaru
- Halaman Buku: tabel dan modal tambah/edit/hapus
- Halaman Kategori: tabel dan modal tambah/edit/hapus
- Navigasi hash router dan notifikasi toast

## Cara Menjalankan

### Backend
```bash
cd rest-api-perpustakaan
composer dump-autoload -o
cp .env.example .env
# Sesuaikan kredensial MySQL pada .env
mysql -u root -p < database/schema.sql
php -S localhost:8000 -t public
```

### Frontend
```bash
cd frontend-perpustakaan
php -S localhost:3000
```

Buka `http://localhost:3000`.

### Pengujian
```bash
cd rest-api-perpustakaan
./tests/api_test.sh
```

Skrip pengujian membutuhkan `jq`. Jika `jq` belum ada di `PATH`, letakkan `jq.exe` di `rest-api-perpustakaan/tools/`.

## Bukti Screenshot

| Pertemuan | Langkah | File |
|---|---:|---|
| 1 | 1–5 | `pertemuan-01/langkah-01.png` s.d. `pertemuan-01/langkah-05.png` |
| 2 | 6–9 | `pertemuan-02/langkah-06.png` s.d. `pertemuan-02/langkah-09.png` |
| 3 | 10–11 | `pertemuan-03/langkah-10.png` s.d. `pertemuan-03/langkah-11.png` |
| 4 | 12 | `pertemuan-04/langkah-12.png` |
| 5 | 13–15 | `pertemuan-05/langkah-13.png` s.d. `pertemuan-05/langkah-15.png` |
| 6 | 16–18 | `pertemuan-06/langkah-16.png` s.d. `pertemuan-06/langkah-18.png` |
| 7 | 19–20 | `pertemuan-07/langkah-19.png` s.d. `pertemuan-07/langkah-20.png` |

Screenshot aplikasi final:
- `screenshot-final/dashboard.png`
- `screenshot-final/halaman-buku.png`
- `screenshot-final/halaman-kategori.png`

> Catatan: Langkah 11 diuji dengan cURL karena Postman/Insomnia tidak terpasang di lingkungan ini. Screenshot tetap menampilkan request dan response asli dari server.

## Finalisasi
- 20 langkah LKS terdokumentasi dan memiliki bukti screenshot.
- Backend CRUD Buku dan Kategori berjalan.
- Frontend Dashboard, Buku, dan Kategori terintegrasi.
- Tiga screenshot final berada di `screenshot-final/`.
- `.gitignore` mengecualikan `vendor/`, `.env`, `.DS_Store`, dan `*.log`.
- README berisi identitas, fitur, cara menjalankan backend/frontend, dan tautan bukti pertemuan.
- Commit final dan tag `v1.0-lks-final` sudah di-push ke https://github.com/aerthaze/pbo-lks-20251035-farras
- Link repository dikirim kepada dosen melalui LMS/email.

## Cara OpenCode Melanjutkan
Setelah membaca semua dokumen, periksa `PROJECT_STATUS.md` dan kondisi workspace. Tentukan langkah pertama yang belum selesai. Sampaikan rencana singkat, lalu tunggu persetujuan pengguna sebelum mengerjakan langkah tersebut.
