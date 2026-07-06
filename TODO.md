# TODO - Pengamanan PDF Reader (Laravel)

- [x] Tambahkan endpoint `GET /reader/{book}/pdf` di `routes/web.php`

- [x] Implement method `pdf()` di `PdfReaderController`:

- [x] cek login

- [x] cek buku ada

- [x] cek file ada di `storage/app/private/books`

  - [ ] abort(404/403) sesuai kondisi gagal
- [x] stream PDF pakai `response()->streamDownload()` / `response()->stream()`

- [x] Ubah `resources/views/pdf-reader.blade.php`:

  - [ ] ganti `pdfUrl` agar sumbernya `/reader/{book}/pdf` (bukan `asset('storage/...')`)
  - [ ] tambah JS: disable CTRL+S, CTRL+P, disable klik kanan pada area reader
  - [ ] harden toolbar PDF.js (download/print/open/save) sesuai rule
- [ ] Validasi manual:
  - [ ] PDF terbaca normal
  - [ ] Network: request PDF hanya ke `/reader/{id}/pdf`
  - [ ] URL asli PDF tidak muncul di browser

