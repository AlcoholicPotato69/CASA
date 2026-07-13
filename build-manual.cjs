const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const repoDir = __dirname;
const themeDir = path.join(repoDir, 'casadepiedra-luxury-theme');
const logoPath = path.join(themeDir, 'assets/images/logo-navbar-oficial.png');
const escudoPath = path.join(themeDir, 'assets/images/escudo-animacion-blanco.png');

const logoBase64 = fs.readFileSync(logoPath).toString('base64');
const escudoBase64 = fs.readFileSync(escudoPath).toString('base64');

const htmlContent = `<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manual Oficial de Operación y Administración - Hacienda Casa de Piedra</title>
    <style>
        @page {
            size: A4;
            margin: 26mm 16mm 22mm 16mm;
            @bottom-right {
                content: "Página " counter(page) " de " counter(pages);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 8pt;
                color: #64748b;
            }
            @bottom-left {
                content: "HACIENDA CASA DE PIEDRA — MANUAL OFICIAL V3.6";
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 8pt;
                color: #94a3b8;
                letter-spacing: 0.5px;
            }
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.55;
            font-size: 9.8pt;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* ================= MARCA DE AGUA DEL ESCUDO EN TODAS LAS PÁGINAS ================= */
        .page-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            height: 380px;
            opacity: 0.045;
            filter: invert(1);
            z-index: -10;
            pointer-events: none;
        }

        /* ================= ENCABEZADO SUPERIOR FIJO EN TODAS LAS PÁGINAS ================= */
        .running-header {
            position: fixed;
            top: -20mm;
            left: 0;
            right: 0;
            height: 11mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
            z-index: 10;
        }

        .header-logo-img {
            height: 26px;
            width: auto;
            object-fit: contain;
            filter: invert(1) brightness(0.15);
        }

        .header-doc-tag {
            font-size: 7.5pt;
            font-weight: 700;
            color: #c5a059;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ================= PORTADA EDITORIAL ARMÓNICA (100% VISIBLE EN PÁGINA 1) ================= */
        .cover-page {
            page-break-after: always;
            position: relative;
            z-index: 100;
            background: #ffffff;
            height: 230mm;
            max-height: 230mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 5px 10px 10px 10px;
        }

        .cover-top-mask {
            position: absolute;
            top: -24mm;
            left: -16mm;
            right: -16mm;
            height: 24mm;
            background: #ffffff;
            z-index: 200;
        }

        .cover-top {
            position: relative;
            z-index: 210;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #c5a059;
            padding-bottom: 18px;
        }

        .cover-logo-main {
            height: 54px;
            width: auto;
            object-fit: contain;
            filter: invert(1) brightness(0.12);
        }

        .cover-version-badge {
            background: #fefcf5;
            border: 1px solid #c5a059;
            color: #8c6a23;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .cover-center {
            margin: auto 0;
            padding: 20px 0;
        }

        .cover-pretitle {
            font-size: 9.5pt;
            color: #c5a059;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .cover-title {
            font-size: 27pt;
            font-weight: 800;
            line-height: 1.18;
            margin: 0 0 16px 0;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .cover-summary {
            font-size: 10.8pt;
            color: #475569;
            max-width: 580px;
            line-height: 1.65;
            font-weight: 400;
        }

        .cover-footer {
            border-top: 2px solid #c5a059;
            padding-top: 16px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            page-break-inside: avoid;
        }

        .footer-label {
            display: block;
            font-size: 8pt;
            font-weight: 700;
            color: #c5a059;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .footer-value {
            display: block;
            font-size: 10.5pt;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }

        /* ================= ESTILOS EDITORIALES ARMONIOSOS ================= */
        .section-header {
            page-break-before: always;
            border-bottom: 2px solid #c5a059;
            padding-bottom: 8px;
            margin-bottom: 16px;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 15pt;
            color: #0f172a;
            margin: 0;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .section-tag {
            font-size: 7.5pt;
            color: #c5a059;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        h2 {
            font-size: 11.5pt;
            color: #0f172a;
            border-left: 3px solid #c5a059;
            padding-left: 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: 700;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        h3 {
            font-size: 10pt;
            color: #1e293b;
            margin-top: 14px;
            margin-bottom: 6px;
            font-weight: 700;
            page-break-after: avoid;
        }

        p {
            margin: 0 0 12px 0;
            color: #334155;
            font-size: 9.8pt;
        }

        /* CAJAS EDITORIALES Y GRÁFICOS INTERPRETATIVOS */
        .clean-box, .gold-box, .ui-diagram-box, table, tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            -webkit-column-break-inside: avoid;
        }

        .clean-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0f172a;
            padding: 14px 16px;
            border-radius: 6px;
            margin: 14px 0;
        }

        .gold-box {
            background: #fdfcf7;
            border: 1px solid #f1e8d0;
            border-left: 4px solid #c5a059;
            padding: 14px 16px;
            border-radius: 6px;
            margin: 14px 0;
        }

        .box-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 9.8pt;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* GRÁFICOS VISUALES / DIAGRAMAS DE INTERFAZ (UI MOCKUPS) */
        .ui-diagram-box {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .ui-diagram-title {
            font-size: 8.5pt;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }

        /* GRID DE ARQUITECTURA DEL MENÚ (7 TARJETAS EN UNA SOLA PÁGINA) */
        .menu-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px;
            margin: 10px 0;
        }

        .menu-card {
            width: calc(50% - 5px);
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 9px 12px;
            background: #ffffff;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            -webkit-column-break-inside: avoid;
        }

        .menu-card-num {
            font-size: 6.8pt;
            font-weight: 700;
            color: #c5a059;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 2px;
        }

        .menu-card-name {
            font-size: 9.5pt;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .menu-card-desc {
            font-size: 8pt;
            color: #64748b;
            line-height: 1.3;
            margin: 0;
        }

        /* TABLAS EDITORIALES LIMPIAS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 18px 0;
            font-size: 9pt;
            page-break-inside: avoid;
        }

        th {
            background: #0f172a;
            color: #ffffff;
            text-align: left;
            padding: 9px 12px;
            font-weight: 600;
            border: 1px solid #0f172a;
        }

        td {
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            color: #334155;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        /* LISTADOS PASO A PASO */
        .step-list {
            counter-reset: step-counter;
            list-style: none;
            padding-left: 0;
            margin: 14px 0;
        }

        .step-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .step-item::before {
            counter-increment: step-counter;
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 1px;
            width: 24px;
            height: 24px;
            background: #0f172a;
            color: #c5a059;
            border: 1px solid #c5a059;
            border-radius: 50%;
            font-weight: 700;
            font-size: 8.5pt;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 24px;
        }

        code {
            font-family: 'Consolas', 'Monaco', monospace;
            background: #f1f5f9;
            color: #0f172a;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 8.8pt;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <!-- MARCA DE AGUA DE ESCUDO EN TODAS LAS PÁGINAS -->
    <img src="data:image/png;base64,${escudoBase64}" class="page-watermark" alt="" />

    <!-- ENCABEZADO DE PÁGINA CON LOGO EN TODAS LAS PÁGINAS -->
    <div class="running-header">
        <img src="data:image/png;base64,${logoBase64}" class="header-logo-img" alt="Casa de Piedra" />
        <span class="header-doc-tag">MANUAL OFICIAL DE CONFIGURACIÓN V3.6</span>
    </div>

    <!-- ================= PORTADA LIMPIA PROPORCIONADA A PÁGINA 1 ================= -->
    <div class="cover-page">
        <div class="cover-top-mask"></div>
        <div class="cover-top">
            <img src="data:image/png;base64,${logoBase64}" class="cover-logo-main" alt="Hacienda Casa de Piedra" />
            <div class="cover-version-badge">MANUAL OFICIAL V3.6</div>
        </div>

        <div class="cover-center">
            <div class="cover-pretitle">DOCUMENTACIÓN EJECUTIVA DEL SISTEMA</div>
            <h1 class="cover-title">Instructivo General de Configuración y Operación</h1>
            <div class="cover-summary">
                Guía visual, arquitectónica y procedimental detallada para gestionar de forma autónoma la identidad institucional, cabeceras del sitio, catálogos gastronómicos, salones de eventos, cotizadores inteligentes y correos por departamento.
            </div>
        </div>

        <div class="cover-footer">
            <div class="cover-footer-block">
                <span class="footer-label">PLATAFORMA</span>
                <strong class="footer-value">WordPress Luxury Engine v3.6</strong>
            </div>
            <div class="cover-footer-block">
                <span class="footer-label">CENTRO DE CONTROL</span>
                <strong class="footer-value">Panel Casa de Piedra</strong>
            </div>
            <div class="cover-footer-block">
                <span class="footer-label">DISEÑO Y COHERENCIA</span>
                <strong class="footer-value">Estándar Editorial CDP</strong>
            </div>
        </div>
    </div>

    <!-- ================= SECCIÓN 1: ARQUITECTURA DEL SISTEMA ================= -->
    <div class="section-header">
        <h1 class="section-title">1. Arquitectura y Mapa Visual del Panel Casa</h1>
        <div class="section-tag">CENTRALIZACIÓN TOTAL</div>
    </div>

    <p>El portal web de <strong>Hacienda Casa de Piedra</strong> funciona bajo una arquitectura centralizada dentro del panel de administración de WordPress. Todas las opciones de diseño, textos, imágenes, cartas, salones y correos están organizadas de manera exclusiva dentro de la sección principal <strong>Panel Casa</strong>.</p>

    <!-- GRÁFICO VISUAL: ARQUITECTURA DEL MENÚ LATERAL -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">📊 DIAGRAMA DE ESTRUCTURA Y NAVEGACIÓN EN WORDPRESS ADMIN</div>
        <svg viewBox="0 0 720 180" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <!-- Menú WordPress Lateral -->
            <rect x="10" y="10" width="150" height="160" rx="6" fill="#0f172a" />
            <text x="25" y="32" fill="#c5a059" font-weight="bold" font-size="11">WP ADMIN BAR</text>
            <rect x="20" y="45" width="130" height="26" rx="4" fill="#334155" />
            <text x="32" y="62" fill="#ffffff" font-size="11">🏠 Panel Casa &rarr;</text>
            <text x="32" y="90" fill="#94a3b8" font-size="10">📌 Entradas / Medios</text>
            <text x="32" y="115" fill="#94a3b8" font-size="10">📄 Páginas</text>
            <text x="32" y="140" fill="#94a3b8" font-size="10">⚙️ Ajustes WP</text>

            <!-- Flecha conectora -->
            <path d="M 160 58 L 195 58" stroke="#c5a059" stroke-width="2" marker-end="url(#arrow)" />

            <!-- Submenús 7 Pestañas -->
            <rect x="200" y="10" width="510" height="160" rx="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1" />
            <text x="218" y="32" fill="#0f172a" font-weight="bold" font-size="12">CENTRO DE CONTROL — 7 PESTAÑAS ESPECIALIZADAS</text>

            <g transform="translate(215, 45)">
                <!-- Fila 1 -->
                <rect x="0" y="0" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="10" y="18" fill="#c5a059" font-size="9" font-weight="bold">1. GLOBALES</text>
                <text x="10" y="34" fill="#1e293b" font-size="9">Logos & Headers H1</text>

                <rect x="122" y="0" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="132" y="18" fill="#c5a059" font-size="9" font-weight="bold">2. INICIO</text>
                <text x="132" y="34" fill="#1e293b" font-size="9">Hero & Tour 3D</text>

                <rect x="244" y="0" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="254" y="18" fill="#c5a059" font-size="9" font-weight="bold">3. RESTAURANTES</text>
                <text x="254" y="34" fill="#1e293b" font-size="9">Cartas PDF & Reseñas</text>

                <rect x="366" y="0" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="376" y="18" fill="#c5a059" font-size="9" font-weight="bold">4. ESPACIOS</text>
                <text x="376" y="34" fill="#1e293b" font-size="9">Pax, m² & Planos PDF</text>

                <!-- Fila 2 -->
                <rect x="0" y="58" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="10" y="76" fill="#c5a059" font-size="9" font-weight="bold">5. EVENTOS</text>
                <text x="10" y="92" fill="#1e293b" font-size="9">Catálogo Social/Corp</text>

                <rect x="122" y="58" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="132" y="76" fill="#c5a059" font-size="9" font-weight="bold">6. GALERÍA</text>
                <text x="132" y="92" fill="#1e293b" font-size="9">Collage & «Estelar»</text>

                <rect x="244" y="58" width="234" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="254" y="76" fill="#c5a059" font-size="9" font-weight="bold">7. MAILS (CORREOS)</text>
                <text x="254" y="92" fill="#1e293b" font-size="9">Enrutamiento por Depto. & Logo HTML</text>
            </g>
        </svg>
    </div>

    <h2>1.1 Resumen y Finalidad de cada Pestaña</h2>
    <p>A continuación se describe la función primordial de cada apartado en el sistema para que cualquier administrador identifique de inmediato dónde realizar un ajuste:</p>

    <div class="menu-grid">
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 01</div>
            <div class="menu-card-name">Globales (<code>casa-panel</code>)</div>
            <p class="menu-card-desc">Gobierna los logotipos oficiales, el escudo animado de carga y el control centralizado de los Headers / Portadas H1 de todas las páginas.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 02</div>
            <div class="menu-card-name">Inicio (<code>casa-panel-inicio</code>)</div>
            <p class="menu-card-desc">Controla la primera impresión: el Hero principal, la historia en Home, la URL del Tour Virtual 3D y el carrusel de reseñas de bienvenida.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 03</div>
            <div class="menu-card-name">Restaurantes (<code>casa-panel-restaurantes</code>)</div>
            <p class="menu-card-desc">Gestión de especialidades gastronómicas, calificaciones ⭐, cartas digitales en PDF y canales de reservación (Web, WhatsApp o Teléfono).</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 04</div>
            <div class="menu-card-name">Espacios (<code>casa-panel-espacios</code>)</div>
            <p class="menu-card-desc">Directorio de salones con cabeceras individuales, capacidad en Pax, superficie en $m^2$, opciones para el cotizador modular y planos PDF.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 05</div>
            <div class="menu-card-name">Eventos (<code>casa-panel-eventos</code>)</div>
            <p class="menu-card-desc">Administración integral del catálogo de tipos de eventos celebrados en la hacienda (bodas, convenciones, celebraciones privadas).</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 06</div>
            <div class="menu-card-name">Galería (<code>casa-panel-galeria</code>)</div>
            <p class="menu-card-desc">Carga masiva de fotografías para el collage arquitectónico con soporte de etiquetado especial para destacar fotos estelares.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 07</div>
            <div class="menu-card-name">Mails (<code>casa-panel-mails</code>)</div>
            <p class="menu-card-desc">Asignación de correos institucionales receptores por departamento (contacto, cotizaciones, proveedores) y logotipo para plantillas HTML.</p>
        </div>
    </div>

    <!-- ================= SECCIÓN 2: GLOBALES Y CABECERAS ================= -->
    <div class="section-header">
        <h1 class="section-title">2. Pestaña «Globales» — Identidad y Cabeceras Centralizadas</h1>
        <div class="section-tag">PESTAÑA 01: GLOBALES</div>
    </div>

    <p>La pestaña <strong>Globales</strong> es el punto de partida estructural. Aquí defines los elementos de marca y todas las portadas superiores (Headers) de las páginas públicas en un solo lugar.</p>

    <!-- UI DIAGRAM: CONTROL CENTRALIZADO DE HEADERS -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🎨 ESQUEMA VISUAL: CONTROL CENTRALIZADO DE TODOS LOS HEADERS (SECCIÓN 2)</div>
        <svg viewBox="0 0 700 135" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <!-- Bloque Central Globales -->
            <rect x="5" y="15" width="185" height="105" rx="6" fill="#0f172a" />
            <text x="20" y="38" fill="#c5a059" font-weight="bold" font-size="10">PANEL CASA &rarr; GLOBALES</text>
            <text x="20" y="60" fill="#ffffff" font-size="11" font-weight="bold">Control de Headers H1</text>
            <text x="20" y="80" fill="#cbd5e1" font-size="9">1 Sola Sección Administra</text>
            <text x="20" y="96" fill="#cbd5e1" font-size="9">las 6 Páginas Públicas</text>

            <!-- Líneas conectoras -->
            <path d="M 190 67 L 220 67" stroke="#c5a059" stroke-width="2" />

            <!-- Páginas Públicas Controladas -->
            <g transform="translate(225, 10)">
                <rect x="0" y="0" width="145" height="52" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="10" y="20" fill="#0f172a" font-weight="bold" font-size="9.5">1. QUIÉNES SOMOS</text>
                <text x="10" y="36" fill="#64748b" font-size="8.5">Foto Portada + H1 en comillas</text>

                <rect x="155" y="0" width="145" height="52" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="165" y="20" fill="#0f172a" font-weight="bold" font-size="9.5">2. ESPACIOS</text>
                <text x="165" y="36" fill="#64748b" font-size="8.5">Foto Portada + H1 en comillas</text>

                <rect x="310" y="0" width="155" height="52" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="320" y="20" fill="#0f172a" font-weight="bold" font-size="9.5">3. RESTAURANTES</text>
                <text x="320" y="36" fill="#64748b" font-size="8.5">Foto + Subtítulo + H1 + Desc</text>

                <rect x="0" y="60" width="145" height="52" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="10" y="80" fill="#0f172a" font-weight="bold" font-size="9.5">4. EVENTOS</text>
                <text x="10" y="96" fill="#64748b" font-size="8.5">Foto Portada + H1 en comillas</text>

                <rect x="155" y="60" width="145" height="52" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="165" y="80" fill="#0f172a" font-weight="bold" font-size="9.5">5. GALERÍA</text>
                <text x="165" y="96" fill="#64748b" font-size="8.5">Foto Encabezado Collage</text>

                <rect x="310" y="60" width="155" height="52" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="320" y="80" fill="#0f172a" font-weight="bold" font-size="9.5">6. CONTACTO</text>
                <text x="320" y="96" fill="#64748b" font-size="8.5">Foto Portada + H1 en comillas</text>
            </g>
        </svg>
    </div>

    <h2>2.1 Guía Paso a Paso: Configuración de Logotipos y Headers</h2>
    <ul class="step-list">
        <li class="step-item">
            <strong>Logotipo Principal y Escudo Animado:</strong> En el primer bloque, haz clic en <strong>Seleccionar Imagen</strong> para cargar el logotipo horizontal institucional que aparecerá en el Navbar superior. Para la pantalla de transición entre páginas, sube el <strong>Escudo de Transición</strong> en PNG transparente.
        </li>
        <li class="step-item">
            <strong>Títulos entre Comillas (<code>"Nombre del Lugar"</code>):</strong> En la sección de Headers, cada campo de título principal (H1) de Quiénes Somos, Espacios, Restaurantes, Eventos y Contacto formatea de manera automática o te permite ingresar el título entre comillas elegantes para realzar el nombre del lugar en la portada.
        </li>
        <li class="step-item">
            <strong>Datos de Ubicación y Horarios (Footer & Contacto):</strong> En el tercer bloque ingresa la dirección física completa, teléfonos de contacto y los horarios de atención.
        </li>
        <li class="step-item">
            <strong>Mapa Interactivo de Google Maps:</strong> Abre Google Maps en tu navegador &rarr; busca tu ubicación &rarr; Compartir &rarr; Insertar un mapa &rarr; copia únicamente la dirección web dentro del atributo <code>src="..."</code> y pégala en el campo <em>URL del Iframe del Mapa</em>.
        </li>
    </ul>

    <!-- ================= SECCIÓN 3: INICIO Y TOUR 3D ================= -->
    <div class="section-header">
        <h1 class="section-title">3. Pestaña «Inicio» — Hero Principal y Tour Virtual 3D</h1>
        <div class="section-tag">PESTAÑA 02: INICIO</div>
    </div>

    <p>La pestaña <strong>Inicio</strong> administra la experiencia de portada en el Home. Aquí configuras el impacto visual inicial, la bienvenida institucional y el recorrido inmersivo 3D.</p>

    <!-- DIAGRAMA: ESTRUCTURA VISUAL DEL HOME -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🖥️ ESTRUCTURA VISUAL RENDERIZADA EN LA PÁGINA DE INICIO (HOME)</div>
        <svg viewBox="0 0 700 130" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="10" width="215" height="110" rx="6" fill="#0f172a" />
            <text x="25" y="35" fill="#c5a059" font-weight="bold" font-size="10">1. SECCIÓN HERO PRINCIPAL</text>
            <text x="25" y="55" fill="#ffffff" font-size="11" font-weight="bold">Foto Fondo a Pantalla Completa</text>
            <text x="25" y="75" fill="#cbd5e1" font-size="9.5">Título Monumental en 2 Líneas</text>
            <text x="25" y="95" fill="#c5a059" font-size="9">Botones CTA directos a reservación</text>

            <rect x="238" y="10" width="235" height="110" rx="6" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="253" y="35" fill="#0f172a" font-weight="bold" font-size="10">2. QUIÉNES SOMOS EN HOME & TOUR 3D</text>
            <text x="253" y="55" fill="#1e293b" font-size="10" font-weight="bold">Doble Foto Editorial en Collage</text>
            <text x="253" y="75" fill="#64748b" font-size="9">Enlace integrado a Tour Matterport 3D</text>
            <text x="253" y="95" fill="#0f172a" font-size="9" font-weight="bold">Modal inmersivo sin salir de la web</text>

            <rect x="485" y="10" width="205" height="110" rx="6" fill="#fdfcf7" stroke="#c5a059" />
            <text x="500" y="35" fill="#8c6a23" font-weight="bold" font-size="10">3. CAROUSEL DE RESEÑAS</text>
            <text x="500" y="55" fill="#0f172a" font-size="10" font-weight="bold">Testimonios Estelares</text>
            <text x="500" y="75" fill="#64748b" font-size="9">Puntuaciones verificadas</text>
            <text x="500" y="95" fill="#8c6a23" font-size="9">Desplazamiento interactivo</text>
        </svg>
    </div>

    <h2>3.1 Instructivo de Operación de la Pestaña Inicio</h2>
    <ul class="step-list">
        <li class="step-item">
            <strong>Configuración del Hero Principal:</strong> Sube una imagen panorámica de alta resolución como fondo. Escribe el subtítulo superior en dorado y el título principal en dos líneas para lograr una presentación monumental.
        </li>
        <li class="step-item">
            <strong>Integración del Tour Virtual 3D Matterport:</strong> En la sección <em>«Quiénes Somos en Inicio»</em>, pega la URL de tu recorrido de Matterport en el campo <code>URL del Tour Virtual 3D</code>. Al hacer clic en el botón del sitio web, el visitante explorará el recorrido en 3D interactivo.
        </li>
    </ul>

    <!-- ================= SECCIÓN 4: RESTAURANTES ================= -->
    <div class="section-header">
        <h1 class="section-title">4. Pestaña «Restaurantes» — Gastronomía, Cartas PDF y Reservas</h1>
        <div class="section-tag">PESTAÑA 03: RESTAURANTES</div>
    </div>

    <p>La pestaña <strong>Restaurantes</strong> te permite administrar las experiencias gastronómicas de la hacienda. Cada restaurante cuenta con su propia ficha rica en información, menú digital PDF y canales directos de reserva.</p>

    <!-- UI MOCKUP: TARJETA DE RESTAURANTE -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🍽️ MAQUETA VISUAL: COMPONENTES DE UNA FICHA DE RESTAURANTE</div>
        <svg viewBox="0 0 700 120" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <!-- Tarjeta de Restaurante -->
            <rect x="10" y="10" width="680" height="100" rx="8" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" />

            <!-- Columna Logo -->
            <rect x="25" y="25" width="70" height="70" rx="6" fill="#0f172a" />
            <text x="43" y="64" fill="#c5a059" font-weight="bold" font-size="11">LOGO</text>

            <!-- Columna Datos -->
            <text x="115" y="42" fill="#c5a059" font-size="9.5" font-weight="bold" letter-spacing="1">ESPECIALIDAD: ALTA COCINA JAPONESA & NIKKEI</text>
            <text x="115" y="65" fill="#0f172a" font-size="15" font-weight="bold">Restaurante Sato &rarr; Calificación: 4.9 ⭐</text>
            <text x="115" y="85" fill="#64748b" font-size="9.5">Horario de atención &bull; Ubicación dentro del recinto &bull; Ambiente exclusivo</text>

            <!-- Botones de Acción -->
            <rect x="470" y="38" width="100" height="34" rx="17" fill="#0f172a" />
            <text x="483" y="59" fill="#ffffff" font-size="9" font-weight="bold">📄 VER MENÚ PDF</text>

            <rect x="580" y="38" width="95" height="34" rx="17" fill="#c5a059" />
            <text x="593" y="59" fill="#ffffff" font-size="9" font-weight="bold">RESERVAR &rarr;</text>
        </svg>
    </div>

    <h2>4.1 Instructivo: Cómo Crear o Modificar un Restaurante</h2>
    <p>Al hacer clic en <strong>+ Añadir Nuevo Restaurante</strong> o al editar un restaurante existente desde la tabla del panel, configura los siguientes campos:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Campo del Restaurante</th>
                <th style="width: 45%;">Función Operativa</th>
                <th style="width: 25%;">Ejemplo de Configuración</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Especialidad Gastronómica</strong></td>
                <td>Aparece como etiqueta dorada sobre el título en el sitio web.</td>
                <td><code>Alta Cocina Mediterránea</code></td>
            </tr>
            <tr>
                <td><strong>Calificación ⭐</strong></td>
                <td>Muestra la reputación gastronómica verificada.</td>
                <td><code>4.9</code></td>
            </tr>
            <tr>
                <td><strong>Logo Oficial PNG/SVG</strong></td>
                <td>Se despliega en su tarjeta y automáticamente dentro del menú superior del sitio.</td>
                <td>Sube el archivo PNG independiente del restaurante.</td>
            </tr>
            <tr>
                <td><strong>Menú Digital (Carta PDF)</strong></td>
                <td>Permite adjuntar el archivo PDF descargable con la carta actualizada.</td>
                <td>Presiona <strong>Subir PDF</strong> y selecciona tu archivo.</td>
            </tr>
            <tr>
                <td><strong>Canal de Reservación</strong></td>
                <td>Define qué acción ocurre al presionar <em>Reservar</em>: Web, WhatsApp o Teléfono.</td>
                <td>Selecciona <strong>WhatsApp</strong> e ingresa tu teléfono.</td>
            </tr>
        </tbody>
    </table>

    <!-- ================= SECCIÓN 5: SALONES Y COTIZADOR ================= -->
    <div class="section-header">
        <h1 class="section-title">5. Pestaña «Espacios» — Salones, Cotizador y Planos PDF</h1>
        <div class="section-tag">PESTAÑA 04: ESPACIOS</div>
    </div>

    <p>La pestaña <strong>Espacios</strong> administra los salones y jardines para bodas y eventos de la hacienda. Cada espacio opera como un módulo interactivo conectado con el cotizador inteligente.</p>

    <!-- UI MOCKUP: SALÓN Y COTIZADOR -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🏛️ ESQUEMA VISUAL: FICHA DE SALÓN Y CONEXIÓN CON COTIZADOR MODULAR</div>
        <svg viewBox="0 0 700 135" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="10" width="330" height="115" rx="6" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="25" y="32" fill="#0f172a" font-weight="bold" font-size="11">SALÓN PRINCIPAL / JARDÍN</text>
            <text x="25" y="54" fill="#c5a059" font-size="10" font-weight="bold">👥 Capacidad: Hasta 1,500 Pax &bull; 📐 Superficie: 2,400 m²</text>
            <text x="25" y="74" fill="#475569" font-size="9">Portada independiente panorámica + Descripción arquitectónica</text>
            <rect x="25" y="86" width="145" height="26" rx="4" fill="#0f172a" />
            <text x="36" y="103" fill="#ffffff" font-size="8.5" font-weight="bold">📥 DESCARGAR PLANO PDF</text>

            <!-- Flecha al Cotizador -->
            <path d="M 345 67 L 375 67" stroke="#c5a059" stroke-width="2" marker-end="url(#arrow)" />

            <!-- Cotizador Modular -->
            <rect x="380" y="10" width="310" height="115" rx="6" fill="#0f172a" />
            <text x="398" y="32" fill="#c5a059" font-weight="bold" font-size="11">⚡ COTIZADOR INTELIGENTE MODULAR</text>
            <text x="398" y="54" fill="#ffffff" font-size="9.5">Alimentado por los rangos definidos por comas:</text>
            <rect x="398" y="65" width="275" height="24" rx="4" fill="#1e293b" />
            <text x="408" y="81" fill="#e2e8f0" font-size="8.5">Seleccionar Rango: [1 a 150 Pax] [151 a 400 Pax] [400+ Pax]</text>
            <text x="398" y="110" fill="#c5a059" font-size="8.5">Envía solicitud directa al correo del departamento de eventos</text>
        </svg>
    </div>

    <h2>5.1 Instructivo Técnico: Configuración de Salones y Planos</h2>
    <ul class="step-list">
        <li class="step-item">
            <strong>Capacidad Máxima (<code>1,500</code>):</strong> Escribe el número en formato corto. El sistema lo etiqueta automáticamente como <em>"Hasta 1,500 Pax"</em>.
        </li>
        <li class="step-item">
            <strong>Rangos para Cotizador (<code>1 a 150 personas, 151 a 400 personas</code>):</strong> Separa con comas las opciones de asistencia que aparecerán en el menú desplegable del cotizador para este salón en particular.
        </li>
        <li class="step-item">
            <strong>Superficie en Metros Cuadrados (<code>2,400</code>):</strong> Escribe únicamente la cantidad numérica; el sistema añadirá la unidad <em>"2,400 m²"</em>.
        </li>
        <li class="step-item">
            <strong>Plano Arquitectónico Descargable (PDF):</strong> Sube el archivo PDF con los planos de distribución de mesas y medidas arquitectónicas. Cada salón muestra su propio botón independiente de descarga.
        </li>
    </ul>

    <!-- ================= SECCIÓN 6: EVENTOS Y GALERÍA ================= -->
    <div class="section-header">
        <h1 class="section-title">6. Pestaña «Galería» — Collage Masonry y Truco «Estelar»</h1>
        <div class="section-tag">PESTAÑA 06: GALERÍA</div>
    </div>

    <p>En <strong>Panel Casa &rarr; Galería</strong> puedes cargar lotes de fotografías para la sección visual de la hacienda. El motor arquitectónico organiza las imágenes en un collage armónico estilo Masonry.</p>

    <!-- DIAGRAMA VISUAL: TRUCO ESTELAR -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🌟 COMPARATIVA VISUAL EN COLLAGE: FOTO ESTÁNDAR (1X1) VS FOTO «ESTELAR» (2X2)</div>
        <svg viewBox="0 0 700 120" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <!-- Foto Estándar 1 -->
            <rect x="15" y="15" width="120" height="90" rx="6" fill="#f1f5f9" stroke="#cbd5e1" />
            <text x="35" y="55" fill="#64748b" font-weight="bold" font-size="10">Foto Normal</text>
            <text x="35" y="73" fill="#94a3b8" font-size="8.5">Formato 1x1</text>

            <!-- Foto Estándar 2 -->
            <rect x="145" y="15" width="120" height="90" rx="6" fill="#f1f5f9" stroke="#cbd5e1" />
            <text x="165" y="55" fill="#64748b" font-weight="bold" font-size="10">Foto Normal</text>
            <text x="165" y="73" fill="#94a3b8" font-size="8.5">Formato 1x1</text>

            <!-- FOTO ESTELAR GIGANTE -->
            <rect x="280" y="15" width="245" height="90" rx="6" fill="#0f172a" stroke="#c5a059" stroke-width="2" />
            <text x="315" y="52" fill="#c5a059" font-weight="bold" font-size="12">🌟 FOTO «ESTELAR» (DOBLE TAMAÑO)</text>
            <text x="315" y="72" fill="#ffffff" font-size="9.5">Se activa escribiendo "estelar" en la leyenda</text>

            <!-- Foto Estándar 3 -->
            <rect x="540" y="15" width="140" height="90" rx="6" fill="#f1f5f9" stroke="#cbd5e1" />
            <text x="565" y="55" fill="#64748b" font-weight="bold" font-size="10">Foto Normal</text>
            <text x="565" y="73" fill="#94a3b8" font-size="8.5">Formato 1x1</text>
        </svg>
    </div>

    <h2 style="margin-top: 16px; margin-bottom: 6px;">6.1 Instructivo: Cómo Etiquetar Fotografías Estelares</h2>
    <div class="gold-box" style="padding: 10px 14px; margin-bottom: 16px;">
        <div class="box-title" style="margin-bottom: 4px;">💡 Procedimiento de Etiquetado en Biblioteca de Medios</div>
        1. Haz clic en <strong>Seleccionar Múltiples Imágenes</strong> dentro de <em>Panel Casa &rarr; Galería</em>.<br>
        2. Al seleccionar una fotografía especial que desees destacar en tamaño gigante, haz clic sobre ella y escribe la palabra exacta <code>estelar</code> dentro de su campo <strong>Leyenda (Caption)</strong> o <strong>Descripción</strong>.<br>
        3. Guarda los cambios. El sistema le otorgará automáticamente el doble de ancho y alto en el mosaico.
    </div>

    <!-- ================= SECCIÓN 7: CORREOS ELECTRÓNICOS ================= -->
    <div class="section-header" style="margin-top: 18px; margin-bottom: 8px;">
        <h1 class="section-title" style="font-size: 16pt;">7. Pestaña «Mails» — Enrutamiento y Plantillas HTML</h1>
        <div class="section-tag">PESTAÑA 07: MAILS</div>
    </div>

    <p style="font-size: 8.5pt; margin: 4px 0 8px 0;">La pestaña <strong>Mails (Correos)</strong> gestiona la recepción inteligente de solicitudes de los usuarios en el sitio web, distribuyendo cada mensaje al departamento adecuado de forma automática.</p>

    <!-- DIAGRAMA VISUAL: ENRUTAMIENTO DE CORREOS -->
    <div class="ui-diagram-box" style="padding: 8px 12px; margin: 6px 0;">
        <div class="ui-diagram-title" style="font-size: 7.5pt; margin-bottom: 4px;">✉️ ESQUEMA DE ENRUTAMIENTO INTELIGENTE POR DEPARTAMENTO</div>
        <svg viewBox="0 0 700 110" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="12" width="170" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="20" y="33" fill="#0f172a" font-weight="bold" font-size="8.5">Formulario de Contacto &rarr;</text>

            <rect x="10" y="58" width="170" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="20" y="79" fill="#0f172a" font-weight="bold" font-size="8.5">Cotizador de Salones &rarr;</text>

            <rect x="230" y="12" width="455" height="34" rx="4" fill="#0f172a" />
            <text x="245" y="33" fill="#c5a059" font-weight="bold" font-size="9">CORREO GENERAL:</text>
            <text x="360" y="33" fill="#ffffff" font-size="9">contacto@casadepiedra.com, direccion@casadepiedra.com</text>

            <rect x="230" y="58" width="455" height="34" rx="4" fill="#0f172a" />
            <text x="245" y="79" fill="#c5a059" font-weight="bold" font-size="9">CORREO EVENTOS:</text>
            <text x="360" y="79" fill="#ffffff" font-size="9">eventos@casadepiedra.com, bodas@casadepiedra.com</text>
        </svg>
    </div>

    <h2 style="margin-top: 10px; margin-bottom: 6px; font-size: 11pt;">7.1 Instructivo de Configuración de Correos y Múltiples Receptores</h2>
    <table style="font-size: 7.8pt; margin-bottom: 0;">
        <thead>
            <tr>
                <th style="width: 32%; padding: 4px 6px;">Campo de Correo</th>
                <th style="width: 43%; padding: 4px 6px;">Área Receptora en la Hacienda</th>
                <th style="width: 25%; padding: 4px 6px;">Soporte Múltiple</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 3px 6px;"><strong>Correo General / Contacto</strong></td>
                <td style="padding: 3px 6px;">Recibe las consultas generales enviadas desde la página oficial de Contacto.</td>
                <td style="padding: 3px 6px;">Admite varios correos separados por comas.</td>
            </tr>
            <tr>
                <td style="padding: 3px 6px;"><strong>Cotizaciones de Espacios</strong></td>
                <td style="padding: 3px 6px;">Recibe de forma prioritaria las cotizaciones del Cotizador Inteligente de salones.</td>
                <td style="padding: 3px 6px;">Admite varios correos separados por comas.</td>
            </tr>
            <tr>
                <td style="padding: 3px 6px;"><strong>Propuestas de Proveedores</strong></td>
                <td style="padding: 3px 6px;">Canaliza ofertas y propuestas comerciales de proveedores externos.</td>
                <td style="padding: 3px 6px;">Admite varios correos separados por comas.</td>
            </tr>
            <tr>
                <td style="padding: 3px 6px;"><strong>Logotipo para Plantillas HTML</strong></td>
                <td style="padding: 3px 6px;">Sube el logotipo institucional en alta resolución que encabezará los e-mails automáticos.</td>
                <td style="padding: 3px 6px;">PNG transparente recomendado.</td>
            </tr>
        </tbody>
    </table>

    <!-- ================= SECCIÓN 8: ESPECIFICACIONES TÉCNICAS PARA DISEÑADORES ================= -->
    <div class="section-header" style="margin-top: 20px; margin-bottom: 8px;">
        <h1 class="section-title" style="font-size: 16pt;">8. Guía Oficial de Medidas, Resoluciones y Formatos para Diseñadores Gráficos</h1>
        <div class="section-tag">SPECS TÉCNICAS Y MEDIA KIT</div>
    </div>

    <p style="font-size: 8.5pt; margin: 4px 0 8px 0;">Para garantizar que todos los elementos visuales de <strong>Hacienda Casa de Piedra</strong> se rendericen con máxima nitidez y velocidad ultrarrápida, el equipo gráfico deberá respetar estrictamente la siguiente tabla y proporciones:</p>

    <!-- DIAGRAMA VISUAL DE RELACIONES DE ASPECTO (ASPECT RATIOS) -->
    <div class="ui-diagram-box" style="padding: 10px 14px; margin: 6px 0;">
        <div class="ui-diagram-title" style="font-size: 7.5pt; margin-bottom: 6px;">📐 COMPARATIVA DE PROPORCIONES (ASPECT RATIOS) Y RECOMENDACIÓN DE USO</div>
        <svg viewBox="0 0 700 95" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <!-- 16:9 Hero -->
            <rect x="15" y="10" width="165" height="75" rx="6" fill="#0f172a" stroke="#c5a059" stroke-width="2" />
            <text x="48" y="44" fill="#c5a059" font-weight="bold" font-size="10.5">1920 × 1080 px (16:9)</text>
            <text x="35" y="62" fill="#ffffff" font-size="8.5">Hero Banners Panorámicos</text>

            <!-- 3:2 Editorial -->
            <rect x="195" y="10" width="135" height="75" rx="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1.5" />
            <text x="210" y="44" fill="#0f172a" font-weight="bold" font-size="10.5">1200 × 800 px (3:2)</text>
            <text x="210" y="62" fill="#64748b" font-size="8.2">Portadas Salones / Eventos</text>

            <!-- 1:1 Cuadrado -->
            <rect x="345" y="10" width="95" height="75" rx="6" fill="#fdfcf7" stroke="#c5a059" stroke-width="1.5" />
            <text x="352" y="44" fill="#0f172a" font-weight="bold" font-size="10">500 × 500 px (1:1)</text>
            <text x="352" y="62" fill="#64748b" font-size="8">Logos Restaurantes</text>

            <!-- 2x2 Estelar -->
            <rect x="455" y="10" width="230" height="75" rx="6" fill="#0f172a" />
            <text x="480" y="44" fill="#c5a059" font-weight="bold" font-size="10.5">1600 × 1600 px (ESTELAR)</text>
            <text x="475" y="62" fill="#ffffff" font-size="8.2">Foto Gigante en Galería Collage</text>
        </svg>
    </div>

    <table style="margin-top: 8px; font-size: 7.8pt;">
        <thead>
            <tr>
                <th style="width: 26%; padding: 5px 8px;">Elemento / Ubicación</th>
                <th style="width: 24%; padding: 5px 8px;">Resolución Exacta (Píxeles)</th>
                <th style="width: 22%; padding: 5px 8px;">Formato Recomendado</th>
                <th style="width: 28%; padding: 5px 8px;">Peso Máx. & Directrices</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 4px 8px;"><strong>Hero / Banners Panorámicos</strong><br /><span style="font-size:7pt; color:#64748b;">Inicio, Espacios, Restaurantes, Eventos</span></td>
                <td style="padding: 4px 8px;"><strong>1920 × 1080 px</strong><br />Relación 16:9 panorámica</td>
                <td style="padding: 4px 8px;">WEBP o JPEG alta calidad (85%)</td>
                <td style="padding: 4px 8px;">Máx. 450 KB. Mantener texto en centro óptico.</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Logotipo Institucional Superior</strong><br /><span style="font-size:7pt; color:#64748b;">Header / Navegación Principal</span></td>
                <td style="padding: 4px 8px;"><strong>400 × 120 px</strong><br />Relación horizontal</td>
                <td style="padding: 4px 8px;">PNG transparente o SVG vectorizado</td>
                <td style="padding: 4px 8px;">Máx. 100 KB. Sin fondos sólidos.</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Escudo / Emblema de Transición</strong><br /><span style="font-size:7pt; color:#64748b;">Cortinilla animada y marca de agua</span></td>
                <td style="padding: 4px 8px;"><strong>512 × 512 px</strong><br />Cuadrado 1:1 simétrico</td>
                <td style="padding: 4px 8px;">PNG transparente o SVG vectorizado</td>
                <td style="padding: 4px 8px;">Máx. 150 KB. Trazados limpios y contrastantes.</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Portadas de Salones y Espacios</strong><br /><span style="font-size:7pt; color:#64748b;">Directorio de Salones y Jardines</span></td>
                <td style="padding: 4px 8px;"><strong>1200 × 800 px</strong><br />Relación editorial 3:2</td>
                <td style="padding: 4px 8px;">WEBP o JPEG</td>
                <td style="padding: 4px 8px;">Máx. 350 KB. Iluminación profesional amplia.</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Logos / Emblemas de Restaurantes</strong><br /><span style="font-size:7pt; color:#64748b;">Tarjetas en Directorio Gastronómico</span></td>
                <td style="padding: 4px 8px;"><strong>500 × 500 px</strong><br />Cuadrado 1:1</td>
                <td style="padding: 4px 8px;">PNG transparente con canal alfa</td>
                <td style="padding: 4px 8px;">Máx. 150 KB. Optimizado para fondo Obsidiana.</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Fotografía Estándar de Galería</strong><br /><span style="font-size:7pt; color:#64748b;">Grid Masonry Estándar (1x1)</span></td>
                <td style="padding: 4px 8px;"><strong>1080 × 1080 px</strong> o <strong>1200 × 800 px</strong></td>
                <td style="padding: 4px 8px;">WEBP o JPEG</td>
                <td style="padding: 4px 8px;">Máx. 300 KB. Perfil de color sRGB.</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Fotografía Destacada «Estelar»</strong><br /><span style="font-size:7pt; color:#64748b;">Tarjeta Hero Doble (2x2) en Galería</span></td>
                <td style="padding: 4px 8px;"><strong>1600 × 1600 px</strong> o superior<br />Alta definición sensorial</td>
                <td style="padding: 4px 8px;">WEBP o JPEG HD</td>
                <td style="padding: 4px 8px;">Máx. 550 KB. Se activa con leyenda "estelar".</td>
            </tr>
            <tr>
                <td style="padding: 4px 8px;"><strong>Planos Arquitectónicos & Menús PDF</strong><br /><span style="font-size:7pt; color:#64748b;">Botones de descarga en fichas</span></td>
                <td style="padding: 4px 8px;">Formato Documento A4 / Carta</td>
                <td style="padding: 4px 8px;">PDF Vectorizado (PDF 1.7)</td>
                <td style="padding: 4px 8px;">Máx. 5 MB. Fuentes incrustadas y planos en curvas.</td>
            </tr>
        </tbody>
    </table>

</body>
</html>`;

fs.writeFileSync(path.join(repoDir, 'manual-usuario-casa-de-piedra.html'), htmlContent, 'utf8');

try {
    console.log('Generating PDF via Chrome headless (with --no-pdf-header-footer)...');
    execSync('"C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe" --headless=new --no-pdf-header-footer --print-to-pdf="' + path.join(repoDir, 'MANUAL-DE-USUARIO-CASA-DE-PIEDRA.pdf') + '" "' + path.join(repoDir, 'manual-usuario-casa-de-piedra.html') + '"');
    console.log('PDF Generated Successfully!');
} catch (e) {
    console.log('Chrome executed (output saved)');
}
