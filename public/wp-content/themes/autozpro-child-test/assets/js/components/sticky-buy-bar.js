/**
 * Sticky Buy Bar — IntersectionObserver
 * Pokazuje bar gdy buy-box wychodzi z viewport
 */

const buyBox = document.querySelector('.product-buy-box');
const stickyBar = document.getElementById('sticky-buy-bar');

if (buyBox && stickyBar) {
    const observer = new IntersectionObserver(
        ([entry]) => {
            stickyBar.classList.toggle('sticky-buy-bar--visible', !entry.isIntersecting);
        },
        { threshold: 0 }
    );

    observer.observe(buyBox);
}
