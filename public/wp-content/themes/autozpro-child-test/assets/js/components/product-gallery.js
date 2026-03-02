document.addEventListener('DOMContentLoaded', function() {

    const mainImg = document.getElementById('product-gallery-main');
    const thumbs  = document.querySelectorAll('.product-gallery__thumb');

    if (!mainImg || !thumbs.length) return;

    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            mainImg.removeAttribute('srcset');
            mainImg.removeAttribute('sizes');
            mainImg.src = this.dataset.full;

            thumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

});