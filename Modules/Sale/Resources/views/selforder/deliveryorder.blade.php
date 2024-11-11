<!-- resources/views/address-form.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Alamat dengan Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" integrity="sha512-xodZBntM7A2aPLB72pt0KHuy6KO7s3sm27QrmY9zzktsHfKPLb6b7WLSg03p7pX2PeDLwE9ogLl5K3Q9Pa57hQ==" crossorigin="anonymous" />
    <style>
        #map { height: 400px; width: 100%; }
        .form-container { max-width: 600px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        .label-location { font-weight: bold; color: #555; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Input Informasi Alamat</h2>

    <!-- Form Address -->
    <form action="{{ route('address.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="address">Alamat</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="city">Kota</label>
            <input type="text" id="city" name="city" class="form-control" required>
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
            <label class="label-location">Pin Location</label>
            <input type="text" id="location_info" name="location_info" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Alamat</button>
    </form>

    <!-- Map Container -->
    <div id="map"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js" integrity="sha512-tAGcCfYXsaMgBxCu9yWUBxLg3B34k6Yo/3BzBmkP1QZ0bg5+LV7ucwbeHFFl6rsGi0uJ0jRT+lEmgZ6Vnql5dw==" crossorigin="anonymous"></script>
<script>
    // Initialize map
    var map = L.map('map').setView([-6.200000, 106.816666], 13); // Center in Jakarta

    // Add tile layer to map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Add a draggable marker to the map
    var marker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(map);

    // Update location input with marker position
    function updateLocationInput(lat, lng) {
        document.getElementById('location_info').value = `Lat: ${lat}, Lng: ${lng}`;
    }

    // Initial position
    updateLocationInput(marker.getLatLng().lat, marker.getLatLng().lng);

    // Event listener for dragging marker
    marker.on('dragend', function(event) {
        var position = event.target.getLatLng();
        updateLocationInput(position.lat, position.lng);
    });

    // Responsive Map resizing
    window.addEventListener('resize', function() {
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });
</script>
</body>
</html>
