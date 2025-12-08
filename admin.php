<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Management</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php 
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: Login.php');
    exit;
}
echo "<div style='padding:15px; background:#4caf50; color:white; font-size:18px; text-align:center;'>
SELAMAT DATANG " . htmlspecialchars($_SESSION['username']) . "
</div>";
?>
    <?php
// Baca data registrasi dari file
$registrations = array();
if (file_exists('data/registrations.json')) {
    $json_data = file_get_contents('data/registrations.json');
    $registrations = json_decode($json_data, true);
    if (!is_array($registrations)) {
        $registrations = array();
    }
}

// Proses approve/reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    foreach ($registrations as &$reg) {
        if ($reg['id'] == $id) {
            if ($action == 'approve') {
                $reg['status'] = 'approved';
            } elseif ($action == 'reject') {
                $reg['status'] = 'rejected';
            }
            break;
        }
    }
    
    // Simpan perubahan
    file_put_contents('data/registrations.json', json_encode($registrations, JSON_PRETTY_PRINT));
    header('Location: admin.php');
    exit;
}

// Hitung statistik
$total_registrations = count($registrations);
$pending_count = 0;
$approved_count = 0;
    
foreach ($registrations as $reg) {
    if ($reg['status'] == 'pending') $pending_count++;
    if ($reg['status'] == 'approved') $approved_count++;
}
?>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="Dashboard.html"><i class="fas fa-home"></i> Overview</a></li>
                    <li><a href="transactions.html"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="admin.html" class="active"><i class="fas fa-users-cog"></i> Admin</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
    <div class="user-profile">
        <img src="icon/favicon-64x64.png" alt="User">
        <div class="user-info">
            <span class="user-name">
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <span class="user-role">Administrator</span>
        </div>
    </div>

    <a class="logout-btn" href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <button class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Admin Panel</h1>
                </div>
                <div class="header-right">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <a href="#" target="_blank" class="built-with">
                        Admin Panel
                    </a>
                </div>
            </header>

            <div class="content-wrapper">
                <!-- Admin Stats -->
<div class="admin-stats">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $total_registrations; ?></h3>
            <p>Total Registrations</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $approved_count; ?></h3>
            <p>Approved</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-content">
            <h3>1</h3>
            <p>Admin Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-user-clock"></i>
        </div>
        <div class="stat-content">
            <h3><?php echo $pending_count; ?></h3>
            <p>Pending Approval</p>
        </div>
    </div>
</div>

                <!-- User Management -->
                <div class="user-management">
                    <div class="section-header">
                        <h2>User Management</h2>
                        <button class="btn-primary" onclick="showAddUserModal()">
                            <i class="fas fa-plus"></i> Add User
                        </button>
                    </div>

                    <!-- Search and Filter -->
                    <div class="search-filter">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search users..." id="userSearch">
                        </div>
                        <select class="filter-select" id="roleFilter">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                            <option value="moderator">Moderator</option>
                        </select>
                        <select class="filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    
                    <!-- Event Registrations Management -->
<div class="user-management">
    <div class="section-header">
        <h2>Users Registrations</h2>
    </div>

    <!-- Registrations Table -->
     <?php
include 'koneksi.php';

// Query ambil semua pembeli
$sql = "SELECT * FROM tb_pembeli ORDER BY id_pembeli DESC";
$result = mysqli_query($koneksi, $sql);

// Jika gagal query
if(!$result){
    die("QUERY ERROR: " . mysqli_error($koneksi));
}

// Fetch data sebagai array asosiatif
$pembeli = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
   <div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Bergabung</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pembeli)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data pembeli</td>
                </tr>
            <?php else: ?>
                <?php foreach($pembeli as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_pembeli']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pembeli']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                    <td><?= htmlspecialchars($row['create_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>

<!--tiket-->

<?php
// Query tiket
$tiket_sql = "SELECT * FROM tb_tiket ORDER BY id_tiket DESC";
$tiket_query = mysqli_query($koneksi, $tiket_sql);
$query = mysqli_query($koneksi, "SELECT * FROM tb_tiket ORDER BY id_tiket DESC");
if (!$tiket_query) {
    die("QUERY TIKET ERROR: " . mysqli_error($koneksi));
}
?>
<div class="table-container">
    <h2 style="margin-bottom:15px;">DATA PEMBELIAN TIKET</h2>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Tiket</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($tiket_query) == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">Belum ada transaksi tiket</td>
                </tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($tiket_query)): ?>
                <tr>
                    <td><?= $row['id_tiket'] ?></td>
                    <td><?= htmlspecialchars($row['judul_tiket']) ?></td>
                    <td><?= $row['jumlah_tiket'] ?></td>
                    <td><?= number_format($row['harga_satuan']) ?></td>
                    <td><?= number_format($row['total_harga']) ?></td>
                    <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                    <td><?= htmlspecialchars($row['status_pembayaran']) ?></td>

                
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

                 


                    <!-- Pagination -->
                    <div class="pagination">
                        <button class="page-btn" disabled><i class="fas fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="settings-section">
                    <h2>System Settings</h2>
                    <div class="settings-grid">
                        <div class="setting-item">
                            <label for="maxUsers">Max Users per Page</label>
                            <input type="number" id="maxUsers" value="10" min="5" max="50">
                        </div>
                        <div class="setting-item">
                            <label for="autoApprove">Auto-approve New Users</label>
                            <label class="switch">
                                <input type="checkbox" id="autoApprove">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <label for="emailNotif">Email Notifications</label>
                            <label class="switch">
                                <input type="checkbox" id="emailNotif" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <label for="sessionTimeout">Session Timeout (minutes)</label>
                            <input type="number" id="sessionTimeout" value="30" min="15" max="120">
                        </div>
                    </div>
                    <button class="btn-primary save-settings">Save Settings</button>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New User</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <div class="form-group">
                        <label for="userName">Name</label>
                        <input type="text" id="userName" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="email" id="userEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Role</label>
                        <select id="userRole" required>
                            <option value="user">User</option>
                            <option value="moderator">Moderator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userStatus">Status</label>
                        <select id="userStatus" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn-primary" onclick="saveUser()">Save User</button>
            </div>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
