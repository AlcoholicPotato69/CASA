import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Star } from 'lucide-react';

gsap.registerPlugin(ScrollTrigger);

const GoogleReviews = () => {
  const sectionRef = useRef(null);

  // This is a placeholder structure.
  // To make this fully live, implement the Google Places API:
  // `fetch('https://maps.googleapis.com/maps/api/place/details/json?place_id=CHIJqZEkox1VvYQRqZEkow1QEuA&fields=reviews&key=YOUR_API_KEY')`
  const reviews = [
    {
      author_name: "María Fernanda Lopez",
      rating: 5,
      relative_time_description: "hace 2 semanas",
      text: "Un lugar mágico. La arquitectura de la hacienda está impecable. Asistí a una boda en el Salón Principal y todo el servicio fue de primera calidad, muy lujoso.",
      profile_photo_url: "https://ui-avatars.com/api/?name=MF&background=315b7c&color=fff"
    },
    {
      author_name: "Carlos Medina",
      rating: 5,
      relative_time_description: "hace 1 mes",
      text: "Excelente espacio para eventos empresariales. Tienen todos los servicios y los jardines están muy bien cuidados. Los restaurantes dentro también son una gran ventaja.",
      profile_photo_url: "https://ui-avatars.com/api/?name=CM&background=CC5D00&color=fff"
    },
    {
      author_name: "Ana Victoria S.",
      rating: 5,
      relative_time_description: "hace 1 mes",
      text: "La atención y la logística fueron de 10. La Terraza del Mezquite tiene una iluminación increíble en la noche. Definitivamente el mejor lugar de León.",
      profile_photo_url: "https://ui-avatars.com/api/?name=AV&background=0a0a0a&color=d4af37"
    }
  ];

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.review-card', {
        y: 40,
        opacity: 0,
        duration: 1,
        stagger: 0.15,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: sectionRef.current,
          start: 'top 85%'
        }
      });
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  return (
    <section className="section" ref={sectionRef}>
      <div className="container">
        <div style={{ marginBottom: '4rem', textAlign: 'center' }}>
          <span className="text-script" style={{ fontSize: '3rem' }}>Experiencias</span>
          <h2 className="text-h2" style={{ marginTop: '1rem', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '1rem' }}>
            Reseñas de Google Maps
          </h2>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.25rem', marginTop: '1rem', color: 'var(--color-accent)' }}>
            <Star fill="currentColor" /> <Star fill="currentColor" /> <Star fill="currentColor" /> <Star fill="currentColor" /> <Star fill="currentColor" />
            <span style={{ color: 'var(--color-text-secondary)', marginLeft: '0.5rem', fontFamily: 'var(--font-body)', fontWeight: 600 }}>4.8 de 5</span>
          </div>
        </div>

        <div className="grid md:grid-cols-3 gap-8">
          {reviews.map((review, idx) => (
            <a
              key={idx}
              href="https://maps.app.goo.gl/nPM297u455R8KF9V9"
              target="_blank"
              rel="noopener noreferrer"
              className="review-card luxury-card"
              style={{ padding: '2rem', display: 'flex', flexDirection: 'column', gap: '1.5rem', textDecoration: 'none', transition: 'all 0.3s' }}
            >
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                  <img src={review.profile_photo_url} alt={review.author_name} style={{ width: '48px', height: '48px', borderRadius: '50%' }} />
                  <div>
                    <h4 style={{ fontFamily: 'var(--font-body)', fontWeight: 600, color: 'var(--color-text-primary)' }}>{review.author_name}</h4>
                    <span style={{ fontSize: '0.8rem', color: 'var(--color-text-secondary)' }}>{review.relative_time_description}</span>
                  </div>
                </div>
                <span style={{ fontSize: '0.72rem', color: 'var(--color-accent)', fontWeight: 500 }}>Ver en Google ↗</span>
              </div>
              <div style={{ display: 'flex', gap: '0.1rem', color: 'var(--color-accent)' }}>
                {[...Array(review.rating)].map((_, i) => <Star key={i} size={16} fill="currentColor" />)}
              </div>
              <p style={{ color: 'var(--color-text-secondary)', fontSize: '0.95rem', lineHeight: 1.6, fontStyle: 'italic' }}>
                "{review.text}"
              </p>
            </a>
          ))}
        </div>
        
        {/* Mapa Interactivo 100% Responsivo en Modo Oscuro Sólido */}
        <div style={{
          marginTop: '3.5rem',
          borderRadius: '24px',
          overflow: 'hidden',
          border: '1px solid rgba(212,175,55,0.35)',
          background: '#111111',
          boxShadow: '0 25px 65px rgba(0,0,0,0.95)',
          height: 'clamp(360px, 46vw, 520px)',
          width: '100%',
          position: 'relative'
        }}>
          <iframe
            title="Casa de Piedra Ubicación en Google Maps"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.2825381283!2d-101.6992601!3d21.1585368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842bbf53a2e4d0e3%3A0xfe1f47b7b2f6b0a3!2sCasa%20De%20Piedra!5e0!3m2!1ses-419!2smx!4v1710000000000!5m2!1ses-419!2smx"
            width="100%"
            height="100%"
            style={{
              border: 0,
              display: 'block',
              width: '100%',
              height: '100%',
              filter: 'invert(100%) hue-rotate(180deg) contrast(112%) saturate(105%)'
            }}
            allowFullScreen=""
            loading="lazy"
            referrerPolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>

        <div style={{ textAlign: 'center', marginTop: '3.8rem', marginBottom: '1.5rem' }}>
          <a href="https://www.google.com/maps/place/Casa+De+Piedra/@21.1511013,-101.6954442,3365m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m6!3m5!1s0x842bbf53a2e4d0e3:0xfe1f47b7b2f6b0a3!8m2!3d21.1585368!4d-101.6992601!16s%2Fg%2F11f_b_l520?entry=ttu&g_ep=EgoyMDI2MDcwNy4wIKXMDSoASAFQAw%3D%3D#" target="_blank" rel="noreferrer" style={{ color: 'var(--color-accent)', textDecoration: 'underline', fontFamily: 'var(--font-heading)', fontSize: '1.2rem', display: 'inline-block', padding: '0.5rem 1rem' }}>
            Ver todas las reseñas en Google
          </a>
        </div>
      </div>
    </section>
  );
};

export default GoogleReviews;
