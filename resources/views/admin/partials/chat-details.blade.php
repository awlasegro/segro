<div class="chat-console-details-section">
    <div class="chat-console-details-heading">General Info</div>
    <div class="chat-console-detail-row">
        <span class="chat-console-avatar">{{ strtoupper(substr($selectedUser->name, 0, 1)) }}</span>
        <div>
            <div class="chat-console-detail-name">{{ $selectedUser->name }}</div>
            <div class="chat-console-detail-sub">{{ $stats['email'] ?? '—' }}</div>
        </div>
    </div>
    <div class="chat-console-detail-line">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span>Member since {{ $stats['member_since'] ?? '—' }}</span>
    </div>
    <div class="chat-console-detail-line">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        <span>{{ $stats['location'] ?? 'Unknown location' }}</span>
    </div>
</div>

<div class="chat-console-details-section">
    <div class="chat-console-details-heading">Account</div>
    <div class="chat-console-stat-grid">
        <div class="chat-console-stat">
            <span class="chat-console-stat-value">${{ number_format($stats['total_funds'] ?? 0, 2) }}</span>
            <span class="chat-console-stat-label">Total Funds</span>
        </div>
        <div class="chat-console-stat">
            <span class="chat-console-stat-value">{{ $stats['completed_orders'] ?? 0 }}</span>
            <span class="chat-console-stat-label">Orders Completed</span>
        </div>
        <div class="chat-console-stat">
            <span class="chat-console-stat-value">{{ $stats['credibility'] ?? 0 }}%</span>
            <span class="chat-console-stat-label">Credibility</span>
        </div>
        <div class="chat-console-stat">
            <span class="chat-console-stat-value">{{ $stats['membership'] ?? '—' }}</span>
            <span class="chat-console-stat-label">Membership</span>
        </div>
    </div>
    <div class="chat-console-detail-line" style="margin-top: 4px;">
        <span class="chat-console-status-badge {{ strtolower($stats['status'] ?? '') === 'active' ? 'is-active' : 'is-inactive' }}">{{ ucfirst($stats['status'] ?? 'unknown') }}</span>
        <span class="chat-console-username">&#64;{{ $stats['username'] ?? '—' }}</span>
    </div>
</div>
