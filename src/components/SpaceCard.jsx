import React from 'react';

const SpaceCard = ({ title, capacity, image, isLarge }) => {
  return (
    <div className={`luxury-card group`} style={{ height: isLarge ? '500px' : '400px', display: 'flex', flexDirection: 'column' }}>
      <img 
        src={image} 
        alt={title} 
        className="img-cover group-hover:scale-105" 
        loading="lazy"
        style={{ position: 'absolute', inset: 0, zIndex: 0 }}
      />
      
      {/* Soft dark gradient at bottom for text readability */}
      <div 
        style={{
          position: 'absolute',
          inset: 0,
          background: 'linear-gradient(to top, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0) 70%)',
          zIndex: 1,
          pointerEvents: 'none'
        }}
      />

      <div style={{
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        padding: '2rem',
        zIndex: 2,
        color: 'var(--color-text-primary)'
      }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end' }}>
          <div>
            <span style={{ 
              display: 'inline-block', 
              padding: '4px 12px', 
              borderRadius: 'var(--radius-pill)', 
              background: 'rgba(255,255,255,0.1)',
              backdropFilter: 'blur(8px)',
              fontSize: '0.75rem',
              fontWeight: '600',
              letterSpacing: '0.05em',
              marginBottom: '1rem',
              textTransform: 'uppercase',
              color: 'var(--color-accent)'
            }}>
              Hasta {capacity} personas
            </span>
            <h3 className="text-h3" style={{ margin: 0, textShadow: '0 2px 10px rgba(0,0,0,0.5)' }}>
              {title}
            </h3>
          </div>
          
          <div style={{
            width: '40px',
            height: '40px',
            borderRadius: '50%',
            background: 'var(--color-accent)',
            color: '#000',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            transform: 'translateX(-10px) translateY(10px) scale(0)',
            opacity: 0,
            transition: 'all 0.4s var(--ease-elastic)'
          }} className="group-hover:opacity-100 group-hover:transform-none">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SpaceCard;
