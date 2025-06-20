<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Menambahkan style untuk peta agar tidak rusak --}}
        <style>
            .leaflet-container { height: 256px; width: 100%; border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
        </style>

    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Cek apakah elemen #map ada di halaman ini
                if (document.getElementById('map')) {

                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    const addressInput = document.getElementById('full_address');
                    const cityInput = document.getElementById('city');
                    const provinceInput = document.getElementById('province');
                    const postcodeInput = document.getElementById('postal_code');

                    const initialLat = parseFloat(latInput.value) || -2.5489;
                    const initialLng = parseFloat(lngInput.value) || 118.0149;
                    const initialZoom = latInput.value ? 17 : 5;

                    const map = L.map('map').setView([initialLat, initialLng], initialZoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

                    // ==================================================
                    // FUNGSI BARU UNTUK REVERSE GEOCODING (Menerjemahkan Koordinat)
                    // ==================================================
                    function reverseGeocode(lat, lng) {
                        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data.address) {
                                    // Ekstrak data alamat, gunakan '' jika tidak ada
                                    const road = data.address.road || '';
                                    const village = data.address.village || data.address.suburb || '';
                                    const city = data.address.city || data.address.county || '';
                                    const province = data.address.state || '';
                                    const postcode = data.address.postcode || '';

                                    // Gabungkan menjadi alamat lengkap
                                    const fullAddress = `${road}, ${village}, ${data.address.city_district || ''}`.replace(/, ,/g, ',').replace(/^,|,$/g, '').trim();

                                    // Isi input form
                                    addressInput.value = fullAddress;
                                    cityInput.value = city;
                                    provinceInput.value = province;
                                    postcodeInput.value = postcode;
                                }
                            })
                            .catch(error => console.error('Error fetching address:', error));
                    }


                    // Fungsi untuk update posisi marker dan input
                    function updateMarkerAndInputs(lat, lng) {
                        marker.setLatLng([lat, lng]);
                        map.panTo([lat, lng]);
                        latInput.value = lat;
                        lngInput.value = lng;

                        // Panggil fungsi reverse geocoding setelah posisi diupdate
                        reverseGeocode(lat, lng);
                    }

                    // Event saat peta diklik
                    map.on('click', function(e) {
                        updateMarkerAndInputs(e.latlng.lat, e.latlng.lng);
                    });

                    // Event saat marker selesai digeser
                    marker.on('dragend', function(e) {
                        const latlng = e.target.getLatLng();
                        updateMarkerAndInputs(latlng.lat, latlng.lng);
                    });
                }
            });
        </script>

    </body>
</html>
