document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-btn');
    const drawer = document.querySelector('.drawer');

    if (menuBtn && drawer) {
        menuBtn.addEventListener('click', () => {
            drawer.style.left = drawer.style.left === '0px' ? '-280px' : '0px';
        });
    }

    // Add smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Testimonial Carousel
    const carousel = document.querySelector('.testimonial-carousel');
    if (carousel) {
        const items = document.querySelectorAll('.testimonial-item');
        const totalItems = items.length;
        let currentIndex = 0;

        document.querySelector('.next-btn').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        });

        document.querySelector('.prev-btn').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
        });

        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
    }

    // Lightbox Modal for Projects
    const lightboxModal = document.getElementById('lightbox-modal');
    if (lightboxModal) {
        const modalImg = document.getElementById('lightbox-img');
        const closeBtn = lightboxModal.querySelector('.close-btn');
        const prevBtn = lightboxModal.querySelector('.prev-btn');
        const nextBtn = lightboxModal.querySelector('.next-btn');
        let currentMedia = [];
        let currentIndex = 0;

        document.querySelectorAll('.project-thumbnail').forEach(item => {
            item.addEventListener('click', event => {
                currentMedia = JSON.parse(event.target.dataset.media);
                currentIndex = 0;
                updateLightbox();
                lightboxModal.style.display = 'block';
            });
        });

        function updateLightbox() {
            if (currentMedia.length > 0) {
                modalImg.src = currentMedia[currentIndex];
                prevBtn.style.display = currentMedia.length > 1 ? 'block' : 'none';
                nextBtn.style.display = currentMedia.length > 1 ? 'block' : 'none';
            }
        }

        closeBtn.addEventListener('click', () => {
            lightboxModal.style.display = 'none';
        });

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + currentMedia.length) % currentMedia.length;
            updateLightbox();
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % currentMedia.length;
            updateLightbox();
        });

        window.addEventListener('click', (event) => {
            if (event.target === lightboxModal) {
                lightboxModal.style.display = 'none';
            }
        });
    }

    // Theme Toggle
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            document.body.classList.add(currentTheme);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.classList.add('dark-mode');
        }

        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            let theme = document.body.classList.contains('dark-mode') ? 'dark-mode' : '';
            localStorage.setItem('theme', theme);
        });
    }
});