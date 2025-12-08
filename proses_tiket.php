<?php
// proses_tiket.php
session_start();
include 'koneksi.php'; // pastikan koneksi menghasilkan $koneksi (mysqli)

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Pastikan user sudah login (ambil nama dari session jika tersedia)
if (isset($_SESSION['nama_pembeli']) && $_SESSION['nama_pembeli'] !== '') {
    $nama_pembeli = $_SESSION['nama_pembeli'];
} else {
    // Jika memang ingin menerima nama dari form, uncomment baris berikut:
    // $nama_pembeli = trim($_POST['nama_pembeli'] ?? '');
    // Untuk keamanan, kalau user belum login, tolak.
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='Login.php';</script>";
    exit;
}

// Ambil input dari form — gunakan null coalescing untuk mencegah undefined index
$judul_tiket   = trim($_POST['judul_tiket'] ?? '');
$jumlah_tiket  = intval($_POST['jumlah_tiket'] ?? 0);
$harga_satuan  = intval($_POST['harga_satuan'] ?? 0);
$total_harga   = intval($_POST['total_harga'] ?? ($jumlah_tiket * $harga_satuan));
$metode        = trim($_POST['metode_pembayaran'] ?? '');

// Validasi sederhana
$errors = [];
if ($judul_tiket === '') $errors[] = 'Judul tiket harus diisi.';
if ($jumlah_tiket <= 0) $errors[] = 'Jumlah tiket harus lebih dari 0.';
if ($harga_satuan <= 0) $errors[] = 'Harga satuan tidak valid.';
if ($metode === '') $errors[] = 'Pilih metode pembayaran.';

if (!empty($errors)) {
    // Gabungkan pesan error dan kembalikan ke index
    $msg = implode("\\n", $errors);
    echo "<script>alert('Gagal:\\n{$msg}'); window.history.back();</script>";
    exit;
}

// Pastikan koneksi valid
if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die('Database connection error.');
}

// Prepared statement untuk insert
$stmt = $koneksi->prepare(
    "INSERT INTO tb_tiket 
     (nama_pembeli, judul_tiket, jumlah_tiket, harga_satuan, total_harga, metode_pembayaran, status_pembayaran)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    die('Prepare failed: ' . htmlspecialchars($koneksi->error));
}

$status = 'pending'; // default status

// bind_param: s = string, i = integer
$stmt->bind_param(
    'ssiiiss',
    $nama_pembeli,
    $judul_tiket,
    $jumlah_tiket,
    $harga_satuan,
    $total_harga,
    $metode,
    $status
);

// Execute dan cek
if ($stmt->execute()) {
    $stmt->close();
    // redirect atau tampil pesan sukses
    header('Location: index.php?success=1');
    exit;
} else {
    $err = $stmt->error;
    $stmt->close();
    // tampilkan pesan error DB (bersihkan untuk produksi)
    echo "<h3>Database error:</h3><pre>" . htmlspecialchars($err) . "</pre>";
    exit;
}
