<?php
/**
 * Modal visor de planos PDF.
 * Los botones .js-open-pdf-modal abren el PDF en pantalla; la descarga es opcional.
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="casa-pdf-modal-overlay" hidden data-lenis-prevent="true" role="dialog" aria-modal="true" aria-labelledby="casa-pdf-modal-title">
    <style>
        #casa-pdf-modal-overlay {
            position: fixed !important;
            inset: 0 !important;
            z-index: 9999998 !important;
            background: rgba(0,0,0,0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            box-sizing: border-box;
        }
        #casa-pdf-modal-overlay.is-open {
            display: flex !important;
        }
        .casa-pdf-modal {
            width: min(1120px, 100%);
            height: min(90vh, 920px);
            background: #0e0e0e;
            border: 1px solid rgba(193,98,30,0.45);
            border-radius: 18px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.95), 0 0 40px rgba(193,98,30,0.18);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .casa-pdf-modal-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid rgba(193,98,30,0.28);
            background: linear-gradient(90deg, rgba(20,20,20,0.98), rgba(35,30,15,0.98));
        }
        .casa-pdf-modal-bar h3 {
            margin: 0;
            color: var(--color-accent, #c1621e);
            font-family: var(--font-heading, Georgia, serif);
            font-size: clamp(1.05rem, 2.4vw, 1.35rem);
            font-weight: 500;
            line-height: 1.25;
        }
        .casa-pdf-modal-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .casa-pdf-modal-actions a,
        .casa-pdf-modal-actions button {
            font-family: var(--font-body, Inter, sans-serif);
            font-size: 0.78rem;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            cursor: pointer;
            border: 1px solid rgba(193,98,30,0.4);
            color: #fff;
            background: rgba(255,255,255,0.06);
            transition: background 0.2s, color 0.2s;
        }
        .casa-pdf-modal-actions a:hover,
        .casa-pdf-modal-actions button:hover {
            background: var(--color-accent, #c1621e);
            color: #111;
        }
        #casa-pdf-modal-close {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 50%;
            font-size: 1.35rem;
            line-height: 1;
        }
        .casa-pdf-frame-wrap {
            flex: 1;
            min-height: 0;
            background: #111;
            position: relative;
        }
        #casa-pdf-frame {
            width: 100%;
            height: 100%;
            border: 0;
            background: #111;
        }
        .casa-pdf-hint {
            display: none;
            padding: 0.55rem 1.1rem;
            color: rgba(255,255,255,0.62);
            font-size: 0.78rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            text-align: center;
        }
        @media (max-width: 900px) {
            .casa-pdf-hint { display: block; }
        }
        body.casa-pdf-modal-open {
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .casa-pdf-modal {
                height: 94vh;
            }
            .casa-pdf-modal-bar {
                flex-wrap: wrap;
            }
        }
    </style>
    <div class="casa-pdf-modal">
        <div class="casa-pdf-modal-bar">
            <h3 id="casa-pdf-modal-title">Planos del espacio</h3>
            <div class="casa-pdf-modal-actions">
                <a id="casa-pdf-open-tab" href="#" target="_blank" rel="noopener noreferrer">Abrir pestaña</a>
                <a id="casa-pdf-download" href="#" target="_blank" rel="noopener noreferrer">Descargar PDF</a>
                <button type="button" id="casa-pdf-modal-close" aria-label="Cerrar visor de planos">&times;</button>
            </div>
        </div>
        <div class="casa-pdf-frame-wrap">
            <iframe id="casa-pdf-frame" title="Visor de planos PDF"></iframe>
        </div>
        <p class="casa-pdf-hint">En algunos teléfonos el visor es limitado: usa «Abrir pestaña» o descarga el PDF si lo necesitas.</p>
    </div>
</div>
<script>
(function () {
    var overlay = document.getElementById('casa-pdf-modal-overlay');
    if (!overlay) return;
    var frame = document.getElementById('casa-pdf-frame');
    var titleEl = document.getElementById('casa-pdf-modal-title');
    var openTab = document.getElementById('casa-pdf-open-tab');
    var downloadBtn = document.getElementById('casa-pdf-download');
    var closeBtn = document.getElementById('casa-pdf-modal-close');

    function viewerSrc(url) {
        var clean = String(url || '').split('#')[0];
        return clean + '#view=FitH&toolbar=1&navpanes=0';
    }

    function openModal(url, title) {
        if (!url) return;
        titleEl.textContent = title || 'Planos del espacio';
        openTab.href = url;
        downloadBtn.href = url;
        downloadBtn.setAttribute('download', '');
        frame.src = viewerSrc(url);
        overlay.hidden = false;
        overlay.classList.add('is-open');
        document.body.classList.add('casa-pdf-modal-open');
    }

    function closeModal() {
        overlay.classList.remove('is-open');
        overlay.hidden = true;
        document.body.classList.remove('casa-pdf-modal-open');
        frame.src = 'about:blank';
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.js-open-pdf-modal');
        if (!btn) return;
        e.preventDefault();
        openModal(btn.getAttribute('data-pdf-url'), btn.getAttribute('data-pdf-title'));
    });

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
            closeModal();
        }
    });
})();
</script>
