<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pick Location</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <style>
        #map {
            height: 400px;
            border-radius: 8px;
        }

        #suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: #fff;
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
        }

        .suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f8f9fa;
        }

        #loading {
            text-align: center;
            margin-bottom: 1rem;
            display: none;
        }

        .loading-spinner {
            width: 1.5rem;
            height: 1.5rem;
            border: 0.25em solid transparent;
            border-top: 0.25em solid #000;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="text-center mb-4">Pilih Titik Penjemputan</h2>

        <div class="mb-3 position-relative">
            <input type="text" id="search-input" class="form-control" placeholder="Search for a location..."
                autocomplete="off" />
            <div id="suggestions"></div>
        </div>

        <div id="loading">
            <div class="loading-spinner"></div>
            <div>Mencari lokasi...</div>
        </div>

        <div id="map" class="mb-3"></div>

        <form action="/orderPaket/updateLocation" method="post" class="text-center">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="latitudeInput" id="latitudeInput" />
            <input type="hidden" name="longitudeInput" id="longitudeInput" />
            <input type="hidden" name="distanceInput" id="distanceInput" />
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div id="distance" class="mt-3 text-center fw-semibold text-dark"></div>
    </div>

    <script>
        const startLocation = L.latLng(-2.2318895045108835, 113.8875164970548);
        const map = L.map('map').setView(startLocation, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker(startLocation).addTo(map).bindPopup('A68 Laundry').openPopup();

        const finishMarker = L.marker(startLocation, {
            draggable: true
        }).addTo(map);

        const latInput = document.getElementById('latitudeInput');
        const lngInput = document.getElementById('longitudeInput');
        const disInput = document.getElementById('distanceInput');

        const distanceEl = document.getElementById('distance');
        const suggestionsEl = document.getElementById('suggestions');
        const loadingEl = document.getElementById('loading');
        const searchInput = document.getElementById('search-input');

        // Update hidden inputs
        function updateLatLngInputs(latlng) {
            latInput.value = latlng.lat;
            lngInput.value = latlng.lng;
        }

        // Get driving distance from OSRM
        function getRouteDistance(start, finish) {
            const url =
                `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${finish.lng},${finish.lat}?overview=false`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.routes && data.routes.length) {
                        const distKm = (data.routes[0].legs[0].distance / 1000).toFixed(2);
                        distanceEl.textContent = `Jarak: ${distKm} km`;
                        disInput.value = distKm;
                    } else {
                        distanceEl.textContent = 'Jarak: Tidak tersedia';
                    }
                })
                .catch(() => {
                    distanceEl.textContent = 'Gagal mengambil jarak';
                });
        }

        // Search locations within bounding box
        function searchLocations(query) {
            const viewbox = '113.8500,-2.0800,114.0600,-2.3100'; // bounding box Palangka Raya
            const url = `/orderPaket/geocode?q=${encodeURIComponent(query)}&viewbox=${viewbox}&bounded=1`;

            loadingEl.style.display = 'block';
            suggestionsEl.innerHTML = '';

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        suggestionsEl.innerHTML =
                            '<div class="suggestion-item">No results found in Palangka Raya</div>';
                        return;
                    }
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = item.display_name;
                        div.onclick = () => {
                            const latlng = L.latLng(item.lat, item.lon);
                            map.setView(latlng, 16);
                            finishMarker.setLatLng(latlng);
                            updateLatLngInputs(latlng);
                            searchInput.value = item.display_name;
                            suggestionsEl.innerHTML = '';
                            loadingEl.style.display = 'none';
                            getRouteDistance(startLocation, latlng);
                        };
                        suggestionsEl.appendChild(div);
                    });
                })
                .catch(() => {
                    suggestionsEl.innerHTML = '<div class="suggestion-item">Error fetching results</div>';
                })
                .finally(() => {
                    loadingEl.style.display = 'none';
                });
        }

        // Event listeners
        finishMarker.on('dragend', () => {
            const latlng = finishMarker.getLatLng();
            updateLatLngInputs(latlng);
            getRouteDistance(startLocation, latlng);
        });

        map.on('click', e => {
            finishMarker.setLatLng(e.latlng);
            updateLatLngInputs(e.latlng);
            getRouteDistance(startLocation, e.latlng);
        });

        searchInput.addEventListener('keydown', e => {
            if (e.key === "Enter") {
                const query = e.target.value.trim();
                if (query.length >= 3) {
                    searchLocations(query);
                } else {
                    suggestionsEl.innerHTML = '';
                }
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target)) {
                suggestionsEl.innerHTML = '';
            }
        });

        // Init hidden inputs & distance
        updateLatLngInputs(startLocation);
        getRouteDistance(startLocation, startLocation);
    </script>
</body>

</html>
