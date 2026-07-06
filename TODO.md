# TODO - Membership Status (pending/active/rejected)

- [x] Pastikan `users.membership_status` ada (migration sudah ada)
- [x] Register user baru otomatis `membership_status=pending`
- [x] Admin menu “Permintaan Registrasi” menampilkan user pending
- [x] Admin approve → `active`, reject → `rejected`
- [x] User non-active ditolak login dengan pesan yang benar
- [x] User non-active tidak boleh mengakses peminjaman/reader (pakai middleware `membership.active`)
- [x] Guest klik “Pinjam Buku” diarahkan ke halaman Register via middleware
- [ ] Jalankan test manual:
  - Register → Login (harus redirect/ditolak + pesan)
  - Guest akses `/reader/{id}` → harus ke register
  - Admin approve user → user bisa akses `/reader/{id}`

