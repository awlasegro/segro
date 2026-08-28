<!-- Floating support chat launcher (see app/Http/Controllers/ChatController.php) -->
<div id="chat-widget-launcher" class="chat-widget-launcher" onclick="toggleChatWidget()" title="Chat with support">
    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</div>

<div id="chat-widget-panel" class="chat-widget-panel">
    <div class="chat-widget-header">
        <span>Support Chat</span>
        <div class="chat-widget-close" onclick="toggleChatWidget()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
    </div>
    <div id="chat-widget-messages" class="chat-widget-messages"></div>
    <div class="chat-widget-input-bar">
        <input type="file" id="chat-widget-image-input" accept="image/*" style="display: none;">
        <button type="button" class="chat-attach-btn" onclick="document.getElementById('chat-widget-image-input').click()" title="Attach image">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
        </button>
        <textarea id="chat-widget-textarea" placeholder="Type a message..."></textarea>
        <button class="chat-send-btn" onclick="sendWidgetChatMessage()">
            <svg viewBox="0 0 24 24">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </button>
    </div>
</div>

<script>
    (function () {
        var launcher = document.getElementById('chat-widget-launcher');
        var panel = document.getElementById('chat-widget-panel');
        var messagesBox = document.getElementById('chat-widget-messages');
        var textarea = document.getElementById('chat-widget-textarea');
        var imageInput = document.getElementById('chat-widget-image-input');
        var lastId = 0;
        var pollTimer = null;
        var loaded = false;

        function scrollToBottom() {
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }

        function appendMessage(message) {
            if (messagesBox.querySelector('[data-id="' + message.id + '"]')) {
                return;
            }
            var bubble = document.createElement('div');
            bubble.className = 'message-bubble ' + (message.sender === 'admin' ? 'received' : 'sent');
            bubble.setAttribute('data-id', message.id);

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
            time.className = 'message-time';
            time.textContent = new Date(message.created_at).toLocaleString();
            bubble.appendChild(time);

            messagesBox.appendChild(bubble);
            lastId = Math.max(lastId, message.id);
        }

        function fetchMessages() {
            fetch('{{ route('chat.fetch') }}')
                .then(function (res) { return res.json(); })
                .then(function (messages) {
                    var hadNew = false;
                    messages.forEach(function (message) {
                        if (message.id > lastId) {
                            appendMessage(message);
                            hadNew = true;
                        }
                    });
                    if (hadNew) {
                        scrollToBottom();
                    }
                });
        }

        window.toggleChatWidget = function () {
            var isOpen = panel.classList.toggle('open');

            if (isOpen) {
                if (!loaded) {
                    loaded = true;
                    fetchMessages();
                }
                if (!pollTimer) {
                    pollTimer = setInterval(fetchMessages, 4000);
                }
            } else if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        };

        function submitMessage(formData) {
            fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(function (res) { return res.json(); })
                .then(function (message) {
                    appendMessage(message);
                    scrollToBottom();
                });
        }

        window.sendWidgetChatMessage = function () {
            var text = textarea.value.trim();
            if (!text) {
                return;
            }

            var formData = new FormData();
            formData.append('message', text);
            submitMessage(formData);
            textarea.value = '';
        };

        imageInput.addEventListener('change', function () {
            if (!imageInput.files || !imageInput.files[0]) {
                return;
            }

            var formData = new FormData();
            formData.append('image', imageInput.files[0]);
            submitMessage(formData);
            imageInput.value = '';
        });
    })();
</script>
