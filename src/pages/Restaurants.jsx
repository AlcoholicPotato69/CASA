import React, { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Link, useNavigate } from 'react-router-dom';
import { Utensils, Calendar, ExternalLink, ChevronDown } from 'lucide-react';

gsap.registerPlugin(ScrollTrigger);

const restaurantsData = [
  {
    id: "argentilia",
    name: "Argentilia",
    category: "Alta Cocina Argentina & Parrilla Josper",
    description: "Especialistas en alta cocina argentina y cortes a la parrilla de leña en horno Josper. Un ambiente sofisticado e inolvidable con pescados, mariscos, pastas y una cava excepcional en el corazón de Casa de Piedra.",
    image: "https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069&auto=format&fit=crop",
    logo: "/images/logos/argentilia-logo.png",
    menuUrl: "https://argentilia.mx",
    reservaUrl: "https://argentilia.mx",
    phone: "477 717 1727",
    schedule: "Lunes a Domingo: 13:00 - 23:30 hrs"
  },
  {
    id: "lucio",
    name: "Lucio Ítalo-Argentino",
    category: "Cocina Ítalo-Argentina de Autor",
    description: "Una propuesta exquisita que entrelaza la tradición artesanal de las pastas y risottos italianos con la maestría de los cortes asados a la brasa argentina. Sabor contemporáneo en un entorno de lujo y distinción.",
    image: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop",
    logo: "/images/logos/lucio-logo.png",
    menuUrl: "https://grupomrl.com.mx",
    reservaUrl: "https://grupomrl.com.mx",
    phone: "477 717 2600",
    schedule: "Martes a Domingo: 13:30 - 23:00 hrs"
  },
  {
    id: "manolo",
    name: "Manolo Taberna Española",
    category: "Auténtica Taberna Española & Tapeo Ibérico",
    description: "Auténtica esencia del tapeo y la gastronomía ibérica. Desde jamón ibérico de bellota y paellas tradicionales hasta mariscos frescos y selectos vinos españoles en una atmósfera cálida y festiva.",
    image: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop",
    logo: "/images/logos/manolo-logo.png",
    menuUrl: "https://grupomrl.com.mx",
    reservaUrl: "https://grupomrl.com.mx",
    phone: "477 717 2600",
    schedule: "Miércoles a Domingo: 14:00 - 00:00 hrs"
  },
  {
    id: "sato",
    name: "Sato Cocina Nikkei",
    category: "Alta Cocina Japonesa & Nikkei",
    description: "Espacio diseñado para el placer de los sentidos, la relajación y el buen gusto. Transportamos al comensal a las calles de Japón mediante una creativa cocina Nikkei que entrelaza la milenaria tradición culinaria japonesa con vibrantes ingredientes y técnicas peruanas en el recinto de Casa de Piedra.",
    image: "/images/sato/card.jpg",
    logo: "/images/logos/sato-logo.png",
    menuUrl: "https://35c2d3e7-6884-40a6-8878-3c8bcb7cdbd5.filesusr.com/ugd/e0ec2b_bbdace41ace0486cbfb3c13b75ab0e57.pdf",
    reservaUrl: "https://api.whatsapp.com/send?phone=5214773949444&text=!Hola!%20quiero%20hacer%20una%20reservación",
    phone: "477 394 9444",
    schedule: "Lunes a Domingo: 13:00 - 23:00 hrs"
  },
  {
    id: "casa-mia",
    name: "Casa Mía Trattoria & Wine Bar",
    category: "Cocina Italiana de Autor & Wine Bar",
    description: "Un rincón íntimo y elegante dedicado a la alta cocina italiana y a la cultura del vino. Pastas artesanales hechas en casa, risottos trufados, carpaccios y una selección extraordinaria de etiquetas internacionales para maridajes inolvidables.",
    image: "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2070&auto=format&fit=crop",
    logo: "/images/logos/casa-mia-logo.png",
    menuUrl: "https://casadepiedraleon.mx",
    reservaUrl: "https://casadepiedraleon.mx",
    phone: "477 717 2600",
    schedule: "Martes a Domingo: 13:30 - 23:30 hrs"
  },
  {
    id: "valentina",
    name: "Valentina Cocina Contemporánea",
    category: "Alta Cocina Contemporánea & Asador",
    description: "Experiencia sensorial que combina técnicas culinarias de vanguardia con los mejores ingredientes de origen. Cortes selectos, pescados de importación y coctelería de autor en una atmósfera sofisticada y vibrante dentro de Casa de Piedra.",
    image: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop",
    logo: "/images/logos/valentina-logo.png",
    menuUrl: "https://casadepiedraleon.mx",
    reservaUrl: "https://casadepiedraleon.mx",
    phone: "477 717 2600",
    schedule: "Lunes a Domingo: 13:00 - 00:00 hrs"
  }
];

