<?php
/**
 * Privacy & Cookie Consent Pop-up Modal + Navigation Lock
 * Casa de Piedra - Grupo AlCon
 */
?>

<!-- 1. Cookie & Privacy Banner Pop-up -->
<div id="casa-privacy-banner" class="casa-privacy-banner" style="display: none;">
    <div class="privacy-banner-content">
        <div class="privacy-banner-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path>
                <path d="M8.5 8.5v.01"></path>
                <path d="M16 15.5v.01"></path>
                <path d="M12 12v.01"></path>
                <path d="M11 17v.01"></path>
                <path d="M7 14v.01"></path>
            </svg>
        </div>
        <div class="privacy-banner-text">
            <h4>Uso de Cookies y Aviso de Privacidad</h4>
            <p>
                En <strong>Casa de Piedra</strong> (Grupo AlCon) utilizamos cookies propias y herramientas de análisis/rastreo de Google para optimizar su experiencia web, personalizar contenido y fines estadísticos. Al continuar navegando en nuestro sitio web, usted consiente el tratamiento de su información de conformidad con nuestro <a href="<?php echo esc_url(home_url('/aviso-de-privacidad')); ?>">Aviso de Privacidad</a>.
            </p>
        </div>
        <div class="privacy-banner-actions">
            <button id="btn-privacy-accept" class="btn-privacy-accept">
                Aceptar y Continuar
            </button>
            <button id="btn-privacy-deny" class="btn-privacy-deny">
                Rechazar
            </button>
        </div>
    </div>
</div>

<!-- 2. Blocked Navigation Overlay (When user explicitly denies cookies/privacy) -->
<div id="casa-privacy-blocked" class="casa-privacy-blocked" style="display: none;">
    <div class="privacy-blocked-modal">
        <div class="privacy-blocked-shield">
            <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="1.4">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
            </svg>
        </div>
        <span class="privacy-blocked-tag">Navegación Restringida</span>
        <h3>Aviso de Privacidad Requerido</h3>
        <p>
            Para garantizar la seguridad técnica, el correcto funcionamiento del sitio web y dar cumplimiento a la legislación en materia de protección de datos de <strong>Grupo AlCon</strong>, es necesario aceptar nuestro Aviso de Privacidad y el uso de cookies funcionales y de análisis para poder navegar por el portal.
        </p>
        <div class="privacy-blocked-btns">
            <button id="btn-privacy-unblock" class="btn-privacy-accept">
                Aceptar Aviso de Privacidad y Navegar
            </button>
            <a href="<?php echo esc_url(home_url('/aviso-de-privacidad')); ?>" class="btn-privacy-link">
                Consultar Aviso de Privacidad Completo
            </a>
        </div>
    </div>
</div>

<style>
/* Cookie Banner Styles */
.casa-privacy-banner {
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%) translateY(120%);
    width: calc(100% - 3rem);
    max-width: 960px;
    background: rgba(14, 14, 14, 0.96);
    border: 1px solid rgba(212, 175, 55, 0.45);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.75), 0 0 20px rgba(212, 175, 55, 0.12);
    z-index: 99999;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease;
}

.casa-privacy-banner.is-visible {
    transform: translateX(-50%) translateY(0%);
}

.privacy-banner-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.privacy-banner-icon {
    color: var(--color-accent);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.privacy-banner-text {
    flex-grow: 1;
}

.privacy-banner-text h4 {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    color: #fff;
    margin: 0 0 0.4rem 0;
    font-weight: 500;
}

.privacy-banner-text p {
    font-size: 0.88rem;
    color: rgba(255, 255, 255, 0.82);
    line-height: 1.55;
    margin: 0;
}

.privacy-banner-text p a {
    color: var(--color-accent);
    text-decoration: underline;
    font-weight: 500;
}

.privacy-banner-actions {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    flex-shrink: 0;
}

.btn-privacy-accept {
    background: linear-gradient(135deg, var(--color-accent) 0%, #b89728 100%);
    color: #0c0c0c;
    border: none;
    padding: 0.75rem 1.6rem;
    border-radius: 999px;
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    white-space: nowrap;
}

.btn-privacy-accept:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212, 175, 55, 0.35);
}

