<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = mysqli_prepare($conn,
    "SELECT Rowid, Name, Email, City, Telephone, Description FROM addrbook WHERE Rowid = ?"
);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$row) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Kontak tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

$errors = [];
$data   = [
    'name'        => $row['Name'],
    'email'       => $row['Email'],
    'city'        => $row['City'] ?? '',
    'telephone'   => $row['Telephone'] ?? '',
    'description' => $row['Description'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']        = trim($_POST['name']        ?? '');
    $data['email']       = trim($_POST['email']       ?? '');
    $data['city']        = trim($_POST['city']        ?? '');
    $data['telephone']   = trim($_POST['telephone']   ?? '');
    $data['description'] = trim($_POST['description'] ?? '');

    if ($data['name'] === '') $errors[] = 'Nama wajib diisi.';
    if ($data['email'] === '') {
        $errors[] = 'E-mail wajib diisi.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format e-mail tidak valid.';
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn,
            "UPDATE addrbook SET Name=?, Email=?, City=?, Telephone=?, Description=?
             WHERE Rowid=?"
        );
        mysqli_stmt_bind_param($stmt, 'sssssi',
            $data['name'], $data['email'],
            $data['city'], $data['telephone'],
            $data['description'], $id
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Data kontak berhasil diperbarui!'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Gagal memperbarui data: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kontak &mdash; Buku Alamat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="index.php" class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="font-bold text-slate-900 leading-tight">Buku Alamat</h1>
                    <p class="text-xs text-slate-500">Perbarui Entri Kontak</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="index.php" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-10 w-full flex-1">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 shadow-sm">
            <div class="mb-6 pb-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Perbarui Data Kontak</h2>
                    <p class="text-xs text-slate-500 mt-1">Ubah informasi kontak di bawah ini lalu klik simpan perubahan.</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-100 text-slate-600">
                    ID: #<?= $id ?>
                </span>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $err): ?>
                            <li><?= h($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="edit.php?id=<?= $id ?>" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="<?= h($data['name']) ?>" required autofocus maxlength="100"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat E-mail <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="<?= h($data['email']) ?>" required maxlength="100"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kota
                        </label>
                        <input type="text" id="city" name="city" value="<?= h($data['city']) ?>" maxlength="50"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label for="telephone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Telepon
                        </label>
                        <input type="text" id="telephone" name="telephone" value="<?= h($data['telephone']) ?>" maxlength="20"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Deskripsi / Catatan Tambahan
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"><?= h($data['description']) ?></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="index.php" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-indigo-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="py-6 border-t border-slate-200 text-center text-xs text-slate-500 bg-white">
        Sistem Manajemen Buku Alamat &middot; Modul Pemrograman Web PHP &amp; MySQL
    </footer>
</body>
</html>
