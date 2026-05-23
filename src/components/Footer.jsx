import React from 'react';
import { Link } from 'react-router-dom';
import { MapPin, Phone, Mail } from 'lucide-react';

const Footer = () => {
  return (
    <footer style={{ backgroundColor: '#050505', borderTop: '1px solid var(--color-border)', paddingTop: '4rem', paddingBottom: '2rem' }}>
      <div className="container">
        <div className="grid md:grid-cols-12 gap-12" style={{ paddingBottom: '4rem' }}>
          
          {/* Brand & Socials */}
          <div className="md:col-span-4" style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
            <img 
              src="https://casadepiedraleon.mx/wp-content/uploads/2023/09/Logo_Footer.png" 
              alt="Casa de Piedra" 
              style={{ width: '180px', opacity: 0.9 }} 
            />
            <p className="text-body-lg" style={{ fontSize: '0.9rem', maxWidth: '300px' }}>
              La sede más emblemática de León, Guanajuato. Eventos sociales y empresariales de alto nivel desde 1845.
            </p>
            <div style={{ display: 'flex', gap: '1rem', marginTop: '1rem' }}>
              <a href="https://instagram.com" target="_blank" rel="noreferrer" style={{ color: 'var(--color-accent)', padding: '0.5rem 0.8rem', border: '1px solid var(--color-border)', borderRadius: 'var(--radius-pill)', textDecoration: 'none', fontFamily: 'var(--font-heading)', fontWeight: 600 }}>
                Instagram
              </a>
              <a href="https://facebook.com" target="_blank" rel="noreferrer" style={{ color: 'var(--color-accent)', padding: '0.5rem 0.8rem', border: '1px solid var(--color-border)', borderRadius: 'var(--radius-pill)', textDecoration: 'none', fontFamily: 'var(--font-heading)', fontWeight: 600 }}>
                Facebook
              </a>
            </div>
          </div>

          {/* Contact Info */}
          <div className="md:col-span-4" style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
            <h4 style={{ color: 'var(--color-text-primary)', fontSize: '1.2rem', fontFamily: 'var(--font-heading)' }}>Contacto</h4>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
              <div style={{ display: 'flex', gap: '1rem', alignItems: 'flex-start', color: 'var(--color-text-secondary)' }}>
                <MapPin size={18} style={{ color: 'var(--color-accent)', flexShrink: 0, marginTop: '4px' }} />
                <span>Blvd. Juan Alonso de Torres 2002, Col. Valle del Campestre, León, Gto.</span>
              </div>
              <div style={{ display: 'flex', gap: '1rem', alignItems: 'center', color: 'var(--color-text-secondary)' }}>
                <Phone size={18} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                <a href="tel:+524777172600" style={{ color: 'inherit', textDecoration: 'none' }}>477 717 2600 (Ext. 502)</a>
              </div>
              <div style={{ display: 'flex', gap: '1rem', alignItems: 'center', color: 'var(--color-text-secondary)' }}>
                <Mail size={18} style={{ color: 'var(--color-accent)', flexShrink: 0 }} />
                <a href="mailto:eventos@casadepiedraleon.mx" style={{ color: 'inherit', textDecoration: 'none' }}>eventos@casadepiedraleon.mx</a>
              </div>
            </div>
          </div>

          {/* Quick Links */}
          <div className="md:col-span-4" style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
            <h4 style={{ color: 'var(--color-text-primary)', fontSize: '1.2rem', fontFamily: 'var(--font-heading)' }}>Enlaces</h4>
            <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
              <li><Link to="/quienes-somos" style={{ color: 'var(--color-text-secondary)', textDecoration: 'none' }}>Quiénes Somos</Link></li>
              <li><Link to="/espacios" style={{ color: 'var(--color-text-secondary)', textDecoration: 'none' }}>Espacios</Link></li>
              <li><Link to="/restaurantes" style={{ color: 'var(--color-text-secondary)', textDecoration: 'none' }}>Restaurantes</Link></li>
              <li><Link to="/terminos-y-condiciones" style={{ color: 'var(--color-text-secondary)', textDecoration: 'none' }}>Términos y Condiciones</Link></li>
            </ul>
          </div>
        </div>

        <div style={{ borderTop: '1px solid var(--color-border)', paddingTop: '2rem', display: 'flex', flexDirection: 'column', md: { flexDirection: 'row' }, justifyContent: 'space-between', alignItems: 'center', gap: '1rem', textAlign: 'center', color: 'var(--color-text-secondary)', fontSize: '0.8rem' }}>
          <div>COPYRIGHT © {new Date().getFullYear()} CASA DE PIEDRA. TODOS LOS DERECHOS RESERVADOS.</div>
          <Link to="/terminos-y-condiciones" style={{ color: 'inherit', textDecoration: 'underline' }}>Aviso de Privacidad</Link>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
