<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Lokasi dengan Deteksi Otomatis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        .map-container {
            max-width: 700px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        #map {
            height: 400px;
            width: 100%;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .location-button {
            margin: 10px;
            padding: 8px 12px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Input Lokasi dengan Deteksi Otomatis</h2>

    <!-- Form Address -->
    <form action="" method="POST">
        @csrf
        <div class="form-group">
            <label for="address">Alamat</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="city">Kota</label>
            <div style="display: flex; align-items: center;">
                <input type="text" id="city" name="city" class="form-control" required>
                <button type="button" onclick="searchCity()" style="margin-left: 10px;">Cari</button>
            </div>
            <div id="city-error" class="error-message" style="display: none;">Kota tidak ditemukan.</div>
        </div>

        <div class="form-group">
            <label for="province">Provinsi</label>
            <input type="text" id="province" name="province" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="postal_code">Kode Pos</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Pin Location</label>
            <input type="text" id="location_info" name="location_info" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
    </form>
</div>

<!-- Map Container -->
<div class="map-container">
    <div id="map"></div>
    <button class="location-button" onclick="centerOnCurrentLocation()">Lokasi Saat Ini</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script>
    const defaultLat = -6.200000;
    const defaultLng = 106.816666;

    var map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    function updateLocationInputs(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('address').value = data.address.road || '';
                document.getElementById('city').value = data.address.city || data.address.town || data.address.village || '';
                document.getElementById('province').value = data.address.state || '';
                document.getElementById('postal_code').value = data.address.postcode || '';
                document.getElementById('location_info').value = `Lat: ${lat}, Lng: ${lng}`;
            })
            .catch(error => console.error('Error:', error));
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15);
                marker.setLatLng([userLat, userLng]);
                updateLocationInputs(userLat, userLng);
            }, error => {
                console.error('Error retrieving location:', error.message);
                alert('Tidak dapat mendeteksi lokasi Anda. Menampilkan lokasi default.');
            });
        } else {
            alert('Geolocation tidak didukung di browser Anda.');
        }
    }

    function centerOnCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15);
                marker.setLatLng([userLat, userLng]);
                updateLocationInputs(userLat, userLng);
            }, error => {
                if (error.code === error.PERMISSION_DENIED) {
                    alert("Izin lokasi diperlukan untuk mengakses lokasi saat ini.");
                    getLocation();  // Minta izin ulang
                } else {
                    console.error('Error retrieving location:', error.message);
                    alert('Tidak dapat mendeteksi lokasi Anda.');
                }
            });
        } else {
            alert('Geolocation tidak didukung di browser Anda.');
        }
    }

    getLocation();

    marker.on('dragend', function(event) {
        const latLng = event.target.getLatLng();
        updateLocationInputs(latLng.lat, latLng.lng);
    });

    function searchCity() {
        const city = document.getElementById('city').value;
        const cityError = document.getElementById('city-error');
        cityError.style.display = 'none';

        if (city) {
            fetch(`https://nominatim.openstreetmap.org/search?city=${encodeURIComponent(city)}&format=json&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = data[0].lat;
                        const lon = data[0].lon;
                        map.setView([lat, lon], 13);
                        marker.setLatLng([lat, lon]);
                        updateLocationInputs(lat, lon);
                    } else {
                        cityError.style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            alert("Silakan masukkan nama kota terlebih dahulu.");
        }
    }

    window.addEventListener('resize', function() {
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });
</script>

</body>
</html>
