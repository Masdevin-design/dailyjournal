<?php
include "koneksi.php"; // Pastikan koneksi di-include

// === LOGIKA SIMPAN & HAPUS TETAP DISINI ===
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    if ($nama_gambar != '') {
        $cek_upload = move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $nama_gambar);
        if ($cek_upload) {
            $gambar = $nama_gambar;
        } else {
            echo "<script>alert('Gagal upload gambar');</script>";
            return;
        }
    }

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        if ($nama_gambar == '') {
            $gambar = $_POST['gambar_lama'];
        } else {
            unlink("img/" . $_POST['gambar_lama']);
        }
        $stmt = $conn->prepare("UPDATE article SET judul=?, isi=?, gambar=?, tanggal=?, username=? WHERE id=?");
        $stmt->bind_param("sssssi", $judul, $isi, $gambar, $tanggal, $username, $id);
        $simpan = $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO article (judul, isi, gambar, tanggal, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $judul, $isi, $gambar, $tanggal, $username);
        $simpan = $stmt->execute();
    }

    if ($simpan) {
        echo "<script>alert('Simpan data sukses'); document.location='admin.php?page=article';</script>";
    } else {
        echo "<script>alert('Simpan data gagal'); document.location='admin.php?page=article';</script>";
    }
    $stmt->close();
}

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];
    if ($gambar != '') { unlink("img/" . $gambar); }
    $stmt = $conn->prepare("DELETE FROM article WHERE id = ?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();
    if ($hapus) {
        echo "<script>alert('Hapus data sukses'); document.location='admin.php?page=article';</script>";
    } else {
        echo "<script>alert('Hapus data gagal'); document.location='admin.php?page=article';</script>";
    }
    $stmt->close();
}
?>

<div class="container">
    <div class="mb-4">
        <h3 class="page-title mb-2">Article</h3>
        <div class="page-divider"></div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </button>
        </div>
        <div class="col-md-6 text-end">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Cari artikel (judul/isi)...">
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive" id="article_data">
        
    </div>

    <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Article</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" placeholder="Tuliskan Judul Artikel" required>
                        </div>
                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi</label>
                            <textarea class="form-control" name="isi" placeholder="Tuliskan Isi Artikel" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="gambar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="simpan" name="simpan" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Fungsi untuk memuat data (load)
    load_data();

    function load_data(keyword){
        $.ajax({
            method: "POST",
            url: "article_data.php",
            data: { keyword: keyword },
            success: function(hasil){
                $('#article_data').html(hasil);
            }
        });
    }

    // Event saat mengetik di kolom pencarian
    $('#search').keyup(function(){
        var search_word = $(this).val();
        load_data(search_word);
    });
});
</script>