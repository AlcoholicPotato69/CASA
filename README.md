# Casa de Piedra - Frontend Web Application

Este repositorio contiene el código fuente oficial del sitio web de **Casa de Piedra**, el recinto más exclusivo de León para eventos sociales y empresariales.

El proyecto es una **Single Page Application (SPA)** de alto rendimiento, construida con los estándares modernos de desarrollo web, enfocada en ofrecer una experiencia de usuario (UX) premium mediante animaciones fluidas y diseño adaptable (responsive design).

---

## 🛠 Tecnologías Utilizadas

- **Framework Core**: [React.js](https://react.dev/) (v19)
- **Bundler**: [Vite](https://vitejs.dev/) (Empaquetador ultrarrápido)
- **Enrutamiento**: `react-router-dom` (Navegación sin recargas)
- **Animaciones**: [GSAP](https://gsap.com/) (GreenSock Animation Platform) y ScrollTrigger
- **Scroll Fluido**: [Lenis](https://lenis.studiofreight.com/) (Smooth scroll de alto rendimiento)
- **Iconografía**: `lucide-react`
- **Estilos**: Vanilla CSS con Variables Nativas (CSS Custom Properties)

---

## 🚀 Instalación y Despliegue Local

Para ejecutar este proyecto en tu entorno local, asegúrate de tener instalado [Node.js](https://nodejs.org/) (recomendada la versión LTS).

1. **Clonar el repositorio** y navegar a la carpeta raíz:
   ```bash
   cd CASA
   ```
2. **Instalar dependencias**:
   ```bash
   npm install
   ```
3. **Servidor de Desarrollo** (Levantar la aplicación localmente):
   ```bash
   npm run dev
   ```
   *El sitio estará disponible, por defecto, en `http://localhost:5173`.*

4. **Construcción para Producción** (Generar archivos optimizados):
   ```bash
   npm run build
   ```
   *Esto generará una carpeta `dist/` lista para ser subida a cualquier servidor estático (Vercel, Netlify, cPanel, AWS S3).*

---

## 📂 Estructura del Proyecto

```text
CASA/
├── public/                 # Archivos estáticos crudos (Imágenes, sitemap, robots.txt, favicon)
├── src/
│   ├── components/         # Componentes reutilizables (Navegación, Botones, Lightbox, Footer)
│   ├── pages/              # Vistas principales (Home, Espacios, Eventos, Contacto, etc.)
│   ├── App.jsx             # Componente raíz, configuración de Rutas y Lenis (Scroll)
│   ├── index.css           # Estilos globales, diseño base y variables de color
│   └── main.jsx            # Punto de entrada de React
├── index.html              # Plantilla HTML principal (Contiene GA4 y Etiquetas SEO)
├── package.json            # Dependencias y scripts del proyecto
└── vite.config.js          # Configuración del empaquetador Vite
```

---

## 🔧 Guía de Mantenimiento (Para equipo de TI)

### 1. Activar Google Analytics (GA4)
Actualmente, el sitio tiene el código de rastreo preparado en el `index.html`. 
- Abre `index.html`.
- Busca la etiqueta `<script>` de Google Analytics (alrededor de la línea 17).
- Reemplaza el texto `G-XXXXXXXXXX` por el "ID de Medición" real de la cuenta de la empresa.

### 2. Modificar Textos o Agregar Nuevos Espacios/Restaurantes
El sitio utiliza "diccionarios de datos" dentro de las vistas de detalle para inyectar la información de forma dinámica. 
- Para **Espacios**: Abre `src/pages/SpaceDetail.jsx` y modifica el objeto `spacesData`.
- Para **Restaurantes**: Abre `src/pages/RestaurantDetail.jsx` y modifica el objeto `restaurantsData`.
- Al agregar un nuevo registro a estos objetos, el componente generará automáticamente su vista web y su galería de fotos al acceder a su respectiva ruta URL (`/espacios/nuevo-espacio`).

### 3. Actualización de la Galería de Imágenes (Lightbox)
El componente global `Lightbox.jsx` está diseñado para funcionar como un carrusel.
- En la información de cada espacio (`SpaceDetail.jsx`), busca la propiedad `gallery: ["url1", "url2", ...]`.
- Reemplaza las URLs de Unsplash o rutas locales por los enlaces de las fotografías oficiales de Casa de Piedra. El visor de pantalla completa se ajustará automáticamente a la cantidad de imágenes que añadas.

### 4. Animaciones GSAP (Troubleshooting)
El proyecto usa `gsap.context()` dentro de los `useEffect` de las páginas para asegurar que las animaciones se limpien (`revert()`) cuando el usuario cambia de página de forma asíncrona.
- **Importante:** Si agregas una nueva página con animaciones, siempre envuelve la lógica de GSAP en un contexto para evitar comportamientos fantasma o bugs al presionar el botón "Atrás" del navegador.

### 5. SEO (Optimización de Motores de Búsqueda)
El sitio ya está pre-configurado para su óptima indexación:
- `public/sitemap.xml`
- `public/robots.txt`
- Metadatos estáticos y OpenGraph en `index.html` (Para previsualizaciones en WhatsApp/Redes Sociales).
Si la estructura de URL cambia en un futuro, es imperativo actualizar el archivo `sitemap.xml`.

---

## 🎨 Tipografía y Colores

El sitio utiliza una paleta de colores de lujo oscuro ("OLED Black", "Gold Champagne" y "Off-white"). Todo esto es controlable desde `src/index.css` a través de variables CSS.

- **Fuente principal (Títulos):** Cormorant Garamond
- **Fuente secundaria (Párrafos):** Inter
- **Fuente cursiva (Acentos):** Great Vibes

*(Desarrollado y optimizado con arquitectura moderna)*
