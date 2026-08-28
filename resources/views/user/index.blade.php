<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEGRO - Smart Space. Sustainable Logistics.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/index.css') }}">
</head>
<body style="padding-bottom: 0;">

    <!-- 1. Dark Top Header Bar -->
    <header class="top-header">
        <div class="logo top-header-logo" onclick="window.location.href='/'">
            <img src="{{ asset('images/logo-dark.png') }}" alt="SEGRO">
        </div>
        <div class="top-header-right">
            <span class="share-quote">944.60 GBX | SEGRO at 16:35 GMT</span>
            <a href="/support" class="top-header-link">Contact Us</a>

            <!-- eyeball icon (accessibility) -->
            <span class="icon-btn" title="Accessibility settings">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </span>

            <!-- Language EN with globe -->
            <div class="lang-selector">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
                <span>EN</span>
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>

            <a href="/user-login" class="login-pill-btn">Login</a>

            <!-- Search Icon -->
            <span class="icon-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>

            <!-- Hamburger mobile trigger -->
            <div class="mobile-menu-trigger" onclick="toggleMobileMenu()">
                <div class="hamburger-circle">
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Login Bar (visible under top-header only in mobile) -->
    <div class="mobile-login-bar">
        <a href="/user-login" class="login-pill-btn">Login</a>
    </div>

    <!-- 2. Light Sub-Navigation Menu Bar (Desktop only) -->
    <nav class="sub-nav">
        <div class="nav-links">
            <a href="#" class="nav-link">Estates</a>
            <a href="#" class="nav-link">Countries</a>
            <a href="#" class="nav-link">Sustainability</a>
            <a href="#" class="nav-link">About</a>
            <a href="#" class="nav-link">Investors</a>
            <a href="#" class="nav-link">Careers</a>
            <a href="#" class="nav-link">Media</a>
        </div>
        <a href="/user-login" class="property-btn">Find a property</a>
    </nav>

    <!-- 3. Main Workspace Layout -->
    <main class="main-layout">

        <!-- Sidebar Filter System (Desktop only) -->
        <aside class="sidebar">
            <div class="vertical-strip">
                <div class="vertical-strip-top">
                    <!-- small (o) circle top -->
                    <span class="vertical-index">0</span>
                    <span class="vertical-title">Interests</span>
                </div>
                <!-- arrow pointing left bottom -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </div>

            <div class="sidebar-content">
                <div class="sidebar-welcome">
                    <h1>Welcome to <span style="color: var(--segro-red);">SEGRO</span></h1>
                    <p>Customize your homepage by selecting a filter below.</p>
                </div>

                <div class="filter-list">
                    <div class="filter-item" onclick="window.location.href='/user-login'">
                        <span class="filter-radio"></span>
                        <span>Property</span>
                    </div>
                    <div class="filter-item" onclick="window.location.href='/user-login'">
                        <span class="filter-radio"></span>
                        <span>Investor information</span>
                    </div>
                    <div class="filter-item" onclick="window.location.href='/company-information'">
                        <span class="filter-radio"></span>
                        <span>Responsible SEGRO</span>
                    </div>
                    <div class="filter-item" onclick="window.location.href='/user-login'">
                        <span class="filter-radio"></span>
                        <span>Careers and culture at SEGRO</span>
                    </div>
                    <div class="filter-item" onclick="window.location.href='/company-information'">
                        <span class="filter-radio"></span>
                        <span>Our history</span>
                    </div>
                    <div class="filter-item" onclick="window.location.href='/company-information'">
                        <span class="filter-radio"></span>
                        <span>Community Investment</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Right Content Cards: Two Equal Columns to match Grid Spacings exactly -->
        <div class="content-grid-container">

            <!-- LEFT COLUMN (50% Width) -->
            <div class="grid-column-left">

                <!-- Card 1: Southern Approach Green Wall Warehouse -->
                <div class="grid-card hero-card">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('images/imgi_6_2021-2-southern-approach-nfte-5004.jpg') }}" alt="SEGRO Southern Approach NFTE 5004" class="card-img">
                    </div>
                    <div class="card-body">
                        <p>For over 100 years we have been creating the space that enables extraordinary things to happen. From modern big box warehouses, used primarily for regional, national and international distribution hubs, to urban warehousing, we provide the high-quality assets that allow our customers to thrive.</p>

                        <a href="/company-information" class="arrow-link">
                            <span class="arrow-circle">
                                <!-- black arrow inside -->
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                            <span>Our purpose</span>
                        </a>
                    </div>
                </div>

                <!-- Card 4: What does SEGRO do? (Smart Bins Slough Video Promo) -->
                <div class="grid-card hero-card">
                    <div class="card-img-wrapper" style="height: 300px;">
                        <img src="{{ asset('images/imgi_8_video-grab-1.jpg') }}" alt="SEGRO Smart Bins Slough" class="card-img">
                        <div class="video-play-btn" onclick="window.location.href='/user-login'">
                            <span>Play Film</span>
                            <span style="font-size: 9px; line-height: 1;">▶</span>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 30px 30px 40px 30px;">
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            <h3 style="font-family:'Barlow', sans-serif; font-size: 24px; font-weight: 800; margin:0; letter-spacing: -0.2px;">What does SEGRO do?</h3>
                            <p style="font-size: 14px; color: var(--segro-text-grey); line-height: 1.5; font-weight: 500;">Watch our latest video to find out how we enable extraordinary things to happen</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN (50% Width) -->
            <div class="grid-column-right">

                <!-- Card 2: LTA Tennis Partnership -->
                <div class="grid-card split-card">
                    <div class="split-text">
                        <div>
                            <h3>Opening Up<br>racket sports</h3>
                            <p>SEGRO partners with the Lawn Tennis Association for 2026</p>
                        </div>
                        <a href="/company-information" class="arrow-link">
                            <span class="arrow-circle">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                            <span>Read more</span>
                        </a>
                    </div>
                    <div class="split-img">
                        <img src="{{ asset('images/imgi_7_james-craddock-uk-managing-director-at-segro-and-scott-lloyd-chief-executive-of-the-lta.jpg') }}" alt="James Craddock Scott Lloyd LTA">
                    </div>
                </div>

                <!-- Card 3: Find a Property (Red Block with V-Park Night) -->
                <div class="grid-card split-card red-card">
                    <div class="split-img">
                        <img src="{{ asset('images/imgi_4_v-park-grand-union-full-width.jpg') }}" alt="SEGRO V-Park Grand Union at night">
                    </div>
                    <div class="split-text">
                        <div>
                            <h3>Find a property</h3>
                            <p style="color: #FFFFFF; font-size: 13px; font-weight: 500;">View available units on our estates across Europe</p>
                        </div>
                        <a href="/user-login" class="arrow-link">
                            <span class="arrow-circle" style="background-color: #FFFFFF; color: var(--segro-red);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                            <span>Properties map</span>
                        </a>
                    </div>
                </div>

                <!-- Row containing Card 5 & Card 6 side-by-side -->
                <div class="metric-row" style="display: flex; gap: 24px; height: 220px; width: 100%;">
                    <!-- Card 5: Share Price Metric -->
                    <div class="grid-card metric-card" style="flex: 1; min-height: 0;">
                        <div class="metric-card-value">
                            <!-- down chevron blue -->
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22d3ee" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            <span>944.60 GBX</span>
                        </div>
                        <span class="metric-card-label">SEGRO at 16:35 GMT</span>
                    </div>

                    <!-- Card 6: Download Circle Card wrapper -->
                    <div class="grid-card circle-card-wrapper" style="flex: 1; min-height: 0; background: transparent; border: none; border-radius: 0;">
                        <div class="circle-card" onclick="window.location.href='/user-login'">
                            <h4>Download<br>Annual Report<br>& Accounts</h4>

                            <span class="arrow-circle">
                                <!-- download arrow icon -->
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 7: Responsible SEGRO -->
                <div class="grid-card split-card">
                    <div class="split-text">
                        <div>
                            <h3>Responsible<br>SEGRO</h3>
                            <p style="color: #FFFFFF; font-size: 13px; font-weight: 500;">Find out more about our progress in this space.</p>
                        </div>
                        <a href="/company-information" class="arrow-link">
                            <span class="arrow-circle">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                            <span>Responsible SEGRO</span>
                        </a>
                    </div>
                    <div class="split-img">
                        <img src="{{ asset('images/segro_beekeepers.jpg') }}" alt="SEGRO Environmental Beekeepers">
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- 4. White Corporate Footer -->
    <footer class="footer">
        <div class="footer-cols">

            <div class="footer-col">
                <h5>All about SEGRO</h5>
                <ul class="footer-links">
                    <li><a href="/company-information">Putting responsibility first</a></li>
                    <li><a href="/user-login">Investors</a></li>
                    <li><a href="/company-information">Insights</a></li>
                    <li><a href="/company-information">News</a></li>
                    <li><a href="/user-register">Join us</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Popular search</h5>
                <ul class="footer-links">
                    <li><a href="/user-login">Find a property</a></li>
                    <li><a href="/user-login">Find an estate</a></li>
                    <li><a href="/user-login">Download our Annual Report</a></li>
                    <li><a href="/company-information">Our history</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Keep in touch</h5>
                <ul class="footer-links">
                    <li><a href="/support">Contact</a></li>
                    <li><a href="/termsconditions">Customer care policy</a></li>
                    <li><a href="/support">Email Alerts</a></li>
                    <li><a href="/company-information">Marketing Materials</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <div class="footer-social-wrapper">
                    <!-- linkedin icon -->
                    <a href="#" class="social-icon">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    <!-- youtube icon -->
                    <a href="#" class="social-icon">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.163c-.272-.98-1.077-1.754-2.097-2.01-1.852-.5-9.285-.5-9.285-.5s-7.433 0-9.286.5c-1.02.256-1.825 1.03-2.097 2.01-.272.98-.272 3.018-.272 3.018s0 2.038.272 3.018c.272.98 1.077 1.754 2.097 2.01 1.853.5 9.286.5 9.286.5s7.433 0 9.285-.5c1.02-.256 1.825-1.03 2.097-2.01.272-.98.272-3.018.272-3.018s0-2.038-.272-3.018zM9.545 13.97v-8.06l7.078 4.03-7.078 4.03z"/>
                        </svg>
                    </a>
                    <!-- twitter icon -->
                    <a href="#" class="social-icon">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                </div>

                <div class="corporate-info">
                    <strong>SEGRO plc</strong>
                    Registered Office: 1 New Burlington Place,<br>
                    London W1S 2HR<br>
                    UK Registered No. 167591<br>
                    Place of Registration: England & Wales
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <div style="margin-bottom:10px;">
                © SEGRO 2026
            </div>

            <div class="footer-bottom-links">
                <a href="/termsconditions">Disclaimer</a>
                <a href="/termsconditions">Privacy policy</a>
                <a href="/termsconditions">Cookies policy</a>
                <a href="/termsconditions">Modern Slavery and Human Trafficking</a>
            </div>

            <!-- Footer dark Logo -->
            <div class="logo logo-dark" onclick="window.location.href='/'">
                <img src="{{ asset('images/logo-dark.png') }}" alt="SEGRO">
            </div>
        </div>
    </footer>

    <!-- 5. Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="mobile-menu-overlay">
        <!-- Overlay Header -->
        <div class="top-header">
            <div class="logo top-header-logo" onclick="window.location.href='/'">
                <img src="{{ asset('images/logo-dark.png') }}" alt="SEGRO">
            </div>
            <div class="top-header-right">
                <span class="icon-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </span>
                <div class="lang-selector">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span>EN</span>
                </div>
                <span class="icon-btn">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <!-- Close X button in dotted circle -->
                <div class="hamburger-circle" onclick="toggleMobileMenu()" style="cursor: pointer; border-color: rgba(255,255,255,0.6);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overlay Menu Body -->
        <div class="mobile-menu-body">
            <div>
                <!-- Share quote at top right -->
                <div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
                    <div style="text-align: right;">
                        <div style="font-size: 14px; font-weight: 700; color: #FFFFFF;">944.60 GBX</div>
                        <div style="font-size: 11px; color: var(--segro-text-grey);">SEGRO at 16:35 GMT</div>
                    </div>
                </div>

                <!-- Menu Links -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <a href="#" class="mobile-menu-link">Estates</a>
                    <a href="#" class="mobile-menu-link">Countries</a>
                    <a href="#" class="mobile-menu-link">Sustainability</a>
                    <a href="#" class="mobile-menu-link">About</a>
                    <a href="#" class="mobile-menu-link">Investors</a>
                    <a href="#" class="mobile-menu-link">Careers</a>
                    <a href="#" class="mobile-menu-link">Media</a>
                </div>
            </div>

            <div>
                <!-- Dashed separator -->
                <div style="border-top: 1px dashed rgba(255, 255, 255, 0.3); margin: 30px 0;"></div>

                <!-- Bottom links -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <a href="/support" class="mobile-menu-link" style="font-size: 16px; font-weight: 500;">Contact Us</a>
                    <a href="/user-login" class="property-btn" style="display: block; text-align: center; border-radius: 25px; padding: 12px; font-size: 14px; font-weight: bold;">Find a property</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Mobile Interests Sticky Bottom Footer Bar -->
    <div class="mobile-interests-footer" onclick="toggleMobileInterestsDrawer()">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-family: 'Barlow', sans-serif; font-size: 18px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.2px;">Interests</span>
            <span class="vertical-index" style="margin: 0; width: 22px; height: 22px; font-size: 9px; font-weight: bold; border-color: rgba(255,255,255,0.5);">0</span>
        </div>
        <svg class="drawer-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </div>

    <!-- Mobile Interests Drawer Popup Sheet -->
    <div id="mobileInterestsDrawer" class="mobile-interests-drawer">
        <div style="padding: 24px 24px 0 24px;">
            <h3 style="font-family: 'Barlow', sans-serif; font-size: 24px; font-weight: 800; margin: 0 0 8px 0; letter-spacing: -0.5px;">Welcome to <span style="color: var(--segro-red);">SEGRO</span></h3>
            <p style="font-size: 12px; color: var(--segro-text-grey); margin: 0 0 15px 0; line-height: 1.5;">Customize your homepage by selecting a filter below.</p>
        </div>
        <div class="filter-list" style="margin-top: 0; padding: 0 24px 30px 24px;">
            <div class="filter-item" onclick="window.location.href='/user-login'">
                <span class="filter-radio"></span>
                <span>Property</span>
            </div>
            <div class="filter-item" onclick="window.location.href='/user-login'">
                <span class="filter-radio"></span>
                <span>Investor information</span>
            </div>
            <div class="filter-item" onclick="window.location.href='/company-information'">
                <span class="filter-radio"></span>
                <span>Responsible SEGRO</span>
            </div>
            <div class="filter-item" onclick="window.location.href='/user-login'">
                <span class="filter-radio"></span>
                <span>Careers and culture at SEGRO</span>
            </div>
            <div class="filter-item" onclick="window.location.href='/company-information'">
                <span class="filter-radio"></span>
                <span>Our history</span>
            </div>
            <div class="filter-item" onclick="window.location.href='/company-information'">
                <span class="filter-radio"></span>
                <span>Community Investment</span>
            </div>
        </div>
    </div>

    <!-- Toggle scripts for Mobile navigation and accordion -->
    <script src="{{ asset('js/pages/index.js') }}"></script>
    <script src="{{ asset('js/modern_scripts.js') }}"></script>
</body>
</html>
