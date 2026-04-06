@php
    $b_content = (array) ($block->content ?? []);
    $b_title = $b_content['title'] ?? '';
    $b_phone = $b_content['phone'] ?? '';
    $b_website = $b_content['website'] ?? '';
    $b_email = $b_content['email'] ?? '';
    $b_socials = (array) ($b_content['socials'] ?? ['facebook' => '', 'tiktok' => '', 'youtube' => '', 'zalo' => '']);
    $b_addresses = $b_content['addresses'] ?? [];
    $mapId = 'map-' . uniqid();
@endphp

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .office-map-block {
            background-color: #f8f9fa;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid #5ab0f7;
            /* VinaYuuki blue-ish */
        }

        .office-map-left {
            padding: 3rem 2rem;
            position: relative;
        }

        .office-title {
            color: #1e3a8a;
            /* Dark blue */
            font-weight: 800;
            margin-bottom: 2rem;
        }

        .office-address-list {
            list-style: none;
            padding: 0;
            margin-bottom: 3rem;
        }

        .office-address-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .office-address-item i {
            color: #1e3a8a;
            margin-right: 12px;
            margin-top: 4px;
        }

        .office-address-item.active {
            font-weight: 600;
            background-color: #1e3a8a;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            margin-left: -15px;
            margin-right: -15px;
            cursor: pointer;
        }

        .office-address-item.active i {
            color: #fff;
        }

        .office-socials {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.2rem;
            color: #fff;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            opacity: 0.9;
        }

        .social-icon.facebook {
            background-color: #1877f2;
        }

        .social-icon.tiktok {
            background-color: #000000;
        }

        .social-icon.youtube {
            background-color: #ff0000;
        }

        .social-icon.zalo {
            background-color: #0068ff;
        }

        .office-contact-list {
            list-style: none;
            padding: 0;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: fit-content;
        }

        .office-contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            color: #0d3859;
            font-size: 1.05rem;
        }

        .office-contact-item i {
            width: 32px;
            height: 32px;
            background-color: #0d3859;
            color: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
        }

        .office-map-container {
            height: 100%;
            min-height: 500px;
            width: 100%;
            z-index: 10;
            position: absolute;
            top: 0;
            left: 0;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.2);
        }

        .leaflet-popup-content {
            margin: 15px;
            font-family: inherit;
        }
    </style>
@endonce

<section class="{{ !empty($content['full_width']) ? 'container-fluid px-0' : 'container' }}">
    <div class="row g-0 office-map-block shadow-sm">
        <!-- Thông tin bên trái -->
        <div class="col-lg-5 col-md-12 office-map-left">
            <h3 class="office-title text-uppercase">{{ $b_title }}</h3>

            <ul class="office-address-list">
                @foreach ($b_addresses as $index => $addrItem)
                    @php $addr = (array) $addrItem; @endphp
                    <li class="office-address-item {{ $index === 0 ? 'active' : '' }}"
                        onclick="flyToMap{{ str_replace('-', '', $mapId) }}({{ $addr['lat'] ?? 0 }}, {{ $addr['lng'] ?? 0 }}, this)">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $addr['address'] ?? '' }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="office-socials">
                @if (!empty($b_socials['facebook']))
                    <a href="{{ $b_socials['facebook'] }}" target="_blank" class="social-icon facebook"><i
                            class="fab fa-facebook-f"></i></a>
                @endif
                @if (!empty($b_socials['tiktok']))
                    <a href="{{ $b_socials['tiktok'] }}" target="_blank" class="social-icon tiktok"><i
                            class="fab fa-tiktok"></i></a>
                @endif
                @if (!empty($b_socials['youtube']))
                    <a href="{{ $b_socials['youtube'] }}" target="_blank" class="social-icon youtube"><i
                            class="fab fa-youtube"></i></a>
                @endif
                @if (!empty($b_socials['zalo']))
                    <a href="{{ $b_socials['zalo'] }}" target="_blank" class="social-icon zalo"
                        style="font-size:0.9rem; font-weight:bold;">Zalo</a>
                @endif
            </div>

            <ul class="office-contact-list">
                @if (!empty($b_phone))
                    <li class="office-contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <strong>Hotline:&nbsp;</strong> <a href="tel:{{ str_replace(['.', ' ', '-'], '', $b_phone) }}"
                            class="text-decoration-none ms-1" style="color: #456d85;">{{ $b_phone }}</a>
                    </li>
                @endif
                @if (!empty($b_website))
                    <li class="office-contact-item">
                        <i class="fas fa-globe"></i>
                        <strong>Website:&nbsp;</strong> <a href="{{ $b_website }}" target="_blank"
                            class="text-decoration-none ms-1" style="color: #456d85;">{{ str_replace(['https://', 'http://'], '', $b_website) }}</a>
                    </li>
                @endif
                @if (!empty($b_email))
                    <li class="office-contact-item">
                        <i class="fas fa-envelope"></i>
                        <strong>Email:&nbsp;</strong> <a href="mailto:{{ $b_email }}"
                            class="text-decoration-none ms-1" style="color: #456d85;">{{ $b_email }}</a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Bản đồ bên phải -->
        <div class="col-lg-7 col-md-12 p-0 h-100 position-relative" style="min-height: 500px">
            <div id="{{ $mapId }}" class="office-map-container"></div>
        </div>
    </div>
