-- ============================================================
--  MODUL 08 - Menghubungkan PHP dengan MySQL (PHP 8 / mysqli)
--  File : schema.sql
--  Cara pakai : impor di phpMyAdmin (tab "SQL") atau:
--               mysql -u root -p < schema.sql
--  Charset    : utf8mb4 | Engine : InnoDB
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. DATABASE LATIHAN DASAR (contoh13.php)
-- ============================================================
CREATE DATABASE IF NOT EXISTS latihdb
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE latihdb;

-- Tabel anggota (latihan dasar)
CREATE TABLE IF NOT EXISTS anggota (
    nomor  INT AUTO_INCREMENT PRIMARY KEY,
    nama   VARCHAR(100) NOT NULL,
    email  VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    kota   VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO anggota (nama, email, alamat, kota) VALUES
('Budi Santoso', 'budi@gmail.com', 'Jl. Merdeka No. 12', 'Semarang'),
('Siti Rahma',   'siti@yahoo.com',  'Jl. Pemuda No. 45',  'Semarang');

-- Tabel contoh_tabel (pemahaman query 3 kolom)
CREATE TABLE IF NOT EXISTS contoh_tabel (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nama  VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO contoh_tabel (nama, email) VALUES
('Budi Santoso', 'budi@gmail.com'),
('Siti Rahma',   'siti@yahoo.com');

-- ============================================================
-- 2. DATABASE APLIKASI ADDRESS BOOK (TUGAS 1)
-- ============================================================
CREATE DATABASE IF NOT EXISTS myaddress
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE myaddress;

CREATE TABLE IF NOT EXISTS addrbook (
    Rowid       INT AUTO_INCREMENT PRIMARY KEY,
    Name        VARCHAR(100) NOT NULL,
    Email       VARCHAR(100) NOT NULL,
    City        VARCHAR(50),
    Description TEXT,
    Telephone   VARCHAR(20),
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO addrbook (Name, Email, City, Description, Telephone) VALUES
('Budi Santoso', 'budi@gmail.com', 'Semarang', 'Rekan kerja di kantor', '0812-3456-7890'),
('Siti Rahma',   'siti@yahoo.com',  'Semarang', 'Teman kuliah',          '0857-1234-5678'),
('Andi Pratama', 'andi@outlook.com', 'Jakarta', 'Klien proyek website',  '0821-9876-5432');

-- ============================================================
-- 3. DATABASE APLIKASI PENCARIAN FILM + LOGIN (TUGAS 2)
-- ============================================================
CREATE DATABASE IF NOT EXISTS filmdb
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE filmdb;

-- Tabel pengguna (fasilitas login)
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    nama       VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Akun demo : username = admin , password = admin123
INSERT INTO users (username, password, nama) VALUES
('admin', '$2y$10$vylJudo3JiLuYD9OpEtm9.V1If95BSFdI21fbPaQzGfL3dwgPm25C', 'Administrator');

-- Tabel film (pencarian berdasarkan kategori)
CREATE TABLE IF NOT EXISTS film (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    judul         VARCHAR(200) NOT NULL,
    bintang       VARCHAR(100) NOT NULL,   -- bintang film
    pemeran_utama VARCHAR(100) NOT NULL,   -- pemeran utama
    sutradara     VARCHAR(100) NOT NULL,   -- sutradara
    tahun         INT NOT NULL,            -- tahun terbit
    genre         VARCHAR(100),
    sinopsis      TEXT,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO film (judul, bintang, pemeran_utama, sutradara, tahun, genre, sinopsis) VALUES
('Laskar Pelangi',        'Cut Mini',          'Ikranagara',        'Riri Riza',        2008, 'Drama',          'Kisah perjuangan sepuluh anak Belitung menempuh pendidikan di sekolah sederhana.'),
('Ada Apa dengan Cinta?', 'Nicholas Saputra',  'Dian Sastro',       'Rudy Soedjarwo',   2002, 'Drama Romantis', 'Cerita cinta remaja antara Cinta dan Rangga yang penuh konflik.'),
('The Raid',              'Iko Uwais',         'Iko Uwais',         'Gareth Evans',     2011, 'Laga',           'Aksi seorang polisi yang terjebak dalam gedung penuh penjahat.'),
('Pengabdi Setan',        'Tara Basro',        'Tara Basro',        'Joko Anwar',       2017, 'Horor',          'Keluarga yang dihantui sosok misterius setelah sang ibu meninggal.'),
('Inception',             'Leonardo DiCaprio', 'Leonardo DiCaprio', 'Christopher Nolan', 2010, 'Fiksi Ilmiah',   'Pencuri rahasia yang bekerja lewat mimpi dan lapisan alam bawah sadar.'),
('Interstellar',          'Matthew McConaughey','Matthew McConaughey','Christopher Nolan', 2014, 'Fiksi Ilmiah',  'Perjalanan antar bintang demi menyelamatkan umat manusia.'),
('Titanic',               'Kate Winslet',      'Leonardo DiCaprio', 'James Cameron',    1997, 'Roman',          'Kisah cinta Jack dan Rose di kapal Titanic yang tenggelam.'),
('Parasite',              'Song Kang-ho',      'Song Kang-ho',      'Bong Joon-ho',     2019, 'Thriller',       'Keluarga miskin yang menyusup ke keluarga kaya dengan berbagai tipu daya.'),
('The Dark Knight',       'Heath Ledger',      'Christian Bale',    'Christopher Nolan', 2008, 'Laga',          'Batman menghadapi Joker yang kacau dan penuh kekerasan.'),
('Habibie & Ainun',       'Reza Rahadian',     'Reza Rahadian',     'Faozan Rizal',     2012, 'Drama',          'Kisah cinta B.J. Habibie dan istrinya, Hasri Ainun Besari.'),
('Gundala',               'Abimana Aryasatya', 'Abimana Aryasatya', 'Joko Anwar',       2019, 'Laga',           'Pahlawan super Indonesia yang bangkit dari sambaran petir.'),
('Dilan 1990',            'Iqbaal Ramadhan',   'Iqbaal Ramadhan',   'Fajar Bustomi',    2018, 'Roman',          'Kisah cinta Dilan dan Milea di Bandung tahun 1990.');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
--  CATATAN
--  - Database yang dibuat: latihdb, myaddress, filmdb
--  - Login aplikasi film  : admin / admin123
--  - Sesuaikan kredensial koneksi (root / tanpa password)
--    pada file global.php dan film/config.php bila berbeda.
-- ============================================================
