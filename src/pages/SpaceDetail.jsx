import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import gsap from 'gsap';
import Lightbox from '../components/Lightbox';

const spacesData = {
  "salon-principal": {
    name: "Salón Principal",
    capacity: "1,000 personas",
    area: "1,200 m²",
    heroImage: "/images/salon_principal_1779523069698.png",
    description: "La joya de la corona de Casa de Piedra. Este imponente salón combina la arquitectura colonial de 1845 con todas las facilidades tecnológicas de vanguardia. Ideal para magnas bodas, congresos internacionales y galas de premiación.",
    features: ["Climatización", "Planta de luz", "Accesos de carga", "Camerinos"],
    gallery: [
      "/images/salon_principal_1779523069698.png",
      "https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1530103862676-de3c9de59f9e?q=80&w=2070&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=2069&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1478146896981-b80fe463b330?q=80&w=2070&auto=format&fit=crop"
    ]
  },
  "terraza-mezquite": {
    name: "Terraza del Mezquite",
    capacity: "200 personas",
    area: "350 m²",
    heroImage: "/images/terraza_mezquite_1779523084857.png",
    description: "Bañada por luz natural y rodeada de la vegetación original de la hacienda. La Terraza del Mezquite ofrece un ambiente íntimo y sofisticado, perfecto para bodas civiles, cocteles de negocios y aniversarios.",
    features: ["Techo retráctil", "Vista a jardines", "Barra integrada"],
    gallery: [
      "/images/terraza_mezquite_1779523084857.png",
      "https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=2070&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1522413452208-996906271a5f?q=80&w=2070&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=2070&auto=format&fit=crop"
    ]
  },
  "salon-pavorreales": {
    name: "Salón Pavorreales",
    capacity: "100 personas",
    area: "180 m²",
    heroImage: "/images/salon_pavorreales_1779523097528.png",
    description: "Un espacio caracterizado por su exclusividad y diseño íntimo. El Salón Pavorreales es la elección predilecta para capacitaciones empresariales, baby showers y cenas privadas de alto nivel.",
    features: ["Acústica optimizada", "Privacidad total", "Baños privados"],
    gallery: [
      "/images/salon_pavorreales_1779523097528.png",
      "https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2070&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1505236858219-8359eb29e329?q=80&w=2062&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1974&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1544148103-0773bf10d330?q=80&w=2070&auto=format&fit=crop"
    ]
  },
  "jardin-principal": {
    name: "Jardín Principal",
    capacity: "2,000 personas",
    area: "3,500 m²",
    heroImage: "/images/jardin_principal_1779523113451.png",
    description: "Una inmensa llanura verde enmarcada por la arquitectura centenaria. Nuestro jardín permite la instalación de carpas monumentales, ideal para conciertos, festivales y bodas de ensueño al aire libre.",
    features: ["Espejos de agua", "Árboles centenarios", "Capacidad monumental"],
    gallery: [
      "/images/jardin_principal_1779523113451.png",
      "https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1502635385003-ee1e6a1a742d?q=80&w=2070&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1472653431158-6364773b2a56?q=80&w=2069&auto=format&fit=crop",
      "https://images.unsplash.com/photo-1475721042966-f829c9b42aeb?q=80&w=2070&auto=format&fit=crop"
    ]
  }
};

