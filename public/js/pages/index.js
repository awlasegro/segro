function toggleMobileMenu() {
    const overlay = document.getElementById('mobileMenuOverlay');
    overlay.classList.toggle('open');
}

function toggleMobileInterestsDrawer() {
    const drawer = document.getElementById('mobileInterestsDrawer');
    const chevron = document.querySelector('.mobile-interests-footer .drawer-chevron');
    drawer.classList.toggle('open');

    if (drawer.classList.contains('open')) {
        chevron.style.transform = 'rotate(180deg)';
    } else {
        chevron.style.transform = 'rotate(0deg)';
    }
}
