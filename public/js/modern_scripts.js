/* modern_scripts.js - Unified Scripts for User-facing App */

// Toggle left sidebar drawer
function toggleSidebar() {
    const drawer = document.getElementById('sidebar-drawer');
    const overlay = document.getElementById('sidebar-overlay');
    if (drawer && overlay) {
        drawer.classList.toggle('active');
        overlay.classList.toggle('active');
    }
}

// Copy Text to Clipboard
function copyToClipboard(elementId) {
    const copyText = document.getElementById(elementId);
    if (copyText) {
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        
        navigator.clipboard.writeText(copyText.value)
            .then(() => {
                alert("Copied to clipboard: " + copyText.value);
            })
            .catch(err => {
                console.error("Could not copy text: ", err);
            });
    }
}

// Preset Amount selector on Recharge screen
function selectPresetAmount(amount, element) {
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.value = amount;
        // Dispatch input event so real-time balance calculations run
        amountInput.dispatchEvent(new Event('input'));
    }
    
    // Highlight active chip
    const chips = document.querySelectorAll('.preset-chip');
    chips.forEach(chip => chip.classList.remove('active'));
    if (element) {
        element.classList.add('active');
    }
}

// Client-side state switcher for Recharge screen
function generateRecharge() {
    const amountInput = document.getElementById('amount');
    const amountVal = amountInput ? amountInput.value : '';
    
    if (!amountVal || parseFloat(amountVal) <= 0) {
        alert("Please enter a valid deposit amount.");
        return;
    }
    
    const requestContainer = document.getElementById('recharge-request-container');
    const generatorContainer = document.getElementById('recharge-generator-container');
    const displayAmount = document.getElementById('display-deposit-amount');
    
    if (requestContainer && generatorContainer) {
        generatorContainer.style.display = 'none';
        requestContainer.style.display = 'block';
        if (displayAmount) {
            displayAmount.value = parseFloat(amountVal).toFixed(2);
        }
    }
}

// Cancel Recharge request and return to generator
function cancelRecharge() {
    const requestContainer = document.getElementById('recharge-request-container');
    const generatorContainer = document.getElementById('recharge-generator-container');
    
    if (requestContainer && generatorContainer) {
        requestContainer.style.display = 'none';
        generatorContainer.style.display = 'block';
    }
}

// Handle dynamic file upload selection details and preview
function handleFileSelected(input) {
    const file = input.files[0];
    const previewContainer = document.getElementById('screenshot-preview-container');
    const previewImage = document.getElementById('screenshot-preview');
    const labelText = document.getElementById('upload-label-text');
    
    if (file) {
        labelText.innerText = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImage) {
                previewImage.src = e.target.result;
            }
            if (previewContainer) {
                previewContainer.style.display = 'block';
            }
        }
        reader.readAsDataURL(file);
    } else {
        labelText.innerText = "Tap to upload payment screenshot";
        if (previewContainer) {
            previewContainer.style.display = 'none';
        }
    }
}

// Rating selection on submit order
function selectRating(stars) {
    const ratingInputs = document.querySelectorAll('.rating-star-btn');
    ratingInputs.forEach((star, index) => {
        if (index < stars) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
    
    // Select the radio button in the background if it exists
    const radioBtn = document.getElementById('star' + stars);
    if (radioBtn) {
        radioBtn.checked = true;
    }
}

// Switch tabs on history page
function switchTab(tabId, button) {
    // Hide all tab content
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.style.display = 'none');
    
    // Show selected content
    const selectedContent = document.getElementById(tabId);
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }
    
    // Toggle active button class
    if (button) {
        const buttons = button.parentNode.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
    }
}

// Show feedback screen on order submit
function showFeedbackScreen() {
    const overview = document.getElementById('order-overview-container');
    const feedback = document.getElementById('order-feedback-container');
    if (overview && feedback) {
        overview.style.display = 'none';
        feedback.style.display = 'block';
    }
}

// Back to overview screen on order submit
function backToOverviewScreen() {
    const overview = document.getElementById('order-overview-container');
    const feedback = document.getElementById('order-feedback-container');
    if (overview && feedback) {
        feedback.style.display = 'none';
        overview.style.display = 'block';
    }
}

// Show overpriced modal
function showOverpricedModal() {
    const modal = document.getElementById('overpriced-modal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Hide overpriced modal
function hideOverpricedModal() {
    const modal = document.getElementById('overpriced-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}



