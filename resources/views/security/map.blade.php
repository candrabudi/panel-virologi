@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #threatMap {
            background-color: #0f172a; /* Slate 900 */
            border: 1px solid rgba(255,255,255,0.05);
        }
        .leaflet-popup-content-wrapper {
            background: #ffffff;
            color: #1e293b;
            border-radius: 8px;
        }
    </style>
@endpush

@section('title', 'Global Threat Map')

@section('content')
    <div class="col-span-12">
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                Global Threat Map
            </h2>
        </div>

        <!-- Map Container -->
        <div class="intro-y box mt-5 p-5">
            <div id="threatMap" style="height: 500px; width: 100%;" class="rounded-md overflow-hidden shadow-inner"></div>
            <div class="mt-4 text-center text-slate-500 text-xs">
                Live visualization of detected anomalies and blocked attacks.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS (Using jsDelivr to comply with CSP) -->
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof L === 'undefined') {
                console.error("Leaflet (L) is not defined. Check CSP or network connectivity.");
                document.getElementById('threatMap').innerHTML = '<div class="flex items-center justify-center h-full text-slate-500 font-medium">Failed to load Map Engine (CSP Blocked?)</div>';
                return;
            }

            // Initialize Map
            const map = L.map('threatMap').setView([20, 0], 2);

            // Dark Mode Tiles (CartoDB Dark Matter)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CARTO',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            // Layer for markers so we can clear them
            let markersLayer = L.layerGroup().addTo(map);

            // Fetch and Plot Data
            const loadAttackData = () => {
                axios.get('/security-center/map/data')
                    .then(res => {
                        const attacks = res.data;
                        console.log(`Global Threat Map: Loaded ${attacks.length} attack points.`);
                        
                        markersLayer.clearLayers();

                        attacks.forEach(attack => {
                            if(attack.latitude && attack.longitude) {
                                let color = '#ef4444'; 
                                if(attack.event_type === 'bot_blocked') color = '#f59e0b';
                                if(attack.event_type.includes('rate_limit')) color = '#3b82f6';

                                L.circleMarker([parseFloat(attack.latitude), parseFloat(attack.longitude)], {
                                    radius: 6,
                                    fillColor: color,
                                    color: color,
                                    weight: 1,
                                    opacity: 0.8,
                                    fillOpacity: 0.6
                                })
                                .bindPopup(`
                                    <div class="p-1">
                                        <div class="font-bold border-b pb-1 mb-1 border-slate-200">${attack.country_name || 'Unknown Location'}</div>
                                        <div class="text-xs"><strong>IP:</strong> ${attack.ip_address}</div>
                                        <div class="text-xs text-danger mt-1 font-semibold uppercase">${attack.event_type.replace(/_/g, ' ')}</div>
                                        <div class="text-[10px] text-slate-500 mt-1 italic">${attack.details || ''}</div>
                                    </div>
                                `)
                                .addTo(markersLayer);
                            }
                        });
                    })
                    .catch(e => {
                        console.error("Map Data Load Error:", e);
                    });
            };

            loadAttackData();
            setInterval(loadAttackData, 30000);
        });
    </script>
@endpush
