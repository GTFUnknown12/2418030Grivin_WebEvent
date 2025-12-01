<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Payment History</title>
    <link rel="stylesheet" href="css/admin.css">
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
                    <li><a href="Dashboard.html"><i class="fas fa-home"></i> Overview</a></li>
                    <li><a href="transactions.html" class="active"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="admin.html"><i class="fas fa-users-cog"></i> Admin</a></li>
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
                    <h1>Transactions</h1>
                </div>
                <div class="header-right">
                    <button class="btn-primary" onclick="exportTransactions()">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <div class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <a href="#" target="_blank" class="built-with">
                        TRANSACTIONS
                    </a>
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
                            <h3>$28,450</h3>
                            <p>Total Income</p>
                            <span class="trend positive">+18.5%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="stat-content">
                            <h3>$5,230</h3>
                            <p>Total Expenses</p>
                            <span class="trend negative">+5.2%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="stat-content">
                            <h3>456</h3>
                            <p>Transactions</p>
                            <span class="trend positive">+12.3%</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <h3>94.2%</h3>
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
                        <tbody id="transactionsTableBody">
                            <tr>
                                <td>#TRX001234</td>
                                <td>2024-03-15 14:23</td>
                                <td>
                                    <div class="customer-cell">
                                        <img src="https://picsum.photos/seed/cust1/32/32" alt="Customer">
                                        <span>John Smith</span>
                                    </div>
                                </td>
                                <td><span class="type-badge payment">Payment</span></td>
                                <td class="amount positive">+$450.00</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>
                                    <button class="action-btn view" onclick="viewTransaction('TRX001234')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn download" onclick="downloadReceipt('TRX001234')">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#TRX001233</td>
                                <td>2024-03-15 13:45</td>
                                <td>
                                    <div class="customer-cell">
                                        <img src="https://picsum.photos/seed/cust2/32/32" alt="Customer">
                                        <span>Sarah Johnson</span>
                                    </div>
                                </td>
                                <td><span class="type-badge refund">Refund</span></td>
                                <td class="amount negative">-$125.50</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>
                                    <button class="action-btn view" onclick="viewTransaction('TRX001233')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn download" onclick="downloadReceipt('TRX001233')">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#TRX001232</td>
                                <td>2024-03-15 12:30</td>
                                <td>
                                    <div class="customer-cell">
                                        <img src="https://picsum.photos/seed/cust3/32/32" alt="Customer">
                                        <span>Mike Chen</span>
                                    </div>
                                </td>
                                <td><span class="type-badge withdrawal">Withdrawal</span></td>
                                <td class="amount negative">-$1,200.00</td>
                                <td><span class="status-badge pending">Pending</span></td>
                                <td>
                                    <button class="action-btn view" onclick="viewTransaction('TRX001232')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn download" onclick="downloadReceipt('TRX001232')">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#TRX001231</td>
                                <td>2024-03-15 11:15</td>
                                <td>
                                    <div class="customer-cell">
                                        <img src="https://picsum.photos/seed/cust4/32/32" alt="Customer">
                                        <span>Emily Davis</span>
                                    </div>
                                </td>
                                <td><span class="type-badge payment">Payment</span></td>
                                <td class="amount positive">+$890.00</td>
                                <td><span class="status-badge failed">Failed</span></td>
                                <td>
                                    <button class="action-btn view" onclick="viewTransaction('TRX001231')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn retry" onclick="retryTransaction('TRX001231')">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#TRX001230</td>
                                <td>2024-03-15 10:00</td>
                                <td>
                                    <div class="customer-cell">
                                        <img src="https://picsum.photos/seed/cust5/32/32" alt="Customer">
                                        <span>Alex Wilson</span>
                                    </div>
                                </td>
                                <td><span class="type-badge payment">Payment</span></td>
                                <td class="amount positive">+$320.75</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>
                                    <button class="action-btn view" onclick="viewTransaction('TRX001230')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn download" onclick="downloadReceipt('TRX001230')">
                                        <i class="fas fa-receipt"></i>
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
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
                </div>

                <!-- Transaction Details Modal -->
                <div class="modal" id="transactionModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Transaction Details</h3>
                            <button class="close-modal" onclick="closeTransactionModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="transaction-details">
                                <div class="detail-row">
                                    <span class="label">Transaction ID:</span>
                                    <span id="detailId">#TRX001234</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Date:</span>
                                    <span id="detailDate">2024-03-15 14:23</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Customer:</span>
                                    <span id="detailCustomer">John Smith</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Type:</span>
                                    <span id="detailType">Payment</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Amount:</span>
                                    <span id="detailAmount">$450.00</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Status:</span>
                                    <span id="detailStatus">Completed</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Payment Method:</span>
                                    <span>Credit Card (****1234)</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Description:</span>
                                    <span>Premium Package Subscription</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-secondary" onclick="closeTransactionModal()">Close</button>
                            <button class="btn-primary" onclick="downloadReceipt(document.getElementById('detailId').textContent.substring(1))">
                                <i class="fas fa-download"></i> Download Receipt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/transactions.js"></script>
</body>
</html>
