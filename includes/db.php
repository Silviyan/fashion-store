<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "fashion_store";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Грешка при свързване с базата данни: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>