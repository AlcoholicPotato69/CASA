document.addEventListener('DOMContentLoaded', () => {
    // 0. Initialize Lenis Smooth Scroll
    if (typeof Lenis !== 'undefined') {
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
        });

        window.lenisInstance = lenis;

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);
    }

    // 0.5. Page Transition with Anime.js
    const overlay = document.getElementById('page-transition-overlay');
    if (overlay && typeof anime !== 'undefined') {
        // Entrance animation (Reveal page)
        anime({
            targets: overlay,
            translateY: ['0%', '-100%'],
            easing: 'easeInOutExpo',
            duration: 900,
            complete: function() {
                overlay.style.display = 'none';
                overlay.style.pointerEvents = 'none';
            }
        });

        // Exit animation (Hide page and redirect)
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                const hasGlightbox = this.classList.contains('glightbox') || this.closest('.glightbox') !== null;
                const isImage = href.match(/\.(jpeg|jpg|gif|png|webp|svg|pdf)$/i) !== null;
                
                if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || this.getAttribute('target') === '_blank' || hasGlightbox || isImage) return;
                
                const isLocal = href.startsWith('/') || href.startsWith(window.location.origin);
                
                if (isLocal) {
                    e.preventDefault();
                    overlay.style.display = 'flex';
                    overlay.style.pointerEvents = 'auto'; // Block interaction while transitioning
                    
                    anime({
                        targets: overlay,
                        translateY: ['100%', '0%'],
                        easing: 'easeInOutExpo',
                        duration: 800,
                        complete: function() {
                            window.location.href = href;
                        }
                    });
                }
            });
        });

        // Handle direct load with hash on mobile
        window.addEventListener('load', () => {
            if (window.innerWidth < 1024 && window.location.hash) {
                const targetEl = document.querySelector(window.location.hash);
                if (targetEl) {
                    setTimeout(() => {
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 400);
                }
            }
        });

        // Fix for bfcache (Safari/Chrome back button) causing permanent black screen
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                overlay.style.display = 'none';
                overlay.style.pointerEvents = 'none';
                overlay.style.transform = 'translateY(-100%)';
                if(typeof anime !== 'undefined') anime.set(overlay, { translateY: '-100%' });
            }
        });
    }

    // 1. Navbar Scroll Effect (Manejo del fondo difuminado o sólido)
    const nav = document.querySelector('.header-wrapper');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(10, 10, 10, 0.95)';
                nav.style.borderBottom = '1px solid var(--color-accent)';
            } else {
                nav.style.background = 'rgba(10, 10, 10, 0.85)';
                nav.style.borderBottom = '1px solid var(--color-border-inner)';
            }
        });
    }

    // 2. Fullscreen Drawer Menu (Hamburger Menu)
    const hamburgerBtn = document.getElementById('nav-toggle');
    const fullscreenMenu = document.getElementById('fullscreen-menu');
    const menuLinks = fullscreenMenu ? fullscreenMenu.querySelectorAll('a') : [];
    let isMenuOpen = false;
    let savedScrollPos = 0;

    const closeMobileMenu = () => {
        if (!isMenuOpen) return;
        isMenuOpen = false;
        if (hamburgerBtn) hamburgerBtn.classList.remove('is-active');
        if (fullscreenMenu) {
            gsap.to(fullscreenMenu, {
                clipPath: 'circle(0% at calc(100% - 40px) 40px)',
                duration: 0.8,
                ease: 'power3.inOut'
            });
        }
        if (menuLinks.length > 0) {
            gsap.to(menuLinks, {
                y: 50,
                opacity: 0,
                duration: 0.4,
                ease: 'power2.in'
            });
        }
        // Unlock scroll
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.left = '';
        document.body.style.right = '';
        window.scrollTo(0, savedScrollPos);
        if (window.lenisInstance) window.lenisInstance.start();
    };

    if (hamburgerBtn && fullscreenMenu) {
        hamburgerBtn.addEventListener('click', () => {
            if (!isMenuOpen) {
                isMenuOpen = true;
                hamburgerBtn.classList.add('is-active');
                
                // Lock scroll bulletproof on iOS and Android
                if (window.lenisInstance) window.lenisInstance.stop();
                savedScrollPos = window.pageYOffset || document.documentElement.scrollTop || 0;
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.top = `-${savedScrollPos}px`;
                document.body.style.width = '100%';
                document.body.style.left = '0';
                document.body.style.right = '0';

                gsap.to(fullscreenMenu, {
                    clipPath: 'circle(150% at calc(100% - 40px) 40px)',
                    duration: 1,
                    ease: 'power4.inOut'
                });
                if (menuLinks.length > 0) {
                    gsap.to(menuLinks, {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        stagger: 0.1,
                        delay: 0.3,
                        ease: 'power3.out'
                    });
                }
            } else {
                closeMobileMenu();
            }
        });

        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                closeMobileMenu();
            });
        });
    }

    // 3. GSAP Animations (Reveal Text)
    // Aparecen los elementos marcados con "reveal-text" a medida que entran en la pantalla
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        const revealElements = document.querySelectorAll('.reveal-text');
        
        revealElements.forEach((el) => {
            gsap.from(el, {
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                },
                y: 50,
                opacity: 0,
                duration: 1,
                ease: 'power3.out'
            });
        });
    }

    // Initialize Universal GLightbox (ensures gallery preview works for absolutely everything on mobile and desktop)
    // Auto-wrap any standalone gallery image in a glightbox link if not already wrapped
    document.querySelectorAll('#galeria img, .gallery-grid img, .mobile-section-block img').forEach(img => {
        if (!img.closest('a.glightbox') && !img.closest('a.mobile-luxury-card') && !img.closest('button')) {
            const link = document.createElement('a');
            link.href = img.src || img.getAttribute('src');
            link.className = 'glightbox';
            link.setAttribute('data-gallery', 'universal-gallery');
            link.style.display = 'block';
            link.style.height = '100%';
            link.style.cursor = 'pointer';
            img.style.pointerEvents = 'none';
            if (img.parentNode) {
                img.parentNode.insertBefore(link, img);
                link.appendChild(img);
            }
        }
    });

    if (typeof GLightbox !== 'undefined') {
        window.casaLightbox = GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            zoomable: true
        });
    }
});