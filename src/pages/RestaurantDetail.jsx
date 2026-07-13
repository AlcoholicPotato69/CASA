import React, { useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import gsap from 'gsap';
import { Clock, MapPin, Phone, Utensils, Calendar, Star, Award, CheckCircle, ExternalLink } from 'lucide-react';

const restaurantData = {
  "argentilia": {
    name: "Argentilia",
    category: "Alta Cocina Argentina & Parrilla Josper",
    heroImage: "https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069&auto=format&fit=crop",
    logo: "/images/logos/argentilia-logo.png",
    rating: "4.9",
    reviewsCount: "428 opiniones en Google Maps",
    description: "Especialistas en alta cocina argentina y cortes a la parrilla de leña en horno Josper. Un ambiente sofisticado e inolvidable con pescados, mariscos, pastas artesanales y una cava excepcional en el corazón de Casa de Piedra León.",
    schedule: "Lunes a Domingo: 13:00 - 23:30 hrs",
    phone: "477 717 1727",
    menuUrl: "https://argentilia.mx",
    reservaUrl: "https://argentilia.mx",
    gallery: [
      "https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=1200&auto=format&fit=crop"
    ],
    googleReviews: [
      {
        author: "Carlos Mendoza",
        time: "Hace 2 semanas",
        comment: "El mejor bife de chorizo y vacío al Josper en León. El ambiente dentro de Casa de Piedra es espectacular para cenas ejecutivas y celebraciones especiales."
      },
      {
        author: "Andrea González",
        time: "Hace 1 mes",
        comment: "Excelente cava de vinos, la atención de los meseros es de 10 y el lugar tiene una elegancia colonial refinada inolvidable. Muy recomendable en Casa de Piedra."
      },
      {
        author: "Roberto Garza",
        time: "Hace 2 meses",
        comment: "Excepcional calidad en cortes prime y mariscos. El pulpo a las brasas y las empanadas mendocinas son un obligado en cada visita."
      }
    ]
  },
  "lucio": {
    name: "Lucio Ítalo-Argentino",
    category: "Cocina Ítalo-Argentina de Autor",
    heroImage: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop",
    logo: "/images/logos/lucio-logo.png",
    rating: "4.8",
    reviewsCount: "312 opiniones en Google Maps",
    description: "Una propuesta exquisita que entrelaza la tradición artesanal de las pastas hechas a mano y risottos italianos con la maestría de los cortes asados a la brasa argentina. Sabor contemporáneo en un entorno de lujo y distinción.",
    schedule: "Martes a Domingo: 13:30 - 23:00 hrs",
    phone: "477 717 2600",
    menuUrl: "https://grupomrl.com.mx",
    reservaUrl: "https://grupomrl.com.mx",
    gallery: [
      "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1579684947550-22e945225d9a?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1563245372-f21724e3856d?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1200&auto=format&fit=crop"
    ],
    googleReviews: [
      {
        author: "Mariana Torres",
        time: "Hace 3 semanas",
        comment: "Una fusión perfecta. Su pasta fresca artesanal y el ojo de ribeye son increíbles. La terraza en Casa de Piedra tiene un ambiente íntimo y hermoso."
      },
      {
        author: "Alejandro Villalobos",
        time: "Hace 1 mes",
        comment: "Atención de primer nivel, excelente carta de vinos italianos y argentinos. Ideal para una comida romántica o cena familiar de fin de semana."
      },
      {
        author: "Sofía Navarro",
        time: "Hace 2 meses",
        comment: "El risotto trufado y la entraña al asador valen cada centavo. Un ambiente sofisticado en el corazón del mejor complejo de León."
      }
    ]
  },
  "manolo": {
    name: "Manolo Taberna Española",
    category: "Auténtica Taberna Española & Tapeo Ibérico",
    heroImage: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop",
    logo: "/images/logos/manolo-logo.png",
    rating: "4.9",
    reviewsCount: "385 opiniones en Google Maps",
    description: "Auténtica esencia del tapeo y la gastronomía ibérica. Desde jamón ibérico de bellota y paellas tradicionales hasta mariscos frescos y selectos vinos españoles en una atmósfera cálida, festiva y elegante en Casa de Piedra.",
    schedule: "Miércoles a Domingo: 14:00 - 00:00 hrs",
    phone: "477 717 2600",
    menuUrl: "https://grupomrl.com.mx",
    reservaUrl: "https://grupomrl.com.mx",
    gallery: [
      "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1515443961218-a51367888e4b?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1200&auto=format&fit=crop"
    ],
    googleReviews: [
      {
        author: "Fernando Ruiz",
        time: "Hace 1 semana",
        comment: "Auténtico sabor español en León. El jamón ibérico de bellota y la paella valenciana son extraordinarios. El servicio en Casa de Piedra es impecable."
      },
      {
        author: "Patricia López",
        time: "Hace 1 mes",
        comment: "Las tapas y los vinos de Rioja y Ribera del Duero te transportan a España. Súper ambiente dentro del complejo Casa de Piedra."
      },
      {
        author: "Gabriel Hernández",
        time: "Hace 2 meses",
        comment: "El lechón y el pulpo a la gallega excepcionales. Sin duda el mejor restaurante de cocina española tradicional y de autor del Bajío."
      }
    ]
  },
  "sato": {
    name: "Sato Cocina Nikkei",
    category: "Alta Cocina Japonesa & Nikkei",
    heroImage: "/images/sato/banner.jpg",
    logo: "/images/logos/sato-logo.png",
    rating: "4.9",
    reviewsCount: "510 opiniones en Google Maps",
    description: "Sato Cocina Nikkei es un espacio diseñado para el placer de los sentidos, la relajación y el buen gusto. Transportamos al comensal a las calles de Japón mediante una creativa cocina Nikkei que entrelaza la milenaria tradición culinaria japonesa con vibrantes ingredientes y técnicas peruanas. Una experiencia Omakase y robatayaki con reconocimiento internacional en el emblemático recinto de Casa de Piedra.",
    schedule: "Lunes a Domingo: 13:00 - 23:00 hrs",
    phone: "477 394 9444",
    menuUrl: "https://35c2d3e7-6884-40a6-8878-3c8bcb7cdbd5.filesusr.com/ugd/e0ec2b_bbdace41ace0486cbfb3c13b75ab0e57.pdf",
    reservaUrl: "https://api.whatsapp.com/send?phone=5214773949444&text=!Hola!%20quiero%20hacer%20una%20reservación",
    gallery: [
      "/images/sato/gallery-1.jpg",
      "/images/sato/gallery-2.jpg",
      "/images/sato/gallery-3.jpg",
      "/images/sato/card.jpg",
      "/images/sato/banner.jpg"
    ],
    googleReviews: [
      {
        author: "Valeria Castro",
        time: "Hace 1 semana",
        comment: "La experiencia Omakase y los nigiris de toro y salmón son arte gastronómico puro. Ingredientes fresquísimos y técnica japonesa de Tokio."
      },
      {
        author: "Ignacio Morales",
        time: "Hace 3 semanas",
        comment: "El mejor restaurante japonés de León. El ambiente minimalista en Casa de Piedra y los cócteles con sake son de talla mundial."
      },
      {
        author: "Daniela Ortiz",
        time: "Hace 1 mes",
        comment: "Los ceviches nikkei, el black cod y el sashimi son una delicia total. Servicio atento y altamente profesional."
      }
    ]
  },
  "casa-mia": {
    name: "Casa Mía Trattoria & Wine Bar",
    category: "Cocina Italiana de Autor & Wine Bar",
    heroImage: "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2070&auto=format&fit=crop",
    logo: "/images/logos/casa-mia-logo.png",
    rating: "4.9",
    reviewsCount: "340 opiniones en Google Maps",
    description: "Un rincón íntimo y elegante dedicado a la alta cocina italiana y a la cultura del vino. Pastas artesanales hechas en casa, risottos trufados, carpaccios y una selección extraordinaria de etiquetas internacionales para maridajes inolvidables en Casa de Piedra.",
    schedule: "Martes a Domingo: 13:30 - 23:30 hrs",
    phone: "477 717 2600",
    menuUrl: "https://casadepiedraleon.mx",
    reservaUrl: "https://casadepiedraleon.mx",
    gallery: [
      "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1200&auto=format&fit=crop"
    ],
    googleReviews: [
      {
        author: "Mauricio Elizondo",
        time: "Hace 2 semanas",
        comment: "Una joya italiana en Casa de Piedra. Las pastas hechas en casa y la selección de su Wine Bar hacen que sea el lugar perfecto para una cena inolvidable."
      },
      {
        author: "Lorena Sánchez",
        time: "Hace 1 mes",
        comment: "El ambiente romántico, la iluminación y el carpaccio de res con trufa son espectaculares. Su sommelier te guía con excelentes maridajes."
      },
      {
        author: "Javier Padilla",
        time: "Hace 2 meses",
        comment: "Exquisita comida, vinos italianos increíbles y una terraza que te hace sentir en Toscana en pleno corazón de León."
      }
    ]
  },
  "valentina": {
    name: "Valentina Cocina Contemporánea",
    category: "Alta Cocina Contemporánea & Asador",
    heroImage: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop",
    logo: "/images/logos/valentina-logo.png",
    rating: "4.9",
    reviewsCount: "395 opiniones en Google Maps",
    description: "Experiencia sensorial que combina técnicas culinarias de vanguardia con los mejores ingredientes de origen. Cortes selectos, pescados de importación y coctelería de autor en una atmósfera sofisticada y vibrante dentro de Casa de Piedra.",
    schedule: "Lunes a Domingo: 13:00 - 00:00 hrs",
    phone: "477 717 2600",
    menuUrl: "https://casadepiedraleon.mx",
    reservaUrl: "https://casadepiedraleon.mx",
    gallery: [
      "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1200&auto=format&fit=crop"
    ],
    googleReviews: [
      {
        author: "Eduardo Fuentes",
        time: "Hace 1 semana",
        comment: "Cocina contemporánea de nivel internacional. Cada platillo está cuidado al máximo en presentación y sabor. La mixología de autor es increíble."
      },
      {
        author: "Camila Rivera",
        time: "Hace 3 semanas",
        comment: "El ambiente más chic y elegante de Casa de Piedra. El pescado a las brasas y los postres son dignos de las grandes capitales gastronómicas."
      },
      {
        author: "Héctor Ramírez",
        time: "Hace 1 mes",
        comment: "Servicio de lujo, ingredientes de primerísima calidad y una arquitectura interior que impresiona desde que entras."
      }
    ]
  }
};

const RestaurantDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const data = restaurantData[id];

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.fade-up', {
        y: 40,
        opacity: 0,
        duration: 0.9,
        stagger: 0.12,
        ease: 'power3.out',
      });
    });
    return () => ctx.revert();
  }, [id]);

  if (!data) {
    return (
      <div style={{ paddingTop: 'clamp(8rem, 15vh, 12rem)', paddingBottom: '6rem', textAlign: 'center' }}>
        <h2 className="text-h2" style={{ marginBottom: '1.5rem' }}>Restaurante no encontrado.</h2>
        <Link to="/restaurantes" style={{ color: 'var(--color-accent)' }}>← Volver al catálogo de restaurantes</Link>
      </div>
    );
  }

  const googleRevLink = data.googleReviewsUrl || "https://www.google.com/maps/place/Sato+Casa+de+Piedra/@21.1596493,-101.7019348,1682m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m8!3m7!1s0x842bbf53719c9f9d:0x1cfaf89360074060!8m2!3d21.1596493!4d-101.6993545!9m1!1b1!16s%2Fg%2F11bw62cb4y?entry=ttu&g_ep=EgoyMDI2MDcwNy4wIKXMDSoASAFQAw%3D%3D";

  return (
    <div style={{ background: 'transparent', minHeight: '100vh', paddingBottom: '5rem' }}>
      {/* HERO BANNER SUPERIOR DEL RESTAURANTE */}
      <section style={{ position: 'relative', width: '100%', height: 'clamp(340px, 42vh, 480px)', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
        <img
          src={data.heroImage}
          alt={data.name}
          style={{ position: 'absolute', width: '100%', height: '100%', objectFit: 'cover', filter: 'brightness(0.7)' }}
        />
        <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to bottom, rgba(10,10,10,0.2) 0%, rgba(10,10,10,0.55) 75%, #080808 100%)' }} />
        <div style={{ position: 'relative', zIndex: 2, textAlign: 'center', padding: '0 1rem', marginTop: '30px' }}>
          <span className="text-script" style={{ color: 'var(--color-accent)', fontSize: '1.4rem' }}>Recinto Gastronómico</span>
          <h1 style={{ color: '#fff', fontSize: 'clamp(2.2rem, 4.5vw, 3.8rem)', fontFamily: 'var(--font-heading)', margin: '0.3rem 0', textShadow: '0 10px 30px rgba(0,0,0,0.85)' }}>
            {data.name}
          </h1>
          <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', justifyContent: 'center', gap: '0.8rem', marginTop: '0.8rem' }}>
            <a
              href={googleRevLink}
              target="_blank"
              rel="noopener noreferrer"
              style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', background: 'rgba(10,10,10,0.88)', border: '1.2px solid var(--color-accent)', padding: '0.45rem 1.2rem', borderRadius: '999px', textDecoration: 'none', transition: 'all 0.3s', boxShadow: '0 4px 20px rgba(0,0,0,0.7)' }}
            >
              <span style={{ color: 'var(--color-accent)', fontWeight: 700, fontSize: '0.88rem' }}>⭐ {data.rating}</span>
              <span style={{ color: '#fff', fontSize: '0.85rem', fontWeight: 500 }}>• Reseñas Verificadas en Google →</span>
            </a>

            <div style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', background: 'rgba(10,10,10,0.88)', border: '1px solid rgba(212,175,55,0.45)', padding: '0.45rem 1.2rem', borderRadius: '999px', boxShadow: '0 4px 20px rgba(0,0,0,0.7)' }}>
              <span style={{ color: 'var(--color-accent)', fontSize: '0.95rem' }}>🕒</span>
              <span style={{ color: '#eee', fontSize: '0.85rem', fontWeight: 500 }}>{data.hours || 'Lunes a Domingo • 1:00 PM - 11:00 PM'}</span>
            </div>
          </div>
        </div>
      </section>

      <section className="section" style={{ paddingTop: 'clamp(2.5rem, 4vw, 4rem)', paddingBottom: '4rem' }}>
        <div className="container">
          <style>{`
            .react-rest-grid {
              display: grid;
              grid-template-columns: 1fr;
              gap: 2.5rem;
              align-items: start;
            }
            .react-col-gallery { order: 2; width: 100%; }
            .react-col-info { order: 1; width: 100%; }
            @media screen and (min-width: 1024px) {
              .react-rest-grid {
                grid-template-columns: 1fr 1fr;
                gap: 3.5rem;
              }
              .react-col-gallery { order: 1; }
              .react-col-info { order: 2; }
            }
          `}</style>

          <div style={{ marginBottom: '1.8rem' }}>
            <Link to="/restaurantes" style={{ color: 'var(--color-accent)', textDecoration: 'none', fontSize: '0.95rem', display: 'inline-flex', alignItems: 'center', gap: '0.4rem', fontWeight: 600 }}>
              ← Volver al catálogo de restaurantes
            </Link>
          </div>

          <div className="react-rest-grid">
            
            {/* LADO IZQUIERDO EN PC / SEGUNDO EN MÓVIL: VISOR FOTOGRÁFICO Y MINIATURAS */}
            <div className="react-col-gallery fade-up">
              <div style={{ borderRadius: '1.6rem', overflow: 'hidden', height: 'clamp(400px, 46vw, 640px)', position: 'relative', border: '1px solid rgba(212,175,55,0.45)', background: '#111', boxShadow: '0 30px 70px rgba(0,0,0,0.9)', marginBottom: '1.2rem' }}>
                <img src={data.gallery[0] || data.heroImage} alt={data.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
              </div>

              <div style={{ display: 'flex', gap: '0.75rem', overflowX: 'auto', paddingBottom: '0.5rem' }}>
                {data.gallery.map((imgUrl, i) => (
                  <div key={i} style={{ flex: '0 0 clamp(85px, 16vw, 115px)', height: 'clamp(60px, 11vw, 80px)', borderRadius: '0.8rem', overflow: 'hidden', border: '1.5px solid rgba(212,175,55,0.45)', background: '#000', cursor: 'pointer' }}>
                    <img src={imgUrl} alt={`${data.name} Miniatura ${i + 1}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                  </div>
                ))}
              </div>
            </div>

            {/* LADO DERECHO EN PC / PRIMERO EN MÓVIL: TARJETA OFICIAL COMPLETA */}
            <div className="react-col-info fade-up">
              <div className="luxury-info-panel" style={{ padding: 'clamp(2.2rem, 4.5vw, 3.4rem)', borderRadius: '1.8rem', background: '#111111', border: '1px solid rgba(212, 175, 55, 0.48)', boxShadow: '0 25px 65px rgba(0,0,0,0.92)', position: 'relative', zIndex: 2 }}>
                
                {/* Logo Oficial & Cabecera */}
                <div style={{ marginBottom: '2rem', paddingBottom: '1.6rem', borderBottom: '1px solid rgba(255,255,255,0.14)' }}>
                  <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '1rem', marginBottom: '1rem' }}>
                    <div style={{ height: '55px', maxWidth: '180px' }}>
                      <img src={data.logo} alt={data.name} style={{ height: '100%', width: 'auto', objectFit: 'contain', filter: 'drop-shadow(0 2px 10px rgba(0,0,0,0.8))' }} />
                    </div>
                  </div>

                  <h1 style={{ color: '#fff', fontSize: 'clamp(2.1rem, 3.5vw, 2.8rem)', margin: 0, lineHeight: 1.15 }}>
                    {data.name}
                  </h1>
                </div>

                {/* DESCRIPCIÓN OFICIAL EXTRAÍDA DE SU SITIO WEB */}
                <div className="text-body-lg" style={{ color: '#dedede', fontSize: 'clamp(1.04rem, 1.7vw, 1.14rem)', lineHeight: '1.85', marginBottom: '2rem' }}>
                  <p>{data.description}</p>
                </div>

                {/* 3 BOTONES EN CUADROS CON BORDES REDONDEADOS (GRID HORIZONTAL DE 3 COLUMNAS) */}
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '1rem', marginTop: '1.8rem' }}>
                  {/* Cuadro 1: Google Maps / Ubicación */}
                  <a
                    href={googleRevLink}
                    target="_blank"
                    rel="noopener noreferrer"
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      alignItems: 'center',
                      justifyContent: 'center',
                      padding: '1.35rem 0.6rem 1.15rem',
                      background: 'rgba(255,255,255,0.035)',
                      border: '1.2px solid rgba(212,175,55,0.38)',
                      borderRadius: '1.25rem',
                      textDecoration: 'none',
                      transition: 'all 0.3s'
                    }}
                  >
                    <span style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: '34px', height: '34px', marginBottom: '0.5rem' }}>
                      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M4.5 16.5c-1.5 1.26-2 2.5-2 3.5 0 2.5 4.25 3 9.5 3s9.5-.5 9.5-3c0-1-.5-2.24-2-3.5" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7z" fill="rgba(212,175,55,0.18)" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <circle cx="12" cy="9" r="2.5" fill="var(--color-accent)"/>
                      </svg>
                    </span>
                    <span style={{ color: 'rgba(255,255,255,0.65)', fontSize: '0.74rem', fontWeight: 300, letterSpacing: '0.8px', textTransform: 'uppercase', textAlign: 'center' }}>Ubicación</span>
                  </a>

                  {/* Cuadro 2: Teléfono / Agenda / Reserva */}
                  <a
                    href={data.reservaUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      alignItems: 'center',
                      justifyContent: 'center',
                      padding: '1.35rem 0.6rem 1.15rem',
                      background: 'linear-gradient(145deg, rgba(212,175,55,0.18) 0%, rgba(212,175,55,0.06) 100%)',
                      border: '1.5px solid var(--color-accent)',
                      borderRadius: '1.25rem',
                      textDecoration: 'none',
                      transition: 'all 0.3s',
                      boxShadow: '0 8px 25px rgba(212,175,55,0.15)'
                    }}
                  >
                    <span style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: '34px', height: '34px', marginBottom: '0.5rem' }}>
                      <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="7" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <rect x="12.75" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <rect x="18.5" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <path d="M5 6.5H23C24.1 6.5 25 7.4 25 8.5V21C25 22.1 24.1 23 23 23H5C3.9 23 3 22.1 3 21V8.5C3 7.4 3.9 6.5 5 6.5Z" fill="rgba(212,175,55,0.1)" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <line x1="3" y1="10.5" x2="25" y2="10.5" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <rect x="6.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                        <rect x="10.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                        <rect x="14.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                        <rect x="18.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                        <rect x="6.5" y="17.5" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                        <rect x="10.5" y="17.5" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                        <circle cx="19.5" cy="20.5" r="5.5" fill="#111111" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <path d="M17.2 20.5L18.8 22L22 18.7" stroke="var(--color-accent)" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"/>
                      </svg>
                    </span>
                    <span style={{ color: 'rgba(255,255,255,0.85)', fontSize: '0.74rem', fontWeight: 400, letterSpacing: '0.8px', textTransform: 'uppercase', textAlign: 'center' }}>Reserva</span>
                  </a>

                  {/* Cuadro 3: Libro / Menú */}
                  <a
                    href={data.menuUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      alignItems: 'center',
                      justifyContent: 'center',
                      padding: '1.35rem 0.6rem 1.15rem',
                      background: 'rgba(255,255,255,0.035)',
                      border: '1.2px solid rgba(212,175,55,0.38)',
                      borderRadius: '1.25rem',
                      textDecoration: 'none',
                      transition: 'all 0.3s'
                    }}
                  >
                    <span style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: '34px', height: '34px', marginBottom: '0.5rem' }}>
                      <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 5H21C22.1 5 23 5.9 23 7V23C23 24.1 22.1 25 21 25H14" stroke="var(--color-accent)" strokeWidth="1.8" strokeLinecap="round"/>
                        <path d="M11 5L20 3V5" stroke="var(--color-accent)" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round"/>
                        <path d="M14.5 9C14.5 11 16 12 16 12C16 12 17.5 11 17.5 9V7.5H14.5V9Z" stroke="var(--color-accent)" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round"/>
                        <path d="M16 12V14.5M14.5 14.5H17.5" stroke="var(--color-accent)" strokeWidth="1.4" strokeLinecap="round"/>
                        <path d="M20 7.5V11.5M20 11.5V15M19 7.5V9.5C19 10 20 10.5 20 10.5" stroke="var(--color-accent)" strokeWidth="1.3" strokeLinecap="round" strokeLinejoin="round"/>
                        <line x1="15" y1="17.5" x2="20" y2="17.5" stroke="var(--color-accent)" strokeWidth="1.5" strokeLinecap="round"/>
                        <line x1="16" y1="20.5" x2="20" y2="20.5" stroke="var(--color-accent)" strokeWidth="1.5" strokeLinecap="round"/>
                        <path d="M4 22H15" stroke="var(--color-accent)" strokeWidth="2" strokeLinecap="round"/>
                        <path d="M5 22C5 18.5 7.5 15.5 9.5 15.5C11.5 15.5 14 18.5 14 22" fill="rgba(212,175,55,0.2)" stroke="var(--color-accent)" strokeWidth="1.8" strokeLinecap="round"/>
                        <circle cx="9.5" cy="14" r="1.3" fill="var(--color-accent)"/>
                      </svg>
                    </span>
                    <span style={{ color: 'rgba(255,255,255,0.65)', fontSize: '0.74rem', fontWeight: 300, letterSpacing: '0.8px', textTransform: 'uppercase', textAlign: 'center' }}>Menú</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* SECCIÓN DE RESEÑA AUTÉNTICAS DE GOOGLE MAPS EN CASA DE PIEDRA */}
      <section className="section" style={{ paddingTop: '1rem' }}>
        <div className="container fade-up">
          <div style={{ marginBottom: '2.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '1rem' }}>
            <div>
              <span className="text-caption" style={{ color: 'var(--color-accent)' }}>Google Maps Verified Reviews</span>
              <h2 className="text-h2" style={{ marginTop: '0.4rem' }}>Reseñas en Casa de Piedra.</h2>
            </div>
            <div style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', padding: '0.5rem 1.2rem', background: 'rgba(255,255,255,0.05)', borderRadius: '999px', border: '1px solid var(--color-border)' }}>
              <CheckCircle size={16} style={{ color: 'var(--color-accent)' }} />
              <span style={{ color: '#fff', fontSize: '0.85rem' }}>Opiniones auténticas de clientes verificados</span>
            </div>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {data.googleReviews.map((rev, index) => (
              <a
                key={index}
                href={googleRevLink}
                target="_blank"
                rel="noopener noreferrer"
                className="luxury-card"
                style={{ padding: '2rem', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', textDecoration: 'none', transition: 'all 0.3s' }}
              >
                <div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1rem' }}>
                    <div style={{ display: 'flex', gap: '4px' }}>
                      {[...Array(5)].map((_, s) => (
                        <Star key={s} size={16} fill="var(--color-accent)" color="var(--color-accent)" />
                      ))}
                    </div>
                    <span style={{ fontSize: '0.72rem', color: 'var(--color-accent)', fontWeight: 500 }}>Ver en Google ↗</span>
                  </div>
                  <p style={{ color: '#e0e0e0', fontSize: '0.98rem', lineHeight: '1.65', fontStyle: 'italic', marginBottom: '1.5rem' }}>
                    "{rev.comment}"
                  </p>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderTop: '1px solid var(--color-border)', paddingTop: '1rem' }}>
                  <div>
                    <strong style={{ color: '#fff', display: 'block', fontSize: '0.95rem' }}>{rev.author}</strong>
                    <span style={{ color: '#888', fontSize: '0.78rem' }}>Google Maps Review</span>
                  </div>
                  <span style={{ color: '#aaa', fontSize: '0.8rem' }}>{rev.time}</span>
                </div>
              </a>
            ))}
          </div>
        </div>
      </section>

      {/* SECCIÓN DE RECOMENDACIONES: OTRAS PROPUESTAS GASTRONÓMICAS (ESTILO MOBILE CARD) */}
      <section className="section" style={{ paddingTop: '2rem', paddingBottom: '5rem' }}>
        <div className="container fade-up">
          <div style={{ marginBottom: '2.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '1rem' }}>
            <div>
              <span className="text-caption" style={{ color: 'var(--color-accent)' }}>Experiencia Gastronómica</span>
              <h2 className="text-h2" style={{ marginTop: '0.4rem' }}>Otras Propuestas en Casa de Piedra.</h2>
            </div>
            <Link
              to="/restaurantes"
              style={{
                color: 'var(--color-accent)',
                fontSize: '0.9rem',
                fontWeight: 600,
                textDecoration: 'none',
                padding: '0.65rem 1.4rem',
                border: '1px solid rgba(212,175,55,0.4)',
                borderRadius: '999px'
              }}
            >
              Ver Todos los Restaurantes →
            </Link>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {Object.entries(restaurantsData)
              .filter(([key]) => key !== id)
              .slice(0, 3)
              .map(([key, rest]) => (
                <Link
                  key={key}
                  to={`/restaurantes/${key}`}
                  style={{
                    position: 'relative',
                    borderRadius: '1.4rem',
                    overflow: 'hidden',
                    background: '#111',
                    border: '1px solid rgba(212,175,55,0.35)',
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'flex-end',
                    minHeight: '420px',
                    textDecoration: 'none',
                    boxShadow: '0 15px 40px rgba(0,0,0,0.85)'
                  }}
                >
                  <img
                    src={rest.heroImage || rest.image}
                    alt={rest.name}
                    style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', filter: 'brightness(0.75)' }}
                  />
                  <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(8,8,8,0.95) 0%, rgba(8,8,8,0.45) 55%, transparent 100%)', pointerEvents: 'none' }} />

                  <div style={{ position: 'relative', zIndex: 2, padding: '2.2rem 1.8rem' }}>
                    {rest.logo && (
                      <div style={{ marginBottom: '0.9rem', display: 'flex', alignItems: 'center', height: '55px' }}>
                        <img src={rest.logo} alt={rest.name} style={{ maxWidth: '170px', maxHeight: '52px', width: 'auto', height: 'auto', objectFit: 'contain', objectPosition: 'left center', filter: 'drop-shadow(0 4px 15px rgba(0,0,0,0.95))' }} />
                      </div>
                    )}

                    <span style={{ display: 'inline-block', padding: '0.3rem 0.85rem', background: 'rgba(212,175,55,0.2)', border: '1px solid var(--color-accent)', borderRadius: '999px', color: 'var(--color-accent)', fontSize: '0.72rem', letterSpacing: '1.5px', textTransform: 'uppercase', fontWeight: 600, marginBottom: '0.9rem' }}>
                      {rest.category || 'Alta cocina'}
                    </span>

                    <h4 style={{ color: '#fff', fontSize: '1.4rem', fontFamily: 'var(--font-heading)', margin: 0, lineHeight: 1.2 }}>
                      {rest.name}
                    </h4>
                  </div>
                </Link>
              ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default RestaurantDetail;
