@extends('user.layout.app-master')
@section('title', 'Profile - Brookfield Properties')
@section('content')
    <div class="page-bottom pbottom-70 mt-5 pt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Summary Card (Hero) -->
                    <div class="card bg-transparent border-0 mb-4">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center p-4 rounded">
                                <div class="me-md-3 mb-3 mb-md-0">
                                    <i class="fa fa-user-circle fa-3x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 w-100">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between w-100">
                                        <div class="mb-3 mb-md-0">
                                            <h2 class="h4 mb-1">{{ $userData['user']->username }}</h2>
                                            <span class="badge bg-primary">{{ $userData['membership_level']->level_name }}</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
                                                <i class="fa fa-qrcode me-2"></i>Invitation Code
                                            </button>
                                            <a href="{{ url('user.logout') }}" class="btn btn-danger">
                                                <i class="fa fa-sign-out me-2"></i>Logout
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 text-success"><i class="fa fa-wallet fa-2x"></i></div>
                                    <div>
                                        <div class="text-muted mb-1">Account Balance</div>
                                        <div class="h4 mb-0">${{ number_format($userData['total_funds'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 text-info"><i class="fa fa-line-chart fa-2x"></i></div>
                                    <div>
                                        <div class="text-muted mb-1">Today's Earnings</div>
                                        <div class="h4 mb-0">${{ number_format($userData['today_commission'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Credibility Progress -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Credibility</span>
                                <span class="text-muted">{{ $userData['user']->credibility }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $userData['user']->credibility }}%;" aria-valuenow="{{ $userData['user']->credibility }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Access (icon grid like Home) -->
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="mb-0"><i class="fa fa-th-large me-2"></i>Quick Access</h5>
                            </div>
                            <div class="row g-3 text-center">
                                @php
                                    $apps = [
                                        ['url' => url('profile'), 'label' => 'Profile'],
                                        ['url' => url('data-optimization'), 'label' => 'Lots Optimization'],
                                        ['url' => url('history'), 'label' => 'History'],
                                        ['url' => url('recharge'), 'label' => 'Bind Wallet'],
                                        ['url' => url('recharge-history'), 'label' => 'Recharge History'],
                                        ['url' => url('redemption'), 'label' => 'Redemption'],
                                        ['url' => url('redemption-history'), 'label' => 'Redemption History'],
                                        ['url' => url('support'), 'label' => 'Support'],
                                    ];
                                @endphp
                                @foreach ($apps as $index => $app)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <a href="{{ $app['url'] }}" class="d-block text-decoration-none">
                                            <div class="p-3 border rounded h-100">
                                                <div class="mb-2">
                                                    <img src="assets/images/icons/apps/{{ $index + 1 }}.png" class="img-fluid" alt="{{ $app['label'] }}" style="max-height:48px">
                                                </div>
                                                <small class="d-block">{{ $app['label'] }}</small>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Logout (Theme button ~90% width) -->
                    <div class="row justify-content-center">
                        <div class="col-11 col-md-10 col-lg-9">
                            <a class="btn btn-primary w-100" href="{{ url('user.logout') }}"><i class="fa fa-sign-out me-2"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Invitation Modal (Bootstrap) -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inviteModalLabel">Your Invitation Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img src="{{ asset('images/scan.png') }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
        <h6 class="mb-1">Invitation Code</h6>
        <p id="modal-invite-code" class="fw-bold">{{ $userData['user']->reference_code }}</p>
        <button class="btn btn-success" id="copyInviteBtn"><i class="fa fa-copy me-2"></i>Copy Invitation Code</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var copyBtn = document.getElementById('copyInviteBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                var text = document.getElementById('modal-invite-code').textContent.trim();
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(function(){
                        alert('Invitation code copied to clipboard!');
                    });
                } else {
                    var tempInput = document.createElement('input');
                    tempInput.value = text;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    tempInput.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                    alert('Invitation code copied to clipboard!');
                }
            });
        }
    });
</script>

@include('user.layout.chat')
@endsection
