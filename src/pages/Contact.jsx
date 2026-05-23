import React, { useEffect } from 'react';
import gsap from 'gsap';
import MagneticButton from '../components/MagneticButton';

const Contact = () => {
  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.contact-reveal', {
        y: 50,
        opacity: 0,
        duration: 1,
        stagger: 0.2,
        ease: 'power3.out',
        delay: 0.2
      });
    });
    return () => ctx.revert();
  }, []);

  return (
    <div style={{ paddingTop: 'clamp(8rem, 15vh, 12rem)', paddingBottom: '6rem' }}>
      <div className="container">
        <div style={{ marginBottom: '4rem', textAlign: 'center' }}>
          <span className="text-script" style={{ fontSize: '3rem' }}>Contacto</span>
          <h2 className="text-h2" style={{ marginTop: '1rem' }}>Escribe tu historia aquí.</h2>
        </div>

        <div className="grid md:grid-cols-2 gap-12">
          {/* Info */}
          <div className="contact-reveal luxury-card" style={{ padding: '3rem' }}>
            <h3 className="text-h3" style={{ marginBottom: '2rem', color: 'var(--color-accent)' }}>Ponte en contacto</h3>
            
            <div style={{ marginBottom: '2rem' }}>
              <span className="text-caption">Horario de Oficina</span>
              <p className="text-body-lg">Lunes a Viernes 8:30 hrs – 17:30 hrs.</p>
            </div>
            
            <div style={{ marginBottom: '2rem' }}>
              <span className="text-caption">Teléfono</span>
              <p className="text-body-lg"><a href="tel:+524777172600" style={{ color: 'inherit', textDecoration: 'none' }}>477 717 2600</a></p>
            </div>

            <div style={{ marginBottom: '3rem' }}>
              <span className="text-caption">Correo</span>
              <p className="text-body-lg"><a href="mailto:eventos@casadepiedraleon.mx" style={{ color: 'inherit', textDecoration: 'none' }}>eventos@casadepiedraleon.mx</a></p>
            </div>

            <MagneticButton href="mailto:eventos@casadepiedraleon.mx" variant="primary">
              Solicitar Cotización
            </MagneticButton>
          </div>

          {/* Map */}
            <div className="contact-reveal luxury-card" style={{ height: '400px' }}>
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3662.6833868418894!2d-101.7002938!3d21.159507649999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842bbf53a2e4d0e3%3A0xfe1f47b7b2f6b0a3!2sCasa%20De%20Piedra!5e1!3m2!1ses-419!2smx!4v1779525259315!5m2!1ses-419!2smx" 
              width="100%" 
              height="100%" 
              style={{ border: 0 }} 
              allowFullScreen="" 
              loading="lazy" 
              referrerPolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Contact;
