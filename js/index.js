document.addEventListener('DOMContentLoaded', function() {
    const sliderTrack = document.querySelector('.slider-track');
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const slides = Array.from(sliderTrack.children);
    const slideWidth = slides[0].getBoundingClientRect().width;
    
    slides.forEach(slide => {
        const clone = slide.cloneNode(true);
        sliderTrack.appendChild(clone);
    });
});

