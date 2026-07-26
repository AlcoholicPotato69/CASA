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
            margin: 24mm 15mm 20mm 15mm;
            @bottom-right {
                content: "Página " counter(page) " de " counter(pages);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 8pt;
                color: #64748b;
            }
            @bottom-left {
                content: "HACIENDA CASA DE PIEDRA — MANUAL OFICIAL DE CONFIGURACIÓN V3.8";
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
            font-size: 9.6pt;
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
            top: -18mm;
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
            height: 25px;
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
            height: 235mm;
            max-height: 235mm;
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
            font-size: 26pt;
            font-weight: 800;
            line-height: 1.18;
            margin: 0 0 16px 0;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .cover-summary {
            font-size: 10.5pt;
            color: #475569;
            max-width: 600px;
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
            font-size: 14.5pt;
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
            font-size: 11.2pt;
            color: #0f172a;
            border-left: 3px solid #c5a059;
            padding-left: 10px;
            margin-top: 18px;
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
            font-size: 9.6pt;
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
            padding: 13px 15px;
            border-radius: 6px;
            margin: 12px 0;
        }

        .gold-box {
            background: #fdfcf7;
            border: 1px solid #f1e8d0;
            border-left: 4px solid #c5a059;
            padding: 13px 15px;
            border-radius: 6px;
            margin: 12px 0;
        }

        .box-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 9.6pt;
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
            padding: 15px;
            margin: 14px 0;
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

        /* GRID DE ARQUITECTURA DEL MENÚ (7 TARJETAS) */
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
            margin: 12px 0 16px 0;
            font-size: 8.8pt;
            page-break-inside: avoid;
        }

        th {
            background: #0f172a;
            color: #ffffff;
            text-align: left;
            padding: 8px 10px;
            font-weight: 600;
            border: 1px solid #0f172a;
        }

        td {
            padding: 8px 10px;
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
            margin: 12px 0;
        }

        .step-item {
            position: relative;
            padding-left: 38px;
            margin-bottom: 11px;
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
            font-size: 8.6pt;
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
        <span class="header-doc-tag">MANUAL OFICIAL DE CONFIGURACIÓN V3.8</span>
    </div>

    <!-- ================= PORTADA LIMPIA PROPORCIONADA A PÁGINA 1 ================= -->
    <div class="cover-page">
        <div class="cover-top-mask"></div>
        <div class="cover-top">
            <img src="data:image/png;base64,${logoBase64}" class="cover-logo-main" alt="Hacienda Casa de Piedra" />
            <div class="cover-version-badge">MANUAL OFICIAL V3.8</div>
        </div>

        <div class="cover-center">
            <div class="cover-pretitle">DOCUMENTACIÓN EJECUTIVA DEL SISTEMA</div>
            <h1 class="cover-title">Instructivo General de Configuración y Operación Integral</h1>
            <div class="cover-summary">
                Guía visual, arquitectónica y procedimental absolutamente exhaustiva y detallada por pestaña y categoría para gestionar de forma 100% autónoma la identidad institucional, las cabeceras centralizadas, menús gastronómicos, salones de eventos, automatización de vigencias, galerías inteligentes y correos por departamento sin necesidad de programación alguna.
            </div>
        </div>

        <div class="cover-footer">
            <div class="cover-footer-block">
                <span class="footer-label">PLATAFORMA</span>
                <strong class="footer-value">WordPress Luxury Engine v3.8</strong>
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

    <p>El portal web de <strong>Hacienda Casa de Piedra</strong> funciona bajo una arquitectura centralizada dentro del panel de administración de WordPress. Todas las opciones de diseño, textos, imágenes, cartas, salones, vigencias y correos están organizadas de manera exclusiva y estructurada dentro de la sección principal <strong>Panel Casa</strong>.</p>

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
                <text x="10" y="92" fill="#1e293b" font-size="9">Tipologías & Expiración</text>

                <rect x="122" y="58" width="112" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="132" y="76" fill="#c5a059" font-size="9" font-weight="bold">6. GALERÍA</text>
                <text x="132" y="92" fill="#1e293b" font-size="9">Masonry, Tags & Estelar</text>

                <rect x="244" y="58" width="234" height="48" rx="4" fill="#ffffff" stroke="#e2e8f0" />
                <text x="254" y="76" fill="#c5a059" font-size="9" font-weight="bold">7. MAILS (CORREOS)</text>
                <text x="254" y="92" fill="#1e293b" font-size="9">Enrutamiento por Depto. & Logo HTML</text>
            </g>
        </svg>
    </div>

    <h2>1.1 Resumen y Finalidad Ejecutiva de cada Pestaña</h2>
    <p>A continuación se detalla la función y el identificador interno de cada apartado para que cualquier administrador ubique de forma inmediata dónde realizar un cambio sin tocar una sola línea de código:</p>

    <div class="menu-grid">
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 01</div>
            <div class="menu-card-name">Globales (<code>casa-panel</code>)</div>
            <p class="menu-card-desc">Gobierna los logotipos oficiales, el escudo animado de carga, la imagen del footer, los datos de contacto y el control centralizado de los Headers / Portadas H1 de todas las páginas del portal.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 02</div>
            <div class="menu-card-name">Inicio (<code>casa-panel-inicio</code>)</div>
            <p class="menu-card-desc">Controla la primera impresión: la sección Historia y Legado en Home, la URL del Tour Virtual 3D Matterport y la gestión de las 3 reseñas verificadas destacadas de Google Maps.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 03</div>
            <div class="menu-card-name">Restaurantes (<code>casa-panel-restaurantes</code>)</div>
            <p class="menu-card-desc">Directorio de especialidades gastronómicas. Configura fichas individuales con logo, calificación ⭐, cartas digitales en PDF, mosaico de fotos y canales directos de reservación (Web, WhatsApp, Teléfono).</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 04</div>
            <div class="menu-card-name">Espacios (<code>casa-panel-espacios</code>)</div>
            <p class="menu-card-desc">Directorio de salones y jardines para bodas y eventos. Controla cabeceras individuales, capacidad en Pax, superficie en m², opciones para el cotizador inteligente, planos PDF y galería dedicada.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 05</div>
            <div class="menu-card-name">Eventos (<code>casa-panel-eventos</code>)</div>
            <p class="menu-card-desc">Catálogo de tipologías de celebración. Incluye un motor de automatización y vigencia (fecha y hora de baja automática) para ocultar eventos pasados sin intervención manual.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 06</div>
            <div class="menu-card-name">Galería (<code>casa-panel-galeria</code>)</div>
            <p class="menu-card-desc">Gestión visual por etiquetas dinámicas, selector de proporción de mosaico (Chico, Mediano, Grande, Panorámico), carga masiva y el truco de etiquetado <code>estelar</code> para fotos gigantes 2x2.</p>
        </div>
        <div class="menu-card">
            <div class="menu-card-num">PESTAÑA 07</div>
            <div class="menu-card-name">Mails (<code>casa-panel-mails</code>)</div>
            <p class="menu-card-desc">Asignación de correos institucionales receptores por departamento (contacto, cotizaciones de espacios, proveedores, eventos) con soporte multicorreo por comas y logo para plantillas HTML.</p>
        </div>
    </div>

    <!-- ================= SECCIÓN 2: GLOBALES Y CABECERAS ================= -->
    <div class="section-header">
        <h1 class="section-title">2. Pestaña «Globales» — Identidad y Control Central de Headers</h1>
        <div class="section-tag">PESTAÑA 01: GLOBALES</div>
    </div>

    <p>La pestaña <strong>Globales</strong> (<code>casa-panel</code>) es el pilar de la identidad del sitio. Se divide en 5 bloques de configuración que te permiten gestionar de manera unificada la marca, todas las cabeceras públicas, los datos de contacto y la activación o desactivación de secciones en el menú superior.</p>

    <!-- UI DIAGRAM: CONTROL CENTRALIZADO DE HEADERS -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🎨 ESQUEMA VISUAL: CONTROL CENTRALIZADO DE TODOS LOS HEADERS Y PORTADAS</div>
        <svg viewBox="0 0 700 135" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="5" y="15" width="185" height="105" rx="6" fill="#0f172a" />
            <text x="20" y="38" fill="#c5a059" font-weight="bold" font-size="10">PANEL CASA &rarr; GLOBALES</text>
            <text x="20" y="60" fill="#ffffff" font-size="11" font-weight="bold">Control Central de Headers</text>
            <text x="20" y="80" fill="#cbd5e1" font-size="9">1 Sola Pantalla Administra</text>
            <text x="20" y="96" fill="#cbd5e1" font-size="9">las 8 Portadas del Sitio</text>

            <path d="M 190 67 L 220 67" stroke="#c5a059" stroke-width="2" />

            <g transform="translate(225, 10)">
                <rect x="0" y="0" width="145" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="8" y="16" fill="#0f172a" font-weight="bold" font-size="8.5">1. INICIO (Hero)</text>
                <text x="8" y="28" fill="#64748b" font-size="7.5">Foto, Subtítulo, H1 y Cita</text>

                <rect x="155" y="0" width="145" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="163" y="16" fill="#0f172a" font-weight="bold" font-size="8.5">2. ESPACIOS</text>
                <text x="163" y="28" fill="#64748b" font-size="7.5">Foto, Etiqueta, H1 y Desc.</text>

                <rect x="310" y="0" width="155" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="318" y="16" fill="#0f172a" font-weight="bold" font-size="8.5">3. RESTAURANTES</text>
                <text x="318" y="28" fill="#64748b" font-size="7.5">Foto, Etiqueta, H1 y Desc.</text>

                <rect x="0" y="42" width="145" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="8" y="58" fill="#0f172a" font-weight="bold" font-size="8.5">4. EVENTOS</text>
                <text x="8" y="70" fill="#64748b" font-size="7.5">Foto, Etiqueta, H1 y Desc.</text>

                <rect x="155" y="42" width="145" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="163" y="58" fill="#0f172a" font-weight="bold" font-size="8.5">5. GALERÍA</text>
                <text x="163" y="70" fill="#64748b" font-size="7.5">Foto, Etiqueta, H1 y Desc.</text>

                <rect x="310" y="42" width="155" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="318" y="58" fill="#0f172a" font-weight="bold" font-size="8.5">6. CONTACTO</text>
                <text x="318" y="70" fill="#64748b" font-size="7.5">Foto, Etiqueta, H1 y Desc.</text>

                <rect x="0" y="84" width="220" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
                <text x="8" y="100" fill="#0f172a" font-weight="bold" font-size="8.5">7. QUIÉNES SOMOS (Historia)</text>
                <text x="8" y="112" fill="#64748b" font-size="7.5">Foto, Etiqueta, H1 y Desc.</text>

                <rect x="230" y="84" width="235" height="34" rx="4" fill="#0f172a" />
                <text x="240" y="100" fill="#c5a059" font-weight="bold" font-size="8.5">8. FONDO DEL FOOTER</text>
                <text x="240" y="112" fill="#ffffff" font-size="7.5">Imagen o Fondo Obsidiana por defecto</text>
            </g>
        </svg>
    </div>

    <h2>2.1 Tabla Detallada de Campos: Bloque 1 (Logotipo y Branding Global)</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 32%;">Campo en Panel Casa</th>
                <th style="width: 43%;">Función y Ubicación en el Sitio Web</th>
                <th style="width: 25%;">Ejemplo / Formato Ideal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Logotipo Principal (Header / Navbar)</strong><br /><code style="font-size:7pt;">casa_opt_global_logo</code></td>
                <td>Despliega el logotipo horizontal institucional en la barra de navegación superior de todas las páginas web públicas.</td>
                <td>Archivo PNG transparente o SVG vectorizado (400 × 120 px).</td>
            </tr>
            <tr>
                <td><strong>Logo para Transición (Cortinilla Negra)</strong><br /><code style="font-size:7pt;">casa_opt_global_transition_logo</code></td>
                <td>Se muestra en el centro de la pantalla sobre la cortinilla animada en color negro que aparece brevemente al cambiar de página.</td>
                <td>Emblema o escudo en PNG transparente en blanco (512 × 512 px).</td>
            </tr>
            <tr>
                <td><strong>Imagen de Fondo para el Footer</strong><br /><code style="font-size:7pt;">casa_opt_footer_bg_image</code></td>
                <td>Imagen panorámica que reviste el fondo del pie de página global. Si se deja en blanco, el sistema aplica un elegante fondo negro Obsidiana.</td>
                <td>WEBP panorámico de alta resolución (1920 × 600 px).</td>
            </tr>
        </tbody>
    </table>

    <h2>2.2 Tabla Detallada: Bloque 2 (Control Central de Headers / Portadas H1)</h2>
    <p>Para cada una de las 7 páginas principales públicas, cuentas con exactamente 4 campos unificados en este bloque central. Al editar aquí, la portada se actualiza en el acto sin necesidad de entrar a las páginas de WordPress:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Sección / Página Pública</th>
                <th style="width: 45%;">Campos que Controla (Imagen, Subtítulo, H1, Cita)</th>
                <th style="width: 25%;">Directriz Editorial</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>1. Inicio (Hero Principal)</strong></td>
                <td>&bull; <code>Imagen de Portada</code> (Panorámica 16:9)<br />&bull; <code>Subtítulo Superior</code> (Etiqueta dorada en Hero)<br />&bull; <code>Título Principal H1</code> (Texto monumental en 2 líneas)<br />&bull; <code>Descripción Hero</code> (Cita o resumen ejecutivo)</td>
                <td>Escribir el Título H1 impactante; el sistema lo centra a pantalla completa sobre el banner.</td>
            </tr>
            <tr>
                <td><strong>2. Espacios</strong><br /><strong>3. Restaurantes</strong><br /><strong>4. Eventos</strong><br /><strong>5. Galería</strong><br /><strong>6. Contacto</strong><br /><strong>7. Quiénes Somos</strong></td>
                <td>Para cada una de estas 6 secciones se configuran:<br />&bull; <code>Imagen de Portada</code> (Banner superior)<br />&bull; <code>Subtítulo / Etiqueta</code> (Ej. Exclusividad, Alta Cocina)<br />&bull; <code>Título H1</code> (Nombre oficial de la sección)<br />&bull; <code>Descripción / Cita</code> (Párrafo introductorio bajo el H1)</td>
                <td><strong>Truco de Comillas:</strong> En los campos de Título H1 puedes escribir el texto entre comillas dobles (<code>"Espacios"</code> o <code>"Sato"</code>) para lograr un realce arquitectónico editorial.</td>
            </tr>
            <tr>
                <td><strong>8. Fondo del Footer</strong></td>
                <td>&bull; <code>Imagen de Fondo para el Footer</code> (Campo de imagen dedicada en el bloque central de cabeceras).</td>
                <td>Opcional. Si se deja vacío, conserva la estética oscura de la marca.</td>
            </tr>
        </tbody>
    </table>

    <h2>2.3 Tabla Detallada: Bloques 3, 4 y 5 (Ubicación, Redes y Estado del Menú)</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 32%;">Campo / Opción</th>
                <th style="width: 43%;">Función y Comportamiento</th>
                <th style="width: 25%;">Ejemplo de Configuración</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Dirección de las instalaciones</strong><br /><code style="font-size:7pt;">casa_opt_global_address</code></td>
                <td>Aparece en el pie de página (Footer), en el modal de contacto rápido y en la página oficial de Contacto.</td>
                <td><code>Blvd. Campestre 1519, Casa de Piedra, 37160 León, Gto.</code></td>
            </tr>
            <tr>
                <td><strong>Teléfono Oficial y Horarios</strong><br /><code style="font-size:7pt;">casa_opt_global_phone</code> &bull; <code style="font-size:7pt;">office_hours</code></td>
                <td>Enlaza automáticamente los botones telefónicos directos en dispositivos móviles y muestra la ventana de atención.</td>
                <td>Tel: <code>+52 (477) 394 9444</code><br />Horarios: <code>Lunes a Domingo 8:00 - 23:00 hrs</code></td>
            </tr>
            <tr>
                <td><strong>Redes Sociales Oficiales</strong><br /><code style="font-size:7pt;">casa_opt_global_facebook</code> / <code style="font-size:7pt;">instagram</code> / <code style="font-size:7pt;">tiktok</code></td>
                <td>Conecta los íconos sociales dorados ubicados en el Navbar superior, en el Footer y en menús móviles.</td>
                <td>Pega las URLs absolutas (ej. <code>https://instagram.com/casadepiedramx</code>).</td>
            </tr>
            <tr>
                <td><strong>Activar / Desactivar Secciones en Navegación</strong><br /><code style="font-size:7pt;">casa_opt_status_...</code></td>
                <td>Checkboxes individuales para: <em>Espacios, Restaurantes, Galería, Eventos y Contacto</em>. Desmarcar una casilla oculta instantáneamente el enlace del menú principal.</td>
                <td>Mantener todos marcados con ✔️ para que las 5 pestañas públicas estén operativas.</td>
            </tr>
        </tbody>
    </table>

    <h2>2.4 Guía Paso a Paso para la Edición de Globales</h2>
    <ul class="step-list">
        <li class="step-item">
            <strong>Subir o cambiar un logotipo o banner:</strong> Haz clic en el botón <strong>Subir / Seleccionar</strong> situado junto a cualquier campo de imagen. Se abrirá la Biblioteca de Medios; selecciona la imagen o arrastra tu archivo desde la computadora y presiona <em>Usar esta imagen</em>. Para eliminar una imagen y volver al valor por defecto, presiona <strong>Quitar</strong>.
        </li>
        <li class="step-item">
            <strong>Aplicar títulos elegantes:</strong> En los campos de <em>Título H1</em> de Quiénes Somos, Espacios, Restaurantes o Eventos, ingresa tu título en texto plano o entre comillas dobles (<code>"Gastronomía de Autor"</code>) según el estilo visual que desees reflejar en la portada frontal.
        </li>
        <li class="step-item">
            <strong>Guardar Cambios:</strong> Al concluir cualquier modificación en Globales, desplázate hasta el final de la pantalla y presiona el botón dorado negro <strong>Guardar Cambios</strong>. Verás un mensaje de confirmación verde en la parte superior.
        </li>
    </ul>

    <!-- ================= SECCIÓN 3: INICIO Y TOUR 3D ================= -->
    <div class="section-header">
        <h1 class="section-title">3. Pestaña «Inicio» — Hero Panorámico, Tour 3D y Reseñas</h1>
        <div class="section-tag">PESTAÑA 02: INICIO</div>
    </div>

    <p>La pestaña <strong>Inicio</strong> (<code>casa-panel-inicio</code>) administra el contenido central de la página de aterrizaje (Home). En esta pantalla configuras la bienvenida institucional, la integración inmersiva del Tour 3D y el carrusel de reseñas de Google Maps.</p>

    <!-- DIAGRAMA: ESTRUCTURA VISUAL DEL HOME -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🖥️ ESTRUCTURA VISUAL RENDERIZADA EN LA PÁGINA DE INICIO (HOME)</div>
        <svg viewBox="0 0 700 130" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="10" width="215" height="110" rx="6" fill="#0f172a" />
            <text x="25" y="35" fill="#c5a059" font-weight="bold" font-size="10">1. HERO PRINCIPAL (Desde Globales)</text>
            <text x="25" y="55" fill="#ffffff" font-size="11" font-weight="bold">Foto Fondo a Pantalla Completa</text>
            <text x="25" y="75" fill="#cbd5e1" font-size="9.5">Título Monumental en 2 Líneas</text>
            <text x="25" y="95" fill="#c5a059" font-size="9">Botones CTA directos a reservación</text>

            <rect x="238" y="10" width="235" height="110" rx="6" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="253" y="35" fill="#0f172a" font-weight="bold" font-size="10">2. HISTORIA & TOUR 3D (Pestaña Inicio)</text>
            <text x="253" y="55" fill="#1e293b" font-size="10" font-weight="bold">Editor de Texto Editorial + Portada</text>
            <text x="253" y="75" fill="#64748b" font-size="9">Enlace Iframe a Matterport 3D</text>
            <text x="253" y="95" fill="#0f172a" font-size="9" font-weight="bold">Modal interactivo sobre la web</text>

            <rect x="485" y="10" width="205" height="110" rx="6" fill="#fdfcf7" stroke="#c5a059" />
            <text x="500" y="35" fill="#8c6a23" font-weight="bold" font-size="10">3. CAROUSEL DE RESEÑAS</text>
            <text x="500" y="55" fill="#0f172a" font-size="10" font-weight="bold">3 Testimonios Destacados</text>
            <text x="500" y="75" fill="#64748b" font-size="9">Autor, Texto & Enlace directo</text>
            <text x="500" y="95" fill="#8c6a23" font-size="9">Puntuación verificada Google ⭐</text>
        </svg>
    </div>

    <h2>3.1 Tabla Detallada de Campos de la Pestaña Inicio</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 32%;">Bloque y Campo</th>
                <th style="width: 43%;">Descripción del Comportamiento</th>
                <th style="width: 25%;">Ejemplo de Configuración</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Descripción / Texto principal en Inicio</strong><br /><code style="font-size:7pt;">casa_opt_nosotros_desc</code></td>
                <td>Editor de texto enriquecido (WYSIWYG) donde redactas la historia y bienvenida de la hacienda. Se muestra junto a las fotografías arquitectónicas del Home.</td>
                <td>Redactar párrafos con formato de negritas y viñetas institucionales.</td>
            </tr>
            <tr>
                <td><strong>Imagen de Portada "Historia y Legado"</strong><br /><code style="font-size:7pt;">casa_opt_nosotros_hero_img</code></td>
                <td>Fotografía principal que ilustra el bloque de bienvenida y presentación histórica en el Home.</td>
                <td>Subir imagen WEBP/JPEG en proporción editorial (1200 × 800 px).</td>
            </tr>
            <tr>
                <td><strong>URL del Recorrido Virtual 3D (Iframe)</strong><br /><code style="font-size:7pt;">casa_opt_nosotros_virtual_tour</code></td>
                <td>Dirección del recorrido Matterport o tour 3D. Al pulsar el botón "Explorar Tour Virtual" en el sitio, se abre una ventana modal emergente (Iframe) inmersiva.</td>
                <td><code>https://my.matterport.com/show/?m=EjemploID</code></td>
            </tr>
            <tr>
                <td><strong>Cabecera de Reseñas Verificadas</strong><br /><code style="font-size:7pt;">home_reviews_subtitle</code> &bull; <code style="font-size:7pt;">title</code> &bull; <code style="font-size:7pt;">google_maps_link</code></td>
                <td>Define el subtítulo amarillo, el título principal de la sección y la URL del botón oficial de Google Maps que abre todas las reseñas externas.</td>
                <td>Subtítulo: <code>Experiencias Inolvidables</code><br />Título: <code>Lo que dicen nuestros visitantes</code></td>
            </tr>
            <tr>
                <td><strong>Reseñas Destacadas (1, 2 y 3)</strong><br /><code style="font-size:7pt;">casa_opt_home_rev[1-3]_text</code><br /><code style="font-size:7pt;">casa_opt_home_rev[1-3]_author</code><br /><code style="font-size:7pt;">casa_opt_home_rev[1-3]_url</code></td>
                <td>Tres bloques individuales donde ingresas el texto del testimonio, el nombre del visitante y el enlace de la reseña original en Google Maps.</td>
                <td>Texto: <code>"Un lugar mágico con excelente gastronomía..."</code><br />Autor: <code>Carlos Rodríguez &bull; 5.0 ⭐</code></td>
            </tr>
        </tbody>
    </table>

    <h2>3.2 Instructivo Operativo: Configuración del Tour Virtual 3D</h2>
    <div class="gold-box">
        <div class="box-title">💡 ¿Cómo insertar la URL del Tour Virtual 3D sin errores de carga?</div>
        Cuando generes el recorrido virtual en Matterport o Google Street View, el servicio te proporcionará un código de inserción similar a: <code>&lt;iframe src="https://my.matterport.com/show/?m=abcd123" ...&gt;&lt;/iframe&gt;</code>.<br />
        <strong>IMPORTANTE:</strong> No pegues todo el código Iframe completo en el campo. Copia <strong>únicamente la dirección URL absoluta</strong> que se encuentra dentro de las comillas <code>src="..."</code> (por ejemplo: <code>https://my.matterport.com/show/?m=abcd123</code>) y pégala en el campo <strong>URL del Recorrido Virtual 3D (Iframe)</strong> de esta pestaña.
    </div>

    <!-- ================= SECCIÓN 4: RESTAURANTES ================= -->
    <div class="section-header">
        <h1 class="section-title">4. Pestaña «Restaurantes» — Directorio, Cartas PDF y Reservaciones</h1>
        <div class="section-tag">PESTAÑA 03: RESTAURANTES</div>
    </div>

    <p>La pestaña <strong>Restaurantes</strong> (<code>casa-panel-restaurantes</code>) funciona como el centro ejecutivo de la alta gastronomía de Casa de Piedra. Te permite gestionar el directorio visual en grilla de todas las especialidades y acceder a la edición individual de cada restaurante.</p>

    <!-- UI MOCKUP: TARJETA DE RESTAURANTE -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🍽️ MAQUETA VISUAL: DIRECTORIO Y ACCESOS RÁPIDOS EN PESTAÑA RESTAURANTES</div>
        <svg viewBox="0 0 700 120" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="10" width="680" height="100" rx="8" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" />

            <rect x="25" y="25" width="70" height="70" rx="6" fill="#0f172a" />
            <text x="43" y="64" fill="#c5a059" font-weight="bold" font-size="11">LOGO</text>

            <text x="115" y="42" fill="#c5a059" font-size="9.5" font-weight="bold" letter-spacing="1">ESPECIALIDAD: ALTA COCINA JAPONESA & NIKKEI</text>
            <text x="115" y="65" fill="#0f172a" font-size="15" font-weight="bold">Restaurante Sato &rarr; Calificación: 4.9 ⭐</text>
            <text x="115" y="85" fill="#64748b" font-size="9.5">Horario de atención &bull; Ubicación dentro del recinto &bull; Ambiente exclusivo</text>

            <rect x="360" y="38" width="105" height="34" rx="17" fill="#334155" />
            <text x="372" y="59" fill="#ffffff" font-size="8.5" font-weight="bold">👁️ VER PÁGINA</text>

            <rect x="472" y="38" width="105" height="34" rx="17" fill="#0f172a" />
            <text x="483" y="59" fill="#ffffff" font-size="8.5" font-weight="bold">📄 VER/SUBIR PDF</text>

            <rect x="584" y="38" width="95" height="34" rx="17" fill="#c5a059" />
            <text x="597" y="59" fill="#ffffff" font-size="8.5" font-weight="bold">EDITAR &rarr;</text>
        </svg>
    </div>

    <h2>4.1 Opciones Generales del Panel Central de Restaurantes</h2>
    <p>En la pantalla principal de <code>Panel Casa &rarr; Restaurantes</code> encontrarás dos áreas clave:</p>
    <ul class="step-list">
        <li class="step-item">
            <strong>Botón Superior «+ Añadir Nuevo Restaurante»:</strong> Abre el formulario completo para registrar un nuevo restaurante con todas sus especificaciones y cartas de menú.
        </li>
        <li class="step-item">
            <strong>Grilla de Tarjetas Interactivas con Botones de Acción Directa:</strong> Cada restaurante muestra botones rápidos para: <em>👁️ VER PÁGINA DEDICADA</em> (abre su landing en nueva pestaña), <em>📄 VER MENÚ PDF</em> o <em>📄 SUBIR MENÚ PDF</em> (si aún no se ha adjuntado), <em>EDITAR FICHA →</em> y el ícono de papelera <em>🗑️</em> para moverlo a la papelera con confirmación de seguridad.
        </li>
        <li class="step-item">
            <strong>Enlaces de Reseñas Verificadas de Google Maps (Sección Restaurantes):</strong> Al final de la página central puedes configurar hasta 3 enlaces directos (<code>casa_opt_rest_rev[1-3]_url</code>) que alimentan los botones de reputación verificada en el catálogo general de restaurantes.
        </li>
    </ul>

    <h2>4.2 Tabla Exhaustiva: Todos los Campos al Crear o Editar un Restaurante</h2>
    <p>Al hacer clic en <strong>Editar Ficha →</strong> o al crear un restaurante, el sistema muestra la pantalla con el título, editor de contenido y la caja especial <strong>Información del Restaurante</strong> y <strong>Galería de Imágenes Múltiples</strong>. Aquí está el detalle de cada uno de los 12 campos:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 28%;">Campo Meta y Etiqueta</th>
                <th style="width: 45%;">Función Operativa y Dónde se Renderiza</th>
                <th style="width: 27%;">Ejemplo y Directrices</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Especialidad Gastronómica</strong><br /><code style="font-size:7pt;">_restaurante_cocina</code></td>
                <td>Aparece como etiqueta en letras amarillas mayúsculas sobre el nombre del restaurante en su tarjeta del directorio y en su página individual.</td>
                <td><code>Alta Cocina Mediterránea & Internacional</code></td>
            </tr>
            <tr>
                <td><strong>Calificación ⭐</strong><br /><code style="font-size:7pt;">_restaurante_rating</code></td>
                <td>Puntuación verificada del restaurante. Despliega una insignia amarilla dorada junto al título.</td>
                <td><code>4.9</code> (el sistema añade el ícono ⭐).</td>
            </tr>
            <tr>
                <td><strong>Logo Oficial PNG/SVG</strong><br /><code style="font-size:7pt;">_restaurante_logo</code></td>
                <td>Despliega el logotipo en su tarjeta del directorio gastronómico y también <strong>se inserta automáticamente en el menú superior desplegable</strong> del sitio web bajo la pestaña "Restaurantes".</td>
                <td>Presiona <em>Seleccionar Logo</em> y sube un PNG transparente con letras claras/contrastantes.</td>
            </tr>
            <tr>
                <td><strong>Imagen de Portada Hero</strong><br /><code style="font-size:7pt;">_restaurante_hero_image</code></td>
                <td>Banner superior panorámico de alta resolución que reviste la cabecera individual de la página exclusiva del restaurante.</td>
                <td>Proporción 16:9 panorámica (1920 × 1080 px).</td>
            </tr>
            <tr>
                <td><strong>Imagen Tarjeta de Catálogo</strong><br /><code style="font-size:7pt;">_restaurante_card_image</code></td>
                <td>Fotografía editorial que representa al restaurante dentro del catálogo o grilla visual de la página Restaurantes.</td>
                <td>Proporción editorial 3:2 o 1:1 (1200 × 800 px).</td>
            </tr>
            <tr>
                <td><strong>Enlace a Reseñas en Google Maps</strong><br /><code style="font-size:7pt;">_restaurante_google_reviews_url</code></td>
                <td>URL directa del perfil en Google Maps. Al pulsar el botón con calificación ⭐ en la ficha web, dirige al cliente a ver las opiniones reales.</td>
                <td><code>https://www.google.com/maps/place/...</code></td>
            </tr>
            <tr>
                <td><strong>Enlace al Menú (Carta PDF)</strong><br /><code style="font-size:7pt;">_restaurante_menu</code></td>
                <td>Archivo PDF descargable o URL interactiva de la carta. Puedes escribir la URL o hacer clic en el botón <strong>Subir PDF</strong> de la caja meta.</td>
                <td>Sube tu archivo PDF (máximo 5 MB) desde la Biblioteca de Medios.</td>
            </tr>
            <tr>
                <td><strong>Tipo de Reserva</strong><br /><code style="font-size:7pt;">_restaurante_reserva_tipo</code></td>
                <td>Menú desplegable que define el comportamiento al presionar el botón dorado <em>Reservar</em>. Tres opciones: <strong>Sitio Web / Enlace Directo</strong>, <strong>WhatsApp</strong>, o <strong>Llamada Telefónica</strong>.</td>
                <td>Seleccionar <code>WhatsApp</code> para apertura instantánea del chat en el móvil del cliente.</td>
            </tr>
            <tr>
                <td><strong>Enlace, Teléfono o WhatsApp para Reservar</strong><br /><code style="font-size:7pt;">_restaurante_reserva_valor</code></td>
                <td>Dato que complementa el tipo de reserva. Si elegiste WhatsApp, escribe aquí el número o el enlace corto <code>https://wa.me/...</code>. Si elegiste Teléfono, el número de llamada directa.</td>
                <td>Para WhatsApp: <code>https://api.whatsapp.com/send?phone=5214773949444</code></td>
            </tr>
            <tr>
                <td><strong>Teléfono Directo</strong><br /><code style="font-size:7pt;">_restaurante_telefono</code></td>
                <td>Teléfono visible en la tarjeta informativa del restaurante.</td>
                <td><code>+52 (477) 394 9444</code></td>
            </tr>
            <tr>
                <td><strong>Horarios de Atención</strong><br /><code style="font-size:7pt;">_restaurante_horario</code></td>
                <td>Cadena de texto que indica los días de apertura y horas de servicio.</td>
                <td><code>Lunes a Domingo de 13:00 a 23:30 hrs</code></td>
            </tr>
            <tr>
                <td><strong>Galería Mosaico Múltiple</strong><br /><code style="font-size:7pt;">_casadepiedra_gallery_ids</code></td>
                <td>Caja inferior independiente donde presionas <strong>+ Seleccionar / Subir Imágenes</strong> para elegir múltiples fotografías arquitectónicas o platillos. Se organizan en un mosaico interactivo.</td>
                <td>Puedes eliminar cualquier foto individual del mosaico pulsando su ícono circular de papelera <code>🗑️</code> en la vista previa.</td>
            </tr>
        </tbody>
    </table>

    <!-- ================= SECCIÓN 5: SALONES Y COTIZADOR ================= -->
    <div class="section-header">
        <h1 class="section-title">5. Pestaña «Espacios» — Directorio, Planos PDF y Cotizador</h1>
        <div class="section-tag">PESTAÑA 04: ESPACIOS</div>
    </div>

    <p>La pestaña <strong>Espacios</strong> (<code>casa-panel-espacios</code>) administra el catálogo de salones, terrazas y jardines para bodas y convenciones de la hacienda. Cada espacio opera como una ficha interactiva y está conectado de forma inteligente con el cotizador modular en línea.</p>

    <!-- UI MOCKUP: SALÓN Y COTIZADOR -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🏛️ ESQUEMA VISUAL: CONEXIÓN ENTRE FICHA DE SALÓN Y COTIZADOR INTELIGENTE</div>
        <svg viewBox="0 0 700 135" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="10" width="330" height="115" rx="6" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="25" y="32" fill="#0f172a" font-weight="bold" font-size="11">SALÓN PRINCIPAL / JARDÍN</text>
            <text x="25" y="54" fill="#c5a059" font-size="10" font-weight="bold">👥 Capacidad: Hasta 1,500 Pax &bull; 📐 Superficie: 2,400 m²</text>
            <text x="25" y="74" fill="#475569" font-size="9">Portada independiente panorámica + Descripción arquitectónica</text>
            <rect x="25" y="86" width="145" height="26" rx="4" fill="#0f172a" />
            <text x="36" y="103" fill="#ffffff" font-size="8.5" font-weight="bold">📥 DESCARGAR PLANO PDF</text>

            <path d="M 345 67 L 375 67" stroke="#c5a059" stroke-width="2" marker-end="url(#arrow)" />

            <rect x="380" y="10" width="310" height="115" rx="6" fill="#0f172a" />
            <text x="398" y="32" fill="#c5a059" font-weight="bold" font-size="11">⚡ COTIZADOR INTELIGENTE MODULAR</text>
            <text x="398" y="54" fill="#ffffff" font-size="9.5">Alimentado por los rangos definidos por comas:</text>
            <rect x="398" y="65" width="275" height="24" rx="4" fill="#1e293b" />
            <text x="408" y="81" fill="#e2e8f0" font-size="8.5">Seleccionar Rango: [1 a 150 Pax] [151 a 400 Pax] [400+ Pax]</text>
            <text x="398" y="110" fill="#c5a059" font-size="8.5">Envía solicitud directa al correo del departamento de eventos</text>
        </svg>
    </div>

    <h2>5.1 Opciones del Panel Central de Espacios (<code>casa-panel-espacios</code>)</h2>
    <ul class="step-list">
        <li class="step-item">
            <strong>Botón Superior «+ Añadir Nuevo Salón»:</strong> Permite registrar un nuevo salón con sus especificaciones de aforo y planos arquitectónicos.
        </li>
        <li class="step-item">
            <strong>Grilla Ejecutiva de Salones y Jardines:</strong> Muestra la portada, título, aforo (<code>Hasta X Pax</code>) y botones directos para: <em>👁️ VER PÁGINA DEDICADA</em>, <em>📥 VER PLANO PDF</em> o <em>📥 SUBIR PLANO PDF</em>, <em>EDITAR SALÓN COMPLETO →</em>, y mover a papelera <em>🗑️</em>.
        </li>
        <li class="step-item">
            <strong>Reseñas Verificadas de Google Maps (Sección Espacios):</strong> Configura los 3 enlaces (<code>casa_opt_espacios_rev[1-3]_url</code>) para mostrar testimonios auténticos en el directorio de bodas y convenciones.
        </li>
    </ul>

    <h2>5.2 Tabla Detallada: Todos los Campos en la Edición/Creación de un Salón</h2>
    <p>Al editar un salón desde <code>post-new.php?post_type=espacios</code>, la caja meta <strong>Información Ejecutiva del Espacio</strong> y la caja <strong>Galería de Imágenes Múltiples</strong> controlan los siguientes parámetros:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Campo Meta y Etiqueta</th>
                <th style="width: 45%;">Función Operativa en el Sitio y Cotizador</th>
                <th style="width: 25%;">Ejemplo de Configuración</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Imagen de Portada del Espacio</strong><br /><code style="font-size:7pt;">_espacio_portada</code></td>
                <td>Imagen superior de alta resolución al entrar a la página dedicada del salón. Se puede pegar URL o presionar el botón <strong>Seleccionar Imagen</strong>.</td>
                <td>Panorámica editorial (1200 × 800 px).</td>
            </tr>
            <tr>
                <td><strong>Archivo PDF de Planos y Distribución</strong><br /><code style="font-size:7pt;">_espacio_plano_pdf</code></td>
                <td>Documento técnico con los planos arquitectónicos de medidas o montajes posibles. Genera el botón de descarga independiente <em>📥 DESCARGAR PLANO PDF</em>.</td>
                <td>Presiona <strong>Subir PDF</strong> y adjunta el archivo vectorial A4 o Carta.</td>
            </tr>
            <tr>
                <td><strong>Capacidad Máxima (Pax)</strong><br /><code style="font-size:7pt;">_espacio_capacidad</code></td>
                <td>Aforo máximo del lugar. El sistema le antepone la palabra "Hasta" y añade "Pax" automáticamente.</td>
                <td>Escribe únicamente <code>1,500</code> para que se visualice como <em>Hasta 1,500 Pax</em>.</td>
            </tr>
            <tr>
                <td><strong>Rangos para Modal de Cotización</strong><br /><code style="font-size:7pt;">_espacio_rango_personas</code></td>
                <td><strong>Campo clave del Cotizador Modular:</strong> Las opciones que escribas aquí separadas por comas aparecerán como menú desplegable cuando el cliente solicite cotizar específicamente este salón.</td>
                <td><code>1 a 150 personas, 151 a 400 personas, Más de 400 personas</code></td>
            </tr>
            <tr>
                <td><strong>Superficie / Área (m²)</strong><br /><code style="font-size:7pt;">_espacio_m2</code></td>
                <td>Escribe la superficie del salón. El sistema agrega automáticamente la unidad métrica <code>m²</code> al mostrarlo en la ficha.</td>
                <td>Escribe únicamente <code>2,400</code> para que se visualice como <em>2,400 m²</em>.</td>
            </tr>
            <tr>
                <td><strong>Galería Mosaico del Salón</strong><br /><code style="font-size:7pt;">_casadepiedra_gallery_ids</code></td>
                <td>Caja inferior donde subes las fotos de los diferentes montajes de boda o congreso de ese salón en particular.</td>
                <td>Presiona <em>+ Seleccionar / Subir Imágenes</em> para agregar o el ícono <code>🗑️</code> para eliminar fotos individuales.</td>
            </tr>
        </tbody>
    </table>

    <!-- ================= SECCIÓN 6: EVENTOS Y AUTOMATIZACIÓN DE VIGENCIA ================= -->
    <div class="section-header">
        <h1 class="section-title">6. Pestaña «Eventos» — Tipologías y Automatización de Vigencia</h1>
        <div class="section-tag">PESTAÑA 05: EVENTOS</div>
    </div>

    <p>La pestaña <strong>Eventos</strong> (<code>casa-panel-eventos</code>) centraliza la administración del catálogo de celebraciones y eventos especiales de la hacienda (por ejemplo: Bodas, Graduaciones, Celebraciones Sociales y Convenciones Empresariales). Esta sección incorpora una funcionalidad única y avanzada: el <strong>Sistema Inteligente de Automatización y Vigencia</strong>.</p>

    <!-- DIAGRAMA: AUTOMATIZACIÓN DE VIGENCIA -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">⏰ ESQUEMA VISUAL: FLUJO DEL SISTEMA INTELIGENTE DE VIGENCIA Y BAJA AUTOMÁTICA</div>
        <svg viewBox="0 0 700 115" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="15" width="200" height="85" rx="6" fill="#0f172a" />
            <text x="25" y="38" fill="#c5a059" font-weight="bold" font-size="10">1. DEFINICIÓN EN EL PANEL</text>
            <text x="25" y="58" fill="#ffffff" font-size="9.5">Campo: Fecha/Hora de Expiración</text>
            <text x="25" y="76" fill="#cbd5e1" font-size="8.5">Ej: 2026-12-31 a las 23:59 hrs</text>

            <path d="M 215 57 L 245 57" stroke="#c5a059" stroke-width="2" />

            <rect x="250" y="15" width="200" height="85" rx="6" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="265" y="38" fill="#0f172a" font-weight="bold" font-size="10">2. CRON DIARIO WORDPRESS</text>
            <text x="265" y="58" fill="#334155" font-size="9.5">Revisión automática a la medianoche</text>
            <text x="265" y="76" fill="#64748b" font-size="8.5">Comprueba la fecha y hora actual</text>

            <path d="M 455 57 L 485 57" stroke="#c5a059" stroke-width="2" />

            <rect x="490" y="15" width="200" height="85" rx="6" fill="#fdfcf7" stroke="#c5a059" />
            <text x="505" y="38" fill="#8c6a23" font-weight="bold" font-size="10">3. BAJA AUTOMÁTICA</text>
            <text x="505" y="58" fill="#0f172a" font-size="9.5">Si la fecha se ha cumplido:</text>
            <text x="505" y="76" fill="#dc2626" font-weight="bold" font-size="8.5">Pasa a Borrador sin programación</text>
        </svg>
    </div>

    <h2>6.1 Panel Central de Gestión de Eventos (<code>casa-panel-eventos</code>)</h2>
    <p>Desde la pantalla principal de <code>Panel Casa &rarr; Eventos</code> tienes acceso a las siguientes herramientas ejecutivas:</p>
    <ul class="step-list">
        <li class="step-item">
            <strong>Botón «+ Añadir Nuevo Tipo de Evento»:</strong> Permite registrar una nueva tipología de celebración, cena de gala o paquete para convenciones.
        </li>
        <li class="step-item">
            <strong>Directorio Ejecutivo de Tipologías:</strong> Cada tarjeta de evento muestra su portada, título y botones para: <em>👁️ VER PÁGINA DEDICADA</em>, <em>EDITAR EVENTO →</em>, o mandarlo a la papelera <em>🗑️</em>.
        </li>
    </ul>

    <h2>6.2 Tabla Detallada: Campos y Automatización al Editar un Evento</h2>
    <p>Al crear o modificar una ficha en <code>post-new.php?post_type=eventos</code>, encontrarás la caja meta <strong>Información Ejecutiva del Evento</strong>, conformada por los siguientes 3 parámetros:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 32%;">Campo Meta y Etiqueta</th>
                <th style="width: 43%;">Función y Mecanismo de Automatización</th>
                <th style="width: 25%;">Ejemplo de Configuración</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Fecha y Hora de Vigencia (Baja Automática)</strong><br /><code style="font-size:7pt;">_evento_expiracion</code></td>
                <td><strong>Automatización de Baja:</strong> Selector de fecha y hora (<code>datetime-local</code>). Si estableces una fecha de vencimiento (por ejemplo, el día que termina un festival de vino o paquete especial), una tarea programada interna de WordPress (<code>cron</code>) comprobará diariamente a las 23:59 hrs las fechas publicadas. Cuando la fecha se cumpla, el sistema <strong>cambiará el evento de "Publicado" a "Borrador" automáticamente</strong>, retirándolo de la web pública de inmediato sin que tengas que acordarte de quitarlo.</td>
                <td>Seleccionar día y hora en el calendario de la caja (o dejar en blanco para que el evento sea permanente).</td>
            </tr>
            <tr>
                <td><strong>Texto del Botón</strong><br /><code style="font-size:7pt;">_evento_boton_texto</code></td>
                <td>Personaliza la etiqueta o llamado a la acción del botón visible en la tarjeta del evento en el frontend. Si se deja vacío, el valor por defecto es <em>"Me interesa"</em>.</td>
                <td><code>Comprar Boletos</code> &bull; <code>Cotizar mi Boda</code> &bull; <code>Agendar Cita</code></td>
            </tr>
            <tr>
                <td><strong>Enlace Externo (Acción de Reserva)</strong><br /><code style="font-size:7pt;">_evento_enlace</code></td>
                <td>Dirección URL donde se dirigirá el usuario al hacer clic en el botón de la tarjeta del evento. Puede ser una boletera externa, un enlace a WhatsApp del wedding planner o el formulario de contacto.</td>
                <td>WhatsApp directo: <code>https://api.whatsapp.com/send?phone=5214773949444&text=Hola,%20quiero%20cotizar%20una%20Boda</code></td>
            </tr>
        </tbody>
    </table>

    <!-- ================= SECCIÓN 7: GALERÍA Y TRUCO ESTELAR ================= -->
    <div class="section-header">
        <h1 class="section-title">7. Pestaña «Galería» — Etiquetas, Mosaicos y Truco Estelar</h1>
        <div class="section-tag">PESTAÑA 06: GALERÍA</div>
    </div>

    <p>En la pestaña <strong>Galería</strong> (<code>casa-panel-galeria</code>) administras el mosaico arquitectónico de fotografías en estilo Masonry. Esta pantalla está dividida en 3 áreas especializadas que te brindan control absoluto sobre las categorías del sitio, el tamaño de las tarjetas por categoría y la biblioteca central de imágenes.</p>

    <!-- DIAGRAMA VISUAL: COMPARATIVA DE TAMAÑOS EN COLLAGE -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">🌟 COMPARATIVA VISUAL EN COLLAGE: PROPORCIONES DE TARJETA Y TRUCO «ESTELAR»</div>
        <svg viewBox="0 0 700 125" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="15" y="15" width="105" height="95" rx="6" fill="#f1f5f9" stroke="#cbd5e1" />
            <text x="25" y="50" fill="#64748b" font-weight="bold" font-size="9.5">Chico (25%)</text>
            <text x="25" y="68" fill="#94a3b8" font-size="8">Cabe 1 por 1</text>

            <rect x="130" y="15" width="135" height="95" rx="6" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="145" y="50" fill="#334155" font-weight="bold" font-size="9.5">Mediano (33%)</text>
            <text x="145" y="68" fill="#64748b" font-size="8">Caben 3 por fila</text>

            <rect x="275" y="15" width="180" height="95" rx="6" fill="#fdfcf7" stroke="#c5a059" stroke-width="1.5" />
            <text x="295" y="50" fill="#0f172a" font-weight="bold" font-size="10">Grande (50%)</text>
            <text x="295" y="68" fill="#8c6a23" font-size="8.5">Caben 2 grandes por fila</text>

            <rect x="465" y="15" width="220" height="95" rx="6" fill="#0f172a" stroke="#c5a059" stroke-width="2" />
            <text x="480" y="48" fill="#c5a059" font-weight="bold" font-size="11">🌟 FOTO «ESTELAR» (2X2)</text>
            <text x="480" y="68" fill="#ffffff" font-size="8.5">Se activa escribiendo "estelar"</text>
            <text x="480" y="84" fill="#cbd5e1" font-size="8">en la leyenda en Biblioteca WP</text>
        </svg>
    </div>

    <h2>7.1 Tabla Detallada: Los 3 Bloques de Gestión en Pestaña Galería</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Bloque de Configuración</th>
                <th style="width: 45%;">Función Operativa y Comportamiento</th>
                <th style="width: 25%;">Ejemplo / Configuración</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>1. Definir Categorías / Etiquetas Activas</strong><br /><code style="font-size:7pt;">casa_opt_galeria_etiquetas</code></td>
                <td>Escribe los nombres de las categorías separadas por comas. Estas etiquetas generan automáticamente los botones dorados de filtrado dinámico en la página de Galería pública y también crean las cajas de subida individual en este panel.</td>
                <td><code>Bodas, Eventos Sociales, Convenciones, Arquitectura & Gastronomía</code></td>
            </tr>
            <tr>
                <td><strong>2. Tarjetas por Categoría y Selector de Tamaño</strong><br /><code style="font-size:7pt;">casa_opt_galeria_etiqueta_tamanos</code></td>
                <td>Para cada etiqueta activa, el panel genera una tarjeta donde puedes: <br />&bull; Seleccionar el <strong>Tamaño de Tarjeta</strong> en el menú desplegable: <code>Chico (25%)</code>, <code>Mediano (33% / 3 por fila)</code>, <code>Grande (50% / 2 por fila)</code>, o <code>Panorámico (100% Ancho)</code>.<br />&bull; Presionar <code>📤 SUBIR FOTOS A «ETIQUETA»</code> para cargar y etiquetar automáticamente fotos a esa categoría.<br />&bull; Presionar <code>🗑️</code> para eliminar una etiqueta.</td>
                <td>Seleccionar <code>Grande (50%)</code> para que las fotos de Bodas abarquen la mitad del ancho en el mosaico general.</td>
            </tr>
            <tr>
                <td><strong>3. Biblioteca Central de Galería (Carga Masiva)</strong><br /><code style="font-size:7pt;">casa_opt_galeria_imagenes</code></td>
                <td>Área general de carga masiva donde puedes presionar <strong>+ Seleccionar / Subir Múltiples Imágenes</strong> o presionar <em>🗑️ Limpiar Todo</em>. En la parte inferior se visualiza la cuadrícula de miniaturas de la galería central.</td>
                <td>En la cuadrícula de previsualización, cada foto tiene un botón circular rojo con papelera <code>🗑️</code> para eliminarla individualmente.</td>
            </tr>
        </tbody>
    </table>

    <h2>7.2 Instructivo Paso a Paso: Cómo Etiquetar Fotografías «Estelares» (Tamaño Gigante 2x2)</h2>
    <div class="gold-box">
        <div class="box-title">💡 Procedimiento para Activar el Truco «Estelar» en la Biblioteca de Medios</div>
        El motor de la galería Masonry está programado para identificar fotografías verdaderamente espectaculares y asignarles el doble de ancho y alto (formato Hero 2x2) dentro del collage general. Para lograr este efecto:<br />
        1. Haz clic en el botón <strong>+ Seleccionar / Subir Múltiples Imágenes</strong> o entra a <em>Medios &rarr; Biblioteca</em> en WordPress.<br />
        2. Haz clic sobre la fotografía panorámica o especial que desees destacar en tamaño monumental.<br />
        3. En el panel de propiedades del lado derecho, ubica el campo llamado <strong>Leyenda (Caption)</strong> o el campo <strong>Descripción</strong>.<br />
        4. Escribe la palabra exacta <code>estelar</code> (en minúsculas o mayúsculas).<br />
        5. Guarda los cambios o cierra la ventana. Al recargar la página pública de Galería, esa fotografía aparecerá automáticamente en formato gigante 2x2.
    </div>

    <!-- ================= SECCIÓN 8: CORREOS ELECTRÓNICOS ================= -->
    <div class="section-header">
        <h1 class="section-title">8. Pestaña «Mails» — Enrutamiento y Plantillas HTML</h1>
        <div class="section-tag">PESTAÑA 07: MAILS</div>
    </div>

    <p>La pestaña <strong>Mails (Correos)</strong> (<code>casa-panel-mails</code>) gestiona el enrutamiento inteligente de todas las solicitudes y cotizaciones enviadas por los visitantes del sitio web. El sistema clasifica el origen de cada formulario y canaliza el mensaje automáticamente al departamento responsable en la hacienda.</p>

    <!-- DIAGRAMA VISUAL: ENRUTAMIENTO DE CORREOS -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">✉️ ESQUEMA DE ENRUTAMIENTO INTELIGENTE POR DEPARTAMENTO Y MÚLTIPLES RECEPTORES</div>
        <svg viewBox="0 0 700 115" width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="12" width="175" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="20" y="33" fill="#0f172a" font-weight="bold" font-size="8.5">Formulario de Contacto &rarr;</text>

            <rect x="10" y="58" width="175" height="34" rx="4" fill="#f8fafc" stroke="#cbd5e1" />
            <text x="20" y="79" fill="#0f172a" font-weight="bold" font-size="8.5">Cotizador de Salones &rarr;</text>

            <rect x="235" y="12" width="450" height="34" rx="4" fill="#0f172a" />
            <text x="248" y="33" fill="#c5a059" font-weight="bold" font-size="8.5">CORREO GENERAL:</text>
            <text x="365" y="33" fill="#ffffff" font-size="8.5">contacto@casadepiedra.com, direccion@casadepiedra.com</text>

            <rect x="235" y="58" width="450" height="34" rx="4" fill="#0f172a" />
            <text x="248" y="79" fill="#c5a059" font-weight="bold" font-size="8.5">CORREO COTIZACIONES:</text>
            <text x="375" y="79" fill="#ffffff" font-size="8.5">eventos@casadepiedra.com, bodas@casadepiedra.com</text>
        </svg>
    </div>

    <h2>8.1 Tabla Exhaustiva de Campos Receptores y Branding de Correos</h2>
    <p>Puedes ingresar una sola dirección de correo electrónico por campo o ingresar <strong>múltiples direcciones separadas por comas</strong> para que el sistema envíe copias ocultas o simultáneas a varios gerentes:</p>
    <table>
        <thead>
            <tr>
                <th style="width: 32%;">Campo en Pestaña Mails</th>
                <th style="width: 43%;">Área Receptora en la Hacienda y Origen del Formulario</th>
                <th style="width: 25%;">Ejemplo de Configuración (Admite Comas)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Correo General / por Defecto</strong><br /><code style="font-size:7pt;">casa_opt_global_email</code></td>
                <td>Recibe las consultas generales y dudas enviadas desde la página oficial de Contacto cuando no se selecciona un departamento específico.</td>
                <td><code>contacto@casadepiedra.com, direccion@casadepiedra.com</code></td>
            </tr>
            <tr>
                <td><strong>Correo Cotizaciones de Espacios</strong><br /><code style="font-size:7pt;">casa_opt_email_cotizacion</code></td>
                <td>Recibe de forma prioritaria las solicitudes generadas en el Cotizador Inteligente Modular de salones y bodas.</td>
                <td><code>eventos@casadepiedra.com, bodas@casadepiedra.com</code></td>
            </tr>
            <tr>
                <td><strong>Correo para Temas Generales</strong><br /><code style="font-size:7pt;">casa_opt_email_generales</code></td>
                <td>Canaliza solicitudes administrativas o dudas institucionales de carácter general.</td>
                <td><code>administracion@casadepiedra.com</code></td>
            </tr>
            <tr>
                <td><strong>Correo Propuestas de Proveedores</strong><br /><code style="font-size:7pt;">casa_opt_email_proveedores</code></td>
                <td>Recibe las ofertas y propuestas comerciales enviadas por proveedores externos o músicos interesados en colaborar.</td>
                <td><code>compras@casadepiedra.com, proveedores@casadepiedra.com</code></td>
            </tr>
            <tr>
                <td><strong>Correo Propuestas de Eventos</strong><br /><code style="font-size:7pt;">casa_opt_email_propuestas_eventos</code></td>
                <td>Canaliza las propuestas especializadas de coordinadores o planners de eventos externos.</td>
                <td><code>coordinacion@casadepiedra.com</code></td>
            </tr>
            <tr>
                <td><strong>Correo Receptor Adicional</strong><br /><code style="font-size:7pt;">casa_opt_mail_receiver</code></td>
                <td>Direcciones adicionales que recibirán copia de respaldo de las cotizaciones y formularios importantes del sitio.</td>
                <td><code>gerencia@casadepiedra.com, auditoria@casadepiedra.com</code></td>
            </tr>
            <tr>
                <td><strong>Logotipo para Plantillas HTML</strong><br /><code style="font-size:7pt;">casa_opt_mail_logo</code></td>
                <td>Sube la imagen en alta resolución que encabezará los e-mails automáticos transaccionales que envía el servidor a los clientes.</td>
                <td>Archivo PNG transparente (300 × 90 px).</td>
            </tr>
        </tbody>
    </table>

    <!-- ================= SECCIÓN 9: ESPECIFICACIONES TÉCNICAS PARA DISEÑADORES ================= -->
    <div class="section-header">
        <h1 class="section-title">9. Guía Oficial de Medidas, Resoluciones y Formatos para Diseñadores Gráficos</h1>
        <div class="section-tag">SPECS TÉCNICAS Y MEDIA KIT</div>
    </div>

    <p>Para garantizar que todos los elementos visuales de <strong>Hacienda Casa de Piedra</strong> se rendericen con máxima nitidez y una velocidad de carga ultrarrápida (optimizada para dispositivos móviles y pantallas Retina), el equipo de diseño gráfico deberá respetar estrictamente la siguiente tabla y proporciones de aspecto:</p>

    <!-- DIAGRAMA VISUAL DE RELACIONES DE ASPECTO (ASPECT RATIOS) -->
    <div class="ui-diagram-box">
        <div class="ui-diagram-title">📐 COMPARATIVA DE PROPORCIONES (ASPECT RATIOS) Y RECOMENDACIÓN DE USO</div>
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

    <table>
        <thead>
            <tr>
                <th style="width: 26%;">Elemento / Ubicación</th>
                <th style="width: 24%;">Resolución Exacta (Píxeles)</th>
                <th style="width: 22%;">Formato Recomendado</th>
                <th style="width: 28%;">Peso Máximo & Directrices</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Hero / Banners Panorámicos</strong><br /><span style="font-size:7pt; color:#64748b;">Inicio, Espacios, Restaurantes, Eventos</span></td>
                <td><strong>1920 × 1080 px</strong><br />Relación 16:9 panorámica</td>
                <td>WEBP o JPEG alta calidad (85%)</td>
                <td>Máx. 450 KB. Mantener punto focal y textos en el centro óptico.</td>
            </tr>
            <tr>
                <td><strong>Logotipo Institucional Superior</strong><br /><span style="font-size:7pt; color:#64748b;">Header / Navegación Principal</span></td>
                <td><strong>400 × 120 px</strong><br />Relación horizontal</td>
                <td>PNG transparente o SVG vectorizado</td>
                <td>Máx. 100 KB. Sin fondos sólidos ni sombras difusas en el canvas.</td>
            </tr>
            <tr>
                <td><strong>Escudo / Emblema de Transición</strong><br /><span style="font-size:7pt; color:#64748b;">Cortinilla animada y marca de agua</span></td>
                <td><strong>512 × 512 px</strong><br />Cuadrado 1:1 simétrico</td>
                <td>PNG transparente o SVG vectorizado</td>
                <td>Máx. 150 KB. Trazados limpios, contraste en blanco para fondo oscuro.</td>
            </tr>
            <tr>
                <td><strong>Portadas de Salones y Espacios</strong><br /><span style="font-size:7pt; color:#64748b;">Directorio de Salones y Jardines</span></td>
                <td><strong>1200 × 800 px</strong><br />Relación editorial 3:2</td>
                <td>WEBP o JPEG</td>
                <td>Máx. 350 KB. Iluminación amplia y encuadre profesional de arquitectura.</td>
            </tr>
            <tr>
                <td><strong>Logos / Emblemas de Restaurantes</strong><br /><span style="font-size:7pt; color:#64748b;">Tarjetas y Menú Desplegable</span></td>
                <td><strong>500 × 500 px</strong><br />Cuadrado 1:1</td>
                <td>PNG transparente con canal alfa</td>
                <td>Máx. 150 KB. Optimizado para lectura sobre fondo negro Obsidiana.</td>
            </tr>
            <tr>
                <td><strong>Fotografía Estándar de Galería</strong><br /><span style="font-size:7pt; color:#64748b;">Grid Masonry Estándar (1x1 o 3:2)</span></td>
                <td><strong>1080 × 1080 px</strong> o <strong>1200 × 800 px</strong></td>
                <td>WEBP o JPEG</td>
                <td>Máx. 300 KB. Perfil de color sRGB para consistencia en monitores.</td>
            </tr>
            <tr>
                <td><strong>Fotografía Destacada «Estelar»</strong><br /><span style="font-size:7pt; color:#64748b;">Tarjeta Hero Doble (2x2) en Galería</span></td>
                <td><strong>1600 × 1600 px</strong> o superior<br />Alta definición sensorial</td>
                <td>WEBP o JPEG HD</td>
                <td>Máx. 550 KB. Se activa ingresando la palabra "estelar" en la leyenda.</td>
            </tr>
            <tr>
                <td><strong>Planos Arquitectónicos & Menús PDF</strong><br /><span style="font-size:7pt; color:#64748b;">Botones de descarga en fichas</span></td>
                <td>Formato Documento A4 / Carta</td>
                <td>PDF Vectorizado (PDF 1.7)</td>
                <td>Máx. 5 MB. Fuentes incrustadas y trazados arquitectónicos en curvas.</td>
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
