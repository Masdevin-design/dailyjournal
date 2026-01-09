# Daily Journal — README & Panduan Instalasi

Panduan ini menjelaskan cara menjalankan aplikasi web sederhana yang ada di folder ini.

**Prasyarat**

- Windows dengan XAMPP (Apache + MySQL)
- Browser dan akses ke `http://localhost`

**Langkah instalasi singkat**

1. Salin folder ke `htdocs` XAMPP
   - Tempatkan folder ini di `C:\xampp\htdocs\DevinProjek\crud-php`.
2. Jalankan XAMPP: aktifkan `Apache` dan `MySQL`.
3. Buat database baru di phpMyAdmin
   - Buka `http://localhost/phpmyadmin`.
   - Buat database baru, contoh nama: `devinprojek` (Anda bebas memilih nama lain).
4. Import skrip SQL (struktur + data contoh)

   - File SQL ada di: [crud-php/sql/init.sql](crud-php/sql/init.sql#L1-L99)
   - Di phpMyAdmin: pilih database yang dibuat -> tab `Import` -> pilih file `init.sql` -> `Go`.

5. Cek koneksi database

   - Buka file koneksi: [crud-php/koneksi.php](crud-php/koneksi.php#L1-L50) dan pastikan `host`, `username`, `password`, dan `database` sesuai dengan instalasi XAMPP Anda.
   - Jika file `koneksi.php` sudah cocok dengan `root` tanpa password dan DB bernama seperti yang Anda buat, tidak perlu ubah.

6. Akses aplikasi
   - Buka `http://localhost/DevinProjek/crud-php/` di browser.
   - Halaman utama: [crud-php/index.php](crud-php/index.php#L1-L50)
   - Login: [crud-php/login.php](crud-php/login.php#L1-L50)

**Akun default**

- Username: `admin`
- Password: `123456` (disimpan di database dalam bentuk MD5: `e10adc3949ba59abbe56e057f20f883e`)

**Struktur file penting**

- [crud-php/index.php](crud-php/index.php#L1-L50): Halaman publik daftar artikel.
- [crud-php/article.php](crud-php/article.php#L1-L50): CRUD artikel (tampilan/aksi, tergantung implementasi di file).
- [crud-php/dashboard.php](crud-php/dashboard.php#L1-L50): Panel admin setelah login.
- [crud-php/login.php](crud-php/login.php#L1-L50): Form login.
- [crud-php/logout.php](crud-php/logout.php#L1-L50): Logout.
- [crud-php/koneksi.php](crud-php/koneksi.php#L1-L50): Pengaturan koneksi database.
- Folder `img/`: menyimpan gambar artikel atau foto user.

**Catatan keamanan & penggunaan**

- Password di database saat ini menggunakan MD5 (tidak aman untuk produksi). Untuk tujuan demo/localhost ini diterima, tapi jangan gunakan di sistem nyata.
- Jika ingin mengganti akun admin, cukup ubah tabel `user` melalui phpMyAdmin atau tambahkan pengguna baru.
- Jangan ubah kode kecuali Anda paham; jika perlu bantuan untuk mengubah konfigurasi, saya bisa bantu langkahnya.

**Troubleshooting singkat**

- Jika muncul error koneksi database: periksa pengaturan di [crud-php/koneksi.php](crud-php/koneksi.php#L1-L50) dan pastikan database sudah dibuat serta user MySQL benar.
- Jika halaman kosong atau tidak menemukan file: pastikan folder dipanggil via `http://localhost/DevinProjek/crud-php/` dan file ada di lokasi tersebut.
