<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4f46e5;
        }
        
        .header h1 {
            color: #4f46e5;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        
        .header p {
            color: #666;
            margin: 0;
            font-size: 11px;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .company-info h2 {
            color: #4f46e5;
            margin: 0;
            font-size: 18px;
        }
        
        .company-info p {
            color: #666;
            margin: 5px 0;
            font-size: 11px;
        }
        
        .user-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .user-info h3 {
            color: #4f46e5;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .user-info p {
            margin: 5px 0;
        }
        
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f1f5f9;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-item {
            text-align: center;
            flex: 1;
        }
        
        .summary-item .label {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
        }
        
        .section-title {
            color: #4f46e5;
            font-size: 16px;
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }
        
        table th {
            background-color: #4f46e5;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #4f46e5;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }
        
        table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .status-paid {
            color: #10b981;
            font-weight: bold;
        }
        
        .status-pending {
            color: #f59e0b;
            font-weight: bold;
        }
        
        .status-cancelled {
            color: #ef4444;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 10px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #64748b;
            font-style: italic;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header Perusahaan -->
    <div class="company-info">
        <h2>CwnXtech Events</h2>
        <p>Event & Conference Management System</p>
        <p>www.cwnxtech.com | support@cwnxtech.com</p>
    </div>
    
    <!-- Header Dokumen -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dibuat pada: {{ $date }}</p>
    </div>
    
    <!-- Informasi User -->
    <div class="user-info">
        <h3>Informasi Pelanggan</h3>
        <p><strong>Nama:</strong> {{ $pembeli->nama_pembeli }}</p>
        <p><strong>Email:</strong> {{ $pembeli->email ?? 'N/A' }}</p>
        <p><strong>Telepon:</strong> {{ $pembeli->telepon ?? 'N/A' }}</p>
    </div>
    
    <!-- Ringkasan -->
    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Tiket</div>
            <div class="value">{{ $totalTickets }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Nilai</div>
            <div class="value">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Tanggal Laporan</div>
            <div class="value">{{ date('d/m/Y') }}</div>
        </div>
    </div>
    
    <!-- Detail Tiket -->
    <h3 class="section-title">Detail Tiket</h3>
    
    @if($tickets->count() > 0)
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Tiket</th>
                <th>Judul Tiket</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th>Tanggal Beli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $index => $ticket)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>#{{ str_pad($ticket->id_tiket, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $ticket->judul_tiket }}</td>
                <td class="text-center">{{ $ticket->jumlah_tiket }}</td>
                <td class="text-right">Rp {{ number_format($ticket->total_harga, 0, ',', '.') }}</td>
                <td>{{ $ticket->metode_pembayaran }}</td>
                <td class="text-center">
                    <span class="status-{{ $ticket->status_pembayaran }}">
                        {{ ucfirst($ticket->status_pembayaran) }}
                    </span>
                </td>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>Tidak ada data tiket ditemukan.</p>
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem CwnXtech Events.</p>
        <p>© {{ date('Y') }} CwnXtech. Semua hak dilindungi undang-undang.</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>