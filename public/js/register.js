/* register.js - Registration page password generator */

// Cryptographically-random character pick (avoids Math.random, which
// isn't suitable for generating credentials).
function secureRandomChar(charset) {
    const array = new Uint32Array(1);
    window.crypto.getRandomValues(array);
    return charset[array[0] % charset.length];
}

function generateSecurePassword(length) {
    length = length || 14;
    // Ambiguous-looking characters (0/O, 1/l/I) left out so a generated
    // password is easier to read back and retype if needed.
    const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    const lower = 'abcdefghijkmnpqrstuvwxyz';
    const digits = '23456789';
    const symbols = '!@#$%^&*-_=+';
    const all = upper + lower + digits + symbols;

    // Guarantee at least one character from each category.
    const chars = [
        secureRandomChar(upper),
        secureRandomChar(lower),
        secureRandomChar(digits),
        secureRandomChar(symbols),
    ];

    for (let i = chars.length; i < length; i++) {
        chars.push(secureRandomChar(all));
    }

    // Fisher-Yates shuffle so the guaranteed characters aren't always up front.
    for (let i = chars.length - 1; i > 0; i--) {
        const array = new Uint32Array(1);
        window.crypto.getRandomValues(array);
        const j = array[0] % (i + 1);
        [chars[i], chars[j]] = [chars[j], chars[i]];
    }

    return chars.join('');
}

function fillPassword(password, ...inputs) {
    inputs.forEach(function (input) {
        input.type = 'text';
        input.value = password;
    });
}

function generateLoginPassword() {
    const password = generateSecurePassword(14);
    fillPassword(password, document.getElementById('password'), document.getElementById('confirm-password'));
}

function generateWalletPassword() {
    const password = generateSecurePassword(14);
    fillPassword(password, document.getElementById('wallet-password'));
}
