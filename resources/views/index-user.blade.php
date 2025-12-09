<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/cwnXtechStylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Xcss2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Logo.css') }}">
    <link rel="icon" href="{{ asset('/icon/CwnIcon.png') }}">
    <title>CwnXtech | Event & Conference - Dashboard</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <img src="{{ asset('/icon/CwnIcon.png') }}" alt="logo" class="logo">
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="dropdown">
                        <a href="#events">Events <i data-feather="chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="#">Tech Summit</a>
                            <a href="#">Marketing Conference</a>
                            <a href="#">Leadership Forum</a>
                            <a href="#">All Events</a>
                        </div>
                    </li>
                    <li><a href="#speakers">Speakers</a></li>
                    <li class="dropdown">
                        <a href="#schedule">Schedule <i data-feather="chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="#">Day 1</a>
                            <a href="#">Day 2</a>
                            <a href="#">Day 3</a>
                        </div>
                    </li>
                    @if(Auth::guard('pembeli')->check())
                        <li>
                            <a href="#" style="font-weight: 600;">
                                {{ Auth::guard('pembeli')->user()->nama_pembeli }}
                            </a>
                        </li>
                        <li class="dropdown-content">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="logout-link" style = "">Logout</button>
                            </form>
                        </li>
                        @if(Auth::guard('pembeli')->user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="admin-link">Admin Panel</a></li>
                        @endif
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome, {{ Auth::guard('pembeli')->user()->nama_pembeli }}!</h1>
            <p class="text-xl max-w-2xl mx-auto mb-8 text-gray-300">Manage your event registrations and tickets here.</p>
            <div class="text-white mt-6">
                <p class="text-lg mb-2">Your Tickets: {{ $tickets->count() }}</p>
                <p class="text-sm opacity-80">Next Event: AI Revolution Summit - November 15-20, 2025</p>
            </div>
            <div class="flex gap-4 justify-center">
                <a href="#tickets" class="btn">View My Tickets</a>
                <a href="#events" class="btn" id="Explore">Browse Events</a>
            </div>
        </div>
    </section>

    <section id="events" class="events">
        <div class="container">
            <div class="section-title">
                <h2>Upcoming Events</h2>
                <p class="text-gray-600 max-w-3xl mx-auto mt-4">Join our world-class conferences featuring cutting edge topics and networking opportunities.</p>
            </div>
            <div class="event-grid">
                @foreach($events as $event)
                <div class="event-card">
                    <div class="event-media-container">
                        <img src="{{ $event->image_url ?: 'http://static.photos/technology/640x360/20' }}" alt="{{ $event->title }}" class="event-img">
                        <div class="event-badge">{{ $event->category }}</div>
                    </div>
                    <div class="event-info">
                        <h3>{{ $event->title }}</h3>
                        <div class="event-meta">
                            <span class="event-date">
                                <i data-feather="calendar"></i> {{ $event->start_date->format('M d') }}-{{ $event->end_date->format('d, Y') }}
                            </span>
                            <span class="event-location">
                                <i data-feather="map-pin"></i> {{ $event->location }}
                            </span>
                        </div>
                        <p class="event-desc">{{ Str::limit($event->description, 120) }}</p>
                        <div class="event-cta">
                            <a href="{{ route('events.show', $event->id) }}" class="btn event-details-btn">Learn More</a>
                            <span class="event-price">${{ number_format($event->price, 0) }} <small>Early Bird</small></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="tickets" class="schedule">
        <div class="container">
            <div class="section-title">
                <h2>My Tickets</h2>
                <p class="text-gray-600 max-w-3xl mx-auto mt-4">Your purchased tickets and registration details.</p>
            </div>

            @if($tickets->count() > 0)
            <div class="tickets-list">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Event</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>#{{ str_pad($ticket->id_tiket, 6, '0', STR_PAD_LEFT) }}</td>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="no-tickets">
                <p>You haven't purchased any tickets yet.</p>
                <a href="#buy-ticket" class="btn">Buy Your First Ticket</a>
            </div>
            @endif

            <!--container-tiket-->
            <div class="Container-Tiket" id="buy-ticket">
                <h2 class="FormTiket">PEMBELIAN TIKET</h2>
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="input-tiket">
                        <label class="labelTiket">NAMA</label>
                        <input type="text" class="inputStyle" readonly 
                            value="{{ Auth::guard('pembeli')->user()->nama_pembeli }}" />
                    </div>
                    <div class="input-tiket">
                        <label class="labelTiket">JUDUL TIKET</label>
                        <input type="text" name="judul_tiket" class="inputStyle" required 
                            placeholder="Contoh: AI Revolution Summit Ticket" />
                        @error('judul_tiket')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-tiket">
                        <label class="labelTiket">JUMLAH</label>
                        <input type="number" id="jumlah" name="jumlah_tiket" class="inputStyle" required min="1" value="1" />
                        @error('jumlah_tiket')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-tiket">
                        <label class="labelTiket">HARGA SATUAN (RP)</label>
                        <input type="number" id="harga_satuan" name="harga_satuan" class="inputStyle" required min="0" value="10000" />
                        @error('harga_satuan')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-tiket">
                        <label class="labelTiket">TOTAL HARGA (RP)</label>
                        <input type="number" id="total_harga" name="total_harga" class="inputStyle" readonly value="10000" />
                    </div>
                    <div class="input-tiket">
                        <label class="labelTiket">METODE PEMBAYARAN</label>
                        <select name="metode_pembayaran" class="inputStyle" required>
                            <option value="">- Pilih -</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="COD">COD</option>
                        </select>
                        @error('metode_pembayaran')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="button-tiket">PROSES PEMBELIAN</button>
                </form>
            </div>
        </div>
    </section>

    <section id="speakers" class="speakers">
        <div class="container">
            <div class="section-title">
                <h2>Featured Speakers</h2>
                <p class="text-gray-600 max-w-3xl mx-auto mt-4">Learn from industry experts.</p>
            </div>
            <div class="speaker-grid">
                <div class="speaker-card">
                    <img src="http://static.photos/people/200x200/30" alt="Speaker" class="speaker-img">
                    <h3>Dr. Elena Petrova</h3>
                    <div class="speaker-title">AI Research Lead, DeepMind</div>
                    <p>Specializing in neural networks and deep learning architectures.</p>
                </div>
                <div class="speaker-card">
                    <img src="http://static.photos/people/200x200/31" alt="Speaker" class="speaker-img">
                    <h3>Mark Johnson</h3>
                    <div class="speaker-title">Founder, Web3 Labs</div>
                    <p>Blockchain expert and decentralized application developer.</p>
                </div>
                <div class="speaker-card">
                    <img src="http://static.photos/people/200x200/32" alt="Speaker" class="speaker-img">
                    <h3>Sarah Williams</h3>
                    <div class="speaker-title">CISO, Fortune 500</div>
                    <p>Cybersecurity strategist with 15+ years experience.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="schedule" class="schedule">
        <div class="container">
            <div class="section-title">
                <h2>Event Schedule</h2>
                <p class="text-gray-600 max-w-3xl mx-auto mt-4">Plan your conference experience.</p>
            </div>
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Session</th>
                        <th>Speaker</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>8:30 AM</td>
                        <td>Registration & Breakfast</td>
                        <td>-</td>
                        <td>Lobby</td>
                    </tr>
                    <tr>
                        <td>9:30 AM</td>
                        <td>Keynote: AI in 2024</td>
                        <td>Dr. Elena Petrova</td>
                        <td>Main Hall</td>
                    </tr>
                    <tr>
                        <td>11:00 AM</td>
                        <td>Blockchain Revolution</td>
                        <td>Mark Johnson</td>
                        <td>Room A</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Need Help?</h2>
            <p>Contact our support team for any questions about your tickets or events.</p>
            <a href="mailto:support@cwnxtech.com" class="btn">Contact Support</a>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>CwnXtech</h3>
                    <p>Creating unforgettable conference experiences.</p>
                </div>
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#events">Events</a></li>
                        <li><a href="#tickets">My Tickets</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="mailto:support@cwnxtech.com">Email Support</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; {{ date('Y') }} CwnXtech. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        feather.replace();
        
        // Calculate total harga dynamically
        document.getElementById('jumlah')?.addEventListener('input', function() {
            let jumlah = parseInt(this.value) || 0;
            let harga = parseInt(document.getElementById('harga_satuan').value) || 0;
            document.getElementById('total_harga').value = jumlah * harga;
        });

        document.getElementById('harga_satuan')?.addEventListener('input', function() {
            let jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            let harga = parseInt(this.value) || 0;
            document.getElementById('total_harga').value = jumlah * harga;
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>