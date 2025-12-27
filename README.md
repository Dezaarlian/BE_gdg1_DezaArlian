# 🎟️ Ticket Platform API - The Gatekeeper Logic

Solusi Backend untuk studi kasus "Platform Tiket Event Online". Proyek ini berfokus pada penanganan integritas data transaksi dan pencegahan *overselling* (tiket terjual melebihi kapasitas) menggunakan **Atomic Database Transactions** dan **Row-Level Locking**.

## Fitur Utama

* **Event Management**: Membuat dan melihat daftar event.
* **Booking System**: Pemesanan tiket oleh user.
* **The Gatekeeper Logic**: Mencegah *Race Condition* saat banyak user memesan tiket terakhir secara bersamaan.
* **REST API**: Endpoint berbasis JSON menggunakan Native PHP.

## Tech Stack

* **Language**: PHP 8.x (Native)
* **Database**: MySQL / MariaDB
* **Driver**: PDO (PHP Data Objects)
* **Architecture**: Logic-based (API Folder Structure)

## Struktur Folder

/ticket-api
├── /api
│   ├── events.php       # GET & POST Events
│   └── book.php         # POST Booking (Transaction Logic)
├── /config
│   └── database.php     # Koneksi Database (PDO)
├── database.sql         # Skema Database & Dummy Data
├── ERD_Flowchart.pdf    # Dokumentasi Visual
└── README.md