<?php
session_start();
include "koneksi.php";

// AMBIL DATA GALLERY UNTUK CAROUSEL
$sql_gallery = "SELECT * FROM gallery ORDER BY uploaded_at DESC LIMIT 5";
$result_gallery = $conn->query($sql_gallery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal | Home</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    
    <style>
        body {
            background: linear-gradient(180deg, #ffffff 0%, #f1f4f8 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(90deg, #97682eff, #887547ff) !important;
        }
        .carousel-item img {
            height: 500px;
            object-fit: cover;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">My Daily Journal</a>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['username'])): ?>
                    <a class="nav-link" href="admin.php">Dashboard</a>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- CAROUSEL GALLERY (SOAL 1 - DINAMIS) -->
    <?php if ($result_gallery->num_rows > 0): ?>
    <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- INDICATORS -->
        <div class="carousel-indicators">
            <?php 
            $counter = 0;
            $result_gallery->data_seek(0); // Reset pointer
            while($row = $result_gallery->fetch_assoc()):
            ?>
                <button type="button" data-bs-target="#galleryCarousel" 
                        data-bs-slide-to="<?= $counter ?>" 
                        class="<?= $counter == 0 ? 'active' : '' ?>"></button>
            <?php 
                $counter++;
            endwhile; 
            ?>
        </div>
        
        <!-- SLIDES -->
        <div class="carousel-inner">
            <?php 
            $result_gallery->data_seek(0); // Reset pointer
            $first = true;
            while($row = $result_gallery->fetch_assoc()):
            ?>
            <div class="carousel-item <?= $first ? 'active' : '' ?>">
                <img src="img/<?= $row['image'] ?>" 
                     class="d-block w-100" 
                     alt="<?= $row['title'] ?>">
                <div class="carousel-caption d-none d-md-block">
                    <h5><?= htmlspecialchars($row['title']) ?></h5>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                </div>
            </div>
            <?php 
                $first = false;
            endwhile; 
            ?>
        </div>
        
        <!-- CONTROLS -->
        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <?php else: ?>
    <div class="container text-center py-5">
        <h3>Gallery Empty</h3>
        <p>No gallery images available. Please add images from admin panel.</p>
    </div>
    <?php endif; ?>

    <!-- LATEST ARTICLES -->
    <div class="container mt-5">
        <h2 class="mb-4">Latest Articles</h2>
        <div class="row">
            <?php
            $sql_articles = "SELECT * FROM article ORDER BY tanggal DESC LIMIT 3";
            $result_articles = $conn->query($sql_articles);
            
            while($article = $result_articles->fetch_assoc()):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if($article['gambar']): ?>
                        <img src="img/<?= $article['gambar'] ?>" class="card-img-top" alt="<?= $article['judul'] ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($article['judul']) ?></h5>
                        <p class="card-text"><?= substr(htmlspecialchars($article['isi']), 0, 100) ?>...</p>
                        <small class="text-muted">Posted on <?= $article['tanggal'] ?></small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>