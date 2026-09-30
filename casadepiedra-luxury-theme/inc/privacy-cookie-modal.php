<?php
/**
 * Consentimiento de cookies: Aceptar, Rechazar o configurar categorías.
 */
$privacy_url = home_url('/aviso-de-privacidad');
?>

<div id="casa-privacy-banner" class="casa-privacy-banner" style="display: none;" role="dialog" aria-labelledby="casa-cookie-title" aria-modal="false">
    <div class="privacy-banner-content">
        <div class="privacy-banner-icon" aria-hidden="true">
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
            <h4 id="casa-cookie-title">Uso de Cookies y Aviso de Privacidad</h4>
            <p>
                Este sitio web utiliza cookies propias y de terceros para mejorar tu experiencia de navegación, analizar el tráfico del sitio y mostrarte publicidad personalizada. Puedes aceptar todas las cookies pulsando el botón 'Aceptar', rechazarlas o configurar tus preferencias.
            </p>
            <p class="privacy-banner-legal">
                <a href="<?php echo esc_url($privacy_url); ?>">Aviso de Privacidad</a>
            </p>
        </div>
        <div class="privacy-banner-actions">
            <button type="button" id="btn-privacy-accept" class="btn-privacy-accept">Aceptar</button>
            <button type="button" id="btn-privacy-deny" class="btn-privacy-deny">Rechazar</button>
            <button type="button" id="btn-privacy-configure" class="btn-privacy-configure">Configurar</button>
        </div>
    </div>

    <div id="casa-cookie-prefs" class="casa-cookie-prefs" hidden>
        <label class="casa-cookie-row is-locked">
            <span>
                <strong>Necesarias</strong>
                <em>Técnicas y de seguridad. El sitio no funciona sin ellas.</em>
            </span>
            <input type="checkbox" checked disabled>
        </label>
        <label class="casa-cookie-row">
            <span>
                <strong>Analíticas</strong>
                <em>Miden visitas y el uso del sitio (por ejemplo Google Analytics).</em>
            </span>
            <input type="checkbox" id="casa-cookie-analytics">
        </label>
        <label class="casa-cookie-row">
            <span>
                <strong>Publicidad</strong>
                <em>Permiten anuncios y contenido personalizado de terceros.</em>
            </span>
            <input type="checkbox" id="casa-cookie-ads">
        </label>
        <button type="button" id="btn-privacy-save" class="btn-privacy-accept btn-privacy-save">Guardar preferencias</button>
    </div>
</div>

