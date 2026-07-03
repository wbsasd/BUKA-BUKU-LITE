# Admin Report (Laporan Admin)

## Route
- GET `/admin/laporan` (role: admin)
- GET `/admin/laporan/export/pdf` (role: admin)
- GET `/admin/laporan/export/excel` (role: admin)

## Filter query string (GET)
- `start_date` (nullable, date)
- `end_date` (nullable, date, after_or_equal)
- `status` (nullable, `dipinjam|dikembalikan`)

## Definisi data
- Data **Pengembalian** di laporan = `borrowings` dengan:
  - `status = "dikembalikan"`
  - `return_date` terisi

## Catatan
File ini hanya dokumentasi internal.

