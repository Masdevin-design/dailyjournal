<?php
include "koneksi.php";

$username = $_SESSION['username'];

// Ambil data user
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update profile
if (isset($_POST['update_profile'])) {
    $current_password = md5($_POST['current_password']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $foto = $user['foto'];
    $nama_foto = $_FILES['foto']['name'];
    
    // Upload foto baru
    if ($nama_foto != '') {
        $ekstensi = pathinfo($nama_foto, PATHINFO_EXTENSION);
        $nama_file_unik = time() . '_' . uniqid() . '.' . $ekstensi;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], 'img/' . $nama_file_unik)) {
            if ($foto != '' && file_exists('img/' . $foto)) {
                unlink('img/' . $foto);
            }
            $foto = $nama_file_unik;
        }
    }
    
    // Update tanpa password
    if (empty($new_password)) {
        $stmt = $conn->prepare("UPDATE user SET foto = ? WHERE username = ?");
        $stmt->bind_param("ss", $foto, $username);
    } 
    // Update dengan password
    else {
        if ($current_password !== $user['password']) {
            echo "<script>alert('Password saat ini salah!');</script>";
        } elseif ($new_password !== $confirm_password) {
            echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
        } else {
            $hashed_password = md5($new_password);
            $stmt = $conn->prepare("UPDATE user SET password = ?, foto = ? WHERE username = ?");
            $stmt->bind_param("sss", $hashed_password, $foto, $username);
        }
    }
    
    if (isset($stmt) && $stmt->execute()) {
        echo "<script>alert('Profile berhasil diperbarui!'); window.location.reload();</script>";
    }
    if (isset($stmt)) $stmt->close();
}
?>

<div class="container">
    <div class="mb-4">
        <h3 class="page-title mb-2">Profile Management</h3>
        <div class="page-divider"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <?php if ($user['foto'] && file_exists('img/' . $user['foto'])): ?>
                                <img src="img/<?= $user['foto'] ?>" 
                                     class="rounded-circle mb-3" 
                                     width="150" 
                                     height="150"
                                     style="object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px;">
                                    <i class="bi bi-person display-4 text-white"></i>
                                </div>
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($user['username']) ?></h4>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" 
                                   placeholder="Isi hanya jika ingin ganti password">
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   placeholder="Password baru">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   placeholder="Ulangi password baru">
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Upload foto baru untuk mengganti foto profil</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Profile
                            </button>
                            <a href="admin.php?page=dashboard" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>