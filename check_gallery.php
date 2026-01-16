<?php
include "koneksi.php";

echo "<h3>Check Gallery Table</h3>";

// 1. Check connection
echo "Database: " . ($conn->connect_error ? "ERROR" : "OK") . "<br>";

// 2. Check table exists
$result = $conn->query("SHOW TABLES LIKE 'gallery'");
echo "Table gallery exists: " . ($result->num_rows > 0 ? "YES" : "NO") . "<br>";

// 3. If exists, show data
if ($result->num_rows > 0) {
    $data = $conn->query("SELECT * FROM gallery");
    echo "Total data: " . $data->num_rows . "<br>";
    
    if ($data->num_rows > 0) {
        echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Image</th></tr>";
        while($row = $data->fetch_assoc()) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['image'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Table exists but empty!";
    }
} else {
    echo "<div style='color:red; font-weight:bold;'>TABLE GALLERY DOES NOT EXIST!</div>";
    echo "<p>Run this SQL in phpMyAdmin:</p>";
    echo "<pre>CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `description` text,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);</pre>";
}

$conn->close();
?>