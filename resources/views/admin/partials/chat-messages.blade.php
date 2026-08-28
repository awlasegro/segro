@php $lastDate = null; @endphp
@forelse ($messages as $message)
    @php $msgDate = $message->created_at->format('Y-m-d'); @endphp
    @if ($msgDate !== $lastDate)
        @php $lastDate = $msgDate; @endphp
        <div class="chat-console-day-divider">
            <span>{{ $message->created_at->isToday() ? 'Today' : ($message->created_at->isYesterday() ? 'Yesterday' : $message->created_at->format('M j, Y')) }}</span>
        </div>
    @endif
    <div class="admin-chat-bubble {{ $message->sender === 'admin' ? 'from-admin' : 'from-user' }}" data-id="{{ $message->id }}" data-date="{{ $msgDate }}">
        @if ($message->image)
            <img src="{{ asset('ChatImages/' . $message->image) }}" alt="Attachment">
        @endif
        @if ($message->message)
            {{ $message->message }}
        @endif
        <span class="admin-chat-time">{{ $message->created_at->format('g:i A') }}</span>
    </div>
@empty
    <div class="chat-console-empty-thread">No messages yet.</div>
@endforelse
