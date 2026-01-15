<?php
include "koneksi.php";

$keyword = "";
if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
}

$sql = "SELECT * FROM gallery 
        WHERE title LIKE '%$keyword%' 
        OR description LIKE '%$keyword%' 
        ORDER BY uploaded_at DESC";
$hasil = $conn->query($sql);

?>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = $hasil->fetch_assoc()) {
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row["title"]) ?></td>
            <td><?= htmlspecialchars($row["description"]) ?></td>
            <td>
                <img src="img/<?= $row["image"] ?>" width="100">
            </td>
            <td>
                <button data-bs-toggle="modal" 
                        data-bs-target="#modalEdit<?= $row["id"] ?>">
                    Edit
                </button>
                
                <button data-bs-toggle="modal" 
                        data-bs-target="#modalHapus<?= $row["id"] ?>">
                    Hapus
                </button>
                
                <div class="modal fade" id="modalEdit<?= $row["id"] ?>">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $row["id"] ?>">
                        <input type="text" name="title" value="<?= $row["title"] ?>">
                        <textarea name="description"><?= $row["description"] ?></textarea>
                        <input type="file" name="image">
                        <button type="submit" name="simpan">Update</button>
                    </form>
                </div>
                
                <div class="modal fade" id="modalHapus<?= $row["id"] ?>">
                    <form method="post">
                        <p>Yakin hapus "<?= $row["title"] ?>"?</p>
                        <input type="hidden" name="id" value="<?= $row["id"] ?>">
                        <input type="hidden" name="image" value="<?= $row["image"] ?>">
                        <button type="submit" name="hapus">Ya, Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>