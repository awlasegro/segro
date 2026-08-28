@extends('admin.layout.master')

@section('content')
<style>
    .chat-console { display: flex; height: 78vh; min-height: 520px; background: #fff; border: 1px solid #e2e5eb; border-radius: 8px; overflow: hidden; margin: 0 15px 20px; }

    /* Left: conversation list */
    .chat-console-list { width: 280px; flex-shrink: 0; border-right: 1px solid #e2e5eb; display: flex; flex-direction: column; }
    .chat-console-list-header { padding: 16px; font-weight: 700; font-size: 15px; color: #121213; border-bottom: 1px solid #e2e5eb; }
    .chat-console-list-items { flex: 1; overflow-y: auto; }
    .chat-console-list-item { display: flex; align-items: center; gap: 10px; padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #f0f1f4; transition: background-color .15s; }
    .chat-console-list-item:hover { background: #f8f9fb; }
    .chat-console-list-item.active { background: #fdeef0; border-left: 3px solid #CC171F; padding-left: 13px; }
    .chat-console-list-item-body { flex: 1; min-width: 0; }
    .chat-console-list-item-top { display: flex; justify-content: space-between; align-items: baseline; gap: 6px; }
    .chat-console-list-item-name { font-size: 13px; font-weight: 700; color: #121213; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .chat-console-list-item-time { font-size: 10.5px; color: #888892; flex-shrink: 0; }
    .chat-console-list-item-preview { font-size: 12px; color: #888892; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-top: 2px; }
    .chat-console-unread-badge { background: #CC171F; color: #fff; font-size: 10.5px; font-weight: 700; min-width: 18px; height: 18px; border-radius: 9px; display: flex; align-items: center; justify-content: center; padding: 0 5px; flex-shrink: 0; }
    .chat-console-empty-list { padding: 24px 16px; color: #888892; font-size: 13px; text-align: center; }

    /* Avatars */
    .chat-console-avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #CC171F, #9B0F15); color: #fff; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* Center: thread */
    .chat-console-thread { flex: 1; display: flex; flex-direction: column; min-width: 0; }
    .chat-console-thread-header { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #e2e5eb; font-weight: 700; font-size: 15px; color: #121213; }
    .chat-console-messages { flex: 1; overflow-y: auto; padding: 18px; background: #f4f5f8; display: flex; flex-direction: column; gap: 10px; }
    .chat-console-day-divider { text-align: center; margin: 8px 0; }
    .chat-console-day-divider span { display: inline-block; background: #e9eaee; color: #6b6d72; font-size: 10.5px; font-weight: 600; padding: 3px 12px; border-radius: 12px; }
    .chat-console-empty-thread { text-align: center; color: #888892; font-size: 13px; margin: auto; }
    .chat-console-empty-thread-wrap { flex: 1; display: flex; align-items: center; justify-content: center; color: #888892; font-size: 13px; }

    .admin-chat-bubble { max-width: 70%; padding: 10px 14px; border-radius: 12px; font-size: 13.5px; line-height: 1.4; word-break: break-word; }
    .admin-chat-bubble.from-user { background: #fff; border: 1px solid #e2e5eb; align-self: flex-start; }
    .admin-chat-bubble.from-admin { background: #CC171F; color: #fff; align-self: flex-end; }
    .admin-chat-bubble img { max-width: 100%; border-radius: 8px; display: block; margin-bottom: 4px; }
    .admin-chat-time { font-size: 10px; opacity: .7; display: block; margin-top: 4px; }

    .admin-chat-input-bar { display: flex; gap: 10px; padding: 12px; border-top: 1px solid #e2e5eb; align-items: center; }
    .admin-chat-input-bar textarea { flex: 1; resize: none; border-radius: 8px; border: 1px solid #ccc; padding: 8px 12px; }
    .admin-chat-attach-btn { flex-shrink: 0; width: 38px; height: 38px; border-radius: 50%; border: 1px solid #ccc; background: #fff; color: #55555e; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .admin-chat-attach-btn:hover { border-color: #CC171F; color: #CC171F; }
    .admin-chat-attach-btn svg { width: 16px; height: 16px; }

    /* Right: details panel */
    .chat-console-details { width: 280px; flex-shrink: 0; border-left: 1px solid #e2e5eb; overflow-y: auto; }
    .chat-console-details-title { padding: 16px; font-weight: 700; font-size: 15px; color: #121213; border-bottom: 1px solid #e2e5eb; }
    .chat-console-details-section { padding: 16px; border-bottom: 1px solid #f0f1f4; }
    .chat-console-details-heading { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; color: #888892; margin-bottom: 12px; }
    .chat-console-detail-row { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
    .chat-console-detail-name { font-size: 13.5px; font-weight: 700; color: #121213; }
    .chat-console-detail-sub { font-size: 11.5px; color: #888892; }
    .chat-console-detail-line { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #55555e; margin-bottom: 8px; }
    .chat-console-detail-line svg { width: 14px; height: 14px; flex-shrink: 0; color: #888892; }
    .chat-console-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .chat-console-stat { background: #f8f9fb; border: 1px solid #e2e5eb; border-radius: 8px; padding: 10px; text-align: center; }
    .chat-console-stat-value { display: block; font-size: 14px; font-weight: 700; color: #121213; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .chat-console-stat-label { display: block; font-size: 10px; color: #888892; text-transform: uppercase; letter-spacing: .3px; margin-top: 3px; }
    .chat-console-status-badge { font-size: 10.5px; font-weight: 700; text-transform: uppercase; padding: 2px 8px; border-radius: 10px; }
    .chat-console-status-badge.is-active { background: rgba(16, 185, 129, .12); color: #10b981; }
    .chat-console-status-badge.is-inactive { background: rgba(239, 68, 68, .12); color: #ef4444; }
    .chat-console-username { font-size: 12px; color: #888892; margin-left: 8px; }
</style>

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1>Support Chats</h1>
        </div>
      </div>
    </div>
</section>

<div class="chat-console">
    <div class="chat-console-list">
        <div class="chat-console-list-header">Conversations</div>
        <div class="chat-console-list-items">
            @forelse ($conversations as $conversation)
                <div class="chat-console-list-item {{ $selectedUser && $selectedUser->id === $conversation->id ? 'active' : '' }}" data-user-id="{{ $conversation->id }}" onclick="selectConversation({{ $conversation->id }})">
                    <span class="chat-console-avatar">{{ strtoupper(substr($conversation->name, 0, 1)) }}</span>
                    <div class="chat-console-list-item-body">
                        <div class="chat-console-list-item-top">
                            <span class="chat-console-list-item-name">{{ $conversation->name }}</span>
                            <span class="chat-console-list-item-time">{{ optional(optional($conversation->latest_message)->created_at)->diffForHumans(null, true) }}</span>
                        </div>
                        <div class="chat-console-list-item-preview">
                            @if (optional($conversation->latest_message)->message)
                                {{ $conversation->latest_message->message }}
                            @elseif (optional($conversation->latest_message)->image)
                                Photo attachment
                            @endif
                        </div>
                    </div>
                    @if ($conversation->unread_count > 0)
                        <span class="chat-console-unread-badge">{{ $conversation->unread_count }}</span>
                    @endif
                </div>
            @empty
                <div class="chat-console-empty-list">No support conversations yet.</div>
            @endforelse
        </div>
    </div>

    <div class="chat-console-thread">
        @if ($selectedUser)
            <div class="chat-console-thread-header">
                <span class="chat-console-avatar">{{ strtoupper(substr($selectedUser->name, 0, 1)) }}</span>
                <span id="chat-console-thread-name">{{ $selectedUser->name }}</span>
            </div>
            <div id="chat-console-messages" class="chat-console-messages">
                @include('admin.partials.chat-messages', ['messages' => $messages])
            </div>
            <div class="admin-chat-input-bar">
                <input type="file" id="admin-chat-image-input" accept="image/*" style="display: none;">
                <button type="button" class="admin-chat-attach-btn" onclick="document.getElementById('admin-chat-image-input').click()" title="Attach image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
                </button>
                <textarea id="admin-chat-textarea" rows="1" placeholder="Type a message..."></textarea>
                <button id="admin-chat-send-btn" class="btn btn-primary">Send</button>
            </div>
        @else
            <div class="chat-console-empty-thread-wrap">Select a conversation to start chatting.</div>
        @endif
    </div>

    <div class="chat-console-details">
        <div class="chat-console-details-title">Details</div>
        <div id="chat-console-details-body">
            @if ($selectedUser)
                @include('admin.partials.chat-details', ['selectedUser' => $selectedUser, 'stats' => $stats])
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var selectedUserId = {{ $selectedUser->id ?? 'null' }};
    var messagesBox = document.getElementById('chat-console-messages');
    var textarea = document.getElementById('admin-chat-textarea');
    var sendBtn = document.getElementById('admin-chat-send-btn');
    var imageInput = document.getElementById('admin-chat-image-input');
    var detailsBody = document.getElementById('chat-console-details-body');
    var threadName = document.getElementById('chat-console-thread-name');
    var pollTimer = null;

    function scrollToBottom() {
        if (messagesBox) {
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }
    }

    function lastRenderedDate() {
        if (!messagesBox) return null;
        var bubbles = messagesBox.querySelectorAll('[data-date]');
        if (!bubbles.length) return null;
        return bubbles[bubbles.length - 1].getAttribute('data-date');
    }

    function dayLabel(iso) {
        var today = new Date(); today.setHours(0, 0, 0, 0);
        var yesterday = new Date(today); yesterday.setDate(yesterday.getDate() - 1);
        var parts = iso.split('-');
        var d = new Date(parts[0], parts[1] - 1, parts[2]);
        if (d.getTime() === today.getTime()) return 'Today';
        if (d.getTime() === yesterday.getTime()) return 'Yesterday';
        return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function appendMessage(message) {
        if (!messagesBox || messagesBox.querySelector('[data-id="' + message.id + '"]')) {
            return;
        }

        var dateKey = message.created_at.slice(0, 10);
        if (dateKey !== lastRenderedDate()) {
            var divider = document.createElement('div');
            divider.className = 'chat-console-day-divider';
            var label = document.createElement('span');
            label.textContent = dayLabel(dateKey);
            divider.appendChild(label);
            messagesBox.appendChild(divider);
        }

        var bubble = document.createElement('div');
        bubble.className = 'admin-chat-bubble ' + (message.sender === 'admin' ? 'from-admin' : 'from-user');
        bubble.setAttribute('data-id', message.id);
        bubble.setAttribute('data-date', dateKey);

        if (message.image) {
            var img = document.createElement('img');
            img.src = '{{ asset('ChatImages') }}/' + message.image;
            img.alt = 'Attachment';
            bubble.appendChild(img);
        }
        if (message.message) {
            bubble.appendChild(document.createTextNode(message.message));
        }

        var time = document.createElement('span');
        time.className = 'admin-chat-time';
        time.textContent = new Date(message.created_at).toLocaleString();
        bubble.appendChild(time);

        messagesBox.appendChild(bubble);
    }

    function poll() {
        if (!selectedUserId) return;
        fetch('/admin/chats/' + selectedUserId + '/messages')
            .then(function (res) { return res.json(); })
            .then(function (messages) {
                var hadNew = false;
                messages.forEach(function (message) {
                    if (!messagesBox.querySelector('[data-id="' + message.id + '"]')) {
                        appendMessage(message);
                        hadNew = true;
                    }
                });
                if (hadNew) scrollToBottom();
            });
    }

    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        pollTimer = setInterval(poll, 4000);
    }

    function submitMessage(formData) {
        if (!selectedUserId) return;
        fetch('/admin/chats/' + selectedUserId + '/send', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
            .then(function (res) { return res.json(); })
            .then(function (message) {
                appendMessage(message);
                scrollToBottom();
            });
    }

    if (sendBtn) {
        sendBtn.addEventListener('click', function () {
            var text = textarea.value.trim();
            if (!text) return;
            var formData = new FormData();
            formData.append('message', text);
            submitMessage(formData);
            textarea.value = '';
        });

        textarea.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendBtn.click();
            }
        });

        imageInput.addEventListener('change', function () {
            if (!imageInput.files || !imageInput.files[0]) return;
            var formData = new FormData();
            formData.append('image', imageInput.files[0]);
            submitMessage(formData);
            imageInput.value = '';
        });
    }

    window.selectConversation = function (userId) {
        if (userId === selectedUserId) return;

        document.querySelectorAll('.chat-console-list-item').forEach(function (item) {
            item.classList.toggle('active', parseInt(item.getAttribute('data-user-id'), 10) === userId);
        });

        fetch('/admin/chats/' + userId + '/panel')
            .then(function (res) { return res.json(); })
            .then(function (data) {
                selectedUserId = userId;
                window.history.pushState({}, '', '/admin/chats/' + userId);

                var threadHeader = document.querySelector('.chat-console-thread-header');
                if (threadHeader && threadName) {
                    threadHeader.querySelector('.chat-console-avatar').textContent = data.user.initial;
                    threadName.textContent = data.user.name;
                } else {
                    // First selection after an empty state — reload once to build the header/input bar markup.
                    window.location.href = '/admin/chats/' + userId;
                    return;
                }

                messagesBox.innerHTML = '';
                data.messages.forEach(function (message) {
                    appendMessage(message);
                });
                scrollToBottom();

                var stats = data.stats;
                var statusClass = (stats.status || '').toLowerCase() === 'active' ? 'is-active' : 'is-inactive';
                detailsBody.innerHTML =
                    '<div class="chat-console-details-section">' +
                        '<div class="chat-console-details-heading">General Info</div>' +
                        '<div class="chat-console-detail-row">' +
                            '<span class="chat-console-avatar">' + data.user.initial + '</span>' +
                            '<div><div class="chat-console-detail-name">' + data.user.name + '</div>' +
                            '<div class="chat-console-detail-sub">' + (stats.email || '—') + '</div></div>' +
                        '</div>' +
                        '<div class="chat-console-detail-line">Member since ' + (stats.member_since || '—') + '</div>' +
                        '<div class="chat-console-detail-line">' + (stats.location || 'Unknown location') + '</div>' +
                    '</div>' +
                    '<div class="chat-console-details-section">' +
                        '<div class="chat-console-details-heading">Account</div>' +
                        '<div class="chat-console-stat-grid">' +
                            '<div class="chat-console-stat"><span class="chat-console-stat-value">$' + Number(stats.total_funds).toFixed(2) + '</span><span class="chat-console-stat-label">Total Funds</span></div>' +
                            '<div class="chat-console-stat"><span class="chat-console-stat-value">' + stats.completed_orders + '</span><span class="chat-console-stat-label">Orders Completed</span></div>' +
                            '<div class="chat-console-stat"><span class="chat-console-stat-value">' + stats.credibility + '%</span><span class="chat-console-stat-label">Credibility</span></div>' +
                            '<div class="chat-console-stat"><span class="chat-console-stat-value">' + (stats.membership || '—') + '</span><span class="chat-console-stat-label">Membership</span></div>' +
                        '</div>' +
                        '<div class="chat-console-detail-line" style="margin-top: 12px;">' +
                            '<span class="chat-console-status-badge ' + statusClass + '">' + (stats.status || 'unknown') + '</span>' +
                            '<span class="chat-console-username">@' + (stats.username || '—') + '</span>' +
                        '</div>' +
                    '</div>';

                startPolling();
            });
    };

    if (selectedUserId) {
        startPolling();
    }
})();
</script>
@endsection
