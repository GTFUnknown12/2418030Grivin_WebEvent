<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Payment History</title>
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
                    <li><a href="{{ route('admin.transactions') }}" class="active"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
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
                    <h1>Transactions</h1>
                </div>
                <div class="header-right">
                    <button class="btn-primary" onclick="exportTransactions()">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge">{{ $transactions->where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="built-with">
                        Transactions v1.0
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                <!-- Transaction Stats -->
                <div class="transaction-stats">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
                            <p>Total Income</p>
                            <span class="trend positive">+{{ number_format($stats['success_rate'], 1) }}%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="stat-content">
                            <h3>Rp {{ number_format($stats['total_expenses'], 0, ',', '.') }}</h3>
                            <p>Total Expenses</p>
                            <span class="trend negative">+5.2%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $stats['total_transactions'] }}</h3>
                            <p>Transactions</p>
                            <span class="trend positive">+12.3%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['success_rate'], 1) }}%</h3>
                            <p>Success Rate</p>
                            <span class="trend positive">+2.1%</span>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="transaction-filters">
                    <div class="filter-group">
                        <input type="date" id="startDate" class="date-input">
                        <span>to</span>
                        <input type="date" id="endDate" class="date-input">
                    </div>
                    <select class="filter-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="payment">Payment</option>
                        <option value="refund">Refund</option>
                        <option value="withdrawal">Withdrawal</option>
                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search transactions..." id="transactionSearch">
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date & Time</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>#{{ $transaction->transaction_id }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="customer-cell">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->pembeli->nama_pembeli) }}&background=random&color=fff" alt="Customer">
                                        <span>{{ $transaction->pembeli->nama_pembeli }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge {{ $transaction->type }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="amount {{ $transaction->type == 'payment' ? 'positive' : 'negative' }}">
                                    {{ $transaction->type == 'payment' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $transaction->status }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.transactions.view', $transaction->id) }}" class="action-btn view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="action-btn download" onclick="downloadReceipt('{{ $transaction->transaction_id }}')">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center;">No transactions found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $transactions->links('vendor.pagination.custom') }}
                </div>
            </div>
        </main>
    </div>

    <script>
        // Menu Toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Export Function
        function exportTransactions() {
            alert('Exporting transactions data...');
            // In a real application, this would generate and download a CSV/Excel file
        }

        function downloadReceipt(transactionId) {
            alert(`Downloading receipt for transaction ${transactionId}...`);
            // In a real application, this would generate and download a PDF receipt
        }

        // Filter Transactions
        document.getElementById('transactionSearch')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Date Filter
        document.getElementById('startDate')?.addEventListener('change', filterTransactions);
        document.getElementById('endDate')?.addEventListener('change', filterTransactions);
        document.getElementById('typeFilter')?.addEventListener('change', filterTransactions);
        document.getElementById('statusFilter')?.addEventListener('change', filterTransactions);

        function filterTransactions() {
            // In a real application, this would make an AJAX request to filter transactions
            console.log('Filtering transactions...');
        }

        // Notification Bell
        document.querySelector('.notification-bell').addEventListener('click', function() {
            const pendingCount = this.querySelector('.badge').textContent;
            alert(`You have ${pendingCount} pending transactions.`);
        });
    </script>
</body>
</html>