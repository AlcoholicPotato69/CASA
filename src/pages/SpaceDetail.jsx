import React, { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import gsap from 'gsap';
import MagneticButton from '../components/MagneticButton';
import { Users, LayoutDashboard, CalendarCheck } from 'lucide-react';
import Lightbox from '../components/Lightbox';
import { useState } from 'react';

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

/**
 * SpaceDetail.jsx - Página de Detalles Dinámica por Espacio
 * 
 * Utiliza el parámetro de la URL (id) para buscar en el diccionario `spacesData`
 * toda la información correspondiente a un salón o terraza en particular.
 * Muestra galería de imágenes vinculadas al `Lightbox`.
 * 
 * @returns {JSX.Element} Vista del detalle del espacio.
 */
const SpaceDetail = () => {
  const { id } = useParams();
  const data = spacesData[id];
  const [lightboxData, setLightboxData] = useState({ isOpen: false, index: 0 });

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

  if (!data) return (
    <div style={{ paddingTop: '12rem', paddingBottom: '6rem', textAlign: 'center' }}>Espacio no encontrado. <Link to="/espacios">Volver</Link></div>
  );

  return (
    <div>
      <section style={{ height: '70vh', position: 'relative', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
        <div style={{ position: 'absolute', inset: 0, zIndex: -1 }}>
          <img src={data.heroImage} alt={data.name} className="img-cover" />
          <div style={{ position: 'absolute', inset: 0, backgroundColor: 'rgba(10,10,10,0.5)' }} />
          {/* Watermark temporary flag */}
          <div style={{ position: 'absolute', top: '100px', left: '10px', background: 'rgba(212,175,55,0.9)', color: '#000', padding: '4px 12px', fontSize: '0.8rem', fontWeight: 'bold', borderRadius: '4px', zIndex: 10 }}>IMAGEN TEMPORAL</div>
        </div>
        <div className="container text-center fade-up" style={{ zIndex: 10 }}>
          <span className="text-script" style={{ fontSize: '3rem', display: 'block', marginBottom: '1rem' }}>Espacios</span>
          <h1 className="text-hero" style={{ textTransform: 'none' }}>{data.name}</h1>
        </div>
      </section>

      <section className="section">
        <div className="container">
          <div className="grid md:grid-cols-12 gap-12">
            <div className="md:col-span-7 fade-up">
              <h2 className="text-h2" style={{ marginBottom: '2rem' }}>El Escenario Perfecto</h2>
              <p className="text-body-lg" style={{ marginBottom: '1.5rem', fontSize: '1.25rem' }}>
                {data.description}
              </p>
              
              <h4 style={{ fontFamily: 'var(--font-heading)', fontSize: '1.5rem', marginTop: '3rem', marginBottom: '1.5rem', color: 'var(--color-accent)' }}>Características Destacadas</h4>
              <ul style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem', listStyle: 'none', color: 'var(--color-text-primary)' }}>
                {data.features.map((feature, idx) => (
                  <li key={idx} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                    <div style={{ width: '8px', height: '8px', backgroundColor: 'var(--color-accent)', borderRadius: '50%' }} />
                    {feature}
                  </li>
                ))}
              </ul>
            </div>
            
            <div className="md:col-span-5 fade-up">
              <div className="luxury-card" style={{ padding: '3rem' }}>
                <h3 className="text-h3" style={{ marginBottom: '2rem', color: 'var(--color-accent)' }}>Ficha Técnica</h3>
                
                <div style={{ display: 'flex', flexDirection: 'column', gap: '2rem' }}>
                  <div style={{ display: 'flex', gap: '1.5rem', alignItems: 'flex-start' }}>
                    <Users size={28} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                    <div>
                      <span className="text-caption">Capacidad Máxima</span>
                      <p style={{ marginTop: '0.5rem', color: 'var(--color-text-primary)', fontSize: '1.2rem', fontWeight: 500 }}>{data.capacity}</p>
                    </div>
                  </div>
                  
                  <div style={{ display: 'flex', gap: '1.5rem', alignItems: 'flex-start' }}>
                    <LayoutDashboard size={28} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                    <div>
                      <span className="text-caption">Dimensiones</span>
                      <p style={{ marginTop: '0.5rem', color: 'var(--color-text-primary)', fontSize: '1.2rem', fontWeight: 500 }}>{data.area}</p>
                    </div>
                  </div>

                  <div style={{ display: 'flex', gap: '1.5rem', alignItems: 'flex-start' }}>
                    <CalendarCheck size={28} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                    <div>
                      <span className="text-caption">Disponibilidad</span>
                      <p style={{ marginTop: '0.5rem', color: 'var(--color-text-primary)', fontSize: '1.2rem', fontWeight: 500 }}>Bajo Reserva</p>
                    </div>
                  </div>
                </div>

                <div style={{ marginTop: '3rem' }}>
                  <MagneticButton href="mailto:eventos@casadepiedraleon.mx" variant="primary" className="w-full">
                    Cotizar este Espacio
                  </MagneticButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Galería del Espacio */}
      {data.gallery && data.gallery.length > 0 && (
        <section className="section" style={{ paddingTop: 0 }}>
          <div className="container">
            <h3 className="text-h3 fade-up" style={{ marginBottom: '2rem', textAlign: 'center' }}>Galería del Espacio</h3>
            <div className="grid md:grid-cols-4 gap-4 fade-up" style={{ gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))' }}>
              {data.gallery.map((img, idx) => (
                <div 
                  key={idx} 
                  className="luxury-card" 
                  style={{ height: '250px', cursor: 'zoom-in' }}
                  onClick={() => setLightboxData({ isOpen: true, index: idx })}
                >
                  <img src={img} alt={`Galería ${data.name} ${idx + 1}`} className="img-cover" />
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {lightboxData.isOpen && (
        <Lightbox 
          images={data.gallery} 
          initialIndex={lightboxData.index}
          onClose={() => setLightboxData({ isOpen: false, index: 0 })} 
        />
      )}
    </div>
  );
};

export default SpaceDetail;
