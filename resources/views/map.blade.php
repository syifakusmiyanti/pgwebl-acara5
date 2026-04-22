@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            overflow: hidden;
        }

        /* ================= GLASS NAVBAR ================= */
        .navbar-glass {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            z-index: 2000;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(0, 255, 100, 0.2);
            box-shadow: 0 0 25px rgba(0, 255, 100, 0.15);
        }

        .nav-menu a {
            color: #d1fae5;
            text-decoration: none;
            margin-right: 30px;
            font-size: 14px;
            cursor: pointer;
            position: relative;
        }

        .nav-menu a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0%;
            height: 2px;
            background: #22c55e;
            transition: 0.3s;
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        /* Search */
        .search-box {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            border: 1px solid rgba(34, 197, 94, 0.4);
            overflow: hidden;
        }

        .search-box input {
            background: transparent;
            border: none;
            color: white;
            padding: 8px 15px;
            outline: none;
            width: 170px;
        }

        .search-box button {
            background: linear-gradient(90deg, #16a34a, #22c55e);
            border: none;
            color: white;
            padding: 8px 18px;
            cursor: pointer;
        }

        /* ================= MAP ================= */
        #map {
            height: 100vh;
            width: 100%;
        }

        /* Turunin sedikit control biar aman */
        .leaflet-top {
            margin-top: 100px;
        }

        /* Marker Animation */
        @keyframes bounceIn {
            0% {
                transform: translateY(-200px);
                opacity: 0;
            }

            60% {
                transform: translateY(20px);
                opacity: 1;
            }

            80% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .glow-marker {
            animation: bounceIn 0.8s ease;
            filter: drop-shadow(0 0 8px #22c55e);
        }

        /* ================= MODAL POPUP ================= */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
            z-index: 3000;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .close-btn {
            margin-top: 15px;
            padding: 8px 15px;
            background: #16a34a;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
@endsection

{{-- pointt --}}
@section('content')
    <div id="map"></div>
    <div class="modal" tabindex="-1" id="ModalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>
                        <div class="mb-3">
                            <label for="Name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="Name" name="name"
                                placeholder="Fill Name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
                        </div>
                        </p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal input polyline --}}
    <div class="modal" tabindex="-1" id="ModalInputPolyline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polylines.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>
                        <div class="mb-3">
                            <label for="Name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="Name" name="name"
                                placeholder="Fill Name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polyline" name="geometry_polyline" rows="3"></textarea>
                        </div>
                        </p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal input polygons --}}
    <div class="modal" tabindex="-1" id="ModalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygons.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>
                        <div class="mb-3">
                            <label for="Name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="Name" name="name"
                                placeholder="Fill Name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polygon" name="geometry_polygon" rows="3"></textarea>
                        </div>
                        </p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Leaflet Draw JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    {{-- teraformer js --}}
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    {{-- j query --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // INIT MAP
        var map = L.map('map', {
            zoomControl: false
        }).setView([-7.7956, 110.3695], 10);

        L.control.zoom({
            position: 'topleft'
        }).addTo(map);

        // BASEMAP
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var esriSat = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        );

        var esriTopo = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}'
        );

        L.control.layers({
            "OpenStreetMap": osm,
            "Esri Satellite": esriSat,
            "Esri Topographic": esriTopo
        }, {}, {
            position: 'topright'
        }).addTo(map);

        L.control.scale({
            position: 'bottomleft'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);

            if (type === 'polyline') {
                console.log("Create " + type);
                // Show Geometry in Modal
                $('#geometry_polyline').val(objectGeometry);

                // Show Modal Input Polyline
                $('#ModalInputPolyline').modal('show');

                //modal dismiss reload page
                $('#ModalInputPolyline').on('hidden.bs.modal', function() {
                    location.reload();
                });

                // polygonnn

            } else if (type === 'polygon' || type === 'rectangle') {
                console.log("Create " + type);
                // Show Geometry in Modal
                $('#geometry_polygon').val(objectGeometry);

                // Show Modal Input Polygon
                $('#ModalInputPolygon').modal('show');

                //modal dismiss reload page
                $('#ModalInputPolygon').on('hidden.bs.modal', function() {
                    location.reload();
                });

                // pointt

            } else if (type === 'marker') {
                console.log("Create " + type);
                // Show Geometry in Modal
                $('#geometry_point').val(objectGeometry);

                // Show Modal Input Point
                $('#ModalInputPoint').modal('show');

                //modal dismiss reload page
                $('#ModalInputPoint').on('hidden.bs.modal', function() {
                    location.reload();
                });

            } else {
                console.log('__undefined__');
            }

            drawnItems.addLayer(layer);
        });


        // SEARCH
        function searchLocation() {
            var location = document.getElementById("searchInput").value;
            if (location === "") return;

            fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + location)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        var lat = data[0].lat;
                        var lon = data[0].lon;

                        map.flyTo([lat, lon], 14, {
                            duration: 2
                        });

                        var marker = L.marker([lat, lon]).addTo(map);
                        marker._icon.classList.add("glow-marker");
                        marker.bindPopup("<b>" + location + "</b>").openPopup();
                    } else {
                        alert("Lokasi tidak ditemukan");
                    }
                });
        }

        // MODAL FUNCTION
        function openModal(title, text) {
            document.getElementById("modalTitle").innerText = title;
            document.getElementById("modalText").innerText = text;
            document.getElementById("modal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }

        //Points Layer
        var points = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at;

                layer.on({
                    click: function() {
                        points.bindPopup(popup_content);
                    }
                });
            }

            // onEachFeature

        });

        $.getJSON("/api/points", function(data) {
            points.addData(data); // Menambahkan data ke dalam GeoJSON Point
            map.addLayer(points); // Menambahkan GeoJSON Point ke dalam peta
        });

        //Polylines Layer
        var polylines = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at;

                layer.on({
                    click: function() {
                        polylines.bindPopup(popup_content);
                    }
                });
            }


        });

        $.getJSON("/api/polylines", function(data) {
            polylines.addData(data); // Menambahkan data ke dalam GeoJSON Polyline
            map.addLayer(polylines); // Menambahkan GeoJSON Polyline ke dalam peta
        });

        //Polygons Layer
        var polygons = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at;

                layer.on({
                    click: function() {
                        polygons.bindPopup(popup_content);
                    }
                });
            }

            // onEachFeature

        });

        $.getJSON("/api/polygons", function(data) {
            polygons.addData(data); // Menambahkan data ke dalam GeoJSON Polygon
            map.addLayer(polygons); // Menambahkan GeoJSON Polygon ke dalam peta
        });

        // Control Layer
        var baseMaps = {

        };

        var overlayMaps = {
            "Points": points,
            "Polylines": polylines,
            "Polygons": polygons,
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps);
        controllayer.addTo(map);
    </script>
@endsection