</section>

@once
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Custom icon
        const vnYuukiIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    </script>
@endonce

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @php
            $defaultLat = 21.028511;
            $defaultLng = 105.804817;
            if (count($b_addresses) > 0) {
                $firstAddr = (array) $b_addresses[0];
                if (isset($firstAddr['lat'])) {
                    $defaultLat = $firstAddr['lat'];
                    $defaultLng = $firstAddr['lng'];
                }
            }
        @endphp

        // Init map
        setTimeout(function() {
            if (document.getElementById('{{ $mapId }}')) {
                const map{{ str_replace('-', '', $mapId) }} = L.map('{{ $mapId }}').setView([
                    {{ $defaultLat }}, {{ $defaultLng }}
                ], 15);

                // Add OpenStreetMap tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map{{ str_replace('-', '', $mapId) }});

                // Add markers
                @foreach ($b_addresses as $addrItem)
                    @php $addr = (array) $addrItem; @endphp
                    @if (isset($addr['lat']) && isset($addr['lng']))
                        L.marker([{{ $addr['lat'] }}, {{ $addr['lng'] }}], {
                                icon: vnYuukiIcon
                            })
                            .addTo(map{{ str_replace('-', '', $mapId) }})
                            .bindPopup(
                                '<div style="min-width: 150px;">' +
                                '<b style="color: #1e3a8a; display: block; margin-bottom: 5px;">{{ $b_title }}</b>' +
                                '<div style="font-size: 13px; color: #666; margin-bottom: 10px;">{{ addslashes($addr['address'] ?? '') }}</div>' +
                                '<a href="https://www.google.com/maps?q={{ $addr['lat'] }},{{ $addr['lng'] }}" target="_blank" class="btn btn-sm btn-primary w-100 text-white" style="font-size: 12px; border-radius: 6px;">' +
                                '<i class="fas fa-directions me-1"></i> Chỉ đường (Google Maps)' +
                                '</a>' +
                                '</div>'
                            );
                    @endif
                @endforeach

                // Global function for clicks
                window.flyToMap{{ str_replace('-', '', $mapId) }} = function(lat, lng, element) {
                    // Update UI
                    const ul = element.parentElement;
                    const items = ul.querySelectorAll('.office-address-item');
                    items.forEach(i => i.classList.remove('active'));
                    element.classList.add('active');

                    // Fly to
                    map{{ str_replace('-', '', $mapId) }}.flyTo([lat, lng], 17, {
                        animate: true,
                        duration: 1.5
                    });
                };
            }
        }, 100);
    });
</script>
