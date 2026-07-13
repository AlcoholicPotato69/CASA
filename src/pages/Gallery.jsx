import React, { useEffect, useState } from 'react';
import gsap from 'gsap';
import Lightbox from '../components/Lightbox';

const Gallery = () => {
  const categories = [
    {
      id: 'eventos-especiales',
      title: 'Eventos Especiales',
      subtitle: 'Exclusividad & Distinción',
      cover: 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop',
      count: '12 fotografías',
      images: [
        'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1478146896981-b80fe463b330?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop'
      ]
    },
    {
      id: 'graduaciones',
      title: 'Graduaciones',
      subtitle: 'Gala & Celebración',
      cover: '/images/salon_pavorreales_1779523097528.png',
      count: '15 fotografías',
      images: [
        '/images/salon_pavorreales_1779523097528.png',
        'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1545232972-9bbf0498845e?q=80&w=2070&auto=format&fit=crop'
      ]
    },
    {
      id: 'sociales',
      title: 'Sociales',
      subtitle: 'Aniversarios & Banquetes',
      cover: '/images/jardin_principal_1779523113451.png',
      count: '14 fotografías',
      images: [
        '/images/jardin_principal_1779523113451.png',
        '/images/terraza_mezquite_1779523084857.png',
        'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1519225336804-91fe1f4be143?q=80&w=2070&auto=format&fit=crop'
      ]
    },
    {
      id: 'empresariales',
      title: 'Empresariales',
      subtitle: 'Congresos & Galas',
      cover: '/images/terraza_mezquite_1779523084857.png',
      count: '16 fotografías',
      images: [
        'https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=2069&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=2012&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069&auto=format&fit=crop'
      ]
    },
    {
      id: 'bodas',
      title: 'Bodas',
      subtitle: 'Ceremonias & Recepciones',
      cover: '/images/salon_principal_1779523069698.png',
      count: '18 fotografías',
      images: [
        '/images/salon_principal_1779523069698.png',
        '/images/jardin_principal_1779523113451.png',
        'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2069&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=2070&auto=format&fit=crop'
      ]
    }
  ];

  const [selectedCategory, setSelectedCategory] = useState(null);
  const [activeFilterTag, setActiveFilterTag] = useState(null);
  const [selectedImage, setSelectedImage] = useState(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.gallery-tag-card', {
        y: 60,
        opacity: 0,
        duration: 1.1,
        stagger: 0.15,
        ease: 'power3.out'
      });
    });
    return () => ctx.revert();
  }, [selectedCategory]);

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [selectedCategory]);

  const handleSelectCategoryCard = (cat) => {
    setSelectedCategory(cat);
    setActiveFilterTag(cat.id);
  };

  const currentCategoryObj = activeFilterTag === 'all'
    ? {
        id: 'all',
        title: 'Todas las Fotografías',
        subtitle: 'Colección General',
        images: categories.flatMap(c => c.images)
      }
    : (categories.find(c => c.id === activeFilterTag) || selectedCategory);

  const filterOptions = [
    { id: 'all', label: 'Todas las fotografías' },
    { id: 'bodas', label: 'Bodas' },
    { id: 'graduaciones', label: 'Graduaciones' },
    { id: 'sociales', label: 'Sociales' },
    { id: 'empresariales', label: 'Empresariales' },
    { id: 'eventos-especiales', label: 'Eventos Especiales' }
  ];

  return (
    <div style={{ paddingTop: 'clamp(7rem, 14vh, 10rem)', paddingBottom: '6rem' }}>
      <div className="container">
        {!selectedCategory ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6" style={{ width: '100%' }}>
              {categories.map((cat, index) => {
                const colSpanClass = index < 3 ? 'lg:col-span-2 sm:col-span-1 col-span-1' : 'lg:col-span-3 sm:col-span-2 col-span-1';
                return (
                  <div
                    key={cat.id}
                    className={`gallery-tag-card luxury-card group ${colSpanClass}`}
                    onClick={() => handleSelectCategoryCard(cat)}
                    style={{
                      position: 'relative',
                      height: 'clamp(220px, 28vh, 300px)',
                      cursor: 'pointer',
                      overflow: 'hidden',
                      borderRadius: '16px',
                      backgroundColor: '#111111',
                      border: '1px solid rgba(212,175,55,0.22)',
                      display: 'flex',
                      flexDirection: 'column',
                      justifyContent: 'flex-end',
                      padding: '1.8rem',
                      boxShadow: '0 15px 45px rgba(0,0,0,0.8)',
                      zIndex: 1,
                      transition: 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.transform = 'translateY(-6px)';
                      e.currentTarget.style.borderColor = 'rgba(212,175,55,0.65)';
                      e.currentTarget.style.boxShadow = '0 25px 60px rgba(0,0,0,0.95), 0 0 35px rgba(212,175,55,0.2)';
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.transform = 'translateY(0)';
                      e.currentTarget.style.borderColor = 'rgba(212,175,55,0.22)';
                      e.currentTarget.style.boxShadow = '0 15px 45px rgba(0,0,0,0.8)';
                    }}
                  >
                    <div style={{ position: 'absolute', inset: 0, overflow: 'hidden', zIndex: 0 }}>
                      <img
                        src={cat.cover}
                        alt={cat.title}
                        style={{
                          width: '100%',
                          height: '100%',
                          objectFit: 'cover'
                        }}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                      />
                    </div>
                    <div style={{
                      position: 'absolute',
                      inset: 0,
                      background: 'linear-gradient(to top, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0.3) 50%, rgba(10,10,10,0.1) 100%)',
                      zIndex: 1,
                      pointerEvents: 'none'
                    }} />

                    <div style={{
                      position: 'relative',
                      zIndex: 2,
                      display: 'flex',
                      justifyContent: 'space-between',
                      alignItems: 'flex-end',
                      width: '100%'
                    }}>
                      <h3 className="text-h3" style={{ fontSize: 'clamp(1.6rem, 2.2vw, 2.1rem)', margin: 0, color: '#ffffff', textShadow: '0 2px 10px rgba(0,0,0,0.6)' }}>
                        {cat.title}
                      </h3>
                      
                      <div
                        style={{
                          width: '42px',
                          height: '42px',
                          borderRadius: '50%',
                          background: 'var(--color-accent)',
                          color: '#000',
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          transform: 'translateX(-10px) translateY(10px) scale(0.6)',
                          opacity: 0,
                          transition: 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)',
                          flexShrink: 0,
                          marginLeft: '1rem'
                        }}
                        className="group-hover:opacity-100 group-hover:translate-x-0 group-hover:translate-y-0 group-hover:scale-100"
                      >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                          <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
        ) : (
          <>
            <div style={{ marginBottom: '2.5rem' }}>
              <button
                onClick={() => setSelectedCategory(null)}
                style={{
                  background: 'rgba(17,17,17,0.9)',
                  border: '1px solid rgba(212,175,55,0.35)',
                  color: 'var(--color-accent)',
                  padding: '0.7rem 1.4rem',
                  borderRadius: '999px',
                  cursor: 'pointer',
                  fontSize: '0.88rem',
                  fontWeight: 600,
                  letterSpacing: '0.6px',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '0.6rem',
                  transition: 'all 0.3s ease'
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.background = 'var(--color-accent)';
                  e.currentTarget.style.color = '#000000';
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.background = 'rgba(17,17,17,0.9)';
                  e.currentTarget.style.color = 'var(--color-accent)';
                }}
              >
                <span>←</span> Volver a Categorías
              </button>
            </div>

            <div style={{ marginBottom: '3rem', textAlign: 'center' }}>
              <span className="text-script" style={{ fontSize: '2.4rem', color: 'var(--color-accent)' }}>
                {currentCategoryObj ? currentCategoryObj.subtitle : ''}
              </span>
              <h2 className="text-hero" style={{ marginTop: '0.4rem', fontSize: 'clamp(2.2rem, 4.5vw, 3.4rem)' }}>
                {currentCategoryObj ? currentCategoryObj.title : ''}
              </h2>
            </div>

            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))',
              gap: '2.5rem',
              alignItems: 'start'
            }}
            className="gallery-subpage-container"
            >
              {/* ÁREA DE FOTOGRAFÍAS */}
              <div style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
                gap: '1.5rem'
              }}>
                {currentCategoryObj && currentCategoryObj.images && currentCategoryObj.images.map((src, idx) => (
                  <div
                    key={idx}
                    className="gallery-item luxury-card"
                    style={{
                      height: idx % 3 === 0 ? '380px' : '300px',
                      cursor: 'zoom-in',
                      overflow: 'hidden',
                      borderRadius: '14px',
                      backgroundColor: '#111111',
                      border: '1px solid rgba(212,175,55,0.18)',
                      position: 'relative',
                      zIndex: 1
                    }}
                    onClick={() => setSelectedImage(src)}
                  >
                    <img src={src} alt={`${currentCategoryObj.title} ${idx + 1}`} className="img-cover hover:scale-105 transition-transform duration-700" />
                  </div>
                ))}
              </div>

              {/* PANEL LATERAL DERECHO CON CHECKBOXES (SINGLE SELECT) */}
              <aside
                className="luxury-card"
                style={{
                  backgroundColor: '#111111',
                  border: '1px solid rgba(212,175,55,0.3)',
                  borderRadius: '16px',
                  padding: '2rem',
                  position: 'sticky',
                  top: '110px',
                  zIndex: 2,
                  boxShadow: '0 15px 40px rgba(0,0,0,0.8)'
                }}
              >
                <div style={{ marginBottom: '1.5rem', borderBottom: '1px solid rgba(255,255,255,0.1)', paddingBottom: '1rem' }}>
                  <span style={{
                    fontSize: '0.75rem',
                    textTransform: 'uppercase',
                    letterSpacing: '2px',
                    color: 'var(--color-accent)',
                    fontWeight: 600,
                    display: 'block',
                    marginBottom: '0.4rem'
                  }}>
                    Filtrar por Etiqueta
                  </span>
                  <h4 style={{
                    fontSize: '1.25rem',
                    color: '#ffffff',
                    fontFamily: 'var(--font-heading)',
                    margin: 0
                  }}>
                    Categorías
                  </h4>
                </div>

                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.85rem' }}>
                  {filterOptions.map((opt) => {
                    const isChecked = activeFilterTag === opt.id;
                    return (
                      <label
                        key={opt.id}
                        style={{
                          display: 'flex',
                          alignItems: 'center',
                          gap: '0.75rem',
                          cursor: 'pointer',
                          padding: '0.65rem 0.85rem',
                          borderRadius: '8px',
                          backgroundColor: isChecked ? 'rgba(212,175,55,0.12)' : 'transparent',
                          border: isChecked ? '1px solid rgba(212,175,55,0.4)' : '1px solid transparent',
                          color: isChecked ? '#ffffff' : 'rgba(255,255,255,0.72)',
                          transition: 'all 0.25s ease',
                          userSelect: 'none'
                        }}
                        onClick={(e) => {
                          e.preventDefault();
                          setActiveFilterTag(opt.id);
                        }}
                      >
                        <input
                          type="checkbox"
                          checked={isChecked}
                          onChange={() => setActiveFilterTag(opt.id)}
                          style={{
                            accentColor: '#d4af37',
                            width: '18px',
                            height: '18px',
                            cursor: 'pointer'
                          }}
                        />
                        <span style={{ fontSize: '0.95rem', fontWeight: isChecked ? 600 : 400 }}>
                          {opt.label}
                        </span>
                      </label>
                    );
                  })}
                </div>

                <div style={{
                  marginTop: '1.5rem',
                  paddingTop: '1rem',
                  borderTop: '1px solid rgba(255,255,255,0.08)',
                  fontSize: '0.8rem',
                  color: 'rgba(255,255,255,0.5)',
                  lineHeight: 1.4
                }}>
                  Selecciona una etiqueta para ver instantáneamente su colección fotográfica.
                </div>
              </aside>
            </div>
          </>
        )}
      </div>

      {selectedImage && currentCategoryObj && currentCategoryObj.images && (
        <Lightbox
          images={currentCategoryObj.images}
          initialIndex={currentCategoryObj.images.indexOf(selectedImage)}
          onClose={() => setSelectedImage(null)}
        />
      )}
    </div>
  );
};

export default Gallery;
