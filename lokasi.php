<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Kitar Semula - epkah</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #e0f7fa, #a5d6a7);
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        h1 {
            color: #2e7d32;
            margin-top: 20px;
            font-size: 36px;
        }
        #map {
            width: 100%;
            height: 400px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .search-container {
            margin-top: 20px;
        }
        select {
            padding: 10px;
            font-size: 18px;
            border-radius: 10px;
            border: 2px solid #388e3c;
            background-color: #ffffff;
            cursor: pointer;
        }
        .locations {
            margin-top: 40px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding-bottom: 50px;
        }
        .location-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer; /* Make card clickable */
        }
        .location-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            background: #e8f5e9;
        }
        .location-card h3 {
            margin-top: 0;
            color: #388e3c;
        }
        .location-card p {
            font-size: 20px;
            color: #555;
        }
        a {
            text-decoration: none;
            color: #2e7d32;
        }
        .back-home-btn {
            margin-top: 30px;
            padding: 15px 30px;
            background-color: #81c784; /* light green */
            color: white;
            font-size: 16px;
            border: 2px solid #388e3c;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .back-home-btn:hover {
            background-color: #66bb6a;
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <h1>📍 Lokasi Kitar Semula Mengikut Jajahan</h1>

    <!-- Search Dropdown -->
    <div class="search-container">
        <label for="jajahan">Pilih Jajahan:</label>
        <select id="jajahan" onchange="filterLocations()">
            <option value="">Pilih Jajahan</option>
            <option value="Kota Bharu">Kota Bharu</option>
            <option value="Pengkalan Chepa">Pengkalan Chepa</option>
            <option value="Wakaf Bharu">Wakaf Bharu</option>
        </select>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- List of Locations (Grouped by Jajahan) -->
    <div class="locations" id="location-cards">
        <!-- Cards will be populated based on search -->
    </div>

    <!-- Back to Home Button -->
    <a href="home.php" class="back-home-btn">🏠 Kembali ke Home</a>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Initialize the map
        var map = L.map('map').setView([6.1254, 102.2460], 10); // Lokasi pusat Kelantan

        // Tile layer for Leaflet map (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Data locations for Kelantan (grouped by Jajahan)
        var locations = [
            { name: "Pusat Kitar Semula Kota Bharu", lat: 6.1262, lon: 102.2475, jajahan: "Kota Bharu" },
            { name: "Pusat Kitar Semula Pengkalan Chepa", lat: 6.1267, lon: 102.2995, jajahan: "Pengkalan Chepa" },
            { name: "Pusat Kitar Semula Wakaf Bharu", lat: 6.2052, lon: 102.1539, jajahan: "Wakaf Bharu" }
        ];

        // Function to add markers on the map based on selected Jajahan
        function filterLocations() {
            var selectedJajahan = document.getElementById('jajahan').value;

            // Clear all previous markers
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            // Clear previous location cards
            var locationCardsContainer = document.getElementById('location-cards');
            locationCardsContainer.innerHTML = '';

            // Filter locations by Jajahan
            var filteredLocations = locations.filter(function(location) {
                return location.jajahan === selectedJajahan || selectedJajahan === '';
            });

            // Add filtered locations to the map and display the corresponding cards
            filteredLocations.forEach(function(location) {
                // Add marker to map
                L.marker([location.lat, location.lon])
                    .addTo(map)
                    .bindPopup("<b>" + location.name + "</b><br>Jajahan: " + location.jajahan)
                    .openPopup();

                // Create and append location cards
                var card = document.createElement('div');
                card.className = 'location-card';
                card.innerHTML = `
                    <h3>🌱 ${location.jajahan}</h3>
                    <p>Lokasi Kitar Semula: ${location.name}</p>
                    <p>📍 Klik untuk lihat lokasi di Google Maps</p>
                `;

                // Add click event to open Google Maps when card is clicked
                card.addEventListener('click', function() {
                    window.open(`https://www.google.com/maps?q=${location.lat},${location.lon}&hl=ms`, '_blank');
                });

                locationCardsContainer.appendChild(card);
            });
        }

        // Call the function initially to display all locations
        filterLocations();
    </script>

</body>
</html>
