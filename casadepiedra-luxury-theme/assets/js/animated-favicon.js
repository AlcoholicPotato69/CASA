/**
 * Ex Hacienda Casa de Piedra - Favicon Animado con Transmutación Continua
 * Utiliza exactamente las imágenes oficiales proporcionadas por el usuario con fondo transparente,
 * transmutando suavemente de una imagen a otra en ciclos de 2.5 segundos.
 */
(function() {
    function startAnimatedFavicon() {
        const canvas = document.createElement('canvas');
        if (!canvas.getContext) return;

        const size = 128;
        canvas.width = size;
        canvas.height = size;
        const ctx = canvas.getContext('2d');

        const themeUrl = (typeof casadepiedraThemeUrl !== 'undefined')
            ? casadepiedraThemeUrl
            : (document.querySelector('link[rel="stylesheet"][href*="casadepiedra-luxury-theme"]')
                ? document.querySelector('link[rel="stylesheet"][href*="casadepiedra-luxury-theme"]').href.split('/style.css')[0]
                : '/wp-content/themes/casadepiedra-luxury-theme');

        document.querySelectorAll('link[rel*="icon"]').forEach(link => {
            if (link.id === 'casa-dynamic-favicon' || link.id === 'casa-google-favicon' || (link.rel || '').indexOf('apple') !== -1) {
                return;
            }
            link.remove();
        });

        let favLink = document.getElementById('casa-dynamic-favicon');
        if (!favLink) {
            favLink = document.createElement('link');
            favLink.id = 'casa-dynamic-favicon';
            favLink.rel = 'icon';
            favLink.type = 'image/png';
            document.head.appendChild(favLink);
        }

        const imageNames = ['favicon-1.png', 'favicon-2.png', 'favicon-3.png', 'favicon-4.png'];
        const images = [];
        let loadedCount = 0;

        imageNames.forEach((name, index) => {
            const img = new Image();
            img.onload = () => {
                loadedCount++;
            };
            img.src = `${themeUrl}/assets/images/favicons/${name}`;
            images[index] = img;
        });

        const phaseDuration = 2500;
        const holdDuration = 1500;
        const fadeDuration = 1000;
        let startTime = Date.now();

        function renderFaviconFrame() {
            if (document.hidden || loadedCount < imageNames.length) return;

            const elapsed = (Date.now() - startTime);
            const totalCycle = imageNames.length * phaseDuration;
            const currentMs = elapsed % totalCycle;

            const currentIndex = Math.floor(currentMs / phaseDuration);
            const nextIndex = (currentIndex + 1) % imageNames.length;
            const phaseMs = currentMs % phaseDuration;

            ctx.clearRect(0, 0, size, size);

            const currentImg = images[currentIndex];
            const nextImg = images[nextIndex];

            if (phaseMs <= holdDuration) {
                ctx.globalAlpha = 1.0;
                ctx.drawImage(currentImg, 0, 0, size, size);
            } else {
                const rawT = (phaseMs - holdDuration) / fadeDuration;
                const alphaIn = Math.sin(rawT * (Math.PI / 2));
                const alphaOut = Math.cos(rawT * (Math.PI / 2));

                ctx.globalAlpha = alphaOut;
                ctx.drawImage(currentImg, 0, 0, size, size);

                ctx.globalAlpha = alphaIn;
                ctx.drawImage(nextImg, 0, 0, size, size);
            }

            ctx.globalAlpha = 1.0;
            favLink.href = canvas.toDataURL('image/png');
        }

        setInterval(renderFaviconFrame, 80);
    }

    if (typeof requestIdleCallback === 'function') {
        requestIdleCallback(startAnimatedFavicon, { timeout: 2500 });
    } else {
        setTimeout(startAnimatedFavicon, 2500);
    }
})();
