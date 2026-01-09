<?php
session_start();
include "koneksi.php";

// Jika sudah login, langsung ke admin
if (isset($_SESSION['username'])) {
    header("location:admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];
    $password = md5($_POST['pass']);

    $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $_SESSION['username'] = $row['username'];
        header("location:admin.php");
    } else {
        header("location:login.php?error=1");
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | My Daily Journal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f1f4f8 100%);
            display: flex;
            align-items: center;
        }

        .login-card {
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
        }

        .login-icon {
            color: #2563eb;
        }

        .login-title {
            font-weight: 700;
            letter-spacing: .5px;
            color: #0f172a;
        }

        .form-control {
            border-radius: 12px;
        }

        .btn-login {
            background: #2563eb;
            border: none;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #1e40af;
        }

        .error-text {
            color: #dc2626;
            font-size: .9rem;
        }

        body::before {
        content: none !important;
        }

        .card,
        .login-card {
            position: relative;
            z-index: 10;
        }

        body {
            pointer-events: auto !important;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-5 col-lg-4">
            <div class="card login-card border-0">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle display-4 login-icon"></i>
                        <h5 class="login-title mt-2">My Daily Journal</h5>
                        <small class="text-muted">Admin Panel</small>
                        <hr>
                    </div>

                    <?php if (isset($_GET['error'])) { ?>
                        <div class="text-center mb-3 error-text">
                            Username atau password salah
                        </div>
                    <?php } ?>

                    <form action="" method="post">
                        <input
                            type="text"
                            name="user"
                            class="form-control mb-3 py-2"
                            placeholder="Username"
                            required
                        />

                        <input
                            type="password"
                            name="pass"
                            class="form-control mb-4 py-2"
                            placeholder="Password"
                            required
                        />

                        <div class="d-grid">
                            <button class="btn btn-login text-white py-2">
                                Login
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
