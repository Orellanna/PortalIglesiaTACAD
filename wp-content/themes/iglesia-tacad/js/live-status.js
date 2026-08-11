/**
 * Live Status Checker
 * Polls the server to check if YouTube channel is live
 */
(function() {
    'use strict';

    const POLL_INTERVAL = 30000;
    let pollTimer = null;
    let lastKnownState = { isLive: false, streamUrl: '' };

    function updateLiveUI(isLive, streamUrl) {
        const liveBtn = document.getElementById('live-status-btn');
        const liveIndicator = document.getElementById('live-indicator');
        const liveSection = document.getElementById('live-stream-section');

        console.log('Live Status Update:', { isLive: isLive, streamUrl: streamUrl });

        if (liveBtn) {
            if (isLive) {
                liveBtn.classList.add('live-active');
                liveBtn.classList.remove('live-offline');
                liveBtn.setAttribute('href', streamUrl || '#');
                liveBtn.style.pointerEvents = 'auto';
                liveBtn.style.opacity = '1';
                liveBtn.style.cursor = 'pointer';

                if (liveIndicator) {
                    liveIndicator.classList.add('pulse');
                }
            } else {
                liveBtn.classList.remove('live-active');
                liveBtn.classList.add('live-offline');
                liveBtn.removeAttribute('href');
                liveBtn.style.pointerEvents = 'none';
                liveBtn.style.opacity = '0.6';
                liveBtn.style.cursor = 'default';

                if (liveIndicator) {
                    liveIndicator.classList.remove('pulse');
                }
            }
        }

        if (liveSection) {
            if (isLive && streamUrl) {
                liveSection.style.display = 'block';
                const iframe = liveSection.querySelector('iframe');
                if (iframe) {
                    iframe.src = streamUrl;
                }
            } else {
                liveSection.style.display = 'none';
            }
        }
    }

    function checkLiveStatus() {
        console.log('Checking live status...');

        var baseUrl = 'http://localhost/PortalWebTACAD';
        fetch(baseUrl + '/wp-json/iglesia/v1/live-status')
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                console.log('Live status response:', data);

                if (data.is_live && data.stream_url) {
                    lastKnownState = { isLive: true, streamUrl: data.stream_url };
                    updateLiveUI(true, data.stream_url);
                } else if (lastKnownState.isLive) {
                    console.log('API returned offline but keeping last known state');
                    updateLiveUI(true, lastKnownState.streamUrl);
                } else {
                    updateLiveUI(false, '');
                }
            })
            .catch(function(error) {
                console.log('Live status check failed:', error);
                if (lastKnownState.isLive) {
                    updateLiveUI(true, lastKnownState.streamUrl);
                }
            });
    }

    function initLiveStatus() {
        console.log('Initializing live status checker...');
        checkLiveStatus();
        pollTimer = setInterval(checkLiveStatus, POLL_INTERVAL);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLiveStatus);
    } else {
        initLiveStatus();
    }

    window.refreshLiveStatus = checkLiveStatus;
})();
