<?php
declare(strict_types=1);

const DB_HOST  = 'localhost';
const DB_USER  = 'root';
const DB_PASS  = '';
const DB_NAME  = 'myaddress';
const DB_TABLE = 'addrbook';

mysqli_report(MYSQLI_REPORT_OFF);

function db_connect(): mysqli
{
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$link) {
        die('Koneksi database gagal: ' . htmlspecialchars(mysqli_connect_error(), ENT_QUOTES, 'UTF-8'));
    }
    mysqli_set_charset($link, 'utf8mb4');
    return $link;
}
