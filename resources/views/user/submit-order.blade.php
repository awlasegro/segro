<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Pending - Segro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/modern_style.css') }}">
</head>
<body>

    <!-- Header -->
    <header class="app-header">
        <a href="/data-optimization" class="header-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h1>Review Order</h1>
        <div class="header-icon" style="opacity: 0;"></div>
    </header>

    <!-- Main Container -->
    <div class="container">
        @if (session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($orderData)
            @foreach($orderData as $data)
                <!-- Order Card -->
                <div class="card">
                    
                    <!-- SCREEN 1: ORDER OVERVIEW -->
                    <div id="order-overview-container" style="display: block;">
                        <h2 class="card-title" style="margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">{{ $data['order']->title }}</h2>
                        
                        <!-- Product details panel -->
                        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px;">
                            <img src="{{ asset('OrderImages/' . $data['image']) }}" alt="Product Image" style="width: 110px; height: 110px; border-radius: 12px; object-fit: cover; background-color: #fff; padding: 4px; border: 1px solid var(--border-color);">
                            
                            <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                                <div class="d-flex justify-between" style="font-size: 13px;">
                                    <span style="color: var(--text-secondary);">Item Price:</span>
                                    <span style="font-weight: 700; color: var(--text-primary);">${{ number_format($data['price'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-between" style="font-size: 13px;">
                                    <span style="color: var(--text-secondary);">Your Profit:</span>
                                    <span style="font-weight: 700; color: var(--success-color);">+${{ number_format($data['commission'], 2) }}</span>
                                </div>

                                @if ($data['overpriced_amount'] > 0)
                                    <div class="d-flex justify-between" style="font-size: 13px; border-top: 1px solid var(--border-color); padding-top: 6px;">
                                        <span style="color: var(--danger-color); font-weight: 600;">Add Amount Needed:</span>
                                        <span style="font-weight: 700; color: var(--danger-color);">${{ number_format($data['overpriced_amount'], 2) }}</span>
                                    </div>
                                @else
                                    <div class="d-flex justify-between" style="font-size: 13px; border-top: 1px solid var(--border-color); padding-top: 6px;">
                                        <span style="color: var(--text-secondary);">Total Value:</span>
                                        <span style="font-weight: 700; color: var(--accent-color);">${{ number_format($data['total_value'], 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($data['overpriced_amount'] <= 0)
                            <button type="button" onclick="showFeedbackScreen()" class="btn btn-primary" style="width: 100%;">Proceed Order</button>
                        @else
                            <button type="button" onclick="showOverpricedModal()" class="btn btn-danger" style="width: 100%;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Contact Support (Recharge Required)</span>
                            </button>

                            <!-- OVERPRICED UNLOCK EARNINGS MODAL OVERLAY -->
                            <div id="overpriced-modal" class="modal-overlay" style="display: none;">
                                <div class="modal-card">
                                    <button type="button" class="modal-close-btn" onclick="hideOverpricedModal()">✕</button>
                                    <div style="text-align: center;">
                                        <span style="color: #f59e0b; font-size: 24px; letter-spacing: 2px;">★★★★★</span>
                                    </div>
                                    <div style="text-align: center; margin-top: 10px;">
                                        <span class="modal-badge">High commission</span>
                                    </div>
                                    <h2 class="card-title" style="text-align: center; font-size: 18px; margin-top: 10px; margin-bottom: 5px; border: none; padding: 0;">Unlock your earnings</h2>
                                    <p style="text-align: center; font-size: 11px; color: var(--text-secondary); margin-bottom: 20px; line-height: 1.4;">Top up your wallet to finish this review and claim your commission.</p>
                                    
                                    <div class="modal-box-red">
                                        <p style="font-size: 10px; color: var(--danger-color); font-weight: 700; margin-bottom: 4px; text-transform: uppercase;">Deposit Required</p>
                                        <h3 style="font-size: 24px; font-weight: 800; color: var(--danger-color); margin: 0;">-${{ number_format($data['overpriced_amount'], 2) }}</h3>
                                        <p style="font-size: 9.5px; color: var(--text-muted); margin-top: 4px; margin-bottom: 0;">Add this to your wallet to complete this step</p>
                                    </div>
                                    
                                    <div class="modal-box-green">
                                        <p style="font-size: 10px; color: var(--success-color); font-weight: 700; margin-bottom: 4px; text-transform: uppercase;">You earn on this step</p>
                                        <h3 style="font-size: 20px; font-weight: 800; color: var(--success-color); margin: 0;">+${{ number_format($data['commission'], 2) }}</h3>
                                    </div>
                                    
                                    <div class="modal-table">
                                        <div class="modal-table-row">
                                            <span>Product price</span>
                                            <span style="color: var(--text-primary); font-weight: 600;">${{ number_format($data['price'], 2) }}</span>
                                        </div>
                                        <div class="modal-table-row">
                                            <span>Your balance</span>
                                            <span style="color: var(--text-primary); font-weight: 600;">${{ number_format($funds, 2) }}</span>
                                        </div>
                                        <div class="modal-table-row" style="color: var(--danger-color); font-weight: 600;">
                                            <span>Deposit needed</span>
                                            <span>${{ number_format($data['overpriced_amount'], 2) }}</span>
                                        </div>
                                        <div class="modal-table-row" style="color: var(--success-color); font-weight: 600; border-top: 1px dashed var(--border-color); padding-top: 8px; margin-top: 8px;">
                                            <span>Your profit</span>
                                            <span>+${{ number_format($data['commission'], 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <button type="button" onclick="window.location.href='/recharge'" class="btn btn-primary" style="width: 100%; margin-bottom: 12px; height: 46px;">Submit Review & Earn Commission</button>
                                    <p style="text-align: center; font-size: 10.5px; color: var(--text-secondary); margin-bottom: 15px;">You can deposit more than the minimum.</p>
                                    
                                    <button type="button" onclick="window.location.href='/support'" class="btn btn-secondary" style="width: 100%; height: 46px;">Contact Support</button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- SCREEN 2: ORDER FEEDBACK & SUBMISSION -->
                    <div id="order-feedback-container" style="display: none;">
                        <h2 class="card-title" style="margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Optimize Listing Review</h2>
                        
                        <!-- Stars Selector -->
                        <div style="text-align: center; margin-bottom: 20px; padding-top: 5px;">
                            <span style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 8px;">Rate this Experience</span>
                            <div class="rating-group">
                                <span class="rating-star-btn active" onclick="selectRating(1)">★</span>
                                <span class="rating-star-btn active" onclick="selectRating(2)">★</span>
                                <span class="rating-star-btn active" onclick="selectRating(3)">★</span>
                                <span class="rating-star-btn active" onclick="selectRating(4)">★</span>
                                <span class="rating-star-btn active" onclick="selectRating(5)">★</span>
                            </div>
                        </div>

                        <!-- Comment Dropdown -->
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="feedback">Select Review Comment</label>
                            <select id="feedback" name="feedback">
                                <option value="I just love it! Just Perfect!">I just love it! Just Perfect!</option>
                                <option value="Brilliant, just brilliant!">Brilliant, just brilliant!</option>
                                <option value="I recommend for everyone to use this app and you will love it 😍">I recommend for everyone to use this app and you will love it 😍</option>
                                <option value="This app is truly amazing, very helpful and detailed in almost every aspect.">This app is truly amazing, very helpful and detailed in almost every aspect.</option>
                                <option value="This is the best app of all time. I can say that they provide A-Z services.">This is the best app of all time. I can say that they provide A-Z services.</option>
                                <option value="A great experience in here, one of my favorite locations to visit in my life.">A great experience in here, one of my favorite locations to visit in my life.</option>
                                <option value="I really gotta say. Dynamites come in small packages. And this is totally it😁.">I really gotta say. Dynamites come in small packages. And this is totally it😁.</option>
                                <option value="Excellent Services of any lifestyle planning.">Excellent Services of any lifestyle planning.</option>
                                <option value="5 stars winning all the time for them.">5 stars winning all the time for them.</option>
                                <option value="I give it 5 stars because I had surveyed their brand reputations, have not experienced but they are super COOL.">I give it 5 stars because I had surveyed their brand reputations, have not experienced but they are super COOL.</option>
                                <option value="Highly recommended. Have fun!">Highly recommended. Have fun!</option>
                                <option value="Been using this for a year, well organized and reasonable charges.">Been using this for a year, well organized and reasonable charges.</option>
                                <option value="One of the best apps I have ever used. Perfect!">One of the best apps I have ever used. Perfect!</option>
                                <option value="This app probably will change your life because they changed mine. I’m loving it !">This app probably will change your life because they changed mine. I’m loving it !</option>
                                <option value="I have only used it once and will never forget it in my life. Superb.">I have only used it once and will never forget it in my life. Superb.</option>
                                <option value="You will never regret it. Trust me, they are the best apps ever.">You will never regret it. Trust me, they are the best apps ever.</option>
                                <option value="They are so amazing! It reached beyond my expectations. They solved all my requests.">They are so amazing! It reached beyond my expectations. They solved all my requests.</option>
                                <option value="I used their services for 3 months and they never disappointed me. Bravo!">I used their services for 3 months and they never disappointed me. Bravo!</option>
                                <option value="I went here on my last vacation to spend my honeymoon and they surprised me. Well done and thank you.">I went here on my last vacation to spend my honeymoon and they surprised me. Well done and thank you.</option>
                                <option value="I celebrate my marriage anniversary by using their services and planning. They are really professional.">I celebrate my marriage anniversary by using their services and planning. They are really professional.</option>
                            </select>
                        </div>

                        <!-- Confirmation screenshot uploader -->
                        <div class="form-group" style="margin-bottom: 25px;">
                            <label>Feedback screenshot</label>
                            <div class="upload-dropzone" style="padding: 20px; margin-top: 5px;">
                                <svg class="upload-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="height: 36px; width: 36px; margin-bottom: 8px;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <span class="upload-text" style="font-size: 12.5px;">Upload review verification screenshot</span>
                                <span class="upload-hint" style="font-size: 10px;">PNG, JPG up to 5 MB</span>
                            </div>
                        </div>

                        <!-- Main submit form -->
                        <form action="{{ route('process.order') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $data['order_row_id'] }}">
                            <input type="hidden" name="commission" value="{{ $data['commission'] }}">
                            
                            <!-- Hidden inputs for rating selector -->
                            <input type="radio" id="star1" name="rating" value="1" style="display:none;">
                            <input type="radio" id="star2" name="rating" value="2" style="display:none;">
                            <input type="radio" id="star3" name="rating" value="3" style="display:none;">
                            <input type="radio" id="star4" name="rating" value="4" style="display:none;">
                            <input type="radio" id="star5" name="rating" value="5" style="display:none;" checked>

                            <div class="tier-actions">
                                <button type="submit" class="btn btn-primary" style="flex: 1;">Submit Order</button>
                                <button type="button" onclick="backToOverviewScreen()" class="btn btn-secondary" style="flex: 1;">Back</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>



    <script src="{{ asset('js/modern_scripts.js') }}"></script>
    @include('user.partials.chat-widget')
</body>
</html>
