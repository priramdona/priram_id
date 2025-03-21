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
            <input type="text" id="address" name="address" class="form-control" >
        </div>

        <div class="form-group">
            <label for="city">Kota</label>
            <div style="display: flex; align-items: center;">
                <input type="text" id="city" name="city" class="form-control" >
                <button type="button" onclick="searchCity()" style="margin-left: 10px;">Cari</button>
            </div>
            <div id="city-error" class="error-message" style="display: none;">Kota tidak ditemukan.</div>
        </div>

        <div class="form-group">
            <label for="province">Provinsi</label>
            <input type="text" id="province" name="province" class="form-control" >
        </div>

        <div class="form-group">
            <label for="postal_code">Kode Pos</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control" >
        </div>

        <div class="form-group">
            <label>Pin Location</label>
            <input type="text" id="location_info" name="location_info" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Jarak dari Lokasi Awal:</label>
            <input type="text" id="distance" name="distance" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Lokasi</button>

    </form>
    <form id="distanceForm" method="POST" action="{{ route('calculate.route') }}">
        @csrf
        <label for="from_lat">From Latitude:</label>
        <input type="text" name="from_lat" id="from_lat" value="-6.200000"><br><br>

        <label for="from_lng">From Longitude:</label>
        <input type="text" name="from_lng" id="from_lng" value="106.816666" required><br><br>

        <label for="to_lat">To Latitude:</label>
        <input type="text" name="to_lat" id="to_lat" required><br><br>

        <label for="to_lng">To Longitude:</label>
        <input type="text" name="to_lng" id="to_lng" required><br><br>

        <button type="submit">Calculate Route Distance</button>
    </form>
    <div id="result">
        <!-- Hasil jarak akan ditampilkan di sini -->
    </div>
</div>

<!-- Map Container -->
<div class="map-container">
    <div id="map"></div>
    <div style="display: flex; justify-content: center;">
        <button class="location-button" onclick="centerOnCurrentLocation()">Lokasi Saat Ini</button>
        <button class="location-button" onclick="openGoogleMaps()">Buka di Google Maps</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script>
    const defaultLat = -6.200000;
    const defaultLng = 106.816666;

     document.getElementById('distanceForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.distance_km) {
                    document.getElementById('result').innerText = `Distance: ${result.distance_km} km`;
                } else {
                    document.getElementById('result').innerText = 'Failed to calculate distance.';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('result').innerText = 'Error calculating distance.';
            }
        });
    // Inisialisasi Peta dan Marker
    var map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    // Fungsi untuk memperbarui input lokasi
    function updateLocationInputs(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('address').value = data.address.road || '';
                document.getElementById('city').value = data.address.city || data.address.town || data.address.village || '';
                document.getElementById('province').value = data.address.state || '';
                document.getElementById('postal_code').value = data.address.postcode || '';
                document.getElementById('location_info').value = `Lat: ${lat}, Lng: ${lng}`;
                document.getElementById('to_lat').value = `${lat}`;
                document.getElementById('to_lng').value = `${lng}`;

                calculateDistance(lat, lng);
            })
            .catch(error => console.error('Error:', error));
    }
    // Fungsi untuk mendapatkan lokasi pengguna dan memperbarui peta
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15); // Level zoom 15 untuk lebih akurat
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

    // Fungsi untuk memusatkan peta pada lokasi saat ini
    function centerOnCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15); // Level zoom 15 untuk lebih akurat
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

    // Fungsi untuk membuka Google Maps dengan koordinat yang didapatkan
    function openGoogleMaps() {
        const locationInfo = document.getElementById('location_info').value;
        if (locationInfo) {
            const [lat, lng] = locationInfo.replace('Lat: ', '').replace('Lng: ', '').split(', ');
            if (isNaN(lat) || isNaN(lng)) {
                alert("Koordinat tidak valid. Tidak dapat membuka Google Maps.");
            } else {
                const googleMapsLink = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
                window.open(googleMapsLink, '_blank');
            }
        } else {
            alert("Koordinat tidak tersedia. Silakan deteksi lokasi terlebih dahulu.");
        }
    }

    // Memanggil getLocation secara otomatis saat halaman dimuat
    getLocation();

    // Menangani penarikan marker
    marker.on('dragend', function(event) {
        const latLng = event.target.getLatLng();
        updateLocationInputs(latLng.lat, latLng.lng);
    });

    function calculateDistance(lat2, lng2) {
        // const defaultLat = -6.200000;
        // const defaultLng = 106.816666;
        // const [lat1, lng1] = document.getElementById('from_location').value.split(',').map(Number);
        const lat1 = defaultLat;
        const lng1 = defaultLng;
        const R = 6371; // Radius bumi dalam kilometer
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c; // Jarak dalam kilometer

        document.getElementById('distance').value = `${distance.toFixed(2)} km`;
    }


    // Fungsi pencarian kota
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
                        map.setView([lat, lon], 13); // Level zoom 13 untuk pencarian kota
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

    // Mengupdate ukuran peta saat ukuran layar berubah
    window.addEventListener('resize', function() {
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });
</script>

</body>
</html>
