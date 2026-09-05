@extends('user.layout.app-master')
@section('title', 'Data Optimization System - Brookfield Properties')
@section('content')
    @php
        $userName = Auth::user()->name ?? 'Guest';
    @endphp

    <link rel="stylesheet" href="{{ asset('css/data-optimization.css') }}">

    {{-- Header Spacer to avoid overlap with top menu --}}
    <div class="header-spacer"></div>

    {{-- Flash Messages (Aesthetic, Bootstrap-styled) --}}
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                @if (session('order_message'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-lg mb-3" role="alert">
                        <div class="d-flex align-items-start">
                            <span class="badge badge-pill badge-light mr-2"><i class="fa fa-check text-success"></i></span>
                            <div class="flex-fill">
                                <strong class="d-block">Success</strong>
                                <span>{{ session('order_message') }}</span>
                            </div>
                            <button type="button" class="close ml-2" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('order_success_message'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-lg mb-3" role="alert">
                        <div class="d-flex align-items-start">
                            <span class="badge badge-pill badge-light mr-2"><i class="fa fa-check text-success"></i></span>
                            <div class="flex-fill">
                                <strong class="d-block">Order Completed</strong>
                                <span>{{ session('order_success_message') }}</span>
                            </div>
                            <button type="button" class="close ml-2" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('account_message'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-lg mb-3" role="alert">
                        <div class="d-flex align-items-start">
                            <span class="badge badge-pill badge-light mr-2"><i class="fa fa-exclamation-triangle text-danger"></i></span>
                            <div class="flex-fill">
                                <strong class="d-block">Account Notice</strong>
                                <span>{{ session('account_message') }}</span>
                            </div>
                            <button type="button" class="close ml-2" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Negative Balance Warning --}}
                @if (!empty($userData) && isset($userData['overpriced_amount']) && $userData['overpriced_amount'] > 0)
                    <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 rounded-lg mb-3" role="alert">
                        <div class="d-flex align-items-start">
                            <span class="badge badge-pill badge-light mr-2"><i class="fa fa-exclamation-circle text-warning"></i></span>
                            <div class="flex-fill">
                                <strong class="d-block">Low Balance</strong>
                                <span>Your account balance is negative -${{ number_format($userData['overpriced_amount'], 2) }} for next lot. Please contact support.</span>
                            </div>
                            <button type="button" class="close ml-2" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Page Header --}}
    <section class="py-5 page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2 text-white">Welcome back, {{ $userName }}!</h2>
                    <p class="lead mb-0 text-light">Monitor your funds, earnings, and orders at a glance.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Metrics Cards --}}
    <section class="metrics py-4">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon mr-3 text-success">
                            <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <div class="stat-value">
                                    @if ($userData['overpriced_amount'] > 0)
                                        -${{ number_format($userData['overpriced_amount'], 2) }}
                                    @else
                                        ${{ number_format($userData['total_funds'], 2) }}
                                    @endif
                                </div>
                                <div class="stat-label">Account Balance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon mr-3 text-info">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <div>
                                <div class="stat-value">${{ $userData['today_commission'] ?? 'N/A' }}</div>
                                <div class="stat-label">Today's Earnings</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- First card showing actual orders today --}}
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon mr-3 text-warning">
                                <i class="fa fa-check-square-o"></i>
                            </div>
                            <div>
                                <div class="stat-value">{{ $userData['total_today_orders'] }}</div>
                                <div class="stat-label">Orders Today</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Last card showing membership order limit --}}
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon mr-3 text-primary">
                                <i class="fa fa-list-ul"></i>
                            </div>
                            <div>
                                <div class="stat-value">{{ $userData['membership_level']->order_limit }}</div>
                                <div class="stat-label">Total Orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Generate Order CTA --}}
    <section class="py-4">
        <div class="container text-center">
            <a class="btn btn-primary btn-lg btn-cta" href="{{ route('generate.order') }}">
                <i class="fa fa-cogs mr-2"></i> Generate Lots
            </a>
        </div>
    </section>

    {{-- Chat Widget --}}
    @include('user.layout.chat')
@endsection
