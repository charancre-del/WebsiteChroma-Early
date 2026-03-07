/**
 * Leaflet map layer.
 */
(function () {
  const escapeHtml = (value) =>
    String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\"/g, '&quot;')
      .replace(/'/g, '&#39;');

  const getPopupLabel = () => {
    if (!window.chromaData) {
      return 'View clinic';
    }

    return window.chromaData.viewClinic || window.chromaData.viewCampus || 'View clinic';
  };

  const getMarkerIcon = (kind) => {
    const color = kind === 'clinic' ? '#e11d48' : '#2563eb';

    return L.divIcon({
      className: 'chroma-map-pin',
      html: `<span style=\"display:block;width:18px;height:18px;border-radius:9999px;background:${color};border:3px solid #ffffff;box-shadow:0 10px 24px rgba(15,23,42,.18);\"></span>`,
      iconSize: [18, 18],
      iconAnchor: [9, 9],
      popupAnchor: [0, -8],
    });
  };

  const parseLocations = (raw) => {
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
      console.error('Invalid JSON in data-chroma-locations', error);
      return [];
    }
  };

  const initMap = (container) => {
    if (!container || container.dataset.chromaMapReady === '1' || container._leaflet_id) {
      return;
    }

    const locations = parseLocations(container.getAttribute('data-chroma-locations'));
    if (!locations.length) {
      return;
    }

    const first = locations[0];
    const map = L.map(container, {
      zoomControl: true,
      scrollWheelZoom: false,
    }).setView([first.lat, first.lng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
      maxZoom: 19,
    }).addTo(map);

    const popupLabel = escapeHtml(getPopupLabel());
    const bounds = [];

    locations.forEach((location) => {
      const marker = L.marker([location.lat, location.lng], {
        icon: getMarkerIcon(location.kind),
      }).addTo(map);

      const name = escapeHtml(location.name);
      const city = escapeHtml(location.city);
      const url = String(location.url || '#');

      const popupContent = `
        <div class=\"text-center p-2\">
          <strong class=\"block text-base mb-1\">${name}</strong>
          <p class=\"text-sm text-gray-600 mb-2\">${city}</p>
          <a href=\"${url}\" class=\"text-sm text-blue-600 hover:underline\">${popupLabel} &rarr;</a>
        </div>
      `;

      marker.bindPopup(popupContent);
      bounds.push([location.lat, location.lng]);
    });

    if (bounds.length > 1) {
      map.fitBounds(bounds, { padding: [40, 40] });
    } else {
      map.setView(bounds[0], 13);
    }

    container.dataset.chromaMapReady = '1';

    requestAnimationFrame(() => map.invalidateSize());
    window.addEventListener(
      'resize',
      () => {
        map.invalidateSize();
      },
      { passive: true }
    );
  };

  const initMaps = () => {
    if (typeof L === 'undefined') {
      return;
    }

    const mapContainers = document.querySelectorAll('[data-chroma-map]');
    if (!mapContainers.length) {
      return;
    }

    mapContainers.forEach(initMap);
  };

  window.chromaInitMaps = initMaps;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMaps);
  } else {
    initMaps();
  }
})();
