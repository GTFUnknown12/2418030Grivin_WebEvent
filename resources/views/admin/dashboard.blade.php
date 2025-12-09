<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Overview</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="{{ route('admin.dashboard') }}" class="active"><i class="fas fa-home"></i> Overview</a></li>
                    <li><a href="{{ route('admin.transactions') }}"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="{{ route('admin.users') }}"><i class="fas fa-users-cog"></i> Users</a></li>
                    <li><a href="{{ route('admin.tickets') }}"><i class="fas fa-ticket-alt"></i> Tickets</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="user-profile">
                    <img src="{{ asset('images/user/GrivinSmall.jpg') }}" alt="User" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('pembeli')->user()->nama_pembeli) }}&background=random'">
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
                    <h1>Dashboard Overview</h1>
                </div>
                <div class="header-right">
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="built-with">
                        Admin Dashboard v1.0
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
                            <p>Total Revenue</p>
                            <span class="trend positive">+{{ number_format(($stats['completed_tickets'] / max($stats['total_tickets'], 1)) * 100, 1) }}%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $stats['total_tickets'] }}</h3>
                            <p>Total Tickets</p>
                            <span class="trend positive">+{{ number_format(($stats['completed_tickets'] / max($stats['total_tickets'], 1)) * 100, 1) }}%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $stats['total_pembeli'] }}</h3>
                            <p>Registered Users</p>
                            <span class="trend positive">+{{ number_format($stats['total_pembeli'] / 100 * 5, 1) }}%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $stats['total_events'] }}</h3>
                            <p>Active Events</p>
                            <span class="trend positive">+{{ number_format($stats['total_events'] / 10 * 100, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h2>Revenue Overview</h2>
                            <select class="period-select" id="periodSelect">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90">Last 3 months</option>
                            </select>
                        </div>
                        <div class="chart">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <h2>Ticket Status Distribution</h2>
                        </div>
                        <div class="chart">
                            <canvas id="ticketChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="activity-section">
                    <h2>Recent Tickets</h2>
                    <div class="activity-list">
                        @foreach($recent_tickets as $ticket)
                        <div class="activity-item">
                            <div class="activity-icon {{ $ticket->status_pembayaran == 'completed' ? 'success' : ($ticket->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="activity-content">
                                <p><strong>{{ $ticket->pembeli->nama_pembeli }}</strong> purchased {{ $ticket->jumlah_tiket }} ticket(s) for {{ $ticket->judul_tiket }}</p>
                                <span class="activity-time">{{ $ticket->created_at->diffForHumans() }}</span>
                                <span class="activity-amount">Rp {{ number_format($ticket->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="activity-section">
                    <h2>Recent Users</h2>
                    <div class="user-list">
                        @foreach($recent_pembelis as $pembeli)
                        <div class="user-item">
                            <div class="user-avatar">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pembeli->nama_pembeli) }}&background=random&color=fff" alt="{{ $pembeli->nama_pembeli }}">
                            </div>
                            <div class="user-info">
                                <h4>{{ $pembeli->nama_pembeli }}</h4>
                                <p>{{ $pembeli->email }}</p>
                                <span class="user-join">Joined {{ $pembeli->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="user-stats">
                                <span class="stat">{{ $pembeli->tickets->count() }} tickets</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Menu Toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: [4500000, 5200000, 4800000, 6100000, 5900000, 6800000],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Ticket Status Chart
        const ticketCtx = document.getElementById('ticketChart').getContext('2d');
        const ticketChart = new Chart(ticketCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Failed'],
                datasets: [{
                    data: [{{ $stats['completed_tickets'] }}, {{ $stats['pending_tickets'] }}, {{ $stats['failed_tickets'] }}],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Period Select Change
        document.getElementById('periodSelect').addEventListener('change', function() {
            // In a real application, you would fetch new data based on the selected period
            console.log('Period changed to:', this.value);
        });

        // Notification Bell
        document.querySelector('.notification-bell').addEventListener('click', function() {
            alert('You have 3 unread notifications');
        });
    </script>
</body>
</html>