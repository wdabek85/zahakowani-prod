/**
 * Trust Drawer — wysuwane panele informacyjne
 * Kliknięcie trust-icon[data-drawer] otwiera odpowiedni drawer
 */

const overlay = document.getElementById('trust-drawer-overlay');
const triggers = document.querySelectorAll('[data-drawer]');

if (overlay && triggers.length) {
    let openDrawer = null;

    function open(id) {
        const drawer = document.getElementById(`trust-drawer-${id}`);
        if (!drawer) return;

        openDrawer = drawer;
        drawer.classList.add('trust-drawer--open');
        overlay.classList.add('trust-drawer-overlay--visible');
        document.body.classList.add('trust-drawer-open');
    }

    function close() {
        if (!openDrawer) return;

        openDrawer.classList.remove('trust-drawer--open');
        overlay.classList.remove('trust-drawer-overlay--visible');
        document.body.classList.remove('trust-drawer-open');
        openDrawer = null;
    }

    // Trigger buttons
    triggers.forEach((btn) => {
        btn.addEventListener('click', () => open(btn.dataset.drawer));
    });

    // Close on overlay click
    overlay.addEventListener('click', close);

    // Close buttons inside drawers
    document.querySelectorAll('.trust-drawer__close').forEach((btn) => {
        btn.addEventListener('click', close);
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });
}
