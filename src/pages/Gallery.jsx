import React, { useEffect, useState } from 'react';
import gsap from 'gsap';
import Lightbox from '../components/Lightbox';

const Gallery = () => {
  const images = [
    "/images/salon_principal_1779523069698.png",
    "/images/terraza_mezquite_1779523084857.png",
    "/images/salon_pavorreales_1779523097528.png",
    "/images/jardin_principal_1779523113451.png",
    "https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2069&auto=format&fit=crop", // Placeholder event
    "https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop"  // Placeholder decor
  ];

  const [selectedImage, setSelectedImage] = useState(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from('.gallery-item', {
        y: 50,
        opacity: 0,
        duration: 1,
        stagger: 0.1,
        ease: 'power3.out',
        delay: 0.2
      });
    });
    return () => ctx.revert();
  }, []);

  return (
    <div style={{ paddingTop: 'clamp(8rem, 15vh, 12rem)', paddingBottom: '6rem' }}>
      <div className="container">
        <div style={{ marginBottom: '4rem', textAlign: 'center' }}>
          <span className="text-script" style={{ fontSize: '3rem' }}>Galería</span>
          <h2 className="text-h2" style={{ marginTop: '1rem' }}>Momentos inolvidables.</h2>
        </div>

        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))',
          gap: '1.5rem'
        }}>
          {images.map((src, idx) => (
            <div 
              key={idx} 
              className="gallery-item luxury-card" 
              style={{ height: '300px', cursor: 'zoom-in' }}
              onClick={() => setSelectedImage(src)}
            >
              <img src={src} alt={`Gallery ${idx}`} className="img-cover" />
            </div>
          ))}
        </div>
      </div>

      {selectedImage && (
        <Lightbox 
          src={selectedImage} 
          onClose={() => setSelectedImage(null)} 
        />
      )}
    </div>
  );
};

export default Gallery;
