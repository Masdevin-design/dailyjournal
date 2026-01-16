<?php
include "koneksi.php";

$hlm = (isset($_POST['hlm'])) ? (int)$_POST['hlm'] : 1;
$limit = 4;
$limit_start = ($hlm - 1) * $limit;
$limit_start = ($limit_start < 0) ? 0 : $limit_start;
$no = $limit_start + 1;

$sql = "SELECT * FROM gallery ORDER BY uploaded_at DESC LIMIT $limit_start, $limit";
$hasil = $conn->query($sql);
?>

<table class="table table-hover">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th class="w-25">Judul</th>
      <th class="w-50">Deskripsi</th>
      <th class="w-25">Gambar</th>
      <th class="w-25">Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php
  while ($row = $hasil->fetch_assoc()) {
  ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td>
            <strong><?php echo htmlspecialchars($row["title"]); ?></strong>
            <br>pada : <?php echo $row["uploaded_at"]; ?>
        </td>
        <td><?php echo htmlspecialchars($row["description"]); ?></td>
        <td>
            <?php
            if ($row["image"] != '') {
                if (file_exists('img/' . $row["image"] . '')) {
            ?>
                    <img src="img/<?php echo htmlspecialchars($row["image"]); ?>" width="100">
            <?php
                }
            }
            ?>
        </td>
        <td>
        <a href="#" title="edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row["id"]; ?>">
            <i class="bi bi-pencil"></i>
        </a>
        <a href="#" title="delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?php echo $row["id"]; ?>">
            <i class="bi bi-x-circle"></i>
        </a>

        <!-- Awal Modal Edit -->
        <div class="modal fade" id="modalEdit<?php echo $row["id"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Gallery</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Judul</label>
                                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                <input type="text" class="form-control" name="title" placeholder="Tuliskan Judul Gallery" value="<?php echo htmlspecialchars($row["title"]); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="floatingTextarea2">Deskripsi</label>
                                <textarea class="form-control" placeholder="Tuliskan Deskripsi Gallery" name="description" required><?php echo htmlspecialchars($row["description"]); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Ganti Gambar</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput3" class="form-label">Gambar Lama</label>
                                <?php
                                if ($row["image"] != '') {
                                    if (file_exists('img/' . $row["image"] . '')) {
                                ?>
                                        <br><img src="img/<?php echo htmlspecialchars($row["image"]); ?>" width="100">
                                <?php
                                    }
                                }
                                ?>
                                <input type="hidden" name="image_lama" value="<?php echo $row["image"]; ?>">
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
        <!-- Akhir Modal Edit -->

        <!-- Awal Modal Hapus -->
        <div class="modal fade" id="modalHapus<?php echo $row["id"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Gallery</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Yakin akan menghapus gallery "<strong><?php echo htmlspecialchars($row["title"]); ?></strong>"?</label>
                                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                <input type="hidden" name="image" value="<?php echo $row["image"]; ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">batal</button>
                            <input type="submit" value="hapus" name="hapus" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Akhir Modal Hapus -->
        </td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>

<?php 
$sql1 = "SELECT COUNT(*) as total FROM gallery";
$hasil1 = $conn->query($sql1);
$row = $hasil1->fetch_assoc();
$total_records = $row['total'];
?>
<p>Total gallery : <?php echo $total_records; ?></p>
<nav class="mb-2">
    <ul class="pagination justify-content-end">
    <?php
        $jumlah_page = ceil($total_records / $limit);
        $jumlah_number = 1;
        $start_number = ($hlm > $jumlah_number)? $hlm - $jumlah_number : 1;
        $end_number = ($hlm < ($jumlah_page - $jumlah_number))? $hlm + $jumlah_number : $jumlah_page;

        if($hlm == 1){
            echo '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
            echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
        } else {
            $link_prev = ($hlm > 1)? $hlm - 1 : 1;
            echo '<li class="page-item halaman" id="1"><a class="page-link" href="#">First</a></li>';
            echo '<li class="page-item halaman" id="'.$link_prev.'"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
        }

        for($i = $start_number; $i <= $end_number; $i++){
            $link_active = ($hlm == $i)? ' active' : '';
            echo '<li class="page-item halaman '.$link_active.'" id="'.$i.'"><a class="page-link" href="#">'.$i.'</a></li>';
        }

        if($hlm == $jumlah_page){
            echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
            echo '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
        } else {
        $link_next = ($hlm < $jumlah_page)? $hlm + 1 : $jumlah_page;
            echo '<li class="page-item halaman" id="'.$link_next.'"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
            echo '<li class="page-item halaman" id="'.$jumlah_page.'"><a class="page-link" href="#">Last</a></li>';
        }
    ?>
    </ul>
</nav>