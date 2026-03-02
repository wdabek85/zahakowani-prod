document.addEventListener('DOMContentLoaded', function() {
    
    const descriptionBox = document.querySelector('.product-description');
    const toggleBtn = document.querySelector('.product-description__toggle');
    
    if (!descriptionBox || !toggleBtn) return;
    
    toggleBtn.addEventListener('click', function() {
        const isCollapsed = descriptionBox.getAttribute('data-collapsed') === 'true';
        
        if (isCollapsed) {
            // Rozwiń
            descriptionBox.setAttribute('data-collapsed', 'false');
            toggleBtn.textContent = 'Zwiń Opis';
            
        } else {
            // Zwiń
            descriptionBox.setAttribute('data-collapsed', 'true');
            toggleBtn.textContent = 'Rozwiń Pełen Opis';
            
            // Scroll do początku sekcji
            document.querySelector('#opis').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
    
});