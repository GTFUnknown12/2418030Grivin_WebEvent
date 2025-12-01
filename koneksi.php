<?php 

$servername = 'localhost';
$username = 'root';
$password = ''; 
$database = 'db_cwnx';

// membuat koneksi
$koneksi = mysqli_connect($servername, $username, $password, $database);

// mengecek koneksi
if(!$koneksi) {
    die('Connection Failed:' . mysqli_connect_error());
}
 ?>
