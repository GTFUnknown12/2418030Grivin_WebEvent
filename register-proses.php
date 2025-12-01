<?php 
include 'koneksi.php';

if(isset($_POST['register'])) {

    // Ambil data dari form
    $nama       = $_POST['nama_pembeli'];
    $username   = $_POST['username'];
    $password   = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $alamat     = $_POST['alamat'];
    $email      = $_POST['email'];
    $jk         = $_POST['jenis_kelamin'];
    $tgl_lahir  = $_POST['tanggal_lahir'];

    // Validasi
    if(empty($nama) || empty($username) || empty($alamat) || empty($email) || empty($jk) || empty($tgl_lahir)) {
        echo "
            <script>
                alert('Pastikan semua data terisi!');
                window.location = 'Register.php';
            </script>
        ";
        exit;
    }

    // Query harus sesuai kolom tabel
    $sql = "INSERT INTO tb_pembeli
            (nama_pembeli, username, password, alamat, email, jenis_kelamin, tanggal_lahir)
            VALUES
            ('$nama', '$username', '$password', '$alamat', '$email', '$jk', '$tgl_lahir')";

    if(mysqli_query($koneksi, $sql)) {
        echo "  
            <script>
                alert('Registrasi berhasil, silakan login.');
                window.location = 'Login.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Gagal menyimpan ke database.');
                window.location = 'Register.php';
            </script>
        ";
    }
}
?>