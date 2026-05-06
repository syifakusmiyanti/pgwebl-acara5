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

        #map {
            height: 100vh;
            width: 100%;
        }

        .leaflet-top {
            margin-top: 100px;
        }

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

        /* ★ PULSE DOT MARKER */
        .pulse-marker {
            position: relative;
            width: 44px;
            height: 44px;
        }

        .pulse-marker-core {
            width: 14px;
            height: 14px;
            background: #22c55e;
            border-radius: 50%;
            border: 2.5px solid #ffffff;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.35);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

        .pulse-marker-ring1 {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid rgba(34, 197, 94, 0.55);
            transform: translate(-50%, -50%) scale(0.6);
            animation: pulseRing 2s ease-out infinite;
            z-index: 1;
        }

        .pulse-marker-ring2 {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid rgba(34, 197, 94, 0.28);
            transform: translate(-50%, -50%) scale(0.4);
            animation: pulseRing 2s ease-out infinite;
            animation-delay: 0.6s;
            z-index: 1;
        }

        @keyframes pulseRing {
            0% {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 0.85;
            }

            100% {
                transform: translate(-50%, -50%) scale(1.6);
                opacity: 0;
            }
        }

        /* ★ BOOTSTRAP MODAL — PUTIH dengan green accent */
        #ModalInputPoint .modal-dialog,
        #ModalInputPolyline .modal-dialog,
        #ModalInputPolygon .modal-dialog {
            max-width: 520px;
        }

        #ModalInputPoint .modal-content,
        #ModalInputPolyline .modal-content,
        #ModalInputPolygon .modal-content {
            background: #ffffff !important;
            border: 1.5px solid rgba(34, 197, 94, 0.3) !important;
            border-radius: 16px !important;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12) !important;
            overflow: hidden;
            animation: glassIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
            padding: 0;
            text-align: left;
        }

        @keyframes glassIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        #ModalInputPoint .modal-header,
        #ModalInputPolyline .modal-header,
        #ModalInputPolygon .modal-header {
            background: #f0fdf4 !important;
            border-bottom: 1px solid rgba(34, 197, 94, 0.2) !important;
            padding: 16px 22px 14px !important;
        }

        #ModalInputPoint .modal-title,
        #ModalInputPolyline .modal-title,
        #ModalInputPolygon .modal-title {
            color: #15803d !important;
            font-size: 15px !important;
            font-weight: 600 !important;
        }

        #ModalInputPoint .btn-close,
        #ModalInputPolyline .btn-close,
        #ModalInputPolygon .btn-close {
            filter: none !important;
            opacity: 0.5 !important;
        }

        #ModalInputPoint .modal-body,
        #ModalInputPolyline .modal-body,
        #ModalInputPolygon .modal-body {
            background: #ffffff !important;
            padding: 16px 22px !important;
        }

        #ModalInputPoint .modal-footer,
        #ModalInputPolyline .modal-footer,
        #ModalInputPolygon .modal-footer {
            background: #f9fafb !important;
            border-top: 1px solid rgba(34, 197, 94, 0.15) !important;
            padding: 12px 22px 16px !important;
        }

        #ModalInputPoint .form-label,
        #ModalInputPolyline .form-label,
        #ModalInputPolygon .form-label {
            color: #15803d !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 4px !important;
        }

        #ModalInputPoint .form-control,
        #ModalInputPolyline .form-control,
        #ModalInputPolygon .form-control {
            background: #f9fafb !important;
            border: 1px solid rgba(34, 197, 94, 0.3) !important;
            border-radius: 10px !important;
            color: #1a1a1a !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
        }

        #ModalInputPoint .form-control::placeholder,
        #ModalInputPolyline .form-control::placeholder,
        #ModalInputPolygon .form-control::placeholder {
            color: #9ca3af !important;
        }

        #ModalInputPoint .form-control:focus,
        #ModalInputPolyline .form-control:focus,
        #ModalInputPolygon .form-control:focus {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12) !important;
            background: #ffffff !important;
        }

        #geometry_point,
        #geometry_polyline,
        #geometry_polygon {
            font-family: 'Courier New', monospace !important;
            font-size: 11px !important;
            color: #15803d !important;
            background: #f0fdf4 !important;
            border-color: rgba(34, 197, 94, 0.25) !important;
        }

        #ModalInputPoint input[type="file"].form-control,
        #ModalInputPolyline input[type="file"].form-control,
        #ModalInputPolygon input[type="file"].form-control {
            color: #374151 !important;
        }

        .img-thumbnail {
            border: 1px solid rgba(34, 197, 94, 0.3) !important;
            background: #f9fafb !important;
            border-radius: 10px !important;
            margin-top: 8px;
        }

        #ModalInputPoint .btn-secondary,
        #ModalInputPolyline .btn-secondary,
        #ModalInputPolygon .btn-secondary {
            background: #f3f4f6 !important;
            border: 1px solid #d1d5db !important;
            color: #374151 !important;
            border-radius: 10px !important;
            font-size: 13px !important;
        }

        #ModalInputPoint .btn-primary,
        #ModalInputPolyline .btn-primary,
        #ModalInputPolygon .btn-primary {
            background: linear-gradient(135deg, #166534, #22c55e) !important;
            border: none !important;
            border-radius: 10px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            letter-spacing: 0.3px !important;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.28) !important;
            transition: transform 0.15s, box-shadow 0.15s !important;
        }

        #ModalInputPoint .btn-primary:hover,
        #ModalInputPolyline .btn-primary:hover,
        #ModalInputPolygon .btn-primary:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 7px 20px rgba(34, 197, 94, 0.38) !important;
        }

        /* ★ LEAFLET POPUP */
        .leaflet-popup-content-wrapper {
            background: #ffffff !important;
            border: 1.5px solid rgba(34, 197, 94, 0.25) !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.13) !important;
            padding: 0 !important;
            overflow: hidden;
            width: 300px !important;
        }

        .leaflet-popup-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Judul */
        .lp-title {
            background: #f0fdf4;
            border-bottom: 1px solid rgba(34, 197, 94, 0.2);
            padding: 13px 16px 11px;
            font-size: 15px;
            font-weight: 700;
            color: #15803d;
        }

        /* Baris label-value */
        .lp-row {
            display: flex;
            align-items: baseline;
            padding: 5px 16px;
            gap: 8px;
            border-bottom: 1px solid #f3f4f6;
        }

        .lp-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
            min-width: 70px;
        }

        .lp-val {
            font-size: 12px;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        /* Gambar */
        .leaflet-popup-content .img-thumbnail {
            width: calc(100% - 32px) !important;
            max-width: calc(100% - 32px) !important;
            display: block !important;
            margin: 10px 16px 0 !important;
            border-radius: 10px !important;
            border: 1px solid rgba(34, 197, 94, 0.2) !important;
            object-fit: cover !important;
            max-height: 150px !important;
        }

        /* Footer tombol */
        .lp-footer {
            padding: 10px 16px 12px;
            display: flex;
            justify-content: flex-end;
        }

        .leaflet-popup-content .btn-danger {
            background: #ef4444 !important;
            border: none !important;
            border-radius: 8px !important;
            width: 34px !important;
            height: 34px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 14px !important;
            cursor: pointer;
        }

        .leaflet-popup-content .btn-danger:hover {
            background: #dc2626 !important;
        }

        .leaflet-popup-content .btn-danger i.fa-trash::before {
            content: "🗑" !important;
            font-style: normal !important;
        }

        .leaflet-popup-content form {
            margin: 0 !important;
        }

        .leaflet-popup-tip {
            background: #ffffff !important;
        }

        .leaflet-popup-close-button {
            color: #9ca3af !important;
            font-size: 16px !important;
            padding: 8px 10px !important;
            top: 4px !important;
            right: 4px !important;
            z-index: 10;
        }

        .leaflet-popup-close-button:hover {
            color: #374151 !important;
            background: none !important;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    {{-- modal input point --}}
    <div class="modal" tabindex="-1" id="ModalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                width="400">
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
                <form action="{{ route('polylines.store') }}" method="post" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail"
                                width="400">
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

    {{-- modal input polygon --}}
    <div class="modal" tabindex="-1" id="ModalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygons.store') }}" method="post" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail"
                                width="400">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
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

        // DIGITIZE
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
            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            if (type === 'polyline') {
                $('#geometry_polyline').val(objectGeometry);
                $('#ModalInputPolyline').modal('show');
                $('#ModalInputPolyline').on('hidden.bs.modal', function() {
                    location.reload();
                });

            } else if (type === 'polygon' || type === 'rectangle') {
                $('#geometry_polygon').val(objectGeometry);
                $('#ModalInputPolygon').modal('show');
                $('#ModalInputPolygon').on('hidden.bs.modal', function() {
                    location.reload();
                });

            } else if (type === 'marker') {
                $('#geometry_point').val(objectGeometry);
                $('#ModalInputPoint').modal('show');
                $('#ModalInputPoint').on('hidden.bs.modal', function() {
                    location.reload();
                });
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
                        map.flyTo([data[0].lat, data[0].lon], 14, {
                            duration: 2
                        });
                        var marker = L.marker([data[0].lat, data[0].lon]).addTo(map);
                        marker._icon.classList.add("glow-marker");
                        marker.bindPopup("<b>" + location + "</b>").openPopup();
                    } else {
                        alert("Lokasi tidak ditemukan");
                    }
                });
        }

        // ★ PULSE DOT ICON
        function pulseDotIcon() {
            return L.divIcon({
                className: '',
                html: '<div class="pulse-marker">' +
                    '<div class="pulse-marker-ring2"></div>' +
                    '<div class="pulse-marker-ring1"></div>' +
                    '<div class="pulse-marker-core"></div>' +
                    '</div>',
                iconSize: [44, 44],
                iconAnchor: [22, 22],
                popupAnchor: [0, -24]
            });
        }


        // Points Layer
        var points = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ url('delete-points') }}/" + feature.properties.id;


                // bebas ubah popup_content di sini, ga ngaruh ke style apapun
                var popup_content =
                    "<div class='lp-title'>" + feature.properties.name + "</div>" +
                    "<div class='lp-row'><span class='lp-label'>Nama</span><span class='lp-val'>" + feature
                    .properties.name + "</span></div>" +
                    "<div class='lp-row'><span class='lp-label'>Deskripsi</span><span class='lp-val'>" + feature
                    .properties.description + "</span></div>" +
                    "<div class='lp-row'><span class='lp-label'>Dibuat</span><span class='lp-val'>" + feature
                    .properties.created_at + "</span></div>" +
                    "<img src='{{ asset('storage/images') }}/" + feature.properties.image +
                    "' alt='' class='img-thumbnail' width='100'>" +
                    "<div class='lp-footer'>" +
                    "<form action='" + routedelete + "' method='post'>" +
                    '@csrf' +
                    '@method('delete')' +
                    "<button type='submit' class='btn btn-sm btn-danger' title='Delete feature' " +
                    "onclick='return confirm(\"Are you sure you want to delete this feature?\")'>" +
                    "<i class='fa-solid fa-trash'></i></button>" +
                    "</form>" +
                    "</div>";

                layer.on({
                    click: function(e) {
                        points.bindPopup(popup_content);
                    }
                });
            }
        });

        $.getJSON("/api/points", function(data) {
            points.addData(data);
            map.addLayer(points);
            points.eachLayer(function(layer) {
                if (layer.setIcon) layer.setIcon(pulseDotIcon());
            });
        });



        // Polylines Layer
        var polylines = L.geoJSON(null, {
            style: function() {
                return {
                    color: '#16a34a',
                    weight: 3,
                    opacity: 0.85
                };
            },
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ url('delete-polylines') }}/" + feature.properties.id;

                var popup_content =
                    "<div class='lp-title'>" + feature.properties.name + "</div>" +
                    "<div class='lp-row'><span class='lp-label'>Nama</span><span class='lp-val'>" + feature
                    .properties.name + "</span></div>" +
                    "<div class='lp-row'><span class='lp-label'>Deskripsi</span><span class='lp-val'>" + feature
                    .properties.description + "</span></div>" +
                    "<div class='lp-row'><span class='lp-label'>Dibuat</span><span class='lp-val'>" + feature
                    .properties.created_at + "</span></div>" +
                    "<img src='{{ asset('storage/images') }}/" + feature.properties.image +
                    "' alt='' class='img-thumbnail' width='100'>" +
                    "<div class='lp-footer'>" +
                    "<form action='" + routedelete + "' method='post'>" +
                    '@csrf' +
                    '@method('delete')' +
                    "<button type='submit' class='btn btn-sm btn-danger' title='Delete feature' " +
                    "onclick='return confirm(\"Are you sure you want to delete this feature?\")'>" +
                    "<i class='fa-solid fa-trash'></i></button>" +
                    "</form>" +
                    "</div>";

                layer.on({
                    click: function(e) {
                        polylines.bindPopup(popup_content);
                    }
                });
            }
        });

        $.getJSON("/api/polylines", function(data) {
            polylines.addData(data);
            map.addLayer(polylines);
        });

        // =========================================================
        // Polygons Layer
        // =========================================================
        var polygons = L.geoJSON(null, {
            style: function() {
                return {
                    color: '#16a34a',
                    weight: 2,
                    opacity: 0.9,
                    fillColor: '#22c55e',
                    fillOpacity: 0.15
                };
            },
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ url('delete-polygons') }}/" + feature.properties.id;

                var popup_content =
                    "<div class='lp-title'>" + feature.properties.name + "</div>" +
                    "<div class='lp-row'><span class='lp-label'>Nama</span><span class='lp-val'>" + feature
                    .properties.name + "</span></div>" +
                    "<div class='lp-row'><span class='lp-label'>Deskripsi</span><span class='lp-val'>" + feature
                    .properties.description + "</span></div>" +
                    "<div class='lp-row'><span class='lp-label'>Dibuat</span><span class='lp-val'>" + feature
                    .properties.created_at + "</span></div>" +
                    "<img src='{{ asset('storage/images') }}/" + feature.properties.image +
                    "' alt='' class='img-thumbnail' width='100'>" +
                    "<div class='lp-footer'>" +
                    "<form action='" + routedelete + "' method='post'>" +
                    '@csrf' +
                    '@method('delete')' +
                    "<button type='submit' class='btn btn-sm btn-danger' title='Delete feature' " +
                    "onclick='return confirm(\"Are you sure you want to delete this feature?\")'>" +
                    "<i class='fa-solid fa-trash'></i></button>" +
                    "</form>" +
                    "</div>";

                layer.on({
                    click: function(e) {
                        polygons.bindPopup(popup_content);
                    }
                });
            }
        });

        $.getJSON("/api/polygons", function(data) {
            polygons.addData(data);
            map.addLayer(polygons);
        });

        // Control Layer
        var overlayMaps = {
            "Points": points,
            "Polylines": polylines,
            "Polygons": polygons,
        };
        L.control.layers({}, overlayMaps).addTo(map);
    </script>
@endsection
