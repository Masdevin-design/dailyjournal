<?php
session_start();
require_once 'koneksi.php';
?>
<div class="container">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Gallery
    </button>
    <div class="row">
        <div class="table-responsive" id="gallery_data">
            
        </div>
        <!-- Awal Modal Tambah-->
        <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Gallery</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Judul</label>
                                <input type="text" class="form-control" name="title" placeholder="Tuliskan Judul Gallery" required>
                            </div>
                            <div class="mb-3">
                                <label for="floatingTextarea2">Deskripsi</label>
                                <textarea class="form-control" placeholder="Tuliskan Deskripsi Gallery" name="description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Gambar</label>
                                <input type="file" class="form-control" name="image" required>
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
        <!-- Akhir Modal Tambah-->
    </div>
</div>

<script>
$(document).ready(function(){
    load_data();
    function load_data(hlm){
        $.ajax({
            url : "gallery_data.php",
            method : "POST",
            data : {
                hlm: hlm
            },
            success : function(data){
                $('#gallery_data').html(data);
            }
        })
    }
    
    $(document).on('click', '.halaman', function(){
        var hlm = $(this).attr("id");
        load_data(hlm);
    });
});
</script>

<?php
// HAPUS baris ini: include "upload_foto.php";

//jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $uploaded_at = date("Y-m-d H:i:s");
    $username = $_SESSION['username'] ?? 'admin'; // Ambil dari session
    $image = '';
    $nama_image = $_FILES['image']['name'];

    //jika ada file yang dikirim  
    if ($nama_image != '') {
        $target_dir = "img/";
        $target_file = $target_dir . basename($nama_image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validasi file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>
                alert('Hanya file JPG, JPEG, PNG & GIF yang diizinkan!');
                document.location='admin.php?page=gallery';
            </script>";
            die;
        }
        
        if ($_FILES['image']['size'] > $max_size) {
            echo "<script>
                alert('Ukuran file maksimal 2MB!');
                document.location='admin.php?page=gallery';
            </script>";
            die;
        }
        
        // Generate nama file unik
        $image_name = time() . '_' . uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $image_name;
        } else {
            echo "<script>
                alert('Gagal mengupload gambar!');
                document.location='admin.php?page=gallery';
            </script>";
            die;
        }
    }

    //cek apakah ada id yang dikirimkan dari form
    if (isset($_POST['id'])) {
        //jika ada id, lakukan update data dengan id tersebut
        $id = $_POST['id'];

        if ($nama_image == '') {
            //jika tidak ganti gambar
            $image = $_POST['image_lama'];
        } else {
            //jika ganti gambar, hapus gambar lama
            if ($_POST['image_lama'] != '' && file_exists("img/" . $_POST['image_lama'])) {
                unlink("img/" . $_POST['image_lama']);
            }
        }

        $stmt = $conn->prepare("UPDATE gallery 
                                SET 
                                title = ?,
                                description = ?,
                                image = ?,
                                uploaded_at = ?,
                                username = ?
                                WHERE id = ?");

        $stmt->bind_param("sssssi", $title, $description, $image, $uploaded_at, $username, $id);
        $simpan = $stmt->execute();
    } else {
        //jika tidak ada id, lakukan insert data baru
        $stmt = $conn->prepare("INSERT INTO gallery (title, description, image, uploaded_at, username)
                                VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("sssss", $title, $description, $image, $uploaded_at, $username);
        $simpan = $stmt->execute();
    }

    if ($simpan) {
        echo "<script>
            alert('Simpan data sukses');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert('Simpan data gagal: " . $conn->error . "');
            document.location='admin.php?page=gallery';
        </script>";
    }

    $stmt->close();
    $conn->close();
}

//jika tombol hapus diklik
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $image = $_POST['image'];

    if ($image != '' && file_exists("img/" . $image)) {
        //hapus file gambar
        unlink("img/" . $image);
    }

    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>
            alert('Hapus data sukses');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert('Hapus data gagal: " . $conn->error . "');
            document.location='admin.php?page=gallery';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>