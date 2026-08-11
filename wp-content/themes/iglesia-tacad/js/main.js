/**
 * Iglesia TACAD - Main JS
 */
document.addEventListener('DOMContentLoaded', function () {

    // === HAMBURGER TOGGLE ===
    const navToggle = document.getElementById('nav-toggle');
    const primaryNav = document.getElementById('primary-nav');

    if (navToggle && primaryNav) {
        navToggle.addEventListener('click', function () {
            primaryNav.classList.toggle('open');
            navToggle.classList.toggle('open');
        });

        primaryNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => primaryNav.classList.remove('open'));
        });

        primaryNav.querySelectorAll('li').forEach(li => {
            if (li.querySelector('ul')) {
                li.addEventListener('click', function (e) {
                    if (window.innerWidth <= 768) {
                        e.stopPropagation();
                        li.classList.toggle('open');
                    }
                });
            }
        });
    }

    // === HERO SLIDER ===
    const slides = document.querySelectorAll('.hero-slider .slide');
    const dots = document.querySelectorAll('.hero-dot');
    let currentSlide = 0;
    let sliderInterval;

    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        if (dots.length) dots[currentSlide].classList.remove('active');
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        if (dots.length) dots[currentSlide].classList.add('active');
    }

    function nextSlide() { goToSlide(currentSlide + 1); }
    function prevSlide() { goToSlide(currentSlide - 1); }

    if (slides.length > 0) {
        sliderInterval = setInterval(nextSlide, 7000);

        const nextBtn = document.getElementById('slide-next');
        const prevBtn = document.getElementById('slide-prev');

        if (nextBtn) nextBtn.addEventListener('click', () => { clearInterval(sliderInterval); nextSlide(); sliderInterval = setInterval(nextSlide, 7000); });
        if (prevBtn) prevBtn.addEventListener('click', () => { clearInterval(sliderInterval); prevSlide(); sliderInterval = setInterval(nextSlide, 7000); });

        dots.forEach(dot => {
            dot.addEventListener('click', function () {
                clearInterval(sliderInterval);
                goToSlide(parseInt(this.dataset.slide));
                sliderInterval = setInterval(nextSlide, 7000);
            });
        });
    }

    // === ACCORDION (FAQ) ===
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', function () {
            const item = this.closest('.accordion-item');
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.accordion-item').forEach(i => i.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        });
    });

    // === STICKY HEADER SHADOW & SCROLLED CLASS ===
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
                header.style.boxShadow = '0 4px 30px rgba(0,0,0,0.4)';
            } else {
                header.classList.remove('scrolled');
                header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.3)';
            }
        });
    }

    // === SCROLL REVEAL (Intersection Observer) ===
    const revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
        revealObserver.observe(el);
    });

    // === SCROLL ANIMATION: Old fade-in for cards ===
    const cardObserver = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.info-card, .sermon-card, .hymn-card, .blog-card, .service-card, .ministry-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        cardObserver.observe(el);
    });

    // === COUNTER ANIMATION ===
    function animateCounter(el) {
        const target = parseInt(el.dataset.count);
        if (!target) return;
        const suffix = el.textContent.replace(/[\d]/g, '');
        const duration = 2000;
        const steps = 60;
        const increment = target / steps;
        let current = 0;
        const stepTime = duration / steps;

        function update() {
            current += increment;
            if (current >= target) {
                el.textContent = target + suffix;
                return;
            }
            el.textContent = Math.floor(current) + suffix;
            requestAnimationFrame(function () {
                setTimeout(update, stepTime);
            });
        }
        update();
    }

    const counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-count]').forEach(el => {
        counterObserver.observe(el);
    });

    // === PARALLAX ON SCROLL ===
    const parallaxLayers = document.querySelectorAll('.parallax-layer');
    if (parallaxLayers.length) {
        window.addEventListener('scroll', function () {
            const scrolled = window.scrollY;
            parallaxLayers.forEach(layer => {
                const speed = 0.3;
                const rect = layer.parentElement.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    layer.style.transform = 'translateY(' + (scrolled * speed * 0.1) + 'px)';
                }
            });
        });
    }

    // === SMOOTH SCROLL FOR ANCHOR LINKS ===
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // === GLOW TRACKING ON CARDS ===
    document.querySelectorAll('.info-card, .sermon-card, .ministry-home-card').forEach(card => {
        card.addEventListener('mousemove', function (e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            this.style.transform = this.classList.contains('ministry-home-card')
                ? 'perspective(800px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-8px) scale(1.02)'
                : 'perspective(800px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-6px)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });
});
