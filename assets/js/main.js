/**
 * TokoKu Main JavaScript
 * 
 * TABLE OF CONTENTS:
 * 1. SHARED LOGIC (Theme Toggle, Sticky Header)
 * 2. DESKTOP SPECIFIC LOGIC
 * 3. MOBILE SPECIFIC LOGIC (Drawer, Bottom Nav, Search Modal)
 * 4. PWA & UTILITIES
 */

document.addEventListener('DOMContentLoaded', function() {

    /* ==========================================================================
       1. SHARED LOGIC
       ========================================================================== */
    
    // 🌓 Theme Toggle (Light/Dark Mode)
    const modeToggle = document.getElementById('mode-toggle');
    const body = document.body;
    
    if (modeToggle) {
        modeToggle.addEventListener('click', () => {
            if (body.classList.contains('theme-dark')) {
                body.classList.remove('theme-dark');
                body.classList.add('theme-light');
                localStorage.setItem('tokoku-theme', 'light');
            } else {
                body.classList.remove('theme-light');
                body.classList.add('theme-dark');
                localStorage.setItem('tokoku-theme', 'dark');
            }
            if (typeof updateThemeColor === 'function') updateThemeColor();
        });
    }

    // 🕒 Sticky Header
    const header = document.querySelector('.site-header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header?.classList.add('sticky');
        } else {
            header?.classList.remove('sticky');
        }
    });

    /* ==========================================================================
       2. DESKTOP SPECIFIC LOGIC
       ========================================================================== */
    
    // Desktop specific scripts can go here

    /* ==========================================================================
       3. SHARED COMPONENTS LOGIC
       ========================================================================== */
    
    // 🎡 Hero Slider
    const slider = document.querySelector('.hero-slider-section');
    if (slider) {
        const wrapper = slider.querySelector('.slider-wrapper');
        const slides = slider.querySelectorAll('.slide');
        const prevBtn = slider.querySelector('.slider-prev');
        const nextBtn = slider.querySelector('.slider-next');
        const dotsContainer = slider.querySelector('.slider-dots');
        
        if (wrapper && slides.length > 0) {
            let currentIndex = 0;
            let slideInterval;
            
            // Create Dots
            slides.forEach((_, i) => {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer?.appendChild(dot);
            });
            
            const dots = dotsContainer?.querySelectorAll('.dot');
            
            function updateSlider() {
                wrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
                dots?.forEach(dot => dot.classList.remove('active'));
                if (dots) dots[currentIndex].classList.add('active');
            }
            
            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlider();
            }
            
            function prevSlide() {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateSlider();
            }
            
            function goToSlide(index) {
                currentIndex = index;
                updateSlider();
                resetInterval();
            }
            
            function resetInterval() {
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }
            
            prevBtn?.addEventListener('click', () => { prevSlide(); resetInterval(); });
            nextBtn?.addEventListener('click', () => { nextSlide(); resetInterval(); });
            
            // Start Auto Slide
            slideInterval = setInterval(nextSlide, 5000);
        }
    }

    /* ==========================================================================
       4. MOBILE SPECIFIC LOGIC
       ========================================================================== */
    
    // 📱 Mobile Menu Drawer
    const menuToggle = document.getElementById('menu-toggle');
    const menuDrawer = document.getElementById('mobile-menu-drawer');
    const menuOverlay = document.getElementById('mobile-menu-overlay');
    const menuClose = document.getElementById('mobile-menu-close');
    
    function closeMenu() {
        menuDrawer?.classList.remove('active');
        menuOverlay?.classList.remove('active');
        menuToggle?.classList.remove('active');
        body.classList.remove('menu-open');
    }

    if (menuToggle && menuDrawer) {
        menuToggle.addEventListener('click', () => {
            menuDrawer.classList.add('active');
            menuOverlay?.classList.add('active');
            menuToggle.classList.add('active');
            body.classList.add('menu-open');
        });
        
        menuClose?.addEventListener('click', closeMenu);
        menuOverlay?.addEventListener('click', closeMenu);
    }

    // 📱 Mobile Sub-menu Toggle (Accordion)
    const menuItemsWithChildren = document.querySelectorAll('.mobile-nav-list .menu-item-has-children > a');
    menuItemsWithChildren.forEach(item => {
        item.addEventListener('click', (e) => {
            const parent = item.parentElement;
            const href = item.getAttribute('href');
            if (href === '#' || href === '') {
                e.preventDefault();
                parent.classList.toggle('active');
            } else {
                parent.classList.toggle('active');
            }
        });
    });

    /* ==========================================================================
       4. PWA & UTILITIES
       ========================================================================== */
    
    // 🛡️ Register Service Worker for PWA
    if ('serviceWorker' in navigator && typeof tokokuSearch !== 'undefined') {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(tokokuSearch.themeUrl + '/sw.js')
                .then(reg => console.log('TokoKu SW registered'))
                .catch(err => console.log('SW registration failed:', err));
        });
    }

    // 🎨 Dynamic Theme Color for PWA
    function updateThemeColor() {
        const meta = document.querySelector('meta[name="theme-color"]');
        if (meta) {
            meta.setAttribute('content', body.classList.contains('theme-dark') ? '#0f172a' : '#ffffff');
        }
    }
    updateThemeColor();
});
