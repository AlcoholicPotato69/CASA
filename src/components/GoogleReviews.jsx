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
            <div key={idx} className="review-card luxury-card" style={{ padding: '2rem', display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
                <img src={review.profile_photo_url} alt={review.author_name} style={{ width: '48px', height: '48px', borderRadius: '50%' }} />
                <div>
                  <h4 style={{ fontFamily: 'var(--font-body)', fontWeight: 600, color: 'var(--color-text-primary)' }}>{review.author_name}</h4>
                  <span style={{ fontSize: '0.8rem', color: 'var(--color-text-secondary)' }}>{review.relative_time_description}</span>
                </div>
              </div>
              <div style={{ display: 'flex', gap: '0.1rem', color: 'var(--color-accent)' }}>
                {[...Array(review.rating)].map((_, i) => <Star key={i} size={16} fill="currentColor" />)}
              </div>
              <p style={{ color: 'var(--color-text-secondary)', fontSize: '0.95rem', lineHeight: 1.6, fontStyle: 'italic' }}>
                "{review.text}"
              </p>
            </div>
          ))}
        </div>
        
        <div style={{ textAlign: 'center', marginTop: '3rem' }}>
          <a href="https://maps.app.goo.gl/nPM297u455R8KF9V9" target="_blank" rel="noreferrer" style={{ color: 'var(--color-accent)', textDecoration: 'underline', fontFamily: 'var(--font-heading)', fontSize: '1.2rem' }}>
            Ver todas las reseñas en Google
          </a>
        </div>
      </div>
    </section>
  );
};

export default GoogleReviews;
