(function () {
    function boot() {
        var root = document.getElementById('casa-tour-editor');
        var hidden = document.getElementById('espacio_panorama_tour');
        if (!root || !hidden) return;

        var stops = [];
        try {
            stops = JSON.parse(root.getAttribute('data-stops') || '[]');
        } catch (err) {
            stops = [];
        }
        if (!Array.isArray(stops)) stops = [];
        stops.forEach(function (stop) {
            if (!Array.isArray(stop.links)) stop.links = [];
        });

        var list = root.querySelector('[data-tour-list]');
        var activeSelect = root.querySelector('[data-tour-active]');
        var destSelect = root.querySelector('[data-tour-dest]');
        var linksBox = root.querySelector('[data-tour-links]');
        var stage = root.querySelector('[data-panorama]');
        var activeId = stops[0] ? stops[0].id : '';

        function uid() {
            return 'p' + Math.random().toString(36).slice(2, 8);
        }

        function find(id) {
            for (var i = 0; i < stops.length; i++) {
                if (stops[i].id === id) return stops[i];
            }
            return null;
        }

        function wrapYaw(value) {
            var yaw = value;
            while (yaw > Math.PI) yaw -= Math.PI * 2;
            while (yaw < -Math.PI) yaw += Math.PI * 2;
            return yaw;
        }

        function ensureReturn(fromStop, link) {
            var target = find(link.to);
            if (!target || target.id === fromStop.id) return false;
            if (!Array.isArray(target.links)) target.links = [];
            var exists = target.links.some(function (item) { return item.to === fromStop.id; });
            if (exists) return false;
            target.links.push({
                to: fromStop.id,
                yaw: wrapYaw(link.yaw + Math.PI),
                pitch: link.pitch
            });
            return true;
        }

        function ensureAllReturns() {
            stops.forEach(function (stop) {
                (stop.links || []).forEach(function (link) {
                    ensureReturn(stop, link);
                });
            });
        }

        function write() {
            hidden.value = JSON.stringify(stops);
        }

        function fillSelect(select, excludeId) {
            var current = select.value;
            select.innerHTML = '';
            stops.forEach(function (stop) {
                if (stop.id === excludeId) return;
                var option = document.createElement('option');
                option.value = stop.id;
                option.textContent = stop.name || 'Parada';
                select.appendChild(option);
            });
            if (current && select.querySelector('option[value="' + current + '"]')) {
                select.value = current;
            }
        }

        function pushView() {
            if (!stage || !stage.casaPanorama) return;
            stage.casaPanorama.setTour(stops, activeId);
        }

        function renderLinks() {
            var stop = find(activeId);
            linksBox.innerHTML = '';
            if (!stop || !stop.links.length) {
                var empty = document.createElement('li');
                empty.textContent = 'Esta parada todavía no tiene flechas.';
                linksBox.appendChild(empty);
                return;
            }
            stop.links.forEach(function (link, index) {
                var item = document.createElement('li');
                var label = document.createElement('span');
                var dest = find(link.to);
                label.textContent = 'Flecha hacia ' + ((dest && dest.name) || 'parada');
                var actions = document.createElement('span');
                actions.className = 'casa-tour-actions';
                var back = document.createElement('button');
                back.type = 'button';
                back.className = 'button';
                back.textContent = 'Mover flecha de regreso';
                back.addEventListener('click', function () {
                    if (!find(link.to)) return;
                    activeId = link.to;
                    activeSelect.value = activeId;
                    fillSelect(destSelect, activeId);
                    renderLinks();
                    pushView();
                });
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'button';
                remove.textContent = 'Quitar';
                remove.addEventListener('click', function () {
                    stop.links.splice(index, 1);
                    write();
                    renderLinks();
                    pushView();
                });
                actions.appendChild(back);
                actions.appendChild(remove);
                item.appendChild(label);
                item.appendChild(actions);
                linksBox.appendChild(item);
            });
        }

        function renderSelects() {
            var previous = activeId;
            activeSelect.innerHTML = '';
            stops.forEach(function (stop) {
                var option = document.createElement('option');
                option.value = stop.id;
                option.textContent = stop.name || 'Parada';
                activeSelect.appendChild(option);
            });
            if (previous && find(previous)) activeSelect.value = previous;
            else if (stops[0]) activeSelect.value = stops[0].id;
            activeId = activeSelect.value;
            fillSelect(destSelect, activeId);
            renderLinks();
        }

        function renderList() {
            list.innerHTML = '';
            if (!stops.length) {
                var empty = document.createElement('p');
                empty.className = 'casa-tour-help';
                empty.textContent = 'Aún no hay paradas. Añade la primera fotografía 360.';
                list.appendChild(empty);
            }
            stops.forEach(function (stop, index) {
                var card = document.createElement('div');
                card.className = 'casa-tour-stop';
                var nameLabel = document.createElement('label');
                nameLabel.textContent = 'Nombre de la parada';
                var name = document.createElement('input');
                name.type = 'text';
                name.value = stop.name || '';
                name.placeholder = index === 0 ? 'Entrada' : 'Centro del salón';
                name.addEventListener('input', function () {
                    stop.name = name.value;
                    write();
                    renderSelects();
                    pushView();
                });
                var photoLabel = document.createElement('label');
                photoLabel.textContent = 'Fotografía 360';
                photoLabel.style.marginTop = '8px';
                var row = document.createElement('div');
                row.className = 'casa-tour-row';
                var url = document.createElement('input');
                url.type = 'url';
                url.value = stop.src || '';
                url.placeholder = 'https://.../parada.jpg';
                url.addEventListener('change', function () {
                    stop.src = url.value.trim();
                    write();
                    pushView();
                });
                var upload = document.createElement('button');
                upload.type = 'button';
                upload.className = 'button';
                upload.textContent = 'Subir';
                upload.addEventListener('click', function (e) {
                    e.preventDefault();
                    var frame = wp.media({
                        title: 'Fotografía 360 de la parada',
                        button: { text: 'Usar fotografía' },
                        multiple: false,
                        library: { type: 'image' }
                    });
                    frame.on('select', function () {
                        var attachment = frame.state().get('selection').first().toJSON();
                        stop.src = attachment.url;
                        url.value = attachment.url;
                        write();
                        if (activeId !== stop.id) {
                            activeId = stop.id;
                            activeSelect.value = stop.id;
                            fillSelect(destSelect, activeId);
                            renderLinks();
                        }
                        pushView();
                    });
                    frame.open();
                });
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'button';
                remove.textContent = 'Eliminar parada';
                remove.style.marginTop = '8px';
                remove.addEventListener('click', function () {
                    stops.splice(index, 1);
                    stops.forEach(function (item) {
                        item.links = (item.links || []).filter(function (link) { return link.to !== stop.id; });
                    });
                    if (!find(activeId)) activeId = stops[0] ? stops[0].id : '';
                    write();
                    renderList();
                    renderSelects();
                    pushView();
                });
                row.appendChild(url);
                row.appendChild(upload);
                card.appendChild(nameLabel);
                card.appendChild(name);
                card.appendChild(photoLabel);
                card.appendChild(row);
                card.appendChild(remove);
                list.appendChild(card);
            });
        }

        root.querySelector('[data-tour-add]').addEventListener('click', function () {
            var stop = {
                id: uid(),
                name: stops.length ? 'Parada ' + (stops.length + 1) : 'Entrada',
                src: '',
                links: []
            };
            stops.push(stop);
            activeId = stop.id;
            write();
            renderList();
            renderSelects();
            pushView();
        });

        activeSelect.addEventListener('change', function () {
            activeId = activeSelect.value;
            fillSelect(destSelect, activeId);
            renderLinks();
            pushView();
        });

        root.querySelector('[data-tour-drop]').addEventListener('click', function () {
            var stop = find(activeId);
            var dest = destSelect.value;
            if (!stop || !dest || !stage.casaPanorama) return;
            var view = stage.casaPanorama.getView();
            var duplicate = stop.links.some(function (link) { return link.to === dest; });
            if (duplicate) {
                stop.links = stop.links.map(function (link) {
                    if (link.to !== dest) return link;
                    return { to: dest, yaw: view.yaw, pitch: view.pitch };
                });
            } else {
                stop.links.push({ to: dest, yaw: view.yaw, pitch: view.pitch });
            }
            ensureReturn(stop, stop.links.filter(function (link) { return link.to === dest; })[0]);
            write();
            renderLinks();
            pushView();
        });

        if (stage && stage.casaPanorama) {
            stage.casaPanorama.onHotspotMove = function () {
                write();
            };
            stage.casaPanorama.onScene = function (id) {
                if (!find(id) || id === activeId) return;
                activeId = id;
                activeSelect.value = id;
                fillSelect(destSelect, activeId);
                renderLinks();
            };
        }

        ensureAllReturns();
        write();
        renderList();
        renderSelects();
        pushView();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
