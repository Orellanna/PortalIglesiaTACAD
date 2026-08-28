/**
 * Live Status Checker
 * - Consulta cada 3 segundos el endpoint REST del propio sitio.
 * - El servidor cachea las respuestas a YouTube (15s) para no gastar cuota.
 * - Enciende al PRIMER resultado "live" (detección rápida del inicio).
 * - Requiere 2 confirmaciones consecutivas para apagar (evita parpadeos).
 * - Nunca solapa peticiones y pausa el polling con la pestaña oculta.
 */
(function() {
    'use strict';

    var POLL_INTERVAL = 3000;        // Frecuencia de sondeo del navegador
    var OFFLINE_CONFIRMATIONS = 2;   // Ciclos offline consecutivos antes de apagar

    // Endpoint del propio sitio (inyectado por wp_localize_script)
    var endpoint = (window.iglesiaLive && window.iglesiaLive.restUrl)
        || '/wp-json/iglesia/v1/live-status';
    if (endpoint.indexOf('?') === -1) {
        endpoint += '?_ts=';
    } else {
        endpoint += '&_ts=';
    }

    var pollTimer = null;
    var inFlight = false;
    var offlineStreak = 0;
    var current = { isLive: false, streamUrl: '' };

    function updateLiveUI(isLive, streamUrl) {
        var liveBtn      = document.getElementById('live-status-btn');
        var liveIndicator = document.getElementById('live-indicator');
        var liveSection  = document.getElementById('live-stream-section');

        if (liveBtn) {
            if (isLive) {
                liveBtn.classList.add('live-active');
                liveBtn.classList.remove('live-offline');
                liveBtn.setAttribute('href', streamUrl || '#');
            } else {
                liveBtn.classList.remove('live-active');
                liveBtn.classList.add('live-offline');
                liveBtn.removeAttribute('href');
            }
            liveBtn.style.pointerEvents = isLive ? 'auto' : 'none';
            liveBtn.style.opacity       = isLive ? '1' : '0.6';
            liveBtn.style.cursor        = isLive ? 'pointer' : 'default';
        }

        if (liveIndicator) {
            liveIndicator.classList.toggle('pulse', !!isLive);
        }

        if (liveSection) {
            if (isLive && streamUrl) {
                var iframe = liveSection.querySelector('iframe');
                if (iframe) {
                    var newSrc = streamUrl;
                    // Solo reasignar si cambió, para no reiniciar el video en cada ciclo
                    if (iframe.getAttribute('src') !== newSrc) {
                        iframe.setAttribute('src', newSrc);
                    }
                }
                liveSection.style.display = 'block';
            } else {
                liveSection.style.display = 'none';
            }
        }
    }

    function checkLiveStatus() {
        if (inFlight) return; // No solapar peticiones si la anterior es lenta
        inFlight = true;

        fetch(endpoint + Date.now(), { cache: 'no-store' })
            .then(function(response) {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(function(data) {
                if (data.is_live && data.stream_url) {
                    // Live detectado: encender inmediatamente
                    offlineStreak = 0;
                    current = { isLive: true, streamUrl: data.stream_url };
                    updateLiveUI(true, data.stream_url);
                } else {
                    // Sin live: exigir confirmaciones consecutivas antes de apagar
                    offlineStreak++;
                    if (!current.isLive || offlineStreak >= OFFLINE_CONFIRMATIONS) {
                        current = { isLive: false, streamUrl: '' };
                        updateLiveUI(false, '');
                    }
                }
            })
            .catch(function() {
                // Error de red: conservar el estado actual sin alternar la UI
            })
            .finally(function() {
                inFlight = false;
            });
    }

    function startPolling() {
        if (pollTimer) return;
        checkLiveStatus(); // Chequeo inmediato
        pollTimer = setInterval(checkLiveStatus, POLL_INTERVAL);
    }

    function stopPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
        }
    }

    // Ahorro de recursos: pausar cuando la pestaña está oculta
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopPolling();
        } else {
            startPolling();
        }
    });

    function initLiveStatus() {
        startPolling();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLiveStatus);
    } else {
        initLiveStatus();
    }

    // API pública para forzar un chequeo manual desde consola u otros scripts
    window.refreshLiveStatus = checkLiveStatus;
})();
