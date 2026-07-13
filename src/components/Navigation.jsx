import React, { useState, useEffect, useRef } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X } from 'lucide-react';
import gsap from 'gsap';

/**
 * Navigation.jsx - Componente de Navegación Principal
 * 
 * Barra de navegación superior fija (sticky). Detecta el scroll para cambiar
 * su estilo (volverse sólida o difuminada) y administra el estado del menú
 * lateral (drawer) para dispositivos móviles.
 * 
 * Dependencias:
 * - GSAP: Para animar la entrada/salida del menú móvil.
 * - Lucide React: Iconos de hamburguesa y cerrar.
 */
const Navigation = () => {
  const [isOpen, setIsOpen] = useState(false);
  const menuRef = useRef(null);
  const linksRef = useRef([]);
  const location = useLocation();

  // Efecto para animar el menú móvil con GSAP cuando cambia isMobileMenuOpen
  useEffect(() => {
    if (isOpen) {
      gsap.to(menuRef.current, {
        clipPath: 'circle(150% at calc(100% - 40px) 40px)',
        duration: 1,
        ease: 'power4.inOut'
      });
      gsap.to(linksRef.current, {
        y: 0,
        opacity: 1,
        duration: 0.8,
        stagger: 0.1,
        delay: 0.3,
        ease: 'power3.out'
      });
    } else {
      gsap.to(menuRef.current, {
        clipPath: 'circle(0% at calc(100% - 40px) 40px)',
        duration: 0.8,
        ease: 'power3.inOut'
      });
      gsap.to(linksRef.current, {
        y: 50,
        opacity: 0,
        duration: 0.4,
        ease: 'power2.in'
      });
    }
  }, [isOpen]);

  // Close menu on navigation
  useEffect(() => {
    setIsOpen(false);
  }, [location.pathname]);

  const navStyles = {
    wrapper: {
      position: 'fixed',
      top: 0,
      left: 0,
      width: '100%',
      zIndex: 100, 
      padding: '1rem clamp(1rem, 4vw, 2rem)',
      background: 'rgba(10, 10, 10, 0.85)',
      backdropFilter: 'blur(12px)',
      borderBottom: '1px solid var(--color-border-inner)',
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'center'
    },
    logo: {
      height: '60px',
      objectFit: 'contain',
      cursor: 'pointer'
    },
    hamburger: {
      background: 'rgba(20,20,20,0.8)',
      backdropFilter: 'blur(10px)',
      border: '1px solid var(--color-border)',
      borderRadius: '50%',
      cursor: 'pointer',
      flexDirection: 'column',
      gap: '6px',
      padding: '0',
      zIndex: 101,
      width: '48px', 
      height: '48px',
      alignItems: 'center',
      justifyContent: 'center'
    },
    line: {
      width: '24px',
      height: '2px',
      backgroundColor: 'var(--color-accent)',
      transition: 'transform 0.4s cubic-bezier(0.32, 0.72, 0, 1), background-color 0.4s'
    },
    fullscreenMenu: {
      position: 'fixed',
      inset: 0,
      backgroundColor: '#0a0a0a',
      zIndex: 99,
      clipPath: 'circle(0% at calc(100% - 40px) 40px)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      backgroundImage: 'radial-gradient(circle at center, rgba(212,175,55,0.05) 0%, transparent 70%)'
    },
    desktopNav: {
      display: 'flex',
      alignItems: 'center',
      gap: '2rem'
    },
    desktopLink: {
      fontFamily: 'var(--font-body)',
      fontSize: '0.85rem',
      fontWeight: 600,
      textTransform: 'uppercase',
      letterSpacing: '0.15em',
      color: 'var(--color-text-primary)',
      textDecoration: 'none',
      transition: 'color 0.3s'
    }
  };

  const navItems = [
    { label: 'Inicio', path: '/' },
    { label: 'Venues', path: '/espacios' },
    { label: 'Eventos', path: '/eventos' },
    { label: 'Restaurantes', path: '/restaurantes' },
    { label: 'Galería', path: '/galeria' },
    { label: 'Contacto', path: '#quote-modal', isModal: true }
  ];

  return (
    <>
      <nav style={navStyles.wrapper}>
        <Link to="/" style={{ textDecoration: 'none' }}>
          <img 
            src="https://casadepiedraleon.mx/wp-content/uploads/2023/09/Logo_Header.png" 
            alt="Casa de Piedra" 
            style={navStyles.logo} 
          />
        </Link>
        
        {/* Desktop Navbar (Hidden on mobile) */}
        <div className="desktop-nav" style={navStyles.desktopNav}>
          {navItems.map((item) => (
            <Link 
              key={item.path} 
              to={item.path} 
              onClick={(e) => {
                if (item.isModal) {
                  e.preventDefault();
                  if (window.openQuoteModal) window.openQuoteModal('');
                }
              }}
              style={{
                ...navStyles.desktopLink,
                color: location.pathname === item.path ? 'var(--color-accent)' : 'var(--color-text-primary)'
              }}
              onMouseEnter={(e) => {
                if (location.pathname !== item.path) e.target.style.color = 'var(--color-accent)';
              }}
              onMouseLeave={(e) => {
                if (location.pathname !== item.path) e.target.style.color = 'var(--color-text-primary)';
              }}
            >
              {item.label}
            </Link>
          ))}
        </div>

        {/* Mobile Hamburger (Hidden on desktop) */}
        <button 
          className="mobile-nav-btn"
          style={navStyles.hamburger} 
          onClick={() => setIsOpen(!isOpen)}
        >
          <span style={{
            width: '24px',
            height: '2px',
            background: 'var(--color-accent)',
            transition: 'all 0.3s',
            transform: isOpen ? 'rotate(45deg) translate(5px, 5px)' : 'none'
          }} />
          <span style={{
            width: '24px',
            height: '2px',
            background: 'var(--color-accent)',
            opacity: isOpen ? 0 : 1,
            transition: 'all 0.3s'
          }} />
          <span style={{
            width: '24px',
            height: '2px',
            background: 'var(--color-accent)',
            transition: 'all 0.3s',
            transform: isOpen ? 'rotate(-45deg) translate(6px, -6px)' : 'none'
          }} />
        </button>
      </nav>

      {/* Mobile Fullscreen Menu */}
      <div style={navStyles.fullscreenMenu} ref={menuRef}>
        <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '1.5rem', textAlign: 'center' }}>
          {navItems.map((item, index) => (
            <li key={item.path}>
              <Link 
                to={item.path} 
                onClick={(e) => {
                  setIsOpen(false);
                  if (item.isModal) {
                    e.preventDefault();
                    if (window.openQuoteModal) window.openQuoteModal('');
                  }
                }}
                style={{
                  fontFamily: 'var(--font-heading)',
                  fontSize: 'clamp(1.8rem, 5vw, 4rem)',
                  color: location.pathname === item.path ? 'var(--color-accent)' : 'var(--color-text-primary)',
                  textDecoration: 'none',
                  opacity: 0,
                  transform: 'translateY(50px)',
                  display: 'block',
                  textTransform: 'uppercase',
                  letterSpacing: '0.1em'
                }}
                ref={el => linksRef.current[index] = el}
              >
                {item.label}
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </>
  );
};

export default Navigation;
