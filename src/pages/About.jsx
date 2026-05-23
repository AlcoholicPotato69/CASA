import React, { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import Lightbox from '../components/Lightbox';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const About = () => {
  const sectionRef = useRef(null);
  const [selectedImage, setSelectedImage] = useState(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.about-reveal', {
        y: 60,
        opacity: 0,
        duration: 1.2,
        stagger: 0.2,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: sectionRef.current,
          start: 'top 80%',
        }
      });
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  return (
    <div style={{ paddingTop: 'clamp(8rem, 15vh, 12rem)', paddingBottom: '6rem' }}>
      <section className="section" ref={sectionRef}>
        <div className="container">
          <div style={{ maxWidth: '1000px', margin: '0 auto', textAlign: 'center' }}>
            <div className="about-reveal">
              <span className="text-script" style={{ fontSize: '3rem' }}>Nuestra Historia</span>
              <h2 className="text-h2" style={{ marginBottom: '4rem', marginTop: '1rem' }}>
                Testigo de grandeza y refinamiento a lo largo de los siglos.
              </h2>
            </div>
            
            <div className="about-reveal luxury-card grid md:grid-cols-2 gap-8" style={{ padding: 'clamp(1.5rem, 5vw, 4rem)', textAlign: 'left' }}>
              <div>
                <p className="text-body-lg" style={{ marginBottom: '1.5rem' }}>
                  En el corazón de nuestra querida ciudad se erige la sublime ex hacienda, Casa de Piedra. Fundada en 1845, es famosa por su arquitectura y tradición incomparable.
                </p>
                <p className="text-body-lg">
                  Gracias a su más reciente remodelación, obra del reconocido arquitecto Jalisciense Roberto Elías, Casa de Piedra se ha transformado en un verdadero paraíso de exclusividad y elegancia.
                </p>
              </div>
              <div style={{ position: 'relative', height: '100%', minHeight: '300px', cursor: 'zoom-in' }} onClick={() => setSelectedImage('/images/terraza_mezquite_1779523084857.png')}>
                <img 
                  src="/images/terraza_mezquite_1779523084857.png" 
                  alt="Casa de Piedra - Historia" 
                  className="img-cover" 
                  style={{ borderRadius: 'var(--radius-md)' }}
                />
              </div>
            </div>

            <div className="about-reveal" style={{ marginTop: '4rem' }}>
              <h3 className="text-h3" style={{ marginBottom: '2rem' }}>Nuestra Ubicación</h3>
              <div className="luxury-card" style={{ height: '400px' }}>
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
      </section>

      {selectedImage && (
        <Lightbox 
          src={selectedImage} 
          onClose={() => setSelectedImage(null)} 
        />
      )}
    </div>
  );
};

export default About;
