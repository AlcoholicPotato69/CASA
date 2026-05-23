import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { Link } from 'react-router-dom';
import MagneticButton from '../components/MagneticButton';
import GoogleReviews from '../components/GoogleReviews';

/**
 * Home.jsx - Página de Inicio (Landing Page)
 * 
 * Pantalla principal del sitio web. Contiene el Hero con video/imagen de fondo,
 * títulos animados con GSAP y enlaces directos a Espacios y Galería.
 * 
 * Funcionalidad destacada:
 * - GSAP ScrollTrigger y animations para revelar los textos progresivamente.
 */
const Home = () => {
  const imageRef = useRef(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from(imageRef.current, {
        scale: 1.1,
        duration: 2.5,
        ease: 'power3.out'
      });

      gsap.from('.reveal-text', {
        y: 100,
        opacity: 0,
        duration: 1.2,
        stagger: 0.15,
        ease: 'power4.out',
        delay: 0.3
      });
    });

    return () => ctx.revert();
  }, []);

  return (
    <>
      <section 
        style={{ 
          position: 'relative', 
          minHeight: '100vh',
          display: 'flex',
          alignItems: 'center',
          paddingTop: '12rem',
          overflow: 'hidden'
        }}
      >
        <div style={{ position: 'absolute', inset: 0, zIndex: -1 }}>
          <img 
            ref={imageRef}
            src="/images/salon_principal_1779523069698.png" 
            alt="Casa de Piedra" 
            className="img-cover"
          />
          <div style={{ 
            position: 'absolute', 
            inset: 0, 
            backgroundColor: 'rgba(5, 5, 5, 0.7)',
          }} />
        </div>

        <div className="container" style={{ position: 'relative', zIndex: 10 }}>
          <div style={{ maxWidth: '900px' }}>
            <div style={{ overflow: 'hidden', paddingTop: '20px', marginTop: '-20px' }}>
              <span className="text-script reveal-text" style={{ display: 'block', marginBottom: '1rem', paddingTop: '10px' }}>
                Desde 1845
              </span>
            </div>
            
            <div style={{ overflow: 'hidden' }}>
              <h1 className="text-hero reveal-text" style={{ marginBottom: '1.5rem', textShadow: '0 10px 30px rgba(0,0,0,0.5)' }}>
                El recinto más exclusivo <br /> de León.
              </h1>
            </div>

            <div style={{ overflow: 'hidden' }}>
              <p className="text-body-lg reveal-text" style={{ color: 'rgba(255,255,255,0.8)', marginBottom: '3.5rem', maxWidth: '600px' }}>
                Arquitectura de época, lujo contemporáneo y servicio impecable para bodas, convenciones y eventos que hacen historia.
              </p>
            </div>

            <div style={{ overflow: 'hidden' }}>
              <div className="reveal-text" style={{ display: 'flex', gap: '1.5rem', alignItems: 'center' }}>
                <Link to="/espacios" style={{ textDecoration: 'none' }}>
                  <MagneticButton variant="primary" as="div">
                    Ver Espacios
                  </MagneticButton>
                </Link>
                <Link to="/galeria" style={{ color: 'var(--color-accent)', textDecoration: 'none', fontFamily: 'var(--font-heading)', fontSize: '1.2rem', paddingBottom: '4px', borderBottom: '1px solid var(--color-border)' }}>
                  Ver Galería
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      {/* Google Reviews Section directly on Home */}
      <GoogleReviews />
    </>
  );
};

export default Home;
