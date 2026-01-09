-- 1. Membuat Tabel User (Untuk Login)
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `foto` text NOT NULL,
  PRIMARY KEY (`id`)
);

-- 2. Membuat Tabel Article (Untuk CRUD Berita)
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` text,
  `isi` text,
  `gambar` text,
  `tanggal` datetime,
  `username` varchar(50),
  PRIMARY KEY (`id`)
);

-- 3. Mengisi Akun Admin (Password: 123456 sudah di-enkripsi MD5)
INSERT INTO `user` (`username`, `password`, `foto`) VALUES
('admin', 'e10adc3949ba59abbe56e057f20f883e', '');

-- 4. Mengisi Contoh Data Artikel (Supaya nanti pas dibuka tidak kosong melompong)
INSERT INTO `article` (`judul`, `isi`, `gambar`, `tanggal`, `username`) VALUES
('Selamat Datang di Web Daily Journal', 'Ini adalah contoh artikel pertama yang dibuat otomatis.', '', NOW(), 'admin');
