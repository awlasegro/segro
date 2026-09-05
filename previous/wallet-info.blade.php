@extends('user.layout.app-master')
@section('title', 'Wallet Information - Brookfield Properties')
@section('content')

@php
    $hasWallet = !empty($wallet);
    // current values (preserve old input first)
    $valletAddress = old('vallet-address', optional($wallet)->vallet_address);
    $phoneNumber = old('phone-number', optional($wallet)->phone);
    $selectedCurrency = old('currency', optional($wallet)->type);
    $selectedBlockchain = old('blockchain', optional($wallet)->blockchain);
@endphp

<section class="page">
    <div class="cover" data-image="assets/images/photos/parallax.jpg">
        <div class="page-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Wallet Information</h1>
                    </div>
                    <div class="col-lg-12 text-center">
                        <ol class="breadcrumb">
                            <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="active">Wallet Info</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-bottom pbottom-70">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card shadow-sm border-0">
                                    <div class="card-body">

                                        {{-- Session messages --}}
                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif
                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        {{-- Validation errors --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="balance-section mb-3 text-center">
                                            <h3 class="mb-0">{{ Auth::user()->name }} - Wallet Settings</h3>
                                            <p class="small text-muted">Set your withdrawal address and contact details</p>
                                        </div>

                                        @if($hasWallet)
                                            <div class="alert alert-info">
                                                Your wallet information is already saved and is currently locked. If you need to change it, please contact support.
                                            </div>
                                        @endif

                                        {{-- Note: keep the route as-is; if your POST route name differs update the action --}}
                                        <form action="{{ route('wallet-information.save', [], false) }}" method="POST" novalidate>
                                            @csrf

                                            <div class="mb-3">
                                                <label for="vallet-address" class="form-label">Wallet Address</label>
                                                <input
                                                    type="text"
                                                    id="vallet-address"
                                                    name="vallet-address"
                                                    value="{{ $valletAddress }}"
                                                    placeholder="Enter your wallet address"
                                                    class="form-control"
                                                    @if($hasWallet) disabled aria-disabled="true" @endif
                                                    required
                                                >
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone-number" class="form-label">Phone Number</label>
                                                <input
                                                    type="tel"
                                                    id="phone-number"
                                                    name="phone-number"
                                                    value="{{ $phoneNumber }}"
                                                    placeholder="Enter your phone number"
                                                    class="form-control"
                                                    @if($hasWallet) disabled aria-disabled="true" @endif
                                                    required
                                                >
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="currency" class="form-label">Currency</label>
                                                    <select id="currency" name="currency" class="form-select" @if($hasWallet) disabled aria-disabled="true" @endif required>
                                                        <option value="">Choose currency</option>
                                                        {{-- Order exactly: ETH, BTC, USDT, USDC --}}
                                                        <option value="ETH" {{ $selectedCurrency == 'ETH' ? 'selected' : '' }}>ETH</option>
                                                        <option value="BTC" {{ $selectedCurrency == 'BTC' ? 'selected' : '' }}>BTC</option>
                                                        <option value="USDT" {{ $selectedCurrency == 'USDT' ? 'selected' : '' }}>USDT</option>
                                                        <option value="USDC" {{ $selectedCurrency == 'USDC' ? 'selected' : '' }}>USDC</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="blockchain" class="form-label">Blockchain Network</label>
                                                    <select id="blockchain" name="blockchain" class="form-select" @if($hasWallet) disabled aria-disabled="true" @endif required>
                                                        <option value="">Choose network</option>
                                                        {{-- Order exactly: ERC20, BTC, USDC, USDT, Aptos --}}
                                                        <option value="ERC20" {{ $selectedBlockchain == 'ERC20' ? 'selected' : '' }}>ERC20</option>
                                                        <option value="BTC" {{ $selectedBlockchain == 'BTC' ? 'selected' : '' }}>BTC</option>
                                                        <option value="USDC" {{ $selectedBlockchain == 'USDC' ? 'selected' : '' }}>USDC</option>
                                                        <option value="USDT" {{ $selectedBlockchain == 'USDT' ? 'selected' : '' }}>USDT</option>
                                                        <option value="Aptos" {{ $selectedBlockchain == 'Aptos' ? 'selected' : '' }}>Aptos</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <button
                                                    type="submit"
                                                    class="btn-primary-line w-100 redemption-btn"
                                                    @if($hasWallet) disabled aria-disabled="true" @endif
                                                >
                                                    Save Wallet Information<span class="ms-3">&#10095;&#10095;</span>
                                                </button>
                                            </div>
                                        </form>

                                        <p class="small-text mt-3 text-center">Make sure your wallet address and network match to avoid failed redemptions.</p>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> <!-- /.contact-form -->
                </div>
            </div>
        </div>
    </div>
</section>

{{-- client-side niceties: basic validation & demo script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action$="wallet-information.save"], form[action$="wallet-information.save/"]') || document.querySelector('form');
        if (!form) return;

        // If fields are disabled (wallet exists), skip client-side validation and prevent submit (button also disabled)
        const firstInput = form.querySelector('#vallet-address');
        if (firstInput && firstInput.disabled) {
            // nothing to do - inputs disabled and button disabled
            return;
        }

        form.addEventListener('submit', function (e) {
            // Simple client-side sanitization/validation (non-blocking server remains authoritative)
            const addr = document.getElementById('vallet-address').value.trim();
            const phone = document.getElementById('phone-number').value.trim();
            const currency = document.getElementById('currency').value;
            const blockchain = document.getElementById('blockchain').value;
            let errors = [];

            if (!addr) errors.push('Wallet address is required.');
            if (!phone) errors.push('Phone number is required.');
            if (!currency) errors.push('Please select a currency.');
            if (!blockchain) errors.push('Please select a blockchain network.');

            if (errors.length) {
                e.preventDefault();
                // show a minimal inline alert (server-side messages still authoritative)
                let alertBox = document.querySelector('#client-side-wallet-errors');
                if (!alertBox) {
                    alertBox = document.createElement('div');
                    alertBox.id = 'client-side-wallet-errors';
                    alertBox.className = 'alert alert-danger';
                    form.parentNode.insertBefore(alertBox, form);
                }
                alertBox.innerHTML = '<ul class="mb-0"><li>' + errors.join('</li><li>') + '</li></ul>';
                window.scrollTo({ top: alertBox.offsetTop - 20, behavior: 'smooth' });
            }
        });
    });
</script>

@include('user.layout.chat')
@endsection