<style>
.casa-privacy-banner {
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%) translateY(120%);
    width: calc(100% - 3rem);
    max-width: 960px;
    max-height: calc(100vh - 3rem);
    overflow-y: auto;
    background: rgba(14, 14, 14, 0.96);
    border: 1px solid rgba(193, 98, 30, 0.45);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.75), 0 0 20px rgba(193, 98, 30, 0.12);
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
.privacy-banner-text { flex-grow: 1; }
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
.privacy-banner-legal {
    margin-top: 0.45rem !important;
    font-size: 0.8rem !important;
}
.privacy-banner-text a {
    color: var(--color-accent);
    text-decoration: underline;
    font-weight: 500;
}
.privacy-banner-actions {
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
    flex-shrink: 0;
    min-width: 168px;
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
    box-shadow: 0 8px 20px rgba(193, 98, 30, 0.35);
}
.btn-privacy-deny,
.btn-privacy-configure {
    background: transparent;
    color: rgba(255, 255, 255, 0.78);
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
.btn-privacy-configure:hover {
    border-color: var(--color-accent);
    color: #fff;
}
.casa-cookie-prefs {
    margin-top: 1.15rem;
    padding-top: 1.1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: grid;
    gap: 0.7rem;
}
.casa-cookie-prefs[hidden] { display: none; }
.casa-cookie-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.75rem 0.9rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    cursor: pointer;
}
.casa-cookie-row.is-locked {
    opacity: 0.72;
    cursor: default;
}
.casa-cookie-row strong {
    display: block;
    color: #fff;
    font-size: 0.88rem;
    font-weight: 600;
}
.casa-cookie-row em {
    display: block;
    font-style: normal;
    color: rgba(255, 255, 255, 0.62);
    font-size: 0.78rem;
    line-height: 1.4;
    margin-top: 0.15rem;
}
.casa-cookie-row input {
    width: 18px;
    height: 18px;
    accent-color: var(--color-accent);
    flex-shrink: 0;
}
.btn-privacy-save { justify-self: end; }
@media (max-width: 768px) {
    .casa-privacy-banner {
        bottom: 0.75rem;
        width: calc(100% - 1.5rem);
        padding: 1.15rem 1.15rem 1.25rem;
    }
    .privacy-banner-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    .privacy-banner-actions {
        flex-direction: row;
        flex-wrap: wrap;
        width: 100%;
        min-width: 0;
    }
    .privacy-banner-actions button {
        flex: 1 1 30%;
        text-align: center;
        padding-left: 0.7rem;
        padding-right: 0.7rem;
    }
    .btn-privacy-save { width: 100%; justify-self: stretch; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var KEY = 'casa_cookie_consent';
    var LEGACY = 'casa_privacy_consent';
    var banner = document.getElementById('casa-privacy-banner');
    var prefsBox = document.getElementById('casa-cookie-prefs');
    var btnAccept = document.getElementById('btn-privacy-accept');
    var btnDeny = document.getElementById('btn-privacy-deny');
    var btnConfigure = document.getElementById('btn-privacy-configure');
    var btnSave = document.getElementById('btn-privacy-save');
    var chkAnalytics = document.getElementById('casa-cookie-analytics');
    var chkAds = document.getElementById('casa-cookie-ads');

    function readConsent() {
        try {
            var raw = localStorage.getItem(KEY);
            if (raw) {
                var parsed = JSON.parse(raw);
                if (parsed && typeof parsed === 'object') {
                    return {
                        necessary: true,
                        analytics: !!parsed.analytics,
                        ads: !!parsed.ads
                    };
                }
            }
            var legacy = localStorage.getItem(LEGACY);
            if (legacy === 'accepted') {
                return { necessary: true, analytics: true, ads: true };
            }
            if (legacy === 'denied') {
                return { necessary: true, analytics: false, ads: false };
            }
        } catch (e) {}
        return null;
    }

    function persist(prefs) {
        localStorage.setItem(KEY, JSON.stringify({
            v: 2,
            necessary: true,
            analytics: !!prefs.analytics,
            ads: !!prefs.ads,
            ts: Date.now()
        }));
        localStorage.removeItem(LEGACY);
        if (typeof window.gtag === 'function') {
            window.gtag('consent', 'update', {
                analytics_storage: prefs.analytics ? 'granted' : 'denied',
                ad_storage: prefs.ads ? 'granted' : 'denied',
                ad_user_data: prefs.ads ? 'granted' : 'denied',
                ad_personalization: prefs.ads ? 'granted' : 'denied',
                personalization_storage: prefs.ads ? 'granted' : 'denied',
                functionality_storage: 'granted',
                security_storage: 'granted'
            });
        }
    }

    function hideBanner() {
        if (!banner) return;
        banner.classList.remove('is-visible');
        setTimeout(function () {
            banner.style.display = 'none';
            if (prefsBox) prefsBox.hidden = true;
        }, 500);
    }

    function showBanner(openPrefs) {
        if (!banner) return;
        banner.style.display = 'block';
        requestAnimationFrame(function () {
            banner.classList.add('is-visible');
        });
        if (openPrefs && prefsBox) {
            prefsBox.hidden = false;
        }
    }

    function fillPrefs(prefs) {
        if (chkAnalytics) chkAnalytics.checked = !!(prefs && prefs.analytics);
        if (chkAds) chkAds.checked = !!(prefs && prefs.ads);
    }

    function currentPrefsFromUi() {
        return {
            necessary: true,
            analytics: !!(chkAnalytics && chkAnalytics.checked),
            ads: !!(chkAds && chkAds.checked)
        };
    }

    var stored = readConsent();
    if (stored) {
        persist(stored);
        fillPrefs(stored);
    } else {
        fillPrefs({ analytics: false, ads: false });
        showBanner(false);
    }

    if (btnAccept) {
        btnAccept.addEventListener('click', function () {
            persist({ necessary: true, analytics: true, ads: true });
            hideBanner();
        });
    }
    if (btnDeny) {
        btnDeny.addEventListener('click', function () {
            persist({ necessary: true, analytics: false, ads: false });
            hideBanner();
        });
    }
    if (btnConfigure) {
        btnConfigure.addEventListener('click', function () {
            if (!prefsBox) return;
            prefsBox.hidden = !prefsBox.hidden;
            if (!prefsBox.hidden) {
                fillPrefs(readConsent() || { analytics: false, ads: false });
            }
        });
    }
    if (btnSave) {
        btnSave.addEventListener('click', function () {
            persist(currentPrefsFromUi());
            hideBanner();
        });
    }

    window.casaOpenCookieSettings = function () {
        fillPrefs(readConsent() || { analytics: false, ads: false });
        showBanner(true);
    };
    document.querySelectorAll('[data-open-cookie-settings]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            window.casaOpenCookieSettings();
        });
    });
});
</script>
