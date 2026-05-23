import React from 'react';
import { MessageCircle } from 'lucide-react';

const WhatsAppButton = () => {
  const phoneNumber = "524772892521"; // Number extracted from search research
  const message = "Hola, me gustaría recibir información sobre la renta de espacios para un evento en Casa de Piedra.";
  const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

  return (
    <a 
      href={url} 
      target="_blank" 
      rel="noreferrer"
      style={{
        position: 'fixed',
        bottom: '2rem',
        right: '2rem',
        width: '60px',
        height: '60px',
        backgroundColor: '#25D366',
        color: '#FFF',
        borderRadius: '50%',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        boxShadow: '0 4px 14px rgba(37, 211, 102, 0.4)',
        zIndex: 9999,
        transition: 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)'
      }}
      className="hover:scale-110"
      aria-label="Contactar por WhatsApp"
    >
      <MessageCircle size={32} />
    </a>
  );
};

export default WhatsAppButton;
