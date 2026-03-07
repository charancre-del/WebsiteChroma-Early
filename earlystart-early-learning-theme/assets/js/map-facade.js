/**
 * Map facade.
 * Lazy loads Leaflet and falls back to an embedded map if CDN assets fail.
 */
document.addEventListener('DOMContentLoaded', function () {
  const mapContainers = document.querySelectorAll('[data-chroma-map]');
  if (!mapContainers.length) {
    return;
  }

  const leafletCssSources = [
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css',
  ];

  const leafletJsSources = [
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
    'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js',
  ];

  const parseLocations = (container) => {
    const raw = container.getAttribute('data-chroma-locations');
    if (!raw) {
      return [];
    }

    try {
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed)) {
        return [];
      }

      return parsed
        .map((location) => ({
          ...location,
          lat: Number(location.lat),
          lng: Number(location.lng),
        }))
        .filter((location) => Number.isFinite(location.lat) && Number.isFinite(location.lng));
    } catch (error) {
      console.error('Unable to parse map locations', error);
      return [];
    }
  };

  const buildOsmEmbedSrc = (lat, lng) => {
    const delta = 0.06;
    const left = (lng - delta).toFixed(6);
    const bottom = (lat - delta).toFixed(6);
    const right = (lng + delta).toFixed(6);
    const top = (lat + delta).toFixed(6);

    return `https://www.openstreetmap.org/export/embed.html?bbox=${left}%2C${bottom}%2C${right}%2C${top}&layer=mapnik&marker=${lat}%2C${lng}`;
  };

  const renderFallbackMap = (container) => {
    if (!container || container.dataset.chromaMapReady === '1') {
      return;
    }

    const locations = parseLocations(container);
    const first = locations[0];

    container.innerHTML = '';
    container.style.background = '#e7e5e4';

    if (!first) {
      container.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;padding:16px;color:#57534e;font-weight:600;">Map unavailable</div>';
      container.dataset.chromaMapReady = '1';
      return;
    }

    const iframe = document.createElement('iframe');
    iframe.src = buildOsmEmbedSrc(first.lat, first.lng);
    iframe.loading = 'lazy';
    iframe.referrerPolicy = 'no-referrer-when-downgrade';
    iframe.setAttribute('aria-label', 'Clinic map');
    iframe.style.border = '0';
    iframe.style.width = '100%';
    iframe.style.height = '100%';

    container.appendChild(iframe);
    container.dataset.chromaMapReady = '1';
  };

  const renderFallbackMaps = () => {
    mapContainers.forEach(renderFallbackMap);
  };

  const loadStyleFromSources = (sources, index = 0) => {
    if (index >= sources.length) {
      return;
    }

    const href = sources[index];
    if (document.querySelector(`link[data-chroma-leaflet-css=\"${href}\"]`)) {
      return;
    }

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    link.dataset.chromaLeafletCss = href;
    link.onerror = () => loadStyleFromSources(sources, index + 1);
    document.head.appendChild(link);
  };

  const loadScriptFromSources = (sources, onSuccess, onError, index = 0) => {
    if (index >= sources.length) {
      onError();
      return;
    }

    const src = sources[index];
    const script = document.createElement('script');
    script.src = src;
    script.async = true;
    script.onload = onSuccess;
    script.onerror = () => loadScriptFromSources(sources, onSuccess, onError, index + 1);
    document.body.appendChild(script);
  };

  const ensureMapLayer = (onReady, onError) => {
    if (typeof window.chromaInitMaps === 'function') {
      onReady();
      return;
    }

    const themeUrl = window.chromaData && window.chromaData.themeUrl;
    if (!themeUrl) {
      onError();
      return;
    }

    const mapLayerScript = document.createElement('script');
    mapLayerScript.src = `${themeUrl.replace(/\/$/, '')}/assets/js/map-layer.js`;
    mapLayerScript.async = true;
    mapLayerScript.onload = () => {
      if (typeof window.chromaInitMaps === 'function') {
        onReady();
      } else {
        onError();
      }
    };
    mapLayerScript.onerror = onError;
    document.body.appendChild(mapLayerScript);
  };

  const initializeMaps = () => {
    ensureMapLayer(
      () => {
        if (typeof window.chromaInitMaps === 'function') {
          window.chromaInitMaps();
        } else {
          renderFallbackMaps();
        }
      },
      renderFallbackMaps
    );
  };

  const loadMapAssets = () => {
    if (window.chromaMapAssetsLoaded) {
      return;
    }

    window.chromaMapAssetsLoaded = true;

    loadStyleFromSources(leafletCssSources);

    if (typeof window.L !== 'undefined') {
      initializeMaps();
      return;
    }

    loadScriptFromSources(leafletJsSources, initializeMaps, renderFallbackMaps);
  };

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            loadMapAssets();
            observer.disconnect();
          }
        });
      },
      {
        rootMargin: '200px',
      }
    );

    mapContainers.forEach((container) => observer.observe(container));
  } else {
    loadMapAssets();
  }
});
