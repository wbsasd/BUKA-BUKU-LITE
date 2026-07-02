# PROJECT BLUEPRINT
# BUKA BUKU LITE

## 1. INFORMASI PROYEK

### Nama Proyek
Buka Buku Lite

### Deskripsi
Buka Buku Lite adalah sistem perpustakaan digital berbasis web yang memungkinkan pengguna mencari, membaca, dan mengelola akses e-book serta peminjaman buku digital secara online.

### Tujuan
- Mempermudah pengelolaan perpustakaan.
- Mengurangi proses pencatatan manual.
- Menyediakan akses buku digital.
- Memudahkan proses peminjaman dan pengembalian.

---

# 2. TEKNOLOGI

## Backend
- PHP 8.2
- Laravel 12

## Database
- MySQL

## Frontend
- Blade Template
- Bootstrap 5

## Tools
- Composer
- Git
- VS Code

---

# 3. ROLE PENGGUNA

## Admin

Hak akses:

- Login
- Dashboard
- CRUD Buku
- CRUD Kategori
- Kelola Peminjaman
- Kelola Pengembalian
- Upload PDF Buku
- Lihat Laporan

## Pengguna (Umum)

Hak akses:

- Akses web perpustakaan digital
- Pilih buku / buka detail buku
- Membaca e-book dalam mode trial
- Login
- Register akun (jika diperlukan)
- Meminjam buku
- Lihat Riwayat Peminjaman

## Anggota Premium

Hak akses:

- Login
- Akses baca penuh e-book
- Meminjam buku (sesuai aturan peminjaman)
- Lihat Riwayat Peminjaman

---

# 4. FITUR SISTEM

## Modul Autentikasi

### Admin
- Login
- Logout

### Pengguna (Umum)
- Login
- Logout

### Anggota Premium
- Login
- Logout

---

## Modul Buku

Fitur:

- Tambah Buku
- Edit Buku
- Hapus Buku
- Detail Buku
- Upload Cover
- Upload PDF
- Stok Buku

Field:

- Judul
- Penulis
- Penerbit
- Tahun Terbit
- Deskripsi
- Kategori
- Cover
- File PDF
- Stok

---

## Modul Kategori

Fitur:

- Tambah Kategori
- Edit Kategori
- Hapus Kategori
- Daftar Kategori

---

## Modul Peminjaman

Fitur:

- Ajukan Peminjaman
- Persetujuan Admin
- Pengembalian Buku
- Status Peminjaman

Status:

- Dipinjam
- Dikembalikan

---

## Modul E-Book Reader

Fitur:

- Membaca PDF langsung di browser
- Trial: akses maksimal 5 halaman lalu lock
- Pop up rekomendasi pendaftaran akun agar bisa meminjam buku dan membaca full
- Membaca full e-book:
  - Anggota Premium memiliki akses baca penuh
  - Pengguna Umum (tanpa Premium) hanya memiliki akses trial

---

## Modul Laporan

Laporan:

- Data Buku
- Data Pengguna (jika diperlukan)
- Data Peminjaman
- Data Pengembalian

Export:

- PDF
- Excel

---

# 5. STRUKTUR DATABASE

## users

| Field | Type |
|---------|---------|
| id | bigint |
| name | varchar |
| email | varchar |
| password | varchar |
| role | enum(admin, pengguna, premium) |

---

## categories

| Field | Type |
|---------|---------|
| id | bigint |
| name | varchar |

---

## books

| Field | Type |
|---------|---------|
| id | bigint |
| category_id | bigint |
| title | varchar |
| author | varchar |
| publisher | varchar |
| publication_year | year |
| description | text |
| cover_image | varchar |
| file_pdf | varchar |
| stock | integer |

---

## borrowings

| Field | Type |
|---------|---------|
| id | bigint |
| user_id | bigint |
| book_id | bigint |
| borrow_date | date |
| return_date | date |
| status | varchar |

---

# 6. RELASI DATABASE

categories
↓
books

users
↓
borrowings

books
↓
borrowings

---

# 7. ATURAN BISNIS

- Buku tidak dapat dipinjam jika stok = 0.
- Setiap peminjaman mengurangi stok buku.
- Pengembalian buku menambah stok buku.
- Pengguna umum hanya dapat membaca e-book dalam mode trial maksimal 5 halaman.
- Setelah trial mencapai batas, konten dikunci (lock) dan muncul pop up rekomendasi untuk mendaftar akun / upgrade.
- Anggota premium memiliki akses membaca full e-book.
- PDF hanya dapat diakses oleh pengguna yang login dengan izin sesuai (trial atau premium).
- Admin dapat mengakses seluruh data.

---

# 8. STANDAR UI

Tema:

- Bersih
- Modern
- Responsif

Bahasa:

- Indonesia

Komponen:

- Navbar
- Sidebar
- Dashboard Card
- Data Table
- Form CRUD
- Modal Konfirmasi

---

# 9. KEAMANAN

- Password menggunakan Hash Laravel.
- Validasi Form Request.
- Middleware Authentication.
- Middleware Role.
- CSRF Protection Laravel.

---

# 10. ROADMAP DEVELOPMENT

## Phase 1
Setup Laravel

## Phase 2
Authentication

## Phase 3
CRUD Kategori

## Phase 4
CRUD Buku

## Phase 5
Peminjaman

## Phase 6
E-Book Reader

## Phase 7
Laporan

## Phase 8
Testing

## Phase 9
Deployment

---

# 11. DEFINISI SELESAI (DONE)

Sebuah fitur dianggap selesai jika:

- Migration selesai
- Model selesai
- Controller selesai
- Route selesai
- View selesai
- Validasi selesai
- Testing berhasil
- Tidak ada error pada Laravel Log

# AI DEVELOPMENT RULES

Sebelum membuat kode, selalu baca blueprint.md.

Aturan:

1. Jangan membuat fitur di luar blueprint.
2. Jangan mengubah database tanpa persetujuan.
3. Gunakan Laravel Best Practice.
4. Gunakan MVC Pattern.
5. Gunakan Eloquent ORM.
6. Gunakan Form Request Validation.
7. Gunakan Resource Controller jika memungkinkan.
8. Berikan kode lengkap dan siap pakai.
9. Berikan command artisan yang diperlukan.
10. Jelaskan file yang harus dibuat atau diubah.
11. Gunakan Bahasa Indonesia untuk UI.
12. Pastikan kompatibel dengan PHP 8.2 dan Laravel 12.

