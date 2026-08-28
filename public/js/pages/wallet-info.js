// Handle type / network chip activation
document.querySelectorAll('input[name="wallet-type"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
        e.target.closest('.preset-chips').querySelectorAll('.preset-chip').forEach(c => c.classList.remove('active'));
        e.target.closest('.preset-chip').classList.add('active');
    });
});

// Handle blockchain chip activation
document.querySelectorAll('input[name="blockchain"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
        e.target.closest('.preset-chips').querySelectorAll('.preset-chip').forEach(c => c.classList.remove('active'));
        e.target.closest('.preset-chip').classList.add('active');
    });
});
