import React, { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import gsap from 'gsap';
import MagneticButton from '../components/MagneticButton';
import { Clock, MapPin, Phone } from 'lucide-react';

const restaurantData = {
  "corazon-de-alcachofa": {
    name: "Corazón de Alcachofa",
    category: "Cocina Mediterránea Fusión",
    heroImage: "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2070&auto=format&fit=crop",
    description: "Una propuesta audaz que fusiona ingredientes locales con técnicas y sabores del mediterráneo. Nuestro ambiente sofisticado es el marco perfecto para celebraciones, comidas de negocios o una cena inolvidable en Casa de Piedra.",
    schedule: "Lunes a Sábado: 13:00 - 23:00 | Domingo: 13:00 - 18:00",
    phone: "477 123 4567"
  },
  "agaves": {
    name: "Agaves Casa de Piedra",
    category: "Alta Cocina Mexicana",
    heroImage: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop",
    description: "Una carta que rinde homenaje a la riqueza culinaria de México. Con ingredientes de origen, Agaves ofrece platillos tradicionales reinventados con el toque de alta cocina, ideal para quienes buscan el auténtico sabor nacional en un entorno de lujo.",
    schedule: "Martes a Sábado: 14:00 - 00:00 | Domingo: 14:00 - 19:00",
    phone: "477 987 6543"
  }
};

const RestaurantDetail = () => {
  const { id } = useParams();
  const data = restaurantData[id];

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.fade-up', {
        y: 50,
        opacity: 0,
        duration: 1,
        stagger: 0.15,
        ease: 'power3.out',
      });
    });
    return () => ctx.revert();
  }, [id]);

  if (!data) return <div style={{ paddingTop: '12rem', paddingBottom: '6rem', textAlign: 'center' }}>Restaurante no encontrado. <Link to="/restaurantes">Volver</Link></div>;

  return (
    <div>
      <section style={{ height: '60vh', position: 'relative', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
        <div style={{ position: 'absolute', inset: 0, zIndex: -1 }}>
          <img src={data.heroImage} alt={data.name} className="img-cover" />
          <div style={{ position: 'absolute', inset: 0, backgroundColor: 'rgba(10,10,10,0.6)' }} />
        </div>
        <div className="container text-center fade-up" style={{ zIndex: 10 }}>
          <span className="text-script" style={{ fontSize: '3rem', display: 'block', marginBottom: '1rem' }}>{data.category}</span>
          <h1 className="text-hero" style={{ textTransform: 'none' }}>{data.name}</h1>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="grid md:grid-cols-12 gap-12">
            <div className="md:col-span-7 fade-up">
              <h2 className="text-h2" style={{ marginBottom: '2rem' }}>La Experiencia</h2>
              <p className="text-body-lg" style={{ marginBottom: '1.5rem', fontSize: '1.25rem' }}>
                {data.description}
              </p>
              <p className="text-body-lg">
                Ubicados dentro de las históricas instalaciones de Casa de Piedra, brindamos un entorno seguro, elegante y con estacionamiento disponible para tu total comodidad.
              </p>
            </div>
            
            <div className="md:col-span-5 fade-up">
              <div className="luxury-card" style={{ padding: '3rem' }}>
                <h3 className="text-h3" style={{ marginBottom: '2rem', color: 'var(--color-accent)' }}>Información</h3>
                
                <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
                  <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
                    <Clock size={24} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                    <div>
                      <span className="text-caption">Horarios</span>
                      <p style={{ marginTop: '0.5rem', color: 'var(--color-text-primary)' }}>{data.schedule}</p>
                    </div>
                  </div>
                  
                  <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
                    <Phone size={24} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                    <div>
                      <span className="text-caption">Reservaciones</span>
                      <p style={{ marginTop: '0.5rem', color: 'var(--color-text-primary)' }}>
                        <a href={`tel:+52${data.phone.replace(/ /g, '')}`} style={{ color: 'inherit', textDecoration: 'none' }}>{data.phone}</a>
                      </p>
                    </div>
                  </div>

                  <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start' }}>
                    <MapPin size={24} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                    <div>
                      <span className="text-caption">Ubicación</span>
                      <p style={{ marginTop: '0.5rem', color: 'var(--color-text-primary)' }}>Interior Casa de Piedra, León, Gto.</p>
                    </div>
                  </div>
                </div>

                <div style={{ marginTop: '3rem', display: 'flex', flexDirection: 'column', gap: '1rem' }}>
                  <MagneticButton href="https://google.com" target="_blank" variant="secondary" className="w-full">
                    Ver Menú
                  </MagneticButton>
                  <MagneticButton variant="primary" as="button" onClick={() => window.location.href=`tel:+52${data.phone.replace(/ /g, '')}`}>
                    Llamar para Reservar
                  </MagneticButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default RestaurantDetail;
