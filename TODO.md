# TODO

- [ ] Audit struktur borrowings & filter status (done)
- [ ] Update User Dashboard controller: kirim `borrowingCount` untuk user yang login (status != 'returned')
- [ ] Tambahkan route endpoint JSON `dashboard/borrowings/count` yang mengembalikan count aktif user
- [ ] Update `resources/views/dashboard.blade.php`: bungkus nilai card dengan elemen id yang sama, tanpa mengubah layout/CSS
- [ ] Tambahkan script polling ringan untuk memperbarui angka card setiap beberapa detik
- [ ] Pastikan CSRF token dan auth middleware sesuai

