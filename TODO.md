# TODO - Migration Fix

## Completed
- (pending) 

## To do
1. Audit semua migration di `database/migrations` untuk tabel: `categories`, `books`, `borrowings`, serta `users` role.
2. Jadikan hanya 1 migration final per tabel:
   - `categories`: pilih `2026_07_02_174818_create_categories_table.php`.
   - `books`: pilih `2026_07_02_174818_create_books_table.php` (perbaiki agar kolom match blueprint).
   - `borrowings`: pilih `2026_07_02_174818_create_borrowings_table.php` (tambahkan FK bila diperlukan).
3. Nonaktifkan (no-op) migration duplikat:
   - `2026_07_02_174914_create_categories_table.php`
   - `2026_07_02_174930_create_books_table.php`
   - `2026_07_02_174946_create_borrowings_table.php`
4. Pastikan urutan FK: users -> categories -> books -> borrowings.
5. Pastikan foreign key mengacu ke tabel yang sudah ada dan tipe kolom match.
6. Jalankan `php artisan migrate:fresh` dan verifikasi tidak ada error.
7. Jalankan seeders bila diperlukan.

