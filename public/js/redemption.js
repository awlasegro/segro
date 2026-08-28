/* redemption.js - Live balance preview as the withdrawal amount is typed */

const amountInput = document.getElementById('amount');
const initialBalance = amountInput ? parseFloat(amountInput.max) : 0;
const displayBalance = document.getElementById('display-balance');
const labelBalance = document.getElementById('label-balance');

function updateRealtimeBalance() {
    const val = parseFloat(amountInput.value) || 0;
    const newBalance = Math.max(0, initialBalance - val);

    // Format to 2 decimal places
    const formatted = newBalance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    if (displayBalance) {
        displayBalance.innerText = formatted;
    }
    if (labelBalance) {
        labelBalance.innerText = formatted;
    }
}

if (amountInput) {
    amountInput.addEventListener('input', updateRealtimeBalance);
}
