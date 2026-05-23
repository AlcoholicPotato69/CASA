import React, { useRef, useEffect } from 'react';
import gsap from 'gsap';
import { ArrowUpRight } from 'lucide-react';

const MagneticButton = ({ children, onClick, className = '', href, target, variant = 'primary' }) => {
  const buttonRef = useRef(null);
  const textRef = useRef(null);
  const iconRef = useRef(null);

  useEffect(() => {
    const button = buttonRef.current;
    const text = textRef.current;
    const icon = iconRef.current;

    const xTo = gsap.quickTo(button, "x", { duration: 1, ease: "elastic.out(1, 0.3)" });
    const yTo = gsap.quickTo(button, "y", { duration: 1, ease: "elastic.out(1, 0.3)" });
    
    const textXTo = gsap.quickTo(text, "x", { duration: 1, ease: "elastic.out(1, 0.3)" });
    const textYTo = gsap.quickTo(text, "y", { duration: 1, ease: "elastic.out(1, 0.3)" });

    const handleMouseMove = (e) => {
      const { clientX, clientY } = e;
      const { height, width, left, top } = button.getBoundingClientRect();
      const x = clientX - (left + width / 2);
      const y = clientY - (top + height / 2);
      
      xTo(x * 0.3);
      yTo(y * 0.3);
      textXTo(x * 0.15);
      textYTo(y * 0.15);
      
      gsap.to(icon, { x: 4, y: -4, scale: 1.1, duration: 0.3, ease: "power2.out" });
    };

    const handleMouseLeave = () => {
      xTo(0);
      yTo(0);
      textXTo(0);
      textYTo(0);
      gsap.to(icon, { x: 0, y: 0, scale: 1, duration: 0.3, ease: "power2.out" });
    };

    button.addEventListener("mousemove", handleMouseMove);
    button.addEventListener("mouseleave", handleMouseLeave);

    return () => {
      button.removeEventListener("mousemove", handleMouseMove);
      button.removeEventListener("mouseleave", handleMouseLeave);
    };
  }, []);

  const isPrimary = variant === 'primary';

  const styles = {
    button: {
      position: 'relative',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      padding: '1.25rem 2.5rem',
      backgroundColor: isPrimary ? 'var(--color-accent)' : 'transparent',
      color: isPrimary ? '#FFF' : 'var(--color-text-primary)',
      border: isPrimary ? 'none' : '1px solid var(--color-border)',
      borderRadius: 'var(--radius-pill)',
      cursor: 'pointer',
      textDecoration: 'none',
      fontFamily: 'var(--font-body)',
      fontSize: '1rem',
      fontWeight: '600',
      transition: 'transform 0.2s cubic-bezier(0.32, 0.72, 0, 1), background-color 0.3s, box-shadow 0.3s',
      boxShadow: isPrimary ? '0 10px 30px rgba(204, 93, 0, 0.3)' : 'none',
    },
    buttonInner: {
      position: 'relative',
      zIndex: 1,
      display: 'flex',
      alignItems: 'center',
      gap: '1rem'
    },
    iconWrapper: {
      width: '36px',
      height: '36px',
      borderRadius: '50%',
      backgroundColor: isPrimary ? 'rgba(255,255,255,0.2)' : 'rgba(50,55,60,0.05)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      transition: 'background-color 0.3s'
    }
  };

  const handleMouseEnter = (e) => {
    if (isPrimary) {
      e.currentTarget.style.backgroundColor = 'var(--color-accent-hover)';
    }
  };

  const handleMouseLeaveOver = (e) => {
    if (isPrimary) {
      e.currentTarget.style.backgroundColor = 'var(--color-accent)';
    }
  };

  const content = (
    <span style={styles.buttonInner} ref={textRef}>
      {children}
      <span style={styles.iconWrapper} ref={iconRef}>
        <ArrowUpRight size={18} strokeWidth={2} />
      </span>
    </span>
  );

  if (href) {
    return (
      <a href={href} target={target} rel={target === '_blank' ? 'noopener noreferrer' : undefined} style={styles.button} ref={buttonRef} onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseLeaveOver} className={`group ${className}`}>
        {content}
      </a>
    );
  }

  return (
    <button onClick={onClick} style={styles.button} ref={buttonRef} onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseLeaveOver} className={`group ${className}`}>
      {content}
    </button>
  );
};

export default MagneticButton;
