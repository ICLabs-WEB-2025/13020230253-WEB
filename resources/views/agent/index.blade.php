@extends('layouts.app')

@section('title', 'Dasbor Agen Properti')

@section('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --primary: #4A90E2;
            --primary-dark: #357ABD;
            --primary-light: #D9EEFF;
            --secondary: #6C757D;
            --success: #28A745;
            --info: #17A2B8;
            --warning: #FFC107;
            --danger: #DC3545;
            --text-primary: #343A40;
            --text-secondary: #6C757D;
            --bg-primary: #FFFFFF;
            --bg-secondary: #F8F9FA;
            --border: #E0E0E0;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
            --radius-sm: 0.25rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            padding-top: 60px;
        }

        .listing-container {
            padding: 1rem 0.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .listing-header {
            text-align: center;
            padding: 1rem;
            margin-bottom: 1rem;
            background: var(--bg-primary);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }
        .listing-header h1 {
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        .listing-header p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .dashboard-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .overview-card {
            background: var(--bg-primary);
            padding: 0.75rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: transform 0.2s ease;
            border: 1px solid var(--border);
        }
        .overview-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .overview-card .icon-wrapper {
            background: var(--primary-light);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--primary-dark);
        }
        .overview-card .card-content h4 {
            margin: 0;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.125rem;
        }
        .overview-card .card-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.75rem;
        }
        .overview-card.total-listings .icon-wrapper { color: #2196F3; }
        .overview-card.available-listings .icon-wrapper { color: #4CAF50; }
        .overview-card.sold-listings .icon-wrapper { color: #F44336; }
        .overview-card.unread-messages .icon-wrapper { color: #1976D2; }

        .search-form {
            background: var(--bg-primary);
            padding: 1rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1rem;
            border: 1px solid var(--border);
        }
        .search-form .form-control,
        .search-form .form-select {
            border-radius: var(--radius-sm);
            border-color: var(--border);
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .search-form .form-control:focus,
        .search-form .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(74, 144, 226, 0.2);
        }
        .search-form .btn {
            border-radius: var(--radius-sm);
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        .search-form .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .search-form .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        .search-form .btn-success {
            background-color: var(--success);
            border-color: var(--success);
        }
        .search-form .btn-success:hover {
            background-color: #218838;
        }
        .search-form .justify-content-end {
            margin-top: 0.75rem;
        }

        .empty-state {
            background: var(--bg-primary);
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            text-align: center;
            border: 1px solid var(--border);
        }
        .empty-state i {
            color: var(--primary-light);
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }
        .empty-state h3 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            color: var(--text-secondary);
            max-width: 450px;
            margin: 0 auto 1rem;
        }
        .empty-state .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
        }
        .empty-state .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: transform 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }
        .card-img-top {
            height: 160px;
            object-fit: cover;
            background: var(--bg-secondary);
        }
        .card-body {
            padding: 0.875rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-title {
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .card-text {
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }
        .card-text .fw-bold {
            color: var(--primary);
        }
        .badge {
            font-size: 0.625rem;
            padding: 0.125rem 0.25rem;
            border-radius: var(--radius-sm);
        }
        .mt-auto.d-flex.gap-2 {
            gap: 0.5rem;
            padding-top: 0.5rem;
        }
        .d-flex.gap-2 .btn {
            padding: 0.375rem 0.625rem;
            font-size: 0.75rem;
        }

        .chat-btn {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: var(--info);
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            transition: transform 0.2s ease;
            z-index: 1050;
            font-size: 1.25rem;
        }
        .chat-btn:hover {
            transform: scale(1.1);
            background: #138496;
        }

        .chat-notification-badge {
            top: -3px;
            right: -3px;
            padding: 0.125rem 0.25rem;
            font-size: 0.625rem;
            min-width: 14px;
        }

        .chat-box {
            width: 300px;
            max-height: 400px;
            bottom: 70px;
            right: 15px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
        }
        .chat-box.active {
            display: flex;
        }

        .chat-header {
            padding: 0.5rem;
            border-bottom: 1px solid var(--border);
            background: var(--bg-secondary);
        }
        .chat-close-btn {
            font-size: 0.875rem;
        }

        #conversationList {
            max-height: 100px;
            padding: 0.375rem;
        }
        .conversation-item {
            padding: 0.25rem;
            font-size: 0.75rem;
        }
        .conversation-item .last-message-preview {
            font-size: 0.625rem;
        }

        #chatMessages {
            padding: 0.5rem;
            min-height: 150px;
        }
        .message {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
        }

        .chat-form {
            padding: 0.5rem;
        }
        #chatInput {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .modal-content {
            border-radius: var(--radius-md);
        }
        .modal-header, .modal-body, .modal-footer {
            padding: 0.75rem;
        }
        .modal-title {
            font-size: 1rem;
        }

        @media (max-width: 767px) {
            .dashboard-overview { grid-template-columns: 1fr; }
            .search-form .row { flex-direction: column; }
            .search-form .col-12 { margin-top: 0.5rem; }
            .card-img-top { height: 140px; }
            .chat-box { width: 85%; right: 7.5%; bottom: 60px; }
            .chat-btn { bottom: 10px; right: 10px; }
        }
        @media (max-width: 575px) {
            .card-title { font-size: 0.875rem; }
            .card-text { font-size: 0.7rem; }
            .modal-dialog { margin: 0.5rem; }
        }
    </style>
@endsection

@section('content')
<div class="listing-container">
    <div class="container">
        <div class="listing-header">
            <h1><i class="fas fa-home me-1"></i>Dasbor Agen Properti</h1>
            <p class="text-muted">Kelola properti dan pantau kinerja.</p>
        </div>

        <div class="dashboard-overview">
            <div class="overview-card total-listings">
                <div class="icon-wrapper"><i class="bi bi-house-door-fill"></i></div>
                <div class="card-content">
                    <h4>{{ $totalHouses }}</h4>
                    <p>Total Properti</p>
                </div>
            </div>
            <div class="overview-card available-listings">
                <div class="icon-wrapper"><i class="bi bi-check-circle-fill"></i></div>
                <div class="card-content">
                    <h4>{{ $availableHouses }}</h4>
                    <p>Properti Tersedia</p>
                </div>
            </div>
            <div class="overview-card sold-listings">
                <div class="icon-wrapper"><i class="bi bi-tag-fill"></i></div>
                <div class="card-content">
                    <h4>{{ $soldHouses }}</h4>
                    <p>Properti Terjual</p>
                </div>
            </div>
            <div class="overview-card unread-messages">
                <div class="icon-wrapper"><i class="bi bi-chat-dots-fill"></i></div>
                <div class="card-content">
                    <h4 id="overviewUnreadMessages">0</h4>
                    <p>Pesan Belum Dibaca</p>
                </div>
            </div>
        </div>

        <div class="search-form">
            <form method="GET" action="{{ route('agent.index') }}" id="search-form" class="row g-2 align-items-end">
                <div class="col-md-6 col-lg-5">
                    <label for="search-input" class="visually-hidden">Cari Judul</label>
                    <input type="text" name="search" id="search-input" class="form-control" placeholder="Cari judul..."
                           value="{{ $search ?? '' }}" aria-label="Cari daftar rumah">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label for="sort-by-select" class="visually-hidden">Urutkan</label>
                    <select name="sort_by" id="sort-by-select" class="form-select">
                        <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Harga</option>
                        <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Judul</option>
                        <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                        <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Tanggal</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label for="sort-order-select" class="visually-hidden">Urutan</label>
                    <select name="sort_order" id="sort-order-select" class="form-select">
                        <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Naik</option>
                        <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Turun</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100" id="search-btn">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                </div>
            </form>
            <div class="d-flex justify-content-end mt-1">
                <a href="{{ route('agent.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i>Tambah Properti
                </a>
            </div>
        </div>

        @if ($houses->isEmpty())
            <div class="empty-state">
                <i class="bi bi-house-exclamation"></i>
                <h3>Tidak Ada Properti</h3>
                <p>Belum ada properti. Tambahkan sekarang!</p>
                <a href="{{ route('agent.create') }}" class="btn btn-primary">Tambah</a>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2" id="listing-grid">
                @foreach ($houses as $house)
                    <div class="col">
                        <div class="card h-100" data-house-id="{{ $house->id }}">
                            @php
                                $pathToCheck = ltrim(str_replace('public/', '', $house->photo_path), '/');
                                $imageUrl = Storage::disk('public')->exists($pathToCheck) ? Storage::url($pathToCheck) : 'https://via.placeholder.com/400x220';
                            @endphp
                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $house->title }}" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ Str::limit($house->title, 20) }}</h5>
                                <p class="card-text">Harga: <span class="fw-bold">{{ number_format($house->price, 0, ',', '.') }}</span></p>
                                <p class="card-text">Status: <span class="badge {{ $house->status === 'Tersedia' ? 'bg-success' : 'bg-danger' }}">{{ $house->status }}</span></p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('agent.edit', $house) }}" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-house-id="{{ $house->id }}" data-house-title="{{ $house->title }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-2">
                {{ $houses->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle text-warning me-1"></i>Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Hapus <strong id="house-title"></strong>? Tindakan ini permanen.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
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
        <span id="chatTitle">Live Chat</span>
        <button class="chat-close-btn" onclick="toggleChat"><i class="bi bi-x-lg"></i></button>
    </div>
    <div id="conversationList">
        @forelse ($conversations as $conversation)
            <div class="conversation-item {{ request()->query('conversation_id') == $conversation->id ? 'active-conversation' : '' }}"
                 data-conversation-id="{{ $conversation->id }}" data-buyer-name="{{ $conversation->buyer->name }}">
                <div>
                    <strong>{{ Str::limit($conversation->buyer->name, 12) }}</strong>
                    <div class="last-message-preview">
                        @if ($conversation->messages->isNotEmpty())
                            {{ Str::limit($conversation->messages->first()->message_text, 20) }}
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
            <div class="text-center py-1 text-muted" id="noConversations">Tidak ada percakapan.</div>
        @endforelse
    </div>
    <div id="chatMessages">
        <div class="text-center py-1 text-muted" id="selectConversationPrompt">Pilih percakapan.</div>
    </div>
    <form id="chatForm" class="chat-form">
        <input type="text" id="chatInput" class="form-control mb-1" placeholder="Tulis pesan..." required autocomplete="off" disabled>
        <button type="submit" class="btn btn-primary w-100" id="sendMessageBtn" disabled>Kirim</button>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                const houseId = button.dataset.houseId;
                const houseTitle = button.dataset.houseTitle;
                document.getElementById('delete-form').action = `/agent/houses/${houseId}`;
                document.getElementById('house-title').textContent = houseTitle;
            });
        });

        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');
        const searchBtn = document.getElementById('search-btn');

        searchInput.addEventListener('input', () => {
            searchBtn.disabled = !searchInput.value.trim();
        });

        searchForm.addEventListener('submit', () => {
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
        const overviewUnreadMessages = document.getElementById('overviewUnreadMessages');

        let currentConversationId = null;
        let currentBuyerName = null;
        let pollingInterval;

        function toggleChat() {
            chatVisible = !chatVisible;
            chatBox.classList.toggle('active');
            if (chatVisible) {
                currentConversationId = null;
                currentBuyerName = null;
                chatMessages.innerHTML = '<div class="text-center py-1 text-muted" id="selectConversationPrompt">Pilih percakapan.</div>';
                chatInput.disabled = true;
                sendMessageBtn.disabled = true;
                chatTitle.textContent = 'Live Chat';
                fetchConversations();
                pollingInterval = setInterval(() => {
                    fetchConversations();
                    if (currentConversationId) fetchMessages(currentConversationId);
                }, 3000);
            } else {
                clearInterval(pollingInterval);
            }
            updateNotificationBadge();
        }

        function updateNotificationBadge(totalUnread = null) {
            if (totalUnread === null) {
                totalUnread = Array.from(document.querySelectorAll('.conversation-item .badge.bg-danger')).reduce((sum, badge) => sum + (parseInt(badge.textContent) || 0), 0);
            }
            chatNotificationBadge.textContent = totalUnread;
            chatNotificationBadge.style.display = totalUnread > 0 ? 'block' : 'none';
            overviewUnreadMessages.textContent = totalUnread;
        }

        async function fetchConversations() {
            try {
                const response = await fetch('/agent/conversations', { headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                const data = await response.json();
                conversationList.innerHTML = '';
                let totalUnread = 0;
                if (data.conversations?.length) {
                    data.conversations.forEach(conv => {
                        totalUnread += conv.unread_count || 0;
                        const item = document.createElement('div');
                        item.classList.add('conversation-item', currentConversationId === conv.id ? 'active-conversation' : '');
                        item.dataset.conversationId = conv.id;
                        item.dataset.buyerName = conv.buyer_name || 'Pembeli';
                        item.innerHTML = `<div><strong>${conv.buyer_name || 'Pembeli'}</strong><div class="last-message-preview">${conv.last_message?.message_text?.slice(0, 20) || 'Belum ada pesan'}${conv.last_message?.message_text?.length > 20 ? '...' : ''}</div></div>${conv.unread_count > 0 ? `<span class="badge bg-danger">${conv.unread_count}</span>` : ''}`;
                        item.addEventListener('click', () => selectConversation(conv.id, conv.buyer_name));
                        conversationList.appendChild(item);
                    });
                } else {
                    conversationList.innerHTML = '<div class="text-center py-1 text-muted" id="noConversations">Tidak ada percakapan.</div>';
                }
                updateNotificationBadge(totalUnread);
            } catch (e) {
                console.error('Error:', e);
                conversationList.innerHTML = '<div class="text-center py-1 text-muted" id="noConversations">Gagal memuat.</div>';
            }
        }

        async function selectConversation(conversationId, buyerName) {
            if (currentConversationId === conversationId) return;
            document.querySelectorAll('.conversation-item').forEach(item => item.classList.remove('active-conversation'));
            document.querySelector(`.conversation-item[data-conversation-id="${conversationId}"]`).classList.add('active-conversation');
            currentConversationId = conversationId;
            currentBuyerName = buyerName;
            chatMessages.innerHTML = '';
            chatInput.disabled = false;
            sendMessageBtn.disabled = false;
            chatTitle.textContent = `Obrolan dengan ${buyerName}`;
            selectConversationPrompt.style.display = 'none';
            await fetchMessages(conversationId);
            chatInput.focus();
            markConversationAsRead(conversationId);
        }

        async function markConversationAsRead(conversationId) {
            try {
                await fetch(`/agent/conversations/${conversationId}/mark-as-read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
            } catch (e) {
                console.error('Error:', e);
            }
        }

        function appendMessage(message, isSentByMe) {
            const div = document.createElement('div');
            div.classList.add('message', isSentByMe ? 'sent' : 'received');
            div.innerHTML = `<strong>${isSentByMe ? 'Anda' : message.sender_name || 'Pembeli'}:</strong> ${message.message_text}`;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        async function fetchMessages(conversationId) {
            try {
                const response = await fetch(`/agent/chat/messages/${conversationId}`, { headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                const data = await response.json();
                data.messages?.forEach(msg => appendMessage(msg, msg.sender_type === 'agent' && msg.sender_id === currentUserId));
                chatMessages.scrollTop = chatMessages.scrollHeight;
            } catch (e) {
                console.error('Error:', e);
                chatMessages.innerHTML = '<div class="text-center py-1 text-danger">Gagal memuat.</div>';
            }
        }

        async function sendMessage(messageText) {
            if (!currentConversationId) return alert('Pilih percakapan terlebih dahulu.');
            try {
                sendMessageBtn.disabled = true;
                sendMessageBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                const response = await fetch(`/agent/chat/send/${currentConversationId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message_text: messageText })
                });
                const data = await response.json();
                appendMessage(data.sent_message, true);
                chatInput.value = '';
                const activeItem = document.querySelector(`.conversation-item[data-conversation-id="${currentConversationId}"]`);
                if (activeItem) {
                    activeItem.querySelector('.last-message-preview').textContent = messageText.length > 20 ? messageText.slice(0, 17) + '...' : messageText;
                    conversationList.prepend(activeItem);
                }
            } catch (e) {
                console.error('Error:', e);
                alert('Gagal: ' + e.message);
            } finally {
                sendMessageBtn.disabled = false;
                sendMessageBtn.innerHTML = 'Kirim';
            }
        }

        conversationList.addEventListener('click', e => {
            const item = e.target.closest('.conversation-item');
            if (item) selectConversation(item.dataset.conversationId, item.dataset.buyerName);
        });

        chatForm.addEventListener('submit', e => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (message) sendMessage(message);
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateNotificationBadge();
            const urlParams = new URLSearchParams(window.location.search);
            const conversationId = urlParams.get('conversation_id');
            if (conversationId) {
                const item = document.querySelector(`.conversation-item[data-conversation-id="${conversationId}"]`);
                if (item) {
                    item.click();
                    chatVisible = true;
                    chatBox.classList.add('active');
                }
            }
            fetchConversations();
        });
    </script>
@endsection