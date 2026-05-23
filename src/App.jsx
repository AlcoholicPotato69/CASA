import React, { useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import Lenis from '@studio-freight/lenis';
import Navigation from './components/Navigation';
import Footer from './components/Footer';
import WhatsAppButton from './components/WhatsAppButton';

import Home from './pages/Home';
import About from './pages/About';
import Spaces from './pages/Spaces';
import SpaceDetail from './pages/SpaceDetail';
import Events from './pages/Events';
import Gallery from './pages/Gallery';
import Contact from './pages/Contact';
import Restaurants from './pages/Restaurants';
import RestaurantDetail from './pages/RestaurantDetail';

const ScrollToTop = () => {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);
  return null;
};

// Placeholder for Terms and Conditions
const Terms = () => (
  <div style={{ paddingTop: '10rem', paddingBottom: '6rem' }} className="container text-center">
    <h1 className="text-h2">Términos y Condiciones</h1>
    <p className="text-body-lg mt-4">Documento legal en construcción.</p>
  </div>
);

/**
 * App.jsx - Componente Raíz de la Aplicación
 * 
 * Configura el enrutamiento (React Router), el control global de scroll suave (Lenis) 
 * y estructura la disposición general del sitio web (Navegación, Rutas y Pie de Página).
 * 
 * Dependencias principales:
 * - @studio-freight/lenis: Para animaciones de scroll fluido tipo "smooth scroll".
 * - react-router-dom: Para la navegación sin recargas entre páginas (SPA).
 */
function App() {
  useEffect(() => {
    const lenis = new Lenis({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      direction: 'vertical',
      gestureDirection: 'vertical',
      smooth: true,
      mouseMultiplier: 1,
      smoothTouch: false,
      touchMultiplier: 2,
      infinite: false,
    });

    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    return () => {
      lenis.destroy();
    };
  }, []);

  return (
    <Router>
      <ScrollToTop />
      <div className="noise-overlay" />
      <Navigation />
      <WhatsAppButton />
      <main style={{ minHeight: '80vh' }}>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/quienes-somos" element={<About />} />
          <Route path="/espacios" element={<Spaces />} />
          <Route path="/espacios/:id" element={<SpaceDetail />} />
          <Route path="/eventos" element={<Events />} />
          <Route path="/galeria" element={<Gallery />} />
          <Route path="/contacto" element={<Contact />} />
          <Route path="/restaurantes" element={<Restaurants />} />
          <Route path="/restaurantes/:id" element={<RestaurantDetail />} />
          <Route path="/terminos-y-condiciones" element={<Terms />} />
        </Routes>
      </main>
      <Footer />
    </Router>
  );
}

export default App;
