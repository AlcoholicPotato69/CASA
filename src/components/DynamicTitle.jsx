import { useEffect, useLayoutEffect, useRef } from 'react';
import { useLocation } from 'react-router-dom';

/**
 * DynamicTitle.jsx
 * Controla el título del navegador de forma dinámica, elegante y en MAYÚSCULAS.
 * - En cada ruta muestra: "PÁGINA | CASA DE PIEDRA" (ej: "INICIO | CASA DE PIEDRA", "LA COCCINELLE | CASA DE PIEDRA")
 * - Al cambiar de pestaña en el navegador, activa una animación llamativa invitando al usuario a regresar.
 */
const routeTitles = {
  '/': 'INICIO | CASA DE PIEDRA',
  '/quienes-somos': 'QUIÉNES SOMOS | CASA DE PIEDRA',
  '/espacios': 'VENUES | CASA DE PIEDRA',
  '/venues': 'VENUES | CASA DE PIEDRA',
  '/espacios/salon-principal': 'SALÓN PRINCIPAL | CASA DE PIEDRA',
  '/espacios/jardin-principal': 'JARDÍN PRINCIPAL | CASA DE PIEDRA',
  '/espacios/salon-pavorreales': 'SALÓN PAVORREALES | CASA DE PIEDRA',
  '/espacios/terraza-mezquite': 'TERRAZA DEL MEZQUITE | CASA DE PIEDRA',
  '/restaurantes': 'RESTAURANTES | CASA DE PIEDRA',
  '/restaurantes/la-coccinelle': 'LA COCCINELLE | CASA DE PIEDRA',
  '/restaurantes/panteon-taurino': 'PANTEÓN TAURINO | CASA DE PIEDRA',
  '/restaurantes/la-bikina': 'LA BIKINA | CASA DE PIEDRA',
  '/restaurantes/rooftop': 'ROOFTOP | CASA DE PIEDRA',
  '/galeria': 'GALERÍA | CASA DE PIEDRA',
  '/eventos': 'EVENTOS | CASA DE PIEDRA',
  '/contacto': 'CONTACTO | CASA DE PIEDRA',
  '/terminos-y-condiciones': 'TÉRMINOS Y CONDICIONES | CASA DE PIEDRA',
};

const DynamicTitle = () => {
  const { pathname } = useLocation();
  const activeTitleRef = useRef('INICIO | CASA DE PIEDRA');
  const timerRef = useRef(null);

  useLayoutEffect(() => {
    // Determinar título activo en mayúsculas
    let title = routeTitles[pathname];
    if (!title) {
      if (pathname.startsWith('/espacios/')) {
        const slug = pathname.replace('/espacios/', '').replace(/-/g, ' ').toUpperCase();
        title = `${slug} | CASA DE PIEDRA`;
      } else if (pathname.startsWith('/restaurantes/')) {
        const slug = pathname.replace('/restaurantes/', '').replace(/-/g, ' ').toUpperCase();
        title = `${slug} | CASA DE PIEDRA`;
      } else {
        title = 'CASA DE PIEDRA | LEÓN, GTO.';
      }
    }

    activeTitleRef.current = title;
    if (!document.hidden) {
      document.title = title;
    }
  }, [pathname]);

  useEffect(() => {
    const hiddenMessages = [
      '✨ TE EXTRAÑAMOS | CASA DE PIEDRA',
      '👑 TU EVENTO TE ESPERA | CASA DE PIEDRA',
      '💎 REGRESA A LA ELEGANCIA | CASA DE PIEDRA'
    ];

    const handleVisibilityChange = () => {
      if (document.hidden) {
        let idx = 0;
        document.title = hiddenMessages[idx];
        timerRef.current = setInterval(() => {
          idx = (idx + 1) % hiddenMessages.length;
          document.title = hiddenMessages[idx];
        }, 2200);
      } else {
        if (timerRef.current) {
          clearInterval(timerRef.current);
          timerRef.current = null;
        }
        document.title = activeTitleRef.current;
      }
    };

    document.addEventListener('visibilitychange', handleVisibilityChange);
    return () => {
      document.removeEventListener('visibilitychange', handleVisibilityChange);
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, []);

  return null;
};

export default DynamicTitle;