const SpaceDetail = () => {
  const { id } = useParams();
  const data = spacesData[id];
  const [lightboxData, setLightboxData] = useState({ isOpen: false, index: 0 });
  const [selectedImage, setSelectedImage] = useState(0);

  useEffect(() => {
    window.scrollTo(0, 0);
    const ctx = gsap.context(() => {
      gsap.from('.fade-up', {
        y: 40,
        opacity: 0,
        duration: 0.85,
        stagger: 0.12,
        ease: 'power3.out',
      });
    });
    return () => ctx.revert();
  }, [id]);

  if (!data) return (
    <div style={{ paddingTop: 'clamp(8rem, 15vh, 12rem)', paddingBottom: '6rem', textAlign: 'center', color: '#fff' }}>
      Espacio no encontrado. <Link to="/espacios" style={{ color: 'var(--color-accent)' }}>Volver a Espacios</Link>
    </div>
  );

  const galleryImages = data.gallery || [data.heroImage];
  const activeImage = galleryImages[selectedImage] || data.heroImage;
  const googleMapsLink = "https://www.google.com/maps/place/Sato+Casa+de+Piedra/@21.1596493,-101.7019348,1682m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m8!3m7!1s0x842bbf53719c9f9d:0x1cfaf89360074060!8m2!3d21.1596493!4d-101.6993545!9m1!1b1!16s%2Fg%2F11bw62cb4y?entry=ttu";

  const otherSpacesKeys = Object.keys(spacesData).filter(key => key !== id).slice(0, 3);

  return (
    <div style={{ background: '#080808', minHeight: '100vh' }}>
      {/* 1. HERO BANNER DE ANCHO COMPLETO CON PÍLDORAS OFICIALES */}
      <section style={{ position: 'relative', width: '100%', height: 'clamp(340px, 42vh, 480px)', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
        <img
          src={data.heroImage}
          alt={data.name}
          style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover', filter: 'brightness(0.75)', transform: 'scale(1.03)' }}
        />
        <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to bottom, rgba(8,8,8,0.15) 0%, rgba(8,8,8,0.45) 75%, #080808 100%)', zIndex: 1, pointerEvents: 'none' }} />

        <div style={{ position: 'relative', zIndex: 2, textAlign: 'center', padding: '0 clamp(1rem, 4vw, 3rem)', maxWidth: '1100px', marginTop: '45px' }}>
          <nav aria-label="Breadcrumbs" style={{ marginBottom: '0.8rem', display: 'flex', justifyContent: 'center', flexWrap: 'wrap', alignItems: 'center', gap: '0.5rem' }}>
            <Link to="/" style={{ color: '#bbb', textDecoration: 'none', fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1.5px' }}>Inicio</Link>
            <span style={{ color: 'var(--color-accent)' }}>/</span>
            <Link to="/espacios" style={{ color: '#bbb', textDecoration: 'none', fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1.5px' }}>Espacios</Link>
            <span style={{ color: 'var(--color-accent)' }}>/</span>
            <span style={{ color: '#fff', fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1.5px' }}>{data.name}</span>
          </nav>

          <h1 style={{ color: '#fff', fontSize: 'clamp(2.3rem, 5vw, 4.2rem)', margin: '0 0 1rem 0', fontFamily: 'var(--font-heading)', lineHeight: 1.1, textShadow: '0 10px 30px rgba(0,0,0,0.85)' }}>
            {data.name}
          </h1>

          <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'center', gap: '0.8rem' }}>
            <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', padding: '0.5rem 1.3rem', background: 'rgba(10, 10, 10, 0.78)', border: '1px solid var(--color-accent)', borderRadius: '999px', color: 'var(--color-accent)', fontSize: '0.82rem', letterSpacing: '1.2px', textTransform: 'uppercase', fontWeight: 600 }}>
              👥 Capacidad: Hasta {data.capacity}
            </span>
            <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', padding: '0.5rem 1.3rem', background: 'rgba(10, 10, 10, 0.78)', border: '1px solid rgba(255,255,255,0.22)', borderRadius: '999px', color: '#dedede', fontSize: '0.82rem', letterSpacing: '1px', textTransform: 'uppercase', fontWeight: 500 }}>
              📐 Superficie: {data.area}
            </span>
          </div>
        </div>
      </section>

      {/* 2. SECCIÓN PRINCIPAL: 2 COLUMNAS (GALERÍA A LA IZQUIERDA / TARJETA DE INFORMACIÓN A LA DERECHA) */}
      <main style={{ padding: 'clamp(3.5rem, 7vw, 5.5rem) 0 6rem', background: '#080808', width: '100%' }}>
        <div style={{ maxWidth: '1600px', margin: '0 auto', padding: '0 clamp(1.2rem, 4vw, 3.5rem)' }}>

          <style>{`
            .espacio-react-grid {
              display: grid;
              grid-template-columns: 1fr;
              gap: 2.5rem;
              align-items: start;
            }
            .espacio-col-gallery { order: 2; width: 100%; }
            .espacio-col-info { order: 1; width: 100%; }
            @media screen and (min-width: 1024px) {
              .espacio-react-grid {
                grid-template-columns: 1fr 1fr;
                gap: 3.5rem;
              }
              .espacio-col-gallery { order: 1; }
              .espacio-col-info { order: 2; }
            }
          `}</style>

          <div className="espacio-react-grid">
            
            {/* LADO IZQUIERDO EN PC / SEGUNDO EN MÓVIL: VISOR FOTOGRÁFICO Y MINIATURAS */}
            <div className="espacio-col-gallery fade-up">
              <div
                style={{ borderRadius: '1.4rem', overflow: 'hidden', height: 'clamp(340px, 42vw, 520px)', position: 'relative', border: '1px solid rgba(212,175,55,0.32)', background: '#111', boxShadow: '0 25px 55px rgba(0,0,0,0.8)', cursor: 'zoom-in' }}
                onClick={() => setLightboxData({ isOpen: true, index: selectedImage })}
              >
                <img
                  src={activeImage}
                  alt={data.name}
                  style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'all 0.45s ease' }}
                />
              </div>

              {galleryImages.length > 1 && (
                <div style={{ display: 'flex', gap: '0.75rem', marginTop: '1rem', overflowX: 'auto', paddingBottom: '0.5rem' }}>
                  {galleryImages.map((imgUrl, idx) => (
                    <button
                      key={idx}
                      type="button"
                      onClick={() => setSelectedImage(idx)}
                      style={{
                        flex: '0 0 clamp(80px, 16vw, 105px)',
                        height: 'clamp(56px, 11vw, 72px)',
                        borderRadius: '0.7rem',
                        overflow: 'hidden',
                        border: selectedImage === idx ? '2px solid var(--color-accent)' : '1.5px solid rgba(255,255,255,0.2)',
                        background: '#000',
                        cursor: 'pointer',
                        padding: 0,
                        transition: 'transform 0.2s',
                        transform: selectedImage === idx ? 'scale(1.05)' : 'scale(1)'
                      }}
                    >
                      <img src={imgUrl} alt={`Miniatura ${idx + 1}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    </button>
                  ))}
                </div>
              )}
            </div>

            {/* LADO DERECHO EN PC / PRIMERO EN MÓVIL: TARJETA DEL SALÓN */}
            <div className="espacio-col-info fade-up">
              <div className="luxury-info-panel" style={{ padding: 'clamp(1.8rem, 4vw, 3rem)', borderRadius: '1.4rem', background: '#111111', border: '1px solid rgba(212, 175, 55, 0.35)', boxShadow: '0 20px 50px rgba(0,0,0,0.7)', position: 'relative', zIndex: 2 }}>
                <span style={{ color: 'var(--color-accent)', fontSize: '0.82rem', letterSpacing: '2px', textTransform: 'uppercase', fontWeight: 600, display: 'block', marginBottom: '0.7rem' }}>
                  RECINTO HISTÓRICO & EVENTOS EXCLUSIVOS
                </span>
                <h2 style={{ color: '#fff', fontSize: 'clamp(1.6rem, 3vw, 2.3rem)', fontFamily: 'var(--font-heading)', margin: '0 0 1.2rem 0', lineHeight: 1.2 }}>
                  {data.name}
                </h2>

                <div className="text-body-lg" style={{ color: '#dedede', fontSize: 'clamp(1.02rem, 1.6vw, 1.1rem)', lineHeight: '1.85', marginBottom: '2rem' }}>
                  <p>{data.description}</p>
                </div>

                {/* FICHA RÁPIDA DE 2 COLUMNAS */}
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: '1rem', borderTop: '1px solid rgba(255,255,255,0.12)', borderBottom: '1px solid rgba(255,255,255,0.12)', padding: '1.3rem 0', marginBottom: '1.8rem' }}>
                  <div>
                    <span style={{ color: '#888', display: 'block', fontSize: '0.74rem', textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '0.2rem' }}>Capacidad</span>
                    <strong style={{ color: '#fff', fontSize: '1.05rem', fontFamily: 'var(--font-heading)' }}>Hasta {data.capacity}</strong>
                  </div>
                  <div>
                    <span style={{ color: '#888', display: 'block', fontSize: '0.74rem', textTransform: 'uppercase', letterSpacing: '1px', marginBottom: '0.2rem' }}>Superficie</span>
                    <strong style={{ color: 'var(--color-accent)', fontSize: '1.05rem', fontFamily: 'var(--font-heading)' }}>{data.area}</strong>
                  </div>
                </div>

                {/* 2 BOTONES EN CUADROS CON BORDES REDONDEADOS (GRID HORIZONTAL DE 2 COLUMNAS) */}
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: '1rem', marginTop: '1.8rem' }}>
                  {/* Cuadro 1: Cotizar */}
                  <button
                    type="button"
                    onClick={() => { if (window.openQuoteModal) window.openQuoteModal(t.name); }}
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
                     <span style={{ color: 'rgba(255,255,255,0.85)', fontSize: '0.74rem', fontWeight: 400, letterSpacing: '0.8px', textTransform: 'uppercase', textAlign: 'center' }}>Cotizar</span>
                  </button>

                  {/* Cuadro 3: Libro / Planos */}
                  <Link
                    to="#quote-modal"
                    onClick={(e) => {
                      e.preventDefault();
                      if (window.openQuoteModal) window.openQuoteModal(t.name);
                    }}
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
                        <path d="M5 3C3.5 3 2.5 4.5 2.5 6V22C2.5 23.5 3.5 25 5 25C6.5 25 7.5 23.5 7.5 22V6C7.5 4.5 6.5 3 5 3Z" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <path d="M23 3C21.5 3 20.5 4.5 20.5 6V22C23 23.5 21.5 25 23 25C24.5 25 25.5 23.5 25.5 22V6C25.5 4.5 24.5 3 23 3Z" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <path d="M7.5 5.5H20.5V21.5H7.5" stroke="var(--color-accent)" strokeWidth="1.8"/>
                        <path d="M10.5 9.5H16.5V14.5H10.5V9.5Z" fill="rgba(212,175,55,0.18)" stroke="var(--color-accent)" strokeWidth="1.5"/>
                        <path d="M16.5 14.5H18.5V18.5H13.5V14.5" stroke="var(--color-accent)" strokeWidth="1.5"/>
                        <path d="M16.5 9.5C17.6 9.5 18.5 10.4 18.5 11.5" stroke="var(--color-accent)" strokeWidth="1.4"/>
                      </svg>
                    </span>
                    <span style={{ color: 'rgba(255,255,255,0.65)', fontSize: '0.74rem', fontWeight: 300, letterSpacing: '0.8px', textTransform: 'uppercase', textAlign: 'center' }}>Planos</span>
                  </Link>
                </div>

                <p style={{ color: '#999', fontSize: '0.81rem', textAlign: 'center', margin: '0.6rem 0 0 0', fontStyle: 'italic', lineHeight: 1.4 }}>
                  Consulta la distribución arquitectónica o solicita tu cotización directa
                </p>
              </div>
            </div>

          </div>

          {/* 3. RESEÑAS VERIFICADAS DE GOOGLE MAPS PARA EVENTOS Y RECINTOS */}
          <div style={{ marginTop: 'clamp(4rem, 7vw, 5.5rem)', borderTop: '1px solid rgba(255,255,255,0.12)', paddingTop: 'clamp(3rem, 5vw, 4rem)' }}>
            <div style={{ marginBottom: '2.5rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between', flexWrap: 'wrap', gap: '1rem' }}>
              <div>
                <span style={{ color: 'var(--color-accent)', fontSize: '0.8rem', letterSpacing: '2px', textTransform: 'uppercase', fontWeight: 600, display: 'block' }}>Google Maps Verified Reviews</span>
                <h3 style={{ color: '#fff', fontSize: 'clamp(1.6rem, 3vw, 2.3rem)', fontFamily: 'var(--font-heading)', margin: '0.3rem 0 0 0' }}>Experiencias en Casa de Piedra</h3>
              </div>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem', background: 'rgba(255,255,255,0.05)', padding: '0.6rem 1.1rem', borderRadius: '999px', border: '1px solid rgba(212,175,55,0.35)' }}>
                <span style={{ color: '#f39c12', fontSize: '1.1rem' }}>★★★★★</span>
                <strong style={{ color: '#fff', fontSize: '0.95rem' }}>4.9 / 5.0</strong>
                <span style={{ color: '#888', fontSize: '0.85rem' }}>(Eventos & Salones)</span>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <a
                href="https://maps.app.goo.gl/nPM297u455R8KF9V9"
                target="_blank"
                rel="noopener noreferrer"
                style={{ padding: '1.8rem', borderRadius: '1.2rem', background: '#111', border: '1px solid rgba(212,175,55,0.25)', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', textDecoration: 'none', transition: 'all 0.3s' }}
              >
                <div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.8rem' }}>
                    <div style={{ color: 'var(--color-accent)', fontSize: '1rem' }}>★★★★★</div>
                    <span style={{ fontSize: '0.72rem', color: 'var(--color-accent)', fontWeight: 500 }}>Ver en Google ↗</span>
                  </div>
                  <p style={{ color: '#ddd', fontSize: '0.96rem', lineHeight: 1.65, fontStyle: 'italic', marginBottom: '1.4rem' }}>
                    "Celebramos nuestra boda en este recinto de Casa de Piedra y fue mágico. La arquitectura histórica, los jardines y la logística del salón superaron todas nuestras expectativas."
                  </p>
                </div>
                <div style={{ borderTop: '1px solid rgba(255,255,255,0.1)', paddingTop: '0.8rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <strong style={{ color: '#fff', fontSize: '0.92rem' }}>Boda & Recepción • Cliente Verificado</strong>
                  <span style={{ color: '#888', fontSize: '0.78rem' }}>Google Maps</span>
                </div>
              </a>

              <a
                href="https://maps.app.goo.gl/nPM297u455R8KF9V9"
                target="_blank"
                rel="noopener noreferrer"
                style={{ padding: '1.8rem', borderRadius: '1.2rem', background: '#111', border: '1px solid rgba(212,175,55,0.25)', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', textDecoration: 'none', transition: 'all 0.3s' }}
              >
                <div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.8rem' }}>
                    <div style={{ color: 'var(--color-accent)', fontSize: '1rem' }}>★★★★★</div>
                    <span style={{ fontSize: '0.72rem', color: 'var(--color-accent)', fontWeight: 500 }}>Ver en Google ↗</span>
                  </div>
                  <p style={{ color: '#ddd', fontSize: '0.96rem', lineHeight: 1.65, fontStyle: 'italic', marginBottom: '1.4rem' }}>
                    "Organizamos un congreso directivo en el Salón Principal y las instalaciones están impecables. Acústica de primera, seguridad privada y valet parking excelente."
                  </p>
                </div>
                <div style={{ borderTop: '1px solid rgba(255,255,255,0.1)', paddingTop: '0.8rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <strong style={{ color: '#fff', fontSize: '0.92rem' }}>Evento Corporativo</strong>
                  <span style={{ color: '#888', fontSize: '0.78rem' }}>Google Maps</span>
                </div>
              </a>

              <a
                href="https://maps.app.goo.gl/nPM297u455R8KF9V9"
                target="_blank"
                rel="noopener noreferrer"
                style={{ padding: '1.8rem', borderRadius: '1.2rem', background: '#111', border: '1px solid rgba(212,175,55,0.25)', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', textDecoration: 'none', transition: 'all 0.3s' }}
              >
                <div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '0.8rem' }}>
                    <div style={{ color: 'var(--color-accent)', fontSize: '1rem' }}>★★★★★</div>
                    <span style={{ fontSize: '0.72rem', color: 'var(--color-accent)', fontWeight: 500 }}>Ver en Google ↗</span>
                  </div>
                  <p style={{ color: '#ddd', fontSize: '0.96rem', lineHeight: 1.65, fontStyle: 'italic', marginBottom: '1.4rem' }}>
                    "El lugar más elegante de León por mucho. Cada rincón arquitectónico es hermoso para fotos y la atención del equipo de eventos es sumamente profesional."
                  </p>
                </div>
                <div style={{ borderTop: '1px solid rgba(255,255,255,0.1)', paddingTop: '0.8rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <strong style={{ color: '#fff', fontSize: '0.92rem' }}>Celebración Exclusiva</strong>
                  <span style={{ color: '#888', fontSize: '0.78rem' }}>Google Maps</span>
                </div>
              </a>
            </div>
          </div>

          {/* 4. EXPLORA OTROS ESCENARIOS */}
          <div style={{ marginTop: 'clamp(4.5rem, 8vw, 6.5rem)', borderTop: '1px solid rgba(212,175,55,0.25)', paddingTop: 'clamp(3.5rem, 6vw, 4.5rem)' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '2.8rem', flexWrap: 'wrap', gap: '1rem' }}>
              <div>
                <span style={{ color: 'var(--color-accent)', fontSize: '0.82rem', letterSpacing: '2px', textTransform: 'uppercase', fontWeight: 600, display: 'block' }}>Más Opciones de Celebración</span>
                <h3 style={{ color: '#fff', fontSize: 'clamp(1.7rem, 3.5vw, 2.5rem)', fontFamily: 'var(--font-heading)', margin: '0.4rem 0 0 0' }}>Explora otros Escenarios</h3>
              </div>
              <Link
                to="/espacios"
                style={{ color: 'var(--color-accent)', fontSize: '0.95rem', fontWeight: 600, letterSpacing: '1px', textTransform: 'uppercase', textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '0.4rem', padding: '0.6rem 1.3rem', border: '1px solid rgba(212,175,55,0.4)', borderRadius: '999px' }}
              >
                Ver todos los salones →
              </Link>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {otherSpacesKeys.map((key) => {
                const space = spacesData[key];
                return (
                  <Link
                    key={key}
                    to={`/espacio/${key}`}
                    style={{
                      display: 'block',
                      textDecoration: 'none',
                      borderRadius: '1.4rem',
                      overflow: 'hidden',
                      background: '#111111',
                      border: '1px solid rgba(255,255,255,0.14)',
                      transition: 'all 0.45s cubic-bezier(0.25, 1, 0.5, 1)'
                    }}
                  >
                    <div style={{ height: '260px', position: 'relative', overflow: 'hidden' }}>
                      <img src={space.heroImage} alt={space.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                      <span style={{ position: 'absolute', bottom: '16px', left: '16px', background: 'rgba(10,10,10,0.9)', border: '1px solid var(--color-accent)', color: 'var(--color-accent)', padding: '0.4rem 1rem', borderRadius: '999px', fontSize: '0.8rem', fontWeight: 600, letterSpacing: '1px' }}>
                        Hasta {space.capacity}
                      </span>
                    </div>
                    <div style={{ padding: '1.7rem 1.9rem' }}>
                      <h4 style={{ color: '#fff', fontSize: '1.4rem', fontFamily: 'var(--font-heading)', margin: '0 0 0.5rem 0' }}>
                        {space.name}
                      </h4>
                      <span style={{ color: 'var(--color-accent)', fontSize: '0.9rem', fontWeight: 600, letterSpacing: '0.5px' }}>
                        Conocer Salón →
                      </span>
                    </div>
                  </Link>
                );
              })}
            </div>
          </div>

        </div>
      </main>

      {lightboxData.isOpen && (
        <Lightbox
          images={galleryImages}
          initialIndex={lightboxData.index}
          onClose={() => setLightboxData({ isOpen: false, index: 0 })}
        />
      )}
    </div>
  );
};

export default SpaceDetail;
