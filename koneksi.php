<?php
date_default_timezone_set('Asia/Jakarta');

$servername = "";
$username = "";
$password = "";
$db = "";

// Cek apakah sedang berjalan di Localhost (Laptop) atau Hosting
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    // === KONEKSI LOKAL (XAMPP) ===
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "webdailyjournal"; // Pastikan nama DB lokal benar
} else {
    // === KONEKSI HOSTING (InfinityFree) ===
    $servername = "sql100.infinityfree.com";
    $username = "if0_40866012";
    $password = "vkoLDVddUT7w"; // Password hostingmu 
    $db = "if0_40866012_webdailyjournal";
}

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>