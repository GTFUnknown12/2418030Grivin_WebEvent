<?php

namespace Database\Seeders;

use App\Models\Pembeli;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        Pembeli::create([
            'nama_pembeli' => 'Admin User',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'alamat' => 'Admin Address',
            'email' => 'admin@cwnxtech.com',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'is_admin' => true,
        ]);

        // Create regular user
        $pembeli = Pembeli::create([
            'nama_pembeli' => 'John Doe',
            'username' => 'johndoe',
            'password' => Hash::make('password123'),
            'alamat' => '123 Main Street',
            'email' => 'john@example.com',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1995-05-15',
            'is_admin' => false,
        ]);

        // Create events
        $events = [
            [
                'title' => 'AI Revolution Summit',
                'description' => 'Explore the future of artificial intelligence with leading researchers and industry pioneers.',
                'category' => 'Tech',
                'start_date' => '2024-11-15',
                'end_date' => '2024-11-17',
                'location' => 'San Francisco',
                'price' => 499.00,
                'max_attendees' => 250,
                'image_url' => 'http://static.photos/technology/640x360/20',
                'is_active' => true,
            ],
            [
                'title' => 'Web3 & Blockchain Expo',
                'description' => 'Dive into decentralized technologies, NFTs, and the future of digital ownership.',
                'category' => 'Blockchain',
                'start_date' => '2024-12-05',
                'end_date' => '2024-12-07',
                'location' => 'New York',
                'price' => 599.00,
                'max_attendees' => 300,
                'image_url' => 'http://static.photos/technology/640x360/21',
                'is_active' => true,
            ],
            [
                'title' => 'Cyber Security Forum',
                'description' => 'Learn from top security experts about protecting digital assets in an evolving threat landscape.',
                'category' => 'Security',
                'start_date' => '2025-01-10',
                'end_date' => '2025-01-12',
                'location' => 'London',
                'price' => 549.00,
                'max_attendees' => 200,
                'image_url' => 'http://static.photos/technology/640x360/22',
                'is_active' => true,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        // Create tickets for the user
        $ticket = Ticket::create([
            'pembeli_id' => $pembeli->id_pembeli,
            'judul_tiket' => 'AI Revolution Summit Ticket',
            'jumlah_tiket' => 2,
            'harga_satuan' => 10000,
            'total_harga' => 20000,
            'metode_pembayaran' => 'Transfer Bank',
            'status_pembayaran' => 'completed',
        ]);

        // Create transaction for the ticket
        Transaction::create([
            'transaction_id' => 'TRX' . time() . rand(1000, 9999),
            'pembeli_id' => $pembeli->id_pembeli,
            'ticket_id' => $ticket->id_tiket,
            'amount' => 20000,
            'type' => 'payment',
            'status' => 'completed',
            'payment_method' => 'Transfer Bank',
            'description' => 'Payment for AI Revolution Summit tickets',
        ]);
    }
}