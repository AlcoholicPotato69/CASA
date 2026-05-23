import React, { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Link } from 'react-router-dom';
import SpaceCard from '../components/SpaceCard';

gsap.registerPlugin(ScrollTrigger);

const Spaces = () => {
  const sectionRef = useRef(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      const cards = gsap.utils.toArray('.space-card-wrapper');
      
      cards.forEach((card, i) => {
        gsap.from(card, {
          y: 100,
          opacity: 0,
          duration: 1,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: card,
            start: 'top 85%',
          }
        });
      });
    }, sectionRef);

    return () => ctx.revert();
  }, []);

  const spacesData = [
    {
      id: "salon-principal",
      title: "Salón Principal",
      capacity: "1,000",
      image: "/images/salon_principal_1779523069698.png",
      isLarge: true
    },
    {
      id: "terraza-mezquite",
      title: "Terraza del Mezquite",
      capacity: "200",
      image: "/images/terraza_mezquite_1779523084857.png",
      isLarge: false
    },
    {
      id: "salon-pavorreales",
      title: "Salón Pavorreales",
      capacity: "100",
      image: "/images/salon_pavorreales_1779523097528.png",
      isLarge: false
    },
    {
      id: "jardin-principal",
      title: "Jardín Principal",
      capacity: "2,000",
      image: "/images/jardin_principal_1779523113451.png",
      isLarge: true
    }
  ];

  return (
    <div style={{ paddingTop: '12rem', paddingBottom: '6rem' }}>
      <section className="section" ref={sectionRef}>
        <div className="container">
          <div style={{ marginBottom: '4rem', textAlign: 'center' }}>
            <span className="text-script" style={{ fontSize: '3rem' }}>Nuestros Espacios</span>
            <h2 className="text-h2" style={{ marginTop: '1rem' }}>Escenarios majestuosos.</h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-12 gap-8">
            {spacesData.map((space, index) => (
              <div key={index} className={`space-card-wrapper ${space.isLarge ? 'md:col-span-8' : 'md:col-span-4'}`}>
                <Link to={`/espacios/${space.id}`} style={{ textDecoration: 'none', display: 'block', height: '100%' }}>
                  <SpaceCard {...space} />
                </Link>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default Spaces;
