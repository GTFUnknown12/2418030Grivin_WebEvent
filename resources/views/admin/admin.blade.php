<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Overview</a></li>
                    <li><a href="{{ route('admin.transactions') }}"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="{{ route('admin.users') }}" class="active"><i class="fas fa-users-cog"></i> Users</a></li>
                    <li><a href="{{ route('admin.tickets') }}"><i class="fas fa-ticket-alt"></i> Tickets</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="user-profile">
                    <img src="{{ asset('icon/favicon-64x64.png') }}" alt="User" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('pembeli')->user()->nama_pembeli) }}&background=random'">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::guard('pembeli')->user()->nama_pembeli }}</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <button class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Admin Panel - User Management</h1>
                </div>
                <div class="header-right">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge">{{ $pending_count }}</span>
                    </div>
                    <div class="built-with">
                        Admin Panel v1.0
                    </div>
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
                            <h3>{{ $total_pembeli }}</h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $approved_count }}</h3>
                            <p>Approved Payments</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $pembelis->where('is_admin', true)->count() }}</h3>
                            <p>Admin Users</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $pending_count }}</h3>
                            <p>Pending Payments</p>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="user-management">
                    <div class="section-header">
                        <h2>Registered Users</h2>
                        <div class="search-filter">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search users..." id="userSearch">
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Gender</th>
                                    <th>Birth Date</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelis as $pembeli)
                                <tr>
                                    <td>{{ $pembeli->id_pembeli }}</td>
                                    <td>{{ $pembeli->nama_pembeli }}</td>
                                    <td>{{ $pembeli->username }}</td>
                                    <td>{{ $pembeli->email }}</td>
                                    <td>{{ Str::limit($pembeli->alamat, 30) }}</td>
                                    <td>{{ $pembeli->jenis_kelamin }}</td>
                                    <td>{{ $pembeli->tanggal_lahir->format('Y-m-d') }}</td>
                                    <td>{{ $pembeli->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($pembeli->is_admin)
                                            <span class="status-badge admin">Admin</span>
                                        @else
                                            <span class="status-badge user">User</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" style="text-align: center;">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $pembelis->links() }}
                    </div>
                </div>

                <!-- Tickets Management -->
                <div class="user-management">
                    <div class="section-header">
                        <h2>Ticket Purchases</h2>
                    </div>

                    <!-- Tickets Table -->
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Customer</th>
                                    <th>Title</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Purchase Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                <tr>
                                    <td>#{{ str_pad($ticket->id_tiket, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $ticket->pembeli->nama_pembeli }}</td>
                                    <td>{{ $ticket->judul_tiket }}</td>
                                    <td>{{ $ticket->jumlah_tiket }}</td>
                                    <td>Rp {{ number_format($ticket->total_harga, 0, ',', '.') }}</td>
                                    <td>{{ $ticket->metode_pembayaran }}</td>
                                    <td>
                                        <span class="status-badge {{ $ticket->status_pembayaran }}">
                                            {{ ucfirst($ticket->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.tickets.status', $ticket->id_tiket) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <select name="status_pembayaran" onchange="this.form.submit()" class="status-select">
                                                <option value="pending" {{ $ticket->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="completed" {{ $ticket->status_pembayaran == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="failed" {{ $ticket->status_pembayaran == 'failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </form>
                                        <form action="{{ route('admin.tickets.destroy', $ticket->id_tiket) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" style="text-align:center;">No tickets found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $tickets->links() }}
                    </div>
                </div>

                <!-- System Settings -->
                <div class="settings-section">
                    <h2>System Settings</h2>
                    <div class="settings-grid">
                        <div class="setting-item">
                            <label for="maxUsers">Users per Page</label>
                            <input type="number" id="maxUsers" value="10" min="5" max="50">
                        </div>
                        <div class="setting-item">
                            <label for="autoApprove">Auto-approve Tickets</label>
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
                            <label for="sessionTimeout">Session Timeout (min)</label>
                            <input type="number" id="sessionTimeout" value="30" min="15" max="120">
                        </div>
                    </div>
                    <button class="btn-primary save-settings" onclick="saveSettings()">Save Settings</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Menu Toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // User Search
        document.getElementById('userSearch')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Save Settings
        function saveSettings() {
            const settings = {
                maxUsers: document.getElementById('maxUsers').value,
                autoApprove: document.getElementById('autoApprove').checked,
                emailNotif: document.getElementById('emailNotif').checked,
                sessionTimeout: document.getElementById('sessionTimeout').value
            };
            
            localStorage.setItem('adminSettings', JSON.stringify(settings));
            alert('Settings saved locally!');
        }

        // Load Settings
        window.addEventListener('DOMContentLoaded', function() {
            const savedSettings = localStorage.getItem('adminSettings');
            if (savedSettings) {
                const settings = JSON.parse(savedSettings);
                document.getElementById('maxUsers').value = settings.maxUsers || 10;
                document.getElementById('autoApprove').checked = settings.autoApprove || false;
                document.getElementById('emailNotif').checked = settings.emailNotif !== false;
                document.getElementById('sessionTimeout').value = settings.sessionTimeout || 30;
            }
        });

        // Notification Bell
        document.querySelector('.notification-bell').addEventListener('click', function() {
            alert(`You have ${this.querySelector('.badge').textContent} pending payments to review.`);
        });
    </script>
</body>
</html>