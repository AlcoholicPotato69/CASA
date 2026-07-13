/**
 * Hacienda Casa de Piedra - Favicon Animado con Transmutación Continua
 * Utiliza exactamente las imágenes oficiales proporcionadas por el usuario con fondo transparente,
 * transmutando suavemente de una imagen a otra en ciclos de 2.5 segundos.
 */
(function() {
    const canvas = document.createElement('canvas');
    if (!canvas.getContext) return;

    const size = 128;
    canvas.width = size;
    canvas.height = size;
    const ctx = canvas.getContext('2d');

    // Obtener URL del tema actual desde el script o DOM
    const themeUrl = (typeof casadepiedraThemeUrl !== 'undefined') 
        ? casadepiedraThemeUrl 
        : (document.querySelector('link[rel="stylesheet"][href*="casadepiedra-luxury-theme"]') 
            ? document.querySelector('link[rel="stylesheet"][href*="casadepiedra-luxury-theme"]').href.split('/style.css')[0]
            : '/wp-content/themes/casadepiedra-luxury-theme');

    // Eliminar cualquier favicon previo o por defecto generado por WordPress
    document.querySelectorAll('link[rel*="icon"]').forEach(link => {
        if (link.id !== 'casa-dynamic-favicon') {
            link.remove();
        }
    });

    // Asegurar link único para el favicon animado PNG transparente
    let favLink = document.getElementById('casa-dynamic-favicon');
    if (!favLink) {
        favLink = document.createElement('link');
        favLink.id = 'casa-dynamic-favicon';
        favLink.rel = 'icon';
        favLink.type = 'image/png';
        document.head.appendChild(favLink);
    }

    // Cargar exactamente las 4 imágenes enviadas por el usuario
    const imageNames = ['favicon-1.png', 'favicon-2.png', 'favicon-3.png', 'favicon-4.png'];
    const images = [];
    let loadedCount = 0;

    imageNames.forEach((name, index) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
            loadedCount++;
        };
        img.src = `${themeUrl}/assets/images/favicons/${name}`;
        images[index] = img;
    });

    const phaseDuration = 2500; // 2.5 segundos completos por imagen
    const holdDuration = 1500;  // 1.5s visible estable al 100%
    const fadeDuration = 1000;  // 1.0s de transmutación sedosa
    let startTime = Date.now();

    function renderFaviconFrame() {
        if (loadedCount < imageNames.length) return; // Esperar a que carguen las 4 imágenes

        const elapsed = (Date.now() - startTime);
        const totalCycle = imageNames.length * phaseDuration;
        const currentMs = elapsed % totalCycle;

        const currentIndex = Math.floor(currentMs / phaseDuration);
        const nextIndex = (currentIndex + 1) % imageNames.length;
        const phaseMs = currentMs % phaseDuration;

        // Limpiar el canvas preservando 100% fondo TRANSPARENTE
        ctx.clearRect(0, 0, size, size);

        const currentImg = images[currentIndex];
        const nextImg = images[nextIndex];

        if (phaseMs <= holdDuration) {
            // Fase estable: mostrar imagen actual al 100%
            ctx.globalAlpha = 1.0;
            ctx.drawImage(currentImg, 0, 0, size, size);
        } else {
            // Curva trigonométrica (sin/cos) para una transmutación continua sin pérdida ni parpadeo de opacidad
            const rawT = (phaseMs - holdDuration) / fadeDuration;
            const alphaIn = Math.sin(rawT * (Math.PI / 2));
            const alphaOut = Math.cos(rawT * (Math.PI / 2));

            // Dibujar imagen actual saliendo
            ctx.globalAlpha = alphaOut;
            ctx.drawImage(currentImg, 0, 0, size, size);

            // Dibujar siguiente imagen entrando
            ctx.globalAlpha = alphaIn;
            ctx.drawImage(nextImg, 0, 0, size, size);
        }

        ctx.globalAlpha = 1.0;

        // Actualizar el favicon en tiempo real preservando el fondo transparente PNG
        favLink.href = canvas.toDataURL('image/png');
    }

    // Actualizar a 30 FPS para máxima fluidez y suavidad en transmutación
    setInterval(renderFaviconFrame, 33);
})();
