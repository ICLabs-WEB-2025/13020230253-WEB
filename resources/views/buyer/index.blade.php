<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard - PropertyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding-top: 60px;
        }
        .navbar {
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #e0f7fa !important;
        }
        .container {
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .btn-buy {
            background: #3b82f6;
            color: #fff;
            border-radius: 20px;
            transition: background 0.3s ease;
        }
        .btn-buy:hover {
            background: #2563eb;
        }
        .chat-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #10b981;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        .chat-btn:hover {
            transform: scale(1.1);
        }
        .chat-box {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }
        .chat-box.active {
            display: block;
        }
        .message {
            margin: 0.5rem 0;
            padding: 0.5rem;
            border-radius: 5px;
        }
        .message.sent {
            background: #e0f7fa;
            text-align: right;
        }
        .message.received {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('buyer.index') }}">PropertyApp - Buyer</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('buyer.index') }}">Daftar Rumah</a>
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
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="mb-4" style="color: #1e3a8a;">Daftar Rumah Tersedia</h2>
        <div class="row">
            @forelse ($houses as $house)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        @if ($house->photo_path)
                            <img src="{{ Storage::url($house->photo_path) }}" class="card-img-top" alt="{{ $house->title }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text={{ $house->title }}" class="card-img-top" alt="Placeholder">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $house->title }}</h5>
                            <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($house->price, 0, ',', '.') }}</p>
                            <p class="card-text"><strong>Status:</strong> {{ $house->status }}</p>
                            <p class="card-text"><strong>Agen:</strong> {{ $house->agent->name }}</p>
                            <a href="{{ route('buyer.show', $house->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                            <a href="{{ route('buyer.request', $house->id) }}" class="btn btn-buy btn-sm mt-2">Beli</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Tidak ada rumah tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <button class="chat-btn" onclick="toggleChat()">
        <i class="bi bi-chat-fill"></i>
    </button>
    <div class="chat-box" id="chatBox">
        <div id="chatMessages"></div>
        <form id="chatForm" class="mt-2">
            <input type="text" id="chatInput" class="form-control" placeholder="Ketik pesan...">
            <button type="submit" class="btn btn-primary mt-2">Kirim</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let chatVisible = false;
        function toggleChat() {
            const chatBox = document.getElementById('chatBox');
            chatVisible = !chatVisible;
            chatBox.classList.toggle('active');
        }

        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (message) {
                const chatMessages = document.getElementById('chatMessages');
                const newMessage = document.createElement('div');
                newMessage.classList.add('message', 'sent');
                newMessage.textContent = message;
                chatMessages.appendChild(newMessage);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                input.value = '';

                // Simulasi pesan balasan dari agen (contoh sederhana)
                setTimeout(() => {
                    const reply = document.createElement('div');
                    reply.classList.add('message', 'received');
                    reply.textContent = 'Terima kasih! Agen akan menghubungi Anda.';
                    chatMessages.appendChild(reply);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 1000);
            }
        });
    </script>
</body>
</html>