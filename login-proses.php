<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {

    $requestUsername = $_POST['username'];
    $requestPassword = $_POST['password'];

    // Ambil data user berdasarkan username
    $sql = "SELECT * FROM tb_pembeli WHERE username = '$requestUsername'";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($requestPassword, $row['password'])) {

            // Simpan nama user ke session
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama_pembeli'] = $row['nama_pembeli'];

            header('Location: admin.php');
            exit;

        } else {
            echo "
            <script>
                alert('Password salah!');
                window.location = 'Login.php';
            </script>";
        }

    } else {
        echo "
        <script>
            alert('Username tidak ditemukan!');
            window.location = 'Login.php';
        </script>";
    }
}
?>
