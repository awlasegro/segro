@extends('user.layout.app-master')
@section('title', 'Dashboard - Brookfield Properties')
@section('body_class', 'home-body')
@section('content')
    @php
        // Dynamic user name (fallback to Guest if not logged in)
        $userName = Auth::user()->name ?? 'Guest';
    @endphp

    <!-- ***** Wellcome Area Start ***** -->
    <section class="welcome-area">
        <!-- ***** Wellcome Area Background Start ***** -->
        <div class="welcome-bg" data-bg="assets/images/photos/welcome.jpg"></div>
        <!-- ***** Wellcome Area Background End ***** -->

        <!-- ***** Wellcome Area Content Start ***** -->
        <div class="welcome-content">
            <div class="container">
                <div class="row">
                    <div class="offset-lg-3 offset-md-2 col-lg-6 col-md-8 col-sm-12">
                        <h1>Welcome, {{ $userName }}!</h1>
                        <p>Brookfield Properties is a trusted real estate agency in the United States, helping clients buy,
                            sell, lease, and manage properties with confidence.</p>
                        <a class="btn-white-line" href="{{ url('/data-optimization') }}">GENERATE LOTS</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Wellcome Area Content End ***** -->
    </section>
    <!-- ***** Wellcome Area End ***** -->

    <!-- ***** Home Apps Start ***** -->
    <div class="welcome-apps">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center-heading mb-3">
                        <h2 class="section-title"><i class="fa fa-th-large me-2"></i>Quick Access</h2>
                    </div>
                    <div class="apps">
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
                            <a href="{{ $app['url'] }}" class="app-item text-center">
                                <div class="icon">
                                    <img src="assets/images/icons/apps/{{ $index + 1 }}.png" class="img-fluid"
                                        alt="{{ $app['label'] }}">
                                </div>
                                <small class="d-block mt-2">
                                    {{ $app['label'] }}
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Home Apps End ***** -->

    <!-- ***** Home About - Services Start ***** -->
    <section class="section services-section pbottom-70">
        <div class="container">
            <div class="row">
                <!-- ***** Home About Start ***** -->
                <div class="col-lg-5 col-md-12 col-sm-12 align-self-center mobile-bottom-fix">
                    <div class="left-heading">
                        <h2 class="section-title">Fullâ€‘service real estate agency</h2>
                    </div>
                    <div class="left-text">
                        <p class="dark">We specialize in residential and commercial properties across the U.S., offering
                            expert guidance from discovery to closing.</p>
                        <p>Whether youâ€™re investing, relocating, or expanding your portfolio, our team provides market
                            insights, transparent processes, and dedicated support.</p>
                    </div>
                    <a href="{{ url('/about-us') }}" class="btn-primary-line">OUR SERVICES</a>
                </div>
                <!-- ***** Home About End ***** -->

                <!-- ***** Home Services Start ***** -->
                <div class="offset-lg-1 col-lg-6 col-md-12 col-sm-12 align-self-center">
                    <div class="row">
                        @php
                            $services = [
                                [
                                    'icon' => 'fa-building',
                                    'title' => 'Property Sales & Leasing',
                                    'desc' =>
                                        'Buy, sell, or lease residential and commercial properties with expert negotiation and guidance.',
                                ],
                                [
                                    'icon' => 'fa-key',
                                    'title' => 'Property Management',
                                    'desc' => 'Endâ€‘toâ€‘end management to protect your investment and maximize returns.',
                                ],
                                [
                                    'icon' => 'fa-line-chart',
                                    'title' => 'Investment Advisory',
                                    'desc' => 'Market research and advisory for investors seeking longâ€‘term value.',
                                ],
                                [
                                    'icon' => 'fa-handshake-o',
                                    'title' => 'Tenant Representation',
                                    'desc' => 'Find the right space and secure favorable terms for your business.',
                                ],
                            ];
                        @endphp
                        @foreach ($services as $service)
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <a href="{{ url('/about-us') }}" class="home-services-item"
                                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.2s">
                                    <i class="fa {{ $service['icon'] }}"></i>
                                    <h5 class="services-title">{{ $service['title'] }}</h5>
                                    <p>{{ $service['desc'] }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- ***** Home Services End ***** -->
            </div>
        </div>
    </section>
    <!-- ***** Home About - Services Start ***** -->


    <!-- ***** Features Start ***** -->
    <section class="section background">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-sm-12 col-12 align-self-center mobile-bottom-fix">
                    <img src="assets/images/mockup/home-mockup.png" class="img-fluid d-block mx-auto" alt="">
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12 col-12 align-self-center">
                    <ul class="features">
                        @php
                            $features = [
                                [
                                    'icon' => 'fa-map',
                                    'title' => 'Local market expertise nationwide',
                                    'desc' =>
                                        'From neighborhood trends to national outlooks, our advisors help you make confident decisions.',
                                ],
                                [
                                    'icon' => 'fa-building-o',
                                    'title' => 'Diverse portfolio',
                                    'desc' =>
                                        'Access residential, retail, office, and mixedâ€‘use opportunities across major U.S. markets.',
                                ],
                                [
                                    'icon' => 'fa-check',
                                    'title' => 'Transparent process',
                                    'desc' =>
                                        'Clear communication, streamlined steps, and support from first viewing to closing.',
                                ],
                                [
                                    'icon' => 'fa-users',
                                    'title' => 'Clientâ€‘first approach',
                                    'desc' => 'We align every recommendation with your goals, timeline, and budget.',
                                ],
                            ];
                        @endphp
                        @foreach ($features as $feature)
                            <li data-scroll-reveal="enter bottom move 30px over 0.6s after 0.2s">
                                <div class="count">
                                    <span>
                                        <i class="fa {{ $feature['icon'] }}"></i>
                                    </span>
                                </div>
                                <div class="text">
                                    <h5 class="title">{{ $feature['title'] }}</h5>
                                    <p>{{ $feature['desc'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features End ***** -->


    <!-- ***** Our Team Start ***** -->
    <section class="section pbottom-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center-heading">
                        <h2 class="section-title">Our Team</h2>
                    </div>
                </div>
                <div class="offset-lg-3 col-lg-6">
                    <div class="center-text">
                        <p>Fusce placerat pretium mauris, vel sollicitudin elit lacinia vitae. Quisque sit amet nisi erat.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $team = [
                        ['img' => '1.jpg', 'name' => 'Fletch Skinner', 'role' => 'Product Strategist'],
                        ['img' => '2.jpg', 'name' => 'Lance Bogrol', 'role' => 'Visual Designer'],
                        ['img' => '3.jpg', 'name' => 'Valent Morose', 'role' => 'Android Developer'],
                        ['img' => '4.jpg', 'name' => 'Giles Posture', 'role' => 'iOS Developer'],
                    ];
                @endphp
                @foreach ($team as $member)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="team-item">
                            <div class="header">
                                <div class="img">
                                    <img src="assets/images/photos/team/{{ $member['img'] }}" alt="">
                                </div>
                                <div class="info">
                                    <strong>{{ $member['name'] }}</strong>
                                    <span>{{ $member['role'] }}</span>
                                </div>
                            </div>
                            <ul class="social">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-github"></i></a></li>
                            </ul>
                            <div class="body">
                                Proin arcu ligula, malesuada id tincidunt laoreet, facilisis at justo. Sed at lorem.
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- ***** Our Team End ***** -->


    <!-- ***** Counter Parallax Start ***** -->
    <div class="parallax">
        <div class="parallax-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="count-item">
                            <strong>126</strong>
                            <span>Mobile App<br>Complate</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="count-item">
                            <strong>98</strong>
                            <span>Happy<br>Customer</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="count-item">
                            <strong>176</strong>
                            <span>App<br>Version</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="count-item">
                            <strong>16</strong>
                            <span>Award<br>Win</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Counter Parallax End ***** -->


    <!-- ***** Blog Start ***** -->
    <section class="section pbottom-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center-heading">
                        <h2 class="section-title">Latest Blog Posts</h2>
                    </div>
                </div>
                <div class="offset-lg-3 col-lg-6">
                    <div class="center-text">
                        <p>Fusce placerat pretium mauris, vel sollicitudin elit lacinia vitae. Quisque sit amet nisi erat.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $blogs = [
                        [
                            'img' => '1.jpg',
                            'date' => 'APR 09',
                            'title' => '5 steps to becoming GDPR compliant on mobile apps',
                            'desc' =>
                                'Mauris tellus sem, ultrices varius nisl at, convallis iaculis mauris. Sed eget sem vitae purus tempus dignissim.',
                            'url' => 'green-blog-single.html',
                        ],
                        [
                            'img' => '2.jpg',
                            'date' => 'APR 09',
                            'title' => 'Measuring app success through mobile analytics',
                            'desc' =>
                                'Cras imperdiet faucibus sem, a dignissim urna feugiat sed. Interdum et malesuada fames ac ante ipsum primis.',
                            'url' => 'green-blog-single.html',
                        ],
                        [
                            'img' => '3.jpg',
                            'date' => 'APR 09',
                            'title' => 'How accessibility will influence your app dev',
                            'desc' =>
                                'Quisque euismod nec lacus sit amet maximus. Ut convallis sagittis lorem auctor malesuada. Morbi auctor.',
                            'url' => 'green-blog-single.html',
                        ],
                    ];
                @endphp
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="blog-post-thumb">
                            <div class="img">
                                <img src="assets/images/photos/blog/{{ $blog['img'] }}" alt="">
                            </div>
                            <div class="post-content">
                                <div class="date">{{ $blog['date'] }}</div>
                                <h3>
                                    <a href="{{ $blog['url'] }}">{{ $blog['title'] }}</a>
                                </h3>
                                <div class="text">
                                    {{ $blog['desc'] }}
                                </div>
                                <a href="{{ $blog['url'] }}" class="btn-primary-line">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- ***** Blog End ***** -->
@endsection
