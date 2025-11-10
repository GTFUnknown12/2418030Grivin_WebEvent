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
                    <img src="images/user/GrivinSmall.jpg" alt="User">
                    <div class="user-info">
                        <span class="user-name">Grivin William Revel</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
                <button class="logout-btn" href="Login.html"><i class="fas fa-sign-out-alt"></i> Logout</button>
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
        <h2>Event Registrations</h2>
    </div>

    <!-- Registrations Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Event</th>
                    <th>Ticket Type</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($registrations)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No registrations found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($registrations as $reg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['name']); ?></td>
                            <td><?php echo htmlspecialchars($reg['email']); ?></td>
                            <td><?php echo htmlspecialchars($reg['phone'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($reg['company'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($reg['event']); ?></td>
                            <td><?php echo htmlspecialchars($reg['ticket_type']); ?></td>
                            <td><?php echo date('Y-m-d H:i', $reg['id']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $reg['status']; ?>">
                                    <?php echo ucfirst($reg['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($reg['status'] == 'pending'): ?>
                                    <a href="admin.php?action=approve&id=<?php echo $reg['id']; ?>" class="action-btn edit" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="admin.php?action=reject&id=<?php echo $reg['id']; ?>" class="action-btn delete" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="action-btn" disabled>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="action-btn" disabled>
                                        <i class="fas fa-times"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

                    <!-- Users Table -->
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <img src="https://picsum.photos/seed/user2/40/40" alt="User">
                                            <span>Sarah Johnson</span>
                                        </div>
                                    </td>
                                    <td>sarah@example.com</td>
                                    <td><span class="role-badge admin">Admin</span></td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>2024-01-15</td>
                                    <td>
                                        <button class="action-btn edit" onclick="editUser(1)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete" onclick="deleteUser(1)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <img src="https://picsum.photos/seed/user3/40/40" alt="User">
                                            <span>Mike Chen</span>
                                        </div>
                                    </td>
                                    <td>mike@example.com</td>
                                    <td><span class="role-badge user">User</span></td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>2024-02-20</td>
                                    <td>
                                        <button class="action-btn edit" onclick="editUser(2)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete" onclick="deleteUser(2)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <img src="https://picsum.photos/seed/user4/40/40" alt="User">
                                            <span>Emily Davis</span>
                                        </div>
                                    </td>
                                    <td>emily@example.com</td>
                                    <td><span class="role-badge moderator">Moderator</span></td>
                                    <td><span class="status-badge inactive">Inactive</span></td>
                                    <td>2024-01-30</td>
                                    <td>
                                        <button class="action-btn edit" onclick="editUser(3)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete" onclick="deleteUser(3)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <img src="https://picsum.photos/seed/user5/40/40" alt="User">
                                            <span>Alex Wilson</span>
                                        </div>
                                    </td>
                                    <td>alex@example.com</td>
                                    <td><span class="role-badge user">User</span></td>
                                    <td><span class="status-badge pending">Pending</span></td>
                                    <td>2024-03-10</td>
                                    <td>
                                        <button class="action-btn edit" onclick="editUser(4)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete" onclick="deleteUser(4)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
