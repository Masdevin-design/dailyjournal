<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $nama_gambar = $_FILES['image']['name'];
    if ($nama_gambar != '') {
        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $nama_gambar);
        $image = $nama_gambar;
    }
    
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $query = "UPDATE gallery SET title=?, description=?, image=? WHERE id=?";
    } 
    else {
        $query = "INSERT INTO gallery (title, description, image, username) VALUES (?, ?, ?, ?)";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
}

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['image'];
    
    if ($gambar != '') { 
        unlink("img/" . $gambar); 
    }
    
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>

<div class="container">
    <h3>Gallery Management</h3>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <button data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Gallery
            </button>
        </div>
        <div class="col-md-6">
            <input type="text" id="search" placeholder="Cari gallery...">
        </div>
    </div>
    
    <div id="gallery_data"></div>
    
    <div class="modal fade" id="modalTambah">
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" required>
            <textarea name="description"></textarea>
            <input type="file" name="image" required>
            <button type="submit" name="simpan">Simpan</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    function load_gallery_data(keyword){
        $.ajax({
            method: "POST",
            url: "gallery_data.php",
            data: { keyword: keyword },
            success: function(hasil){
                $('#gallery_data').html(hasil);
            }
        });
    }
    
    load_gallery_data();
    
    $('#search').keyup(function(){
        load_gallery_data($(this).val());
    });
});
</script>