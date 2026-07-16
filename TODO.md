# TODO - Revisi Modal Detail Membership Request (Admin)

- [ ] Buat plan fix implementasi modal agar selalu berada paling atas (z-index/backdrop) dan tidak tertutup sidebar/navbar/card
- [ ] Pindahkan / konversi modal detail membership request agar dirender global di bawah `<body>` (jika saat ini berada di dalam card dashboard)
- [ ] Tambahkan CSS khusus Bootstrap 5 modal agar `backdrop` dan `.modal` memiliki z-index lebih tinggi dari seluruh elemen dashboard
- [ ] Audit CSS admin untuk elemen penyebab stacking context (overflow/transform/position z-index) dan sesuaikan seperlunya
- [ ] Pastikan struktur modal tidak membuat stacking context baru (hindari `transform`/`position: relative`/`overflow: hidden/auto` pada parent)
- [x] Verifikasi: modal dapat dibuka/tutup berulang tanpa stuck backdrop

- [ ] Jalankan pengecekan manual secara visual sesuai checklist

