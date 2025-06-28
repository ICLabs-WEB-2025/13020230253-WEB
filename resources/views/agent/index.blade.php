@extends('layouts.app')

@section('title', 'Daftar Rumah Agen')

@section('styles')
    <style>
        :root {
            --primary: #5A67D8;
            --primary-dark: #434190;
            --primary-light: #C3DAFE;
            --secondary: #6B7280;
            --success: #10B981;
            --info: #38B2AC;
            --warning: #F6AD55;
            --danger: #EF4444;
            --text-primary: #2D3748;
            --text-secondary: #718096;
            --bg-primary: #ffffff;
            --bg-secondary: #F7FAFC;
            --border: #EDF2F7;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            --radius-lg: 1rem;
            --radius-md: 0.75rem;
        }

        .chat-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--info);
            color: #fff;
            border-radius: 50%;
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, background-color 0.3s ease;
            z-index: 1040;
            font-size: 1.8rem;
            cursor: pointer;
            border: 2px solid #333;
            position: relative;
        }

        .chat-btn:hover {
            transform: scale(1.15);
            background: #2C7A7B;
            border-color: #333;
        }

        .chat-notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            padding: 0.3em 0.6em;
            font-size: 0.8rem;
            font-weight: bold;
            display: none;
            z-index: 1050;
            border: 2px solid var(--bg-primary);
            min-width: 25px;
            text-align: center;
            line-height: 1.2;
        }

        .chat-box {
            display: none;
            position: fixed;
            bottom: 110px;
            right: 30px;
            width: 380px;
            background: var(--bg-primary);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            max-height: 550px;
            overflow: hidden;
            z-index: 1040;
            animation: fadeIn 0.3s ease-out;
            border: 1px solid var(--border);
            flex-direction: column;
        }

        .chat-box.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-header {
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background-color: var(--bg-primary);
            border-top-left-radius: var(--radius-lg);
            border-top-right-radius: var(--radius-lg);
        }

        .chat-close-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color 0.2s ease;
        }
        .chat-close-btn:hover {
            color: var(--danger);
        }

        #conversationList {
            padding: 0.8rem 1.5rem;
            border-bottom: 1px solid var(--border);
            max-height: 150px;
            overflow-y: auto;
            background-color: var(--bg-secondary);
        }
        #conversationList::-webkit-scrollbar {
            width: 8px;
        }
        #conversationList::-webkit-scrollbar-track {
            background: var(--border);
            border-radius: 10px;
        }
        #conversationList::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }

        .conversation-item {
            padding: 0.7rem 1rem;
            cursor: pointer;
            border-radius: var(--radius-md);
            margin-bottom: 5px;
            transition: background-color 0.2s ease;
            font-size: 0.95rem;
            color: var(--text-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .conversation-item:hover {
            background-color: #e0e7ee;
        }
        .conversation-item.active-conversation {
            background-color: var(--primary);
            color: #fff;
            font-weight: 600;
        }
        .conversation-item.active-conversation:hover {
            background-color: var(--primary);
        }
        .conversation-item .last-message-preview {
            font-size: 0.85rem;
            color: var(--text-secondary);
            max-width: 70%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .conversation-item.active-conversation .last-message-preview {
            color: rgba(255, 255, 255, 0.8);
        }

        #chatMessages {
            flex-grow: 1;
            padding: 1.5rem;
            overflow-y: auto;
            min-height: 150px;
            background-color: var(--bg-primary);
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
            box-shadow: var(--shadow-sm);
        }

        .message.sent {
            background: var(--primary-light);
            color: var(--text-primary);
            margin-left: auto;
            text-align: left;
            border-bottom-right-radius: 5px;
        }

        .message.received {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            margin-right: auto;
            border-bottom-left-radius: 5px;
        }

        .chat-form {
            padding: 1rem 1.5rem 1.5rem;
            border-top: 1px solid var(--border);
            background-color: var(--bg-primary);
            border-bottom-left-radius: var(--radius-lg);
            border-bottom-right-radius: var(--radius-lg);
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
            outline: none;
        }

        .chat-form .btn {
            border-radius: var(--radius-md);
            font-weight: 600;
            background-color: var(--success);
            border-color: var(--success);
            padding: 0.75rem 1.25rem;
            margin-top: 0.75rem;
        }
        .chat-form .btn:hover {
            background-color: #0B9C6B;
            border-color: #0B9C6B;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: var(--bg-primary);
            box-shadow: var(--shadow-sm);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }
        .card-text {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }
        .badge {
            font-size: 0.75em;
            font-weight: 600;
        }
        .bg-success { background-color: var(--success); }
        .bg-danger { background-color: var(--danger); }
        .bg-warning { background-color: var(--warning); }

        @media (max-width: 768px) {
            .chat-box {
                width: 95%;
                right: 2.5%;
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
    </style>
@endsection

@section('content')
<div class="listing-container">
    <div class="container">
        <div class="listing-header">
            <h1><i class="fas fa-home me-2"></i>Daftar Rumah Saya</h1>
            <p class="text-muted">Kelola daftar properti Anda dengan mudah.</p>
        </div>

        <div class="search-form">
            <form method="GET" action="{{ route('agent.index') }}" id="search-form" class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-5">
                    <label for="search-input" class="form-label visually-hidden">Cari Judul Properti</label>
                    <input type="text" name="search" id="search-input" class="form-control" placeholder="Cari berdasarkan judul properti..."
                           value="{{ $search ?? '' }}" aria-label="Cari daftar rumah">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label for="sort-by-select" class="form-label visually-hidden">Urutkan Berdasarkan</label>
                    <select name="sort_by" id="sort-by-select" class="form-select" aria-label="Urutkan berdasarkan">
                        <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Harga</option>
                        <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Judul</option>
                        <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                        <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Tanggal Ditambahkan</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label for="sort-order-select" class="form-label visually-hidden">Urutan</label>
                    <select name="sort_order" id="sort-order-select" class="form-select" aria-label="Urutan pengurutan">
                        <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Naik</option>
                        <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Turun</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1" id="search-btn">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </form>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('agent.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Rumah Baru
                </a>
            </div>
        </div>

        @if ($houses->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                <h3>Ups! Tidak Ada Daftar Rumah Ditemukan</h3>
                <p>Sepertinya Anda belum menambahkan properti. Mulai dengan menambahkan rumah baru ke portofolio Anda sekarang!</p>
                <a href="{{ route('agent.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Rumah Baru
                </a>
            </div>
        @else
            <div class="row" id="listing-grid">
                @foreach ($houses as $house)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100" data-house-id="{{ $house->id }}">
                            @php
                                $pathToCheck = ltrim(str_replace('public/', '', $house->photo_path), '/');
                                $fileExists = $house->photo_path ? Storage::disk('public')->exists($pathToCheck) : false;
                                $imageUrl = $fileExists ? Storage::url($pathToCheck) : 'https://via.placeholder.com/400x220?text=Gambar+Tidak+Tersedia';
                            @endphp
                            <img src="{{ $imageUrl }}" class="card-img-top lazy" alt="{{ $house->title }}" loading="lazy">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($house->title, 30) }}</h5>
                                <p class="card-text">Harga: <span class="fw-bold text-primary">Rp {{ number_format($house->price, 0, ',', '.') }}</span></p>
                                <p class="card-text">Status:
                                    <span class="badge {{ $house->status === 'Tersedia' ? 'bg-success' : ($house->status === 'Terjual' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $house->status }}
                                    </span>
                                </p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('agent.edit', $house) }}" class="btn btn-primary btn-sm flex-grow-1" aria-label="Edit {{ $house->title }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-house-id="{{ $house->id }}" data-house-title="{{ $house->title }}" aria-label="Hapus {{ $house->title }}">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $houses->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus properti <strong id="house-title"></strong>? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i>Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<button class="chat-btn" onclick="toggleChat()">
    <i class="bi bi-chat-dots-fill"></i>
    <span class="chat-notification-badge" id="chatNotificationBadge">0</span>
</button>

<div class="chat-box" id="chatBox">
    <div class="chat-header">
        <span id="chatTitle">Live Chat dengan Pembeli</span>
        <button class="chat-close-btn" onclick="toggleChat()"><i class="bi bi-x-lg"></i></button>
    </div>

    <div id="conversationList">
        @forelse ($conversations as $conversation)
            <div class="conversation-item {{ request()->query('conversation_id') == $conversation->id ? 'active-conversation' : '' }}"
                 data-conversation-id="{{ $conversation->id }}"
                 data-buyer-name="{{ $conversation->buyer->name }}">
                <div>
                    <strong>{{ Str::limit($conversation->buyer->name, 20) }}</strong>
                    <div class="last-message-preview">
                        @if ($conversation->messages->isNotEmpty())
                            {{ Str::limit($conversation->messages->first()->message_text, 30) }}
                        @else
                            Belum ada pesan
                        @endif
                    </div>
                </div>
                @if ($conversation->unread_count > 0)
                    <span class="badge bg-danger">{{ $conversation->unread_count }}</span>
                @endif
            </div>
        @empty
            <div class="text-center py-3 text-muted" id="noConversations">Tidak ada percakapan baru.</div>
        @endforelse
    </div>

    <div id="chatMessages">
        <div class="text-center py-3 text-muted" id="selectConversationPrompt">Pilih percakapan untuk memulai chat.</div>
    </div>

    <form id="chatForm" class="chat-form">
        <input type="text" id="chatInput" class="form-control mb-2" placeholder="Ketik pesan Anda di sini..." required autocomplete="off" disabled>
        <button type="submit" class="btn btn-success w-100" id="sendMessageBtn" disabled>Kirim Pesan</button>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const houseId = this.dataset.houseId;
                const houseTitle = this.dataset.houseTitle;
                const form = document.getElementById('delete-form');
                const titleElement = document.getElementById('house-title');

                form.action = `/agent/houses/${houseId}`;
                titleElement.textContent = houseTitle;
            });
        });

        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');
        const searchBtn = document.getElementById('search-btn');

        searchBtn.disabled = false;
        searchBtn.classList.remove('btn-secondary');
        searchBtn.classList.add('btn-primary');

        searchInput.addEventListener('input', function () {
            searchBtn.disabled = false;
            searchBtn.classList.remove('btn-secondary');
            searchBtn.classList.add('btn-primary');
        });

        searchForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        });

        let chatVisible = false;
        const chatBox = document.getElementById('chatBox');
        const conversationList = document.getElementById('conversationList');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const chatForm = document.getElementById('chatForm');
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const chatTitle = document.getElementById('chatTitle');
        const selectConversationPrompt = document.getElementById('selectConversationPrompt');
        const chatNotificationBadge = document.getElementById('chatNotificationBadge');
        let currentConversationId = null;
        let currentBuyerName = null;
        let pollingInterval;
        let lastMessageId = 0;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function toggleChat() {
            chatVisible = !chatVisible;
            chatBox.classList.toggle('active');

            if (chatVisible) {
                currentConversationId = null;
                currentBuyerName = null;
                lastMessageId = 0;
                chatMessages.innerHTML = '<div class="text-center py-3 text-muted" id="selectConversationPrompt">Pilih percakapan untuk memulai chat.</div>';
                chatInput.value = '';
                chatInput.disabled = true;
                sendMessageBtn.disabled = true;
                chatTitle.textContent = 'Live Chat dengan Pembeli';
                selectConversationPrompt.style.display = 'block';
                updateNotificationBadge();
                pollingInterval = setInterval(() => {
                    fetchConversations();
                    if (currentConversationId) {
                        fetchMessages(currentConversationId);
                    }
                }, 3000);
            } else {
                clearInterval(pollingInterval);
                updateNotificationBadge();
            }
        }

        function updateNotificationBadge() {
            let totalUnread = 0;
            document.querySelectorAll('.conversation-item .badge.bg-danger').forEach(badge => {
                totalUnread += parseInt(badge.textContent);
            });
            chatNotificationBadge.textContent = totalUnread;
            chatNotificationBadge.style.display = totalUnread > 0 ? 'block' : 'none';
        }

        async function fetchConversations() {
            try {
                const response = await fetch('/agent/conversations', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                conversationList.innerHTML = '';
                let totalUnreadCount = 0;

                if (data.conversations && data.conversations.length > 0) {
                    data.conversations.forEach(conv => {
                        totalUnreadCount += conv.unread_count;
                        const conversationItem = document.createElement('div');
                        conversationItem.classList.add('conversation-item');
                        if (currentConversationId === conv.id) {
                            conversationItem.classList.add('active-conversation');
                        }
                        conversationItem.dataset.conversationId = conv.id;
                        conversationItem.dataset.buyerName = conv.buyer_name || 'Pembeli Tak Dikenal';

                        let lastMessagePreview = conv.last_message ? conv.last_message.message_text : 'Belum ada pesan';
                        if (lastMessagePreview.length > 30) {
                            lastMessagePreview = lastMessagePreview.substring(0, 27) + '...';
                        }

                        conversationItem.innerHTML = `
                            <div>
                                <strong>${conv.buyer_name || 'Pembeli'}</strong>
                                <div class="last-message-preview">${lastMessagePreview}</div>
                            </div>
                            ${conv.unread_count > 0 ? `<span class="badge bg-danger">${conv.unread_count}</span>` : ''}
                        `;
                        conversationItem.addEventListener('click', () => selectConversation(conv.id, conv.buyer_name));
                        conversationList.appendChild(conversationItem);
                    });
                    document.getElementById('noConversations').style.display = 'none';
                } else {
                    document.getElementById('noConversations').style.display = 'block';
                }

                updateNotificationBadge(totalUnreadCount);
            } catch (error) {
                console.error("Error fetching conversations:", error);
                document.getElementById('noConversations').style.display = 'block';
                document.getElementById('noConversations').textContent = 'Gagal memuat percakapan.';
            }
        }

        async function selectConversation(conversationId, buyerName) {
            if (currentConversationId === conversationId) return;

            document.querySelectorAll('.conversation-item').forEach(item => item.classList.remove('active-conversation'));
            const selectedConv = document.querySelector(`.conversation-item[data-conversation-id="${conversationId}"]`);
            if (selectedConv) {
                selectedConv.classList.add('active-conversation');
            }

            currentConversationId = conversationId;
            currentBuyerName = buyerName;
            lastMessageId = 0;
            chatMessages.innerHTML = '';
            chatInput.disabled = false;
            sendMessageBtn.disabled = false;
            chatTitle.textContent = `Chat dengan ${buyerName}`;
            selectConversationPrompt.style.display = 'none';
            fetchMessages(conversationId);
            chatInput.focus();
        }

        function appendMessage(message, isSentByMe) {
            const newMessageDiv = document.createElement('div');
            newMessageDiv.classList.add('message');
            newMessageDiv.classList.add(isSentByMe ? 'sent' : 'received');
            newMessageDiv.innerHTML = isSentByMe
                ? `<strong>Anda:</strong> ${message.message_text}`
                : `<strong>${message.sender_name || 'Pembeli'}:</strong> ${message.message_text}`;
            chatMessages.appendChild(newMessageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            if (message.id > lastMessageId) {
                lastMessageId = message.id;
            }
        }

        async function fetchMessages(conversationId) {
            if (!conversationId) return;

            try {
                const response = await fetch(`/agent/chat/messages/${conversationId}?last_message_id=${lastMessageId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                selectConversationPrompt.style.display = 'none';
                data.messages.forEach(msg => {
                    const isSentByMe = msg.sender_type === 'agent' && msg.sender_id === currentUserId;
                    appendMessage(msg, isSentByMe);
                });

                const activeItem = document.querySelector(`.conversation-item[data-conversation-id="${conversationId}"]`);
                if (activeItem) {
                    const unreadBadge = activeItem.querySelector('.badge.bg-danger');
                    if (unreadBadge) {
                        unreadBadge.remove();
                        updateNotificationBadge();
                    }
                }
            } catch (error) {
                console.error("Error fetching messages:", error);
                chatMessages.innerHTML = '<div class="text-center py-3 text-danger">Gagal memuat pesan.</div>';
            }
        }

        async function sendMessage(messageText) {
            if (!currentConversationId) {
                alert('Pilih percakapan terlebih dahulu.');
                return;
            }

            try {
                sendMessageBtn.disabled = true;
                sendMessageBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kirim...';

                const payload = { message_text: messageText };

                const response = await fetch(`/agent/chat/send/${currentConversationId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                appendMessage(data.sent_message, true);
                chatInput.value = '';
                chatInput.focus();

                const activeItem = document.querySelector(`.conversation-item[data-conversation-id="${currentConversationId}"]`);
                if (activeItem) {
                    const preview = activeItem.querySelector('.last-message-preview');
                    preview.textContent = messageText.length > 30 ? messageText.substring(0, 27) + '...' : messageText;
                }
            } catch (error) {
                console.error("Error sending message:", error);
                alert('Gagal mengirim pesan: ' + error.message);
            } finally {
                sendMessageBtn.disabled = false;
                sendMessageBtn.innerHTML = 'Kirim Pesan';
            }
        }

        conversationList.addEventListener('click', function(e) {
            const conversationItem = e.target.closest('.conversation-item');
            if (conversationItem) {
                const conversationId = conversationItem.dataset.conversationId;
                const buyerName = conversationItem.dataset.buyerName;
                selectConversation(conversationId, buyerName);
            }
        });

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (message) {
                sendMessage(message);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateNotificationBadge();
            const urlParams = new URLSearchParams(window.location.search);
            const conversationId = urlParams.get('conversation_id');
            if (conversationId) {
                const conversationItem = document.querySelector(`.conversation-item[data-conversation-id="${conversationId}"]`);
                if (conversationItem) {
                    conversationItem.click();
                    chatVisible = true;
                    chatBox.classList.add('active');
                }
            }
            fetchConversations();
        });
    </script>
@endsection