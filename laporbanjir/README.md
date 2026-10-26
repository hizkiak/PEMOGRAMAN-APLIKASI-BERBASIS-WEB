# LaporBanjir

Aplikasi pelaporan banjir untuk warga dan pengelolaan laporan oleh petugas BPBD Kabupaten Bandung.

## Menjalankan aplikasi

1. Buat database MySQL bernama `db_laporbanjir` melalui phpMyAdmin.
2. Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` di `.env` dengan akun MySQL lokal.
3. Jalankan `php artisan migrate --seed` untuk membuat tabel serta akun awal petugas.
4. Jalankan `php artisan storage:link` agar foto di `storage/app/public` bisa diakses dari web.
5. Jalankan aplikasi dengan `php artisan serve`.

Form warga tersedia pada `/`. Halaman pengelolaan ada pada `/laporan` dan dilindungi middleware `auth`.

### Akun petugas awal

- Email: `petugas@laporbanjir.test`
- Kata sandi: `password`

Ganti kata sandi akun awal sebelum aplikasi digunakan di lingkungan nyata.

## Fitur dan penyimpanan foto

- Data laporan tersimpan di tabel `laporans`; kolom `foto` berisi path relatif file, misalnya `foto-banjir/nama-file.jpg`.
- Foto bukti menerima JPG/JPEG/PNG dengan ukuran maksimal 2 MB dan disimpan pada disk `public`.
- Saat foto diganti atau laporan dihapus, file foto terkait juga dihapus dari disk `public`.
- Daftar laporan memakai pagination; petugas dapat melihat detail, mengubah data/status, dan menghapus laporan.

## Pilihan pengerjaan

Autentikasi dibuat secara manual menggunakan autentikasi bawaan Laravel. Pilihan ini menjaga cakupan aplikasi tetap ringan karena kebutuhan tugas hanya login petugas dan perlindungan halaman pengelolaan; tidak memerlukan halaman registrasi atau komponen antarmuka tambahan dari Laravel Breeze. Validasi kredensial, regenerasi sesi, logout, dan middleware `auth` tetap menggunakan mekanisme Laravel.