.btn-privacy-deny {
    background: transparent;
    color: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 0.6rem 1.4rem;
    border-radius: 999px;
    font-family: var(--font-heading);
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.25s ease;
    white-space: nowrap;
}

.btn-privacy-deny:hover {
    border-color: #ff5252;
    color: #ff5252;
}

/* Blocked Overlay Styles */
.casa-privacy-blocked {
    position: fixed;
    inset: 0;
    background: rgba(8, 8, 8, 0.96);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
}

.privacy-blocked-modal {
    background: rgba(18, 18, 18, 0.9);
    border: 1px solid rgba(212, 175, 55, 0.4);
    border-radius: 20px;
    max-width: 520px;
    width: 100%;
    padding: 3rem 2.5rem;
    text-align: center;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85);
}

.privacy-blocked-shield {
    margin-bottom: 1.25rem;
}

.privacy-blocked-tag {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--color-accent);
    display: block;
    margin-bottom: 0.5rem;
}

.privacy-blocked-modal h3 {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    color: #fff;
    margin: 0 0 1rem 0;
}

.privacy-blocked-modal p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
    line-height: 1.65;
    margin-bottom: 2rem;
}

.privacy-blocked-btns {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
}

.btn-privacy-link {
    color: var(--color-accent);
    font-size: 0.9rem;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .privacy-banner-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    .privacy-banner-actions {
        flex-direction: row;
        width: 100%;
    }
    .btn-privacy-accept, .btn-privacy-deny {
        flex: 1;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('casa-privacy-banner');
    const blockedOverlay = document.getElementById('casa-privacy-blocked');
    const btnAccept = document.getElementById('btn-privacy-accept');
    const btnDeny = document.getElementById('btn-privacy-deny');
    const btnUnblock = document.getElementById('btn-privacy-unblock');

    const currentPath = window.location.pathname.replace(/\/$/, "");
    const isPrivacyPage = currentPath.endsWith('/aviso-de-privacidad') || currentPath.endsWith('/terminos-y-condiciones');

    // Retrieve consent state
    const consent = localStorage.getItem('casa_privacy_consent');

    // Helper: accept consent
    const acceptConsent = () => {
        localStorage.setItem('casa_privacy_consent', 'accepted');
        if (banner) {
            banner.classList.remove('is-visible');
            setTimeout(() => banner.style.display = 'none', 600);
        }
        if (blockedOverlay) {
            blockedOverlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    };

    // Helper: deny consent
    const denyConsent = () => {
        localStorage.setItem('casa_privacy_consent', 'denied');
        if (banner) {
            banner.classList.remove('is-visible');
            setTimeout(() => banner.style.display = 'none', 600);
        }
        if (!isPrivacyPage && blockedOverlay) {
            blockedOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };

    // Check existing state
    if (consent === 'denied' && !isPrivacyPage) {
        if (blockedOverlay) {
            blockedOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    } else if (!consent) {
        // Show initial pop-up
        if (banner) {
            banner.style.display = 'block';
            setTimeout(() => {
                banner.classList.add('is-visible');
            }, 300);
        }

        // Implicit consent on scroll (> 150px) or interaction with the site
        let implicitTriggered = false;
        const handleImplicitConsent = () => {
            if (implicitTriggered || localStorage.getItem('casa_privacy_consent')) return;
            if (window.scrollY > 150) {
                implicitTriggered = true;
                acceptConsent();
                window.removeEventListener('scroll', handleImplicitConsent);
            }
        };

        window.addEventListener('scroll', handleImplicitConsent, { passive: true });
    }

    // Button event listeners
    if (btnAccept) {
        btnAccept.addEventListener('click', acceptConsent);
    }
    if (btnDeny) {
        btnDeny.addEventListener('click', denyConsent);
    }
    if (btnUnblock) {
        btnUnblock.addEventListener('click', acceptConsent);
    }
});
</script>