const Restaurants = () => {
  const navigate = useNavigate();
  const sectionRef = useRef(null);
  const [selectedRest, setSelectedRest] = useState(null);
  const [dropdownOpen, setDropdownOpen] = useState(false);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.restaurant-card', {
        y: 60,
        opacity: 0,
        duration: 1.1,
        stagger: 0.18,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: sectionRef.current,
          start: 'top 80%',
        }
      });
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  const handleSelectRestaurant = (rest) => {
    setSelectedRest(rest);
    setDropdownOpen(false);
    const element = document.getElementById(`restcard-${rest.id}`);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  };

  return (
    <div style={{ paddingTop: 'clamp(8rem, 15vh, 12rem)', paddingBottom: '6rem' }}>
      <section className="section" ref={sectionRef}>
        <div className="container">
          <div style={{ marginBottom: '3.5rem', textAlign: 'center' }}>
            <span className="text-script" style={{ fontSize: '3rem' }}>Gastronomía</span>
            <h2 className="text-h2" style={{ marginTop: '1rem' }}>Restaurantes de Autor.</h2>
            <p className="text-body-lg" style={{ maxWidth: '640px', margin: '1rem auto' }}>
              Dentro del complejo Casa de Piedra, disfruta de propuestas gastronómicas exclusivas y de talla internacional.
            </p>
          </div>

          <div className="grid md:grid-cols-2 gap-12">
            {restaurantsData.map((rest) => (
              <div 
                id={`restcard-${rest.id}`}
                key={rest.id} 
                onClick={() => navigate(`/restaurantes/${rest.id}`)}
                className="restaurant-card luxury-card" 
                style={{ 
                  height: '100%', 
                  display: 'flex', 
                  flexDirection: 'column', 
                  padding: 0, 
                  overflow: 'hidden',
                  cursor: 'pointer',
                  border: selectedRest?.id === rest.id ? '2px solid var(--color-accent)' : '1px solid var(--color-border)',
                  boxShadow: selectedRest?.id === rest.id ? '0 0 35px rgba(212, 175, 55, 0.45)' : 'none'
                }}
              >
                {/* Hero imagen con logo oficial superpuesto */}
                <div style={{ height: '310px', position: 'relative', overflow: 'hidden' }}>
                  <img src={rest.image} alt={rest.name} className="img-cover hover:scale-105 transition-transform duration-700" />
                  <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(10,10,10,0.92) 0%, rgba(10,10,10,0.3) 60%, transparent 100%)' }} />
                  
                  {/* EL PURO LOGO A PROPORCIÓN CORRECTA SIN CUADRO NI MARCO */}
                  <div style={{ position: 'absolute', bottom: '16px', left: '24px', right: '24px', height: '64px', display: 'flex', alignItems: 'center', justifyContent: 'flex-start' }}>
                    <img 
                      src={rest.logo} 
                      alt={rest.name} 
                      style={{ 
                        maxWidth: '78%', 
                        maxHeight: '64px', 
                        width: 'auto', 
                        height: 'auto', 
                        objectFit: 'contain', 
                        filter: 'drop-shadow(0 4px 18px rgba(0,0,0,0.95))' 
                      }} 
                    />
                  </div>
                </div>

                <div style={{ padding: '2.5rem', display: 'flex', flexDirection: 'column', flex: 1 }}>
                  <span className="text-caption" style={{ color: 'var(--color-accent)', fontSize: '0.82rem' }}>{rest.category}</span>
                  <h3 className="text-h3" style={{ color: 'var(--color-text-primary)', marginTop: '0.4rem', marginBottom: '1rem' }}>{rest.name}</h3>
                  <p className="text-body-lg hidden md:block" style={{ marginBottom: '1.8rem', flex: 1, lineHeight: '1.65' }}>
                    {rest.description}
                  </p>

                  {/* HORARIO RÁPIDO */}
                  <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', color: '#aaa', fontSize: '0.83rem', marginBottom: '1.8rem' }}>
                    <span>🕒 {rest.schedule}</span>
                  </div>

                  {/* BOTÓN DE MENÚ + BOTÓN DE RESERVA AL LADO DEL BOTÓN DE MENÚ */}
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.9rem', alignItems: 'center', paddingTop: '1.2rem', borderTop: '1px solid rgba(255,255,255,0.1)' }}>
                    <a
                      href={rest.menuUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      style={{
                        flex: '1 1 auto',
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: '0.5rem',
                        padding: '0.85rem 1.3rem',
                        background: 'linear-gradient(135deg, #f3e5ab 0%, #d4af37 100%)',
                        color: '#080808',
                        fontWeight: 700,
                        fontSize: '0.85rem',
                        borderRadius: '999px',
                        textDecoration: 'none',
                        textTransform: 'uppercase',
                        letterSpacing: '1px',
                        boxShadow: '0 8px 20px rgba(212,175,55,0.35)',
                        transition: 'transform 0.25s'
                      }}
                      onMouseOver={(e) => e.currentTarget.style.transform = 'translateY(-2px)'}
                      onMouseOut={(e) => e.currentTarget.style.transform = 'translateY(0)'}
                      onClick={(e) => e.stopPropagation()}
                    >
                      <Utensils size={15} />
                      Ver Menú (Carta)
                    </a>

                    <a
                      href={rest.reservaUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      onClick={(e) => e.stopPropagation()}
                      style={{
                        flex: '1 1 auto',
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: '0.5rem',
                        padding: '0.85rem 1.3rem',
                        background: 'transparent',
                        border: '1.5px solid var(--color-accent)',
                        color: '#fff',
                        fontWeight: 600,
                        fontSize: '0.85rem',
                        borderRadius: '999px',
                        textDecoration: 'none',
                        textTransform: 'uppercase',
                        letterSpacing: '1px',
                        transition: 'all 0.25s'
                      }}
                      onMouseOver={(e) => {
                        e.currentTarget.style.background = 'rgba(212,175,55,0.18)';
                      }}
                      onMouseOut={(e) => {
                        e.currentTarget.style.background = 'transparent';
                      }}
                    >
                      <Calendar size={15} style={{ color: 'var(--color-accent)' }} />
                      Reservar Mesa
                    </a>
                  </div>

                  <div style={{ marginTop: '1.4rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <Link 
                      to={`/restaurantes/${rest.id}`}
                      style={{ color: 'var(--color-accent)', fontFamily: 'var(--font-heading)', fontSize: '1.1rem', textDecoration: 'none', borderBottom: '1px solid var(--color-border)', paddingBottom: '3px' }}
                    >
                      Explorar galería y detalles →
                    </Link>
                    <span style={{ color: '#888', fontSize: '0.8rem' }}>Tel. {rest.phone}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default Restaurants;

