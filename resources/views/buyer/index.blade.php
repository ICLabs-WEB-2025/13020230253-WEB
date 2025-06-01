<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembeli - PropertyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #5A67D8; /* Biru keunguan yang lebih lembut */
            --primary-dark: #434190; /* Warna gelap untuk kontras */
            --primary-light: #C3DAFE; /* Warna terang untuk highlight */
            --secondary: #6B7280;
            --success: #10B981;
            --info: #38B2AC; /* Warna baru untuk info/status */
            --warning: #F6AD55; /* Warna untuk status peringatan */
            --danger: #EF4444; /* Warna untuk kesalahan */
            --text-primary: #2D3748;
            --text-secondary: #718096;
            --bg-primary: #ffffff;
            --bg-secondary: #F7FAFC; /* Latar belakang yang lebih terang */
            --border: #EDF2F7;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            --radius-lg: 1rem; /* Radius sudut yang lebih besar */
            --radius-md: 0.75rem;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-secondary);
            margin: 0;
            padding-top: 70px; /* Sesuaikan dengan tinggi navbar baru */
            color: var(--text-primary);
        }

        .navbar {
            background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: var(--shadow-md);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            padding: 0.75rem 0; /* Padding navbar */
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            transition: color 0.3s ease, background-color 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
        }

        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .container {
            padding: 2.5rem 1.5rem; /* Padding lebih besar */
            max-width: 1300px; /* Lebar maksimum yang sedikit lebih besar */
            margin: 0 auto;
        }

        h2 {
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 2.5rem; /* Jarak lebih besar */
        }

        .alert {
            border-radius: var(--radius-md);
            margin-top: 1.5rem;
            font-weight: 500;
        }

        .card {
            border: 1px solid var(--border); /* Border halus */
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: var(--bg-primary);
            box-shadow: var(--shadow-sm);
        }

        .card:hover {
            transform: translateY(-10px); /* Efek hover lebih menonjol */
            box-shadow: var(--shadow-lg);
        }

        .card-img-top {
            height: 220px; /* Tinggi gambar sedikit lebih besar */
            object-fit: cover;
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .card-text {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }
        .card-text strong {
            color: var(--text-primary);
        }

        .btn-action {
            padding: 0.5rem 1.25rem;
            border-radius: 50px; /* Tombol lebih bulat */
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .btn-buy {
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
        }
        .btn-buy:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* Status Badges */
        .badge-status {
            display: inline-block;
            padding: 0.3em 0.6em;
            font-size: 0.75em;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem; /* Rounded corners */
            margin-left: 0.5rem;
        }

        .status-tersedia { background-color: var(--success); color: #fff; }
        .status-terjual { background-color: var(--danger); color: #fff; }
        .status-pending { background-color: var(--warning); color: #fff; }

        .chat-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--info); /* Warna chat button yang lebih menarik */
            color: #fff;
            border-radius: 50%;
            width: 65px; /* Sedikit lebih besar */
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, background-color 0.3s ease;
            z-index: 1040;
            font-size: 1.8rem; /* Ukuran ikon chat */
        }

        .chat-btn:hover {
            transform: scale(1.15); /* Efek hover lebih jelas */
            background: #2C7A7B; /* Warna hover untuk chat */
        }

        .chat-box {
            display: none;
            position: fixed;
            bottom: 110px; /* Sesuaikan posisi di atas tombol chat */
            right: 30px;
            width: 320px; /* Lebar chat box sedikit lebih besar */
            background: var(--bg-primary);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 1.2rem;
            max-height: 450px;
            overflow-y: auto;
            z-index: 1040;
            animation: fadeIn 0.3s ease-out; /* Animasi muncul */
            border: 1px solid var(--border);
        }

        .chat-box.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-header {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .chat-close-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--secondary);
            cursor: pointer;
            transition: color 0.2s ease;
        }
        .chat-close-btn:hover {
            color: var(--danger);
        }

        #chatMessages {
            min-height: 100px;
            max-height: 250px;
            overflow-y: auto;
            padding-right: 5px; /* Untuk scrollbar */
            margin-bottom: 1rem;
        }
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }
        #chatMessages::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 10px;
        }
        #chatMessages::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }

        .message {
            margin-bottom: 0.75rem;
            padding: 0.7rem 1rem;
            border-radius: var(--radius-md);
            max-width: 85%;
            word-wrap: break-word;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .message.sent {
            background: var(--primary-light);
            color: var(--text-primary);
            margin-left: auto;
            text-align: left; /* Sesuaikan teks kiri untuk pesan sendiri */
            border-bottom-right-radius: 5px; /* Bentuk gelembung chat */
        }

        .message.received {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            margin-right: auto;
            border-bottom-left-radius: 5px; /* Bentuk gelembung chat */
        }

        #chatInput {
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        #chatInput:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--primary-dark), 0.25);
            border-color: var(--primary);
        }

        .chat-form .btn {
            border-radius: var(--radius-md);
            font-weight: 600;
            background-color: var(--success);
            border-color: var(--success);
            padding: 0.75rem 1.25rem;
        }
        .chat-form .btn:hover {
            background-color: #0B9C6B;
            border-color: #0B9C6B;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .navbar-brand {
                font-size: 1.3rem;
            }
            .nav-link {
                padding: 0.4rem 0.8rem;
            }
            .card-img-top {
                height: 180px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 56px; /* Lebih kecil untuk navbar mobile */
            }
            .navbar-toggler {
                padding: 0.25rem 0.5rem;
                font-size: 1rem;
            }
            .navbar-nav {
                text-align: center;
                margin-top: 1rem;
                background-color: var(--primary-dark);
                border-radius: var(--radius-md);
                padding: 1rem 0;
            }
            .nav-item {
                margin: 0.5rem 0;
            }
            .container {
                padding: 1.5rem 1rem;
            }
            .card-img-top {
                height: 160px;
            }
            .chat-box {
                width: 90%;
                right: 5%;
                bottom: 100px;
            }
            .chat-btn {
                bottom: 15px;
                right: 15px;
                width: 55px;
                height: 55px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .card-img-top {
                height: 140px;
            }
            .card-body {
                padding: 1rem;
            }
            .card-title {
                font-size: 1.15rem;
            }
            .card-text {
                font-size: 0.9rem;
            }
            .btn-action {
                padding: 0.4rem 1rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('buyer.index') }}">PropertyApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('buyer.index') }}">Daftar Rumah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('buyer.offers') }}">Penawaran Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="text-center">Temukan Rumah Impian Anda</h2>
        <p class="text-center text-muted mb-5">Jelajahi properti terbaik yang tersedia untuk Anda.</p>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse ($houses as $house)
                <div class="col">
                    <div class="card h-100">
                        @php
                            // Membersihkan jalur dengan menghapus 'public/' dan tanda '/' awal
                            $pathToCheck = ltrim(str_replace('public/', '', $house->photo_path), '/');
                            // Memeriksa apakah file ada di storage
                            $fileExists = $house->photo_path ? Storage::disk('public')->exists($pathToCheck) : false;
                            // Menentukan URL gambar: gunakan file jika ada, atau placeholder jika tidak
                            $imageUrl = $fileExists ? Storage::url($pathToCheck) : 'https://via.placeholder.com/400x220?text=Gambar+Tidak+Tersedia';
                        @endphp
                        <img src="{{ $imageUrl }}"
                             class="card-img-top"
                             alt="{{ $house->title }}"
                             loading="lazy">
                        <div class="card-body d-flex flex-column">
                            <div class="flex-grow-1">
                                <h5 class="card-title">{{ Str::limit($house->title, 40) }}</h5>
                                <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($house->price, 0, ',', '.') }}</p>
                                <p class="card-text">
                                    <strong>Status:</strong>
                                    <span class="badge-status status-{{ Str::slug($house->status) }}">
                                        {{ $house->status }}
                                    </span>
                                </p>
                                <p class="card-text"><strong>Agen:</strong> {{ Str::limit($house->agent->name, 25) }}</p>
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('buyer.show', $house->id) }}" class="btn btn-outline-primary btn-action">Lihat Detail</a>
                                <a href="{{ route('buyer.request', $house->id) }}" class="btn btn-buy btn-action">Ajukan Penawaran</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i> Maaf, saat ini tidak ada rumah yang tersedia. Silakan cek kembali nanti!
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <button class="chat-btn" onclick="toggleChat()">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <span>Live Chat dengan Agen</span>
            <button class="chat-close-btn" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div id="chatMessages" class="mb-3"></div>
        <form id="chatForm" class="chat-form">
            <input type="text" id="chatInput" class="form-control mb-2" placeholder="Ketik pesan Anda di sini..." required autocomplete="off">
            <button type="submit" class="btn btn-success w-100">Kirim Pesan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>
        let chatVisible = false;
        const chatBox = document.getElementById('chatBox');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const chatForm = document.getElementById('chatForm');

        function toggleChat() {
            chatVisible = !chatVisible;
            chatBox.classList.toggle('active');
            if (chatVisible) {
                // Scroll ke bawah saat chat box dibuka
                chatMessages.scrollTop = chatMessages.scrollHeight;
                chatInput.focus(); // Fokus ke input pesan
            }
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (message) {
                // Tambahkan pesan yang dikirim
                const newMessage = document.createElement('div');
                newMessage.classList.add('message', 'sent');
                newMessage.textContent = message;
                chatMessages.appendChild(newMessage);
                chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll ke bawah
                chatInput.value = ''; // Kosongkan input

                // Simulasi pesan balasan dari agen
                setTimeout(() => {
                    const reply = document.createElement('div');
                    reply.classList.add('message', 'received');
                    reply.innerHTML = '<span style="font-weight: 600; color: var(--primary-dark);">Agen:</span> Terima kasih! Agen akan menghubungi Anda segera melalui email atau telepon. Mohon tunggu balasan kami.';
                    chatMessages.appendChild(reply);
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll ke bawah
                }, 1500); // Penundaan sedikit lebih lama
            }
        });
    </script>
</body>
</html>