<?php
$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'myaddress';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
