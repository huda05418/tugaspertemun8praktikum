<?php
require 'global.php';
require 'common.php';

$values = [
    'cn'              => $_POST['cn'] ?? '',
    'mail'            => $_POST['mail'] ?? '',
    'locality'        => $_POST['locality'] ?? '',
    'description'     => $_POST['description'] ?? '',
    'telephonenumber' => $_POST['telephonenumber'] ?? '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $cn              = trim($_POST['cn'] ?? '');
    $mail            = trim($_POST['mail'] ?? '');
    $locality        = trim($_POST['locality'] ?? '');
    $description     = trim($_POST['description'] ?? '');
    $telephonenumber = trim($_POST['telephonenumber'] ?? '');

    if ($cn === '') {
        $errors[] = 'Nama wajib diisi.';
    }
    if ($mail === '') {
        $errors[] = 'E-mail wajib diisi.';
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format e-mail tidak valid.';
    }

    if (!$errors) {
        $link = db_connect();
        $stmt = mysqli_prepare($link, 'INSERT INTO ' . DB_TABLE . ' (Name, Email, City, Description, Telephone) VALUES (?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sssss', $cn, $mail, $locality, $description, $telephonenumber);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($link);
            flash_set('success', 'Entri berhasil ditambahkan.');
            header('Location: search.php');
            exit;
        }

        $errors[] = 'Gagal menambahkan data: ' . mysqli_error($link);
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }
}

page_header('Tambah Entri', 'add');
?>
<div class="page-head">
    <h1>Tambah Entri Baru</h1>
    <p class="lead">Kolom bertanda <span class="req">*</span> wajib diisi.</p>
</div>

<?php foreach ($errors as $err): ?>
    <div class="alert alert-danger"><?= e($err) ?></div>
<?php endforeach; ?>

<?php address_form($values, 'add.php', 'Simpan Entri', 'main.php'); ?>
<?php
page_footer();
