import React, { useEffect } from 'react';
import gsap from 'gsap';

const Events = () => {
  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.event-reveal', {
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
          <span className="text-script" style={{ fontSize: '3rem' }}>Eventos</span>
          <h2 className="text-h2" style={{ marginTop: '1rem' }}>Especialidades a tu medida.</h2>
        </div>

        <div className="grid md:grid-cols-2 gap-12">
          {/* Sociales */}
          <div className="event-reveal">
            <div className="luxury-card" style={{ padding: '3rem' }}>
              <h3 className="text-h3" style={{ color: 'var(--color-accent)', marginBottom: '1.5rem' }}>Sociales</h3>
              <p className="text-body-lg" style={{ marginBottom: '2rem' }}>
                Hacemos de tus fechas más importantes, momentos verdaderamente eternos.
              </p>
              <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '1rem', color: 'var(--color-text-primary)' }}>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Bodas</li>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>XV Años</li>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Cumpleaños y Aniversarios</li>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Bautizos y Comuniones</li>
              </ul>
            </div>
          </div>

          {/* Empresariales */}
          <div className="event-reveal">
            <div className="luxury-card" style={{ padding: '3rem' }}>
              <h3 className="text-h3" style={{ color: 'var(--color-accent)', marginBottom: '1.5rem' }}>Empresariales</h3>
              <p className="text-body-lg" style={{ marginBottom: '2rem' }}>
                Proyecta la mejor imagen de tu empresa con infraestructura de primer nivel.
              </p>
              <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '1rem', color: 'var(--color-text-primary)' }}>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Eventos Privados y Públicos</li>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Conferencias y Congresos</li>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Conciertos</li>
                <li style={{ borderBottom: '1px solid var(--color-border-inner)', paddingBottom: '0.5rem' }}>Exposiciones</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Events;
