(function () {
    var VERT = [
        'attribute vec2 aPos;',
        'void main(){ gl_Position = vec4(aPos, 0.0, 1.0); }'
    ].join('\n');

    var FRAG = [
        'precision highp float;',
        'uniform sampler2D uTex;',
        'uniform vec2 uRes;',
        'uniform float uYaw;',
        'uniform float uPitch;',
        'uniform float uFov;',
        'const float PI = 3.141592653589793;',
        'vec3 rotX(vec3 p, float a){',
        '  float c = cos(a), s = sin(a);',
        '  return vec3(p.x, c*p.y - s*p.z, s*p.y + c*p.z);',
        '}',
        'vec3 rotY(vec3 p, float a){',
        '  float c = cos(a), s = sin(a);',
        '  return vec3(c*p.x + s*p.z, p.y, -s*p.x + c*p.z);',
        '}',
        'void main(){',
        '  vec2 ndc = (gl_FragCoord.xy / uRes) * 2.0 - 1.0;',
        '  ndc.x *= uRes.x / uRes.y;',
        '  float z = 1.0 / tan(uFov * 0.5);',
        '  vec3 dir = normalize(vec3(ndc, z));',
        '  dir = rotX(dir, uPitch);',
        '  dir = rotY(dir, uYaw);',
        '  float lon = atan(dir.x, dir.z);',
        '  float lat = asin(clamp(dir.y, -1.0, 1.0));',
        '  vec2 uv = vec2(lon / (2.0 * PI) + 0.5, 0.5 + lat / PI);',
        '  gl_FragColor = texture2D(uTex, uv);',
        '}'
    ].join('\n');

    var ARROW = '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M9 4.2L14 11.2H4L9 4.2Z" fill="#fff"/></svg>';

    function compile(gl, type, source) {
        var shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);
        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            throw new Error(gl.getShaderInfoLog(shader) || 'shader');
        }
        return shader;
    }

    function isPowerOfTwo(n) {
        return n > 0 && (n & (n - 1)) === 0;
    }

    function fitSource(img, maxSize) {
        var longest = Math.max(img.width, img.height);
        if (longest <= maxSize) return img;
        var scale = maxSize / longest;
        var canvas = document.createElement('canvas');
        canvas.width = Math.max(2, Math.round(img.width * scale));
        canvas.height = Math.max(2, Math.round(img.height * scale));
        canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
        return canvas;
    }

    function mount(stage) {
        var src = stage.getAttribute('data-panorama');
        var canvas = stage.querySelector('.espacio-tour__canvas');
        var status = stage.querySelector('.espacio-tour__status');
        var place = stage.querySelector('.espacio-tour__place');
        var hotspotLayer = stage.querySelector('.espacio-tour__hotspots');
        if (!canvas) return;
        if (!hotspotLayer) {
            hotspotLayer = document.createElement('div');
            hotspotLayer.className = 'espacio-tour__hotspots';
            stage.appendChild(hotspotLayer);
        }

        var scenes = [];
        try {
            var rawTour = stage.getAttribute('data-tour');
            if (rawTour) scenes = JSON.parse(rawTour);
        } catch (err) {
            scenes = [];
        }
        if (!Array.isArray(scenes)) scenes = [];
        scenes = scenes.filter(function (scene) { return scene && scene.src; });
        if (!scenes.length && src) {
            scenes = [{ id: 'principal', name: 'Punto principal', src: src, links: [] }];
        }
        var sceneIndex = 0;
        var hotspotButtons = [];

        var gl = canvas.getContext('webgl', { antialias: false, alpha: false, depth: false, stencil: false });
        if (!gl) {
            stage.classList.add('is-error');
            if (status) status.textContent = 'Este navegador no puede mostrar el recorrido 360.';
            return;
        }

        var program;
        try {
            program = gl.createProgram();
            gl.attachShader(program, compile(gl, gl.VERTEX_SHADER, VERT));
            gl.attachShader(program, compile(gl, gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(program);
            if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
                throw new Error(gl.getProgramInfoLog(program) || 'program');
            }
        } catch (err) {
            stage.classList.add('is-error');
            if (status) status.textContent = 'No se pudo iniciar el recorrido 360.';
            return;
        }

        gl.useProgram(program);
        var buffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
        var loc = gl.getAttribLocation(program, 'aPos');
        gl.enableVertexAttribArray(loc);
        gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);

        var uTex = gl.getUniformLocation(program, 'uTex');
        var uRes = gl.getUniformLocation(program, 'uRes');
        var uYaw = gl.getUniformLocation(program, 'uYaw');
        var uPitch = gl.getUniformLocation(program, 'uPitch');
        var uFov = gl.getUniformLocation(program, 'uFov');
        var texture = gl.createTexture();
        gl.bindTexture(gl.TEXTURE_2D, texture);

        var yaw = 0;
        var pitch = 0;
        var fov = 1.15;
        var ready = false;
        var running = false;
        var visible = false;
        var userMoved = stage.getAttribute('data-editor') === '1';
        var held = null;
        var holdStarted = 0;
        var dragging = false;
        var lastX = 0;
        var lastY = 0;
        var lastFrame = 0;
        var currentSrc = '';
        var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function resize() {
            var ratio = Math.min(window.devicePixelRatio || 1, 2);
            var w = Math.max(1, stage.clientWidth);
            var h = Math.max(1, stage.clientHeight);
            var pw = Math.floor(w * ratio);
            var ph = Math.floor(h * ratio);
            if (canvas.width !== pw || canvas.height !== ph) {
                canvas.width = pw;
                canvas.height = ph;
            }
            gl.viewport(0, 0, canvas.width, canvas.height);
        }

        function draw() {
            if (!ready) return;
            gl.uniform2f(uRes, canvas.width, canvas.height);
            gl.uniform1f(uYaw, yaw);
            gl.uniform1f(uPitch, pitch);
            gl.uniform1f(uFov, fov);
            gl.uniform1i(uTex, 0);
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }

        function clampView() {
            if (pitch > 1.15) pitch = 1.15;
            if (pitch < -1.15) pitch = -1.15;
            if (fov < 0.5) fov = 0.5;
            if (fov > 1.7) fov = 1.7;
        }

        function sceneName(id) {
            for (var i = 0; i < scenes.length; i++) {
                if (scenes[i].id === id) return scenes[i].name || 'Parada';
            }
            return 'Parada';
        }

        function updatePlace() {
            if (!place) return;
            var scene = scenes[sceneIndex];
            if (!scene || scenes.length < 2) {
                place.hidden = true;
                return;
            }
            place.hidden = false;
            place.textContent = scene.name || 'Parada';
        }

        function unproject(px, py) {
            var width = stage.clientWidth || 1;
            var height = stage.clientHeight || 1;
            var ndcX = (px / width) * 2 - 1;
            var ndcY = 1 - (py / height) * 2;
            var aspect = width / height;
            var cz = 1 / Math.tan(fov * 0.5);
            var cx = ndcX * aspect;
            var cy = ndcY;
            var len = Math.hypot(cx, cy, cz) || 1;
            cx /= len;
            cy /= len;
            cz /= len;
            var cP = Math.cos(pitch);
            var sP = Math.sin(pitch);
            var y1 = cP * cy - sP * cz;
            var z1 = sP * cy + cP * cz;
            var cY = Math.cos(yaw);
            var sY = Math.sin(yaw);
            var x2 = cY * cx + sY * z1;
            var z2 = -sY * cx + cY * z1;
            var y2 = y1;
            return {
                yaw: Math.atan2(x2, z2),
                pitch: Math.asin(Math.max(-1, Math.min(1, -y2)))
            };
        }

        function project(hYaw, hPitch) {
            var cp = Math.cos(hPitch);
            var sp = Math.sin(hPitch);
            var cy = Math.cos(hYaw);
            var sy = Math.sin(hYaw);
            var wx = sy * cp;
            var wy = -sp;
            var wz = cy * cp;
            var cY = Math.cos(-yaw);
            var sY = Math.sin(-yaw);
            var x1 = cY * wx + sY * wz;
            var z1 = -sY * wx + cY * wz;
            var y1 = wy;
            var cP = Math.cos(-pitch);
            var sP = Math.sin(-pitch);
            var y2 = cP * y1 - sP * z1;
            var z2 = sP * y1 + cP * z1;
            var x2 = x1;
            if (z2 <= 0.08) return null;
            var width = stage.clientWidth || 1;
            var height = stage.clientHeight || 1;
            var aspect = width / height;
            var tan = Math.tan(fov * 0.5);
            var ndcX = (x2 / z2) / tan / aspect;
            var ndcY = (y2 / z2) / tan;
            if (Math.abs(ndcX) > 1.15 || Math.abs(ndcY) > 1.15) return null;
            return {
                x: (ndcX * 0.5 + 0.5) * width,
                y: (0.5 - ndcY * 0.5) * height
            };
        }

        var editor = stage.getAttribute('data-editor') === '1';

        function placeHotspots() {
            var links = (scenes[sceneIndex] && scenes[sceneIndex].links) || [];
            while (hotspotButtons.length < links.length) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'espacio-tour__hotspot';
                btn.setAttribute('data-hotspot', '');
                btn.innerHTML = '<span class="espacio-tour__hotspot-arrow">' + ARROW + '</span><span class="espacio-tour__hotspot-label"></span>';
                btn.addEventListener('pointerdown', function (e) {
                    e.stopPropagation();
                    if (!editor) return;
                    e.preventDefault();
                    var node = e.currentTarget;
                    node._drag = true;
                    node._moved = false;
                    node._sx = e.clientX;
                    node._sy = e.clientY;
                    node.setPointerCapture(e.pointerId);
                });
                btn.addEventListener('pointermove', function (e) {
                    var node = e.currentTarget;
                    if (!node._drag) return;
                    if (Math.abs(e.clientX - node._sx) + Math.abs(e.clientY - node._sy) > 4) node._moved = true;
                    var rect = stage.getBoundingClientRect();
                    var hit = unproject(e.clientX - rect.left, e.clientY - rect.top);
                    var index = Number(node.getAttribute('data-link-index'));
                    var current = (scenes[sceneIndex] && scenes[sceneIndex].links) || [];
                    if (!hit || !current[index]) return;
                    current[index].yaw = hit.yaw;
                    current[index].pitch = hit.pitch;
                });
                btn.addEventListener('pointerup', function (e) {
                    var node = e.currentTarget;
                    if (!node._drag) return;
                    node._drag = false;
                    if (node._moved && stage.casaPanorama && typeof stage.casaPanorama.onHotspotMove === 'function') {
                        stage.casaPanorama.onHotspotMove();
                    }
                });
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var node = e.currentTarget;
                    if (node._moved) {
                        node._moved = false;
                        return;
                    }
                    goTo(node.getAttribute('data-to'));
                });
                hotspotLayer.appendChild(btn);
                hotspotButtons.push(btn);
            }
            hotspotButtons.forEach(function (button, index) {
                var link = links[index];
                if (!link) {
                    button.hidden = true;
                    return;
                }
                var point = ready ? project(link.yaw, link.pitch) : null;
                if (!point) {
                    button.hidden = true;
                    return;
                }
                button.hidden = false;
                button.setAttribute('data-to', link.to);
                button.setAttribute('data-link-index', String(index));
                button.style.left = point.x + 'px';
                button.style.top = point.y + 'px';
                var label = button.querySelector('.espacio-tour__hotspot-label');
                if (label) label.textContent = sceneName(link.to);
            });
        }

        function frame(now) {
            if (!running) return;
            var dt = lastFrame ? Math.min(0.05, (now - lastFrame) / 1000) : 0.016;
            lastFrame = now;
            if (!userMoved && !reduced) yaw += 0.12 * dt;
            if (held === 'left') yaw -= 0.95 * dt;
            if (held === 'right') yaw += 0.95 * dt;
            if (held === 'up') pitch -= 0.75 * dt;
            if (held === 'down') pitch += 0.75 * dt;
            if (held === 'in') fov -= 0.7 * dt;
            if (held === 'out') fov += 0.7 * dt;
            clampView();
            draw();
            placeHotspots();
            requestAnimationFrame(frame);
        }

        function start() {
            if (running || !visible || !ready) return;
            running = true;
            lastFrame = 0;
            requestAnimationFrame(frame);
        }

        function stop() {
            running = false;
        }

        function takeControl() {
            userMoved = true;
            stage.classList.add('is-used');
        }

        function nudge(action) {
            if (action === 'left') yaw -= 0.22;
            if (action === 'right') yaw += 0.22;
            if (action === 'up') pitch -= 0.16;
            if (action === 'down') pitch += 0.16;
            if (action === 'in') fov -= 0.16;
            if (action === 'out') fov += 0.16;
            clampView();
        }

        function applyTexture(source) {
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            var canMip = isPowerOfTwo(source.width) && isPowerOfTwo(source.height);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, canMip ? gl.LINEAR_MIPMAP_LINEAR : gl.LINEAR);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, source);
            if (canMip) gl.generateMipmap(gl.TEXTURE_2D);
            var aniso = gl.getExtension('EXT_texture_filter_anisotropic') || gl.getExtension('WEBKIT_EXT_texture_filter_anisotropic');
            if (aniso) {
                var maxAniso = gl.getParameter(aniso.MAX_TEXTURE_MAX_ANISOTROPY_EXT) || 1;
                gl.texParameterf(gl.TEXTURE_2D, aniso.TEXTURE_MAX_ANISOTROPY_EXT, Math.min(8, maxAniso));
            }
        }

        function load(url) {
            if (!url) {
                ready = false;
                currentSrc = '';
                stage.classList.remove('is-ready', 'is-loading');
                stage.classList.add('is-error');
                if (status) status.textContent = 'Sube la fotografía 360 de esta parada.';
                placeHotspots();
                return;
            }
            if (url === currentSrc && ready) {
                placeHotspots();
                return;
            }
            currentSrc = url;
            stage.classList.add('is-loading');
            stage.classList.remove('is-error');
            if (status) status.textContent = ready ? 'Caminando…' : 'Cargando recorrido…';
            var img = new Image();
            try {
                if (new URL(url, window.location.href).origin !== window.location.origin) {
                    img.crossOrigin = 'anonymous';
                }
            } catch (err) {
                img.crossOrigin = 'anonymous';
            }
            img.onload = function () {
                if (currentSrc !== url) return;
                var maxTex = gl.getParameter(gl.MAX_TEXTURE_SIZE) || 4096;
                applyTexture(fitSource(img, Math.min(maxTex, 8192)));
                ready = true;
                stage.classList.add('is-ready');
                stage.classList.remove('is-loading', 'is-error');
                resize();
                draw();
                placeHotspots();
                start();
            };
            img.onerror = function () {
                if (currentSrc !== url) return;
                ready = false;
                stage.classList.remove('is-loading', 'is-ready');
                stage.classList.add('is-error');
                if (status) status.textContent = 'No se pudo cargar la fotografía 360.';
            };
            img.src = url;
        }

        function goTo(id) {
            var next = -1;
            for (var i = 0; i < scenes.length; i++) {
                if (scenes[i].id === id) next = i;
            }
            if (next < 0) return;
            var from = scenes[sceneIndex];
            var link = null;
            if (from && from.links) {
                from.links.forEach(function (item) {
                    if (item.to === id) link = item;
                });
            }
            sceneIndex = next;
            if (link) {
                yaw = link.yaw;
                pitch = 0;
                takeControl();
            }
            updatePlace();
            load(scenes[sceneIndex].src || '');
            if (stage.casaPanorama && typeof stage.casaPanorama.onScene === 'function') {
                stage.casaPanorama.onScene(scenes[sceneIndex].id);
            }
        }

        function toggleFullscreen() {
            var node = document.fullscreenElement || document.webkitFullscreenElement;
            if (node === stage) {
                (document.exitFullscreen || document.webkitExitFullscreen).call(document);
                return;
            }
            var req = stage.requestFullscreen || stage.webkitRequestFullscreen;
            if (req) req.call(stage);
        }

        stage.casaPanorama = {
            getView: function () { return { yaw: yaw, pitch: pitch }; },
            setTour: function (list, activeId) {
                var prevId = scenes[sceneIndex] ? scenes[sceneIndex].id : '';
                var prevSrc = scenes[sceneIndex] ? scenes[sceneIndex].src : '';
                scenes = Array.isArray(list) ? list.slice() : [];
                sceneIndex = 0;
                scenes.forEach(function (scene, index) {
                    if (scene.id === activeId) sceneIndex = index;
                });
                updatePlace();
                var next = scenes[sceneIndex] || null;
                if (!next || next.src !== prevSrc || next.id !== prevId) {
                    load(next ? next.src : '');
                } else {
                    placeHotspots();
                }
            },
            onScene: null,
            onHotspotMove: null
        };

        stage.querySelectorAll('[data-pan]').forEach(function (btn) {
            btn.addEventListener('pointerdown', function (e) {
                e.preventDefault();
                e.stopPropagation();
                takeControl();
                if (btn.getAttribute('data-pan') === 'full') {
                    toggleFullscreen();
                    return;
                }
                held = btn.getAttribute('data-pan');
                holdStarted = performance.now();
                btn.setPointerCapture(e.pointerId);
            });
            function release(e) {
                if (!held || held !== btn.getAttribute('data-pan')) return;
                if (performance.now() - holdStarted < 220) nudge(held);
                held = null;
                if (e && e.pointerId != null && btn.hasPointerCapture && btn.hasPointerCapture(e.pointerId)) {
                    btn.releasePointerCapture(e.pointerId);
                }
            }
            btn.addEventListener('pointerup', release);
            btn.addEventListener('pointercancel', release);
        });

        stage.addEventListener('pointerdown', function (e) {
            if (e.target.closest('[data-pan], [data-hotspot]')) return;
            dragging = true;
            lastX = e.clientX;
            lastY = e.clientY;
            takeControl();
            stage.classList.add('is-dragging');
            stage.focus({ preventScroll: true });
            stage.setPointerCapture(e.pointerId);
        });
        stage.addEventListener('pointermove', function (e) {
            if (!dragging) return;
            yaw += (e.clientX - lastX) * 0.0045;
            pitch += (e.clientY - lastY) * 0.0045;
            lastX = e.clientX;
            lastY = e.clientY;
            clampView();
        });
        function endDrag(e) {
            if (!dragging) return;
            dragging = false;
            stage.classList.remove('is-dragging');
            if (e && e.pointerId != null && stage.hasPointerCapture && stage.hasPointerCapture(e.pointerId)) {
                stage.releasePointerCapture(e.pointerId);
            }
        }
        stage.addEventListener('pointerup', endDrag);
        stage.addEventListener('pointercancel', endDrag);

        stage.addEventListener('wheel', function (e) {
            e.preventDefault();
            takeControl();
            fov += Math.sign(e.deltaY) * 0.08;
            clampView();
        }, { passive: false });

        stage.addEventListener('keydown', function (e) {
            var map = { ArrowLeft: 'left', ArrowRight: 'right', ArrowUp: 'up', ArrowDown: 'down' };
            if (map[e.key]) {
                e.preventDefault();
                takeControl();
                nudge(map[e.key]);
                return;
            }
            if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                takeControl();
                nudge('in');
            } else if (e.key === '-' || e.key === '_') {
                e.preventDefault();
                takeControl();
                nudge('out');
            } else if (e.key === 'f' || e.key === 'F') {
                e.preventDefault();
                toggleFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', resize);
        window.addEventListener('resize', resize);

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                visible = entries.some(function (entry) { return entry.isIntersecting; });
                if (visible) start();
                else stop();
            }, { rootMargin: '200px 0px' });
            observer.observe(stage);
        } else {
            visible = true;
        }

        updatePlace();
        if (stage.getAttribute('data-editor') === '1') {
            visible = true;
        }
        load((scenes[0] && scenes[0].src) || src || '');
    }

    function boot() {
        document.querySelectorAll('[data-panorama]').forEach(mount);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
