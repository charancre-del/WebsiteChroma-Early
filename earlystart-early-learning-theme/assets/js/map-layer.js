/**
 * Leaflet Map Layer
 *
 * @package EarlyStart_Early_Start
 */

(function () {
  const getMarkerIcon = (kind) => {
    const color = kind === 'clinic' ? '#e11d48' : '#2563eb';

    return L.divIcon({
      className: 'chroma-map-pin',
      html: `<span style="display:block;width:18px;height:18px;border-radius:9999px;background:${color};border:3px solid #ffffff;box-shadow:0 10px 24px rgba(15,23,42,.18);"></span>`,
      iconSize: [18, 18],
      iconAnchor: [9, 9],
      popupAnchor: [0, -8],
    });
  };

  const initMaps = () => {
    const mapContainers = document.querySelectorAll('[data-chroma-map]');

    if (!mapContainers.length || typeof L === 'undefined') {
      return;
    }

    mapContainers.forEach((container) => {
      // Check if map already initialized
      if (container._leaflet_id) return;

      const locationsData = container.getAttribute('data-chroma-locations');

      if (!locationsData) {
        return;
      }

      let locations;
      try {
        locations = JSON.parse(locationsData);
      } catch (e) {
        console.error('Invalid JSON in data-chroma-locations');
        return;
      }

      if (!locations || !locations.length) {
        return;
      }

      // Create map
      const map = L.map(container).setView([locations[0].lat, locations[0].lng], 12);

      // Add tile layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
      }).addTo(map);

      // Add markers
      const bounds = [];
      locations.forEach((location) => {
        const marker = L.marker([location.lat, location.lng], {
          icon: getMarkerIcon(location.kind),
        }).addTo(map);

        const popupContent = `
        <div class="text-center p-2">
          <strong class="block text-base mb-1">${location.name}</strong>
          <p class="text-sm text-gray-600 mb-2">${location.city}</p>
          <a href="${location.url}" class="text-sm text-blue-600 hover:underline">${chromaData.viewCampus || 'View campus'} →</a>
        </div>
      `;

        marker.bindPopup(popupContent);
        bounds.push([location.lat, location.lng]);
      });

      // Fit bounds if multiple locations
      if (bounds.length > 1) {
        map.fitBounds(bounds, {
          padding: [50, 50]
        });
      }
    });
  };

  // Run immediately if Leaflet is ready (dynamic load), otherwise wait for DOM (static load fallback)
  if (typeof L !== 'undefined') {
    initMaps();
  } else {
    document.addEventListener('DOMContentLoaded', initMaps);
  }
})();


