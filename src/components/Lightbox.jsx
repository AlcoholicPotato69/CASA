import React, { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import gsap from 'gsap';

/**
 * Lightbox.jsx - Componente Visor de Imágenes y Galería a Pantalla Completa
 * 
 * Renderiza un portal que flota sobre el DOM principal (zIndex ultra alto).
 * Soporta navegación entre un arreglo de imágenes (Siguiente / Anterior) y 
 * se puede controlar con las flechas del teclado o botón Escape.
 * 
 * @param {string} src - Ruta de una única imagen (fallback).
 * @param {string[]} images - Arreglo de rutas de imágenes para la galería.
 * @param {number} initialIndex - Índice con el que se abre la galería.
 * @param {string} alt - Texto alternativo de la imagen.
 * @param {Function} onClose - Callback que se ejecuta tras la animación de salida.
 */
const Lightbox = ({ src, images: propImages, initialIndex = 0, alt, onClose }) => {
  const [mounted, setMounted] = useState(false);
  const images = propImages || (src ? [src] : []);
  const [currentIndex, setCurrentIndex] = useState(initialIndex);

  useEffect(() => {
    setMounted(true);
    // Bloquear el scroll de la página principal
    document.body.style.overflow = 'hidden';
    
    // Animar entrada
    gsap.fromTo('.lightbox-overlay', { opacity: 0 }, { opacity: 1, duration: 0.3 });
    gsap.fromTo('.lightbox-img', { scale: 0.9, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.4, ease: 'back.out(1.5)' });

    const handleKeyDown = (e) => {
      if (e.key === 'Escape') handleClose();
      if (e.key === 'ArrowRight') handleNext();
      if (e.key === 'ArrowLeft') handlePrev();
    };
    window.addEventListener('keydown', handleKeyDown);

    return () => {
      // Restaurar el scroll
      document.body.style.overflow = '';
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, []);

  const handleClose = () => {
    // Animar salida y luego ejecutar onClose
    gsap.to('.lightbox-overlay', { opacity: 0, duration: 0.3 });
    gsap.to('.lightbox-img', { scale: 0.9, opacity: 0, duration: 0.3, onComplete: onClose });
  };

  const handleNext = () => {
    if (images.length <= 1) return;
    gsap.fromTo('.lightbox-img', { opacity: 0, x: 50 }, { opacity: 1, x: 0, duration: 0.3 });
    setCurrentIndex((prev) => (prev + 1) % images.length);
  };

  const handlePrev = () => {
    if (images.length <= 1) return;
    gsap.fromTo('.lightbox-img', { opacity: 0, x: -50 }, { opacity: 1, x: 0, duration: 0.3 });
    setCurrentIndex((prev) => (prev - 1 + images.length) % images.length);
  };

  if (!mounted || images.length === 0) return null;

  return createPortal(
    <div 
      className="lightbox-overlay"
      onClick={handleClose}
      style={{
        position: 'fixed',
        inset: 0,
        backgroundColor: 'rgba(0,0,0,0.95)',
        zIndex: 99999, // Muy alto para quedar sobre cualquier otra cosa
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        cursor: 'zoom-out',
        padding: '2rem'
      }}
    >
      <button 
        onClick={(e) => { e.stopPropagation(); handleClose(); }}
        style={{
          position: 'absolute',
          top: '2rem',
          right: '2rem',
          background: 'rgba(255,255,255,0.1)',
          border: '1px solid rgba(255,255,255,0.2)',
          color: 'white',
          width: '50px',
          height: '50px',
          borderRadius: '50%',
          cursor: 'pointer',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          zIndex: 100000,
          backdropFilter: 'blur(5px)',
          transition: 'background 0.3s'
        }}
        onMouseEnter={(e) => e.currentTarget.style.background = 'rgba(255,255,255,0.2)'}
        onMouseLeave={(e) => e.currentTarget.style.background = 'rgba(255,255,255,0.1)'}
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <img 
        key={currentIndex}
        className="lightbox-img"
        src={images[currentIndex]} 
        alt={alt || `Lightbox image ${currentIndex + 1}`} 
        onClick={(e) => e.stopPropagation()} // Prevenir cerrar al hacer clic en la imagen
        style={{
          maxWidth: '90%',
          maxHeight: '90%',
          objectFit: 'contain',
          borderRadius: 'var(--radius-md)',
          boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.5)'
        }}
      />

      {images.length > 1 && (
        <>
          {/* Botón Anterior */}
          <button 
            onClick={(e) => { e.stopPropagation(); handlePrev(); }}
            style={{
              position: 'absolute',
              left: '2rem',
              top: '50%',
              transform: 'translateY(-50%)',
              background: 'rgba(255,255,255,0.1)',
              border: '1px solid rgba(255,255,255,0.2)',
              color: 'white',
              width: '50px',
              height: '50px',
              borderRadius: '50%',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              zIndex: 100000,
              backdropFilter: 'blur(5px)'
            }}
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
          
          {/* Botón Siguiente */}
          <button 
            onClick={(e) => { e.stopPropagation(); handleNext(); }}
            style={{
              position: 'absolute',
              right: '2rem',
              top: '50%',
              transform: 'translateY(-50%)',
              background: 'rgba(255,255,255,0.1)',
              border: '1px solid rgba(255,255,255,0.2)',
              color: 'white',
              width: '50px',
              height: '50px',
              borderRadius: '50%',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              zIndex: 100000,
              backdropFilter: 'blur(5px)'
            }}
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>

          {/* Indicador */}
          <div style={{
            position: 'absolute',
            bottom: '2rem',
            left: '50%',
            transform: 'translateX(-50%)',
            color: 'white',
            background: 'rgba(0,0,0,0.5)',
            padding: '4px 12px',
            borderRadius: '12px',
            fontSize: '0.9rem',
            letterSpacing: '1px'
          }}>
            {currentIndex + 1} / {images.length}
          </div>
        </>
      )}
    </div>,
    document.body
  );
};

export default Lightbox;
