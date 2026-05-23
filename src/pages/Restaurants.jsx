import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Link } from 'react-router-dom';

gsap.registerPlugin(ScrollTrigger);

const Restaurants = () => {
  const sectionRef = useRef(null);

  const restaurants = [
    {
      id: "corazon-de-alcachofa",
      name: "Corazón de Alcachofa",
      category: "Cocina Mediterránea Fusión",
      description: "Una experiencia culinaria inigualable que combina los sabores frescos del mediterráneo con toques contemporáneos. Perfecto para comidas de negocios y cenas románticas.",
      image: "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2070&auto=format&fit=crop"
    },
    {
      id: "agaves",
      name: "Agaves Casa de Piedra",
      category: "Alta Cocina Mexicana",
      description: "Rescatando la tradición del reconocido restaurante 'Los Agaves', ofrecemos platillos mexicanos de autor en un ambiente elegante y relajado con vistas a los jardines.",
      image: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop"
    }
  ];

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.restaurant-card', {
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
          <div style={{ marginBottom: '4rem', textAlign: 'center' }}>
            <span className="text-script" style={{ fontSize: '3rem' }}>Gastronomía</span>
            <h2 className="text-h2" style={{ marginTop: '1rem' }}>Restaurantes de Autor.</h2>
            <p className="text-body-lg" style={{ maxWidth: '600px', margin: '1rem auto' }}>
              Dentro del complejo Casa de Piedra, disfruta de propuestas gastronómicas exclusivas para deleitar tus sentidos.
            </p>
          </div>

          <div className="grid md:grid-cols-2 gap-12">
            {restaurants.map((rest) => (
              <Link to={`/restaurantes/${rest.id}`} key={rest.id} style={{ textDecoration: 'none' }} className="restaurant-card">
                <div className="luxury-card" style={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
                  <div style={{ height: '300px', overflow: 'hidden' }}>
                    <img src={rest.image} alt={rest.name} className="img-cover hover:scale-105 transition-transform duration-700" />
                  </div>
                  <div style={{ padding: '2.5rem' }}>
                    <span className="text-caption">{rest.category}</span>
                    <h3 className="text-h3" style={{ color: 'var(--color-text-primary)', marginTop: '0.5rem', marginBottom: '1rem' }}>{rest.name}</h3>
                    <p className="text-body-lg" style={{ marginBottom: '2rem' }}>
                      {rest.description}
                    </p>
                    <span style={{ color: 'var(--color-accent)', fontFamily: 'var(--font-heading)', fontSize: '1.2rem', borderBottom: '1px solid var(--color-border)', paddingBottom: '4px' }}>
                      Ver detalles
                    </span>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default Restaurants;
