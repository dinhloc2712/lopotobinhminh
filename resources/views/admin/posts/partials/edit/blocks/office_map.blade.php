<template x-if="block.type === 'office_map'">
    <div class="bg-white p-3 rounded-4 border mb-3">
{{-- removed shared_styles --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 fw-bold text-dark small text-uppercase">
                <i class="fas fa-map-marked-alt me-2 text-primary"></i>Cấu hình Bản đồ Văn phòng
            </h6>
        </div>

        <div class="row g-3">
            {{-- Tiêu đề --}}
            <div class="col-md-12">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Tiêu đề chính</label>
                <input type="text" x-model="block.content.title" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: HỆ THỐNG VĂN PHÒNG">
            </div>

            {{-- Contact Info --}}
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Điện thoại (Hotline)</label>
                <input type="text" x-model="block.content.phone" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: 056.667.1111">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Website</label>
                <input type="text" x-model="block.content.website" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: https://vinayuuki.com">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Email</label>
                <input type="text" x-model="block.content.email" class="form-control form-control-sm bg-light border-0 py-2" placeholder="VD: info@vinayuuki.com">
            </div>

            {{-- Socials --}}
            <div class="col-12 mt-3" x-data="{
                activeSocials: {
                    facebook: !!block.content.socials?.facebook,
                    tiktok: !!block.content.socials?.tiktok,
                    youtube: !!block.content.socials?.youtube,
                    zalo: !!block.content.socials?.zalo
                },
                toggleSocial(platform) {
                    if (!block.content.socials) {
                        block.content.socials = {facebook: '', tiktok: '', youtube: '', zalo: ''};
                    }
                    this.activeSocials[platform] = !this.activeSocials[platform];
                    if (!this.activeSocials[platform]) {
                        block.content.socials[platform] = '';
                    }
                }
            }">
                <label class="form-label small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.65rem;">Mạng xã hội hiển thị</label>
                <div class="d-flex gap-3 mb-3">
                    <button type="button" @click="toggleSocial('facebook')" class="btn btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" :class="activeSocials.facebook ? 'btn-primary' : 'btn-light border text-muted'" style="width: 40px; height: 40px; transition: all 0.2s;">
                        <i class="fab fa-facebook-f fs-5"></i>
                    </button>
                    <button type="button" @click="toggleSocial('tiktok')" class="btn btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" :class="activeSocials.tiktok ? 'btn-dark' : 'btn-light border text-muted'" style="width: 40px; height: 40px; transition: all 0.2s;">
                        <i class="fab fa-tiktok fs-5"></i>
                    </button>
                    <button type="button" @click="toggleSocial('youtube')" class="btn btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" :class="activeSocials.youtube ? 'btn-danger' : 'btn-light border text-muted'" style="width: 40px; height: 40px; transition: all 0.2s;">
                        <i class="fab fa-youtube fs-5"></i>
                    </button>
                    <button type="button" @click="toggleSocial('zalo')" class="btn btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" :class="activeSocials.zalo ? 'btn-info text-white' : 'btn-light border text-muted'" style="width: 40px; height: 40px; transition: all 0.2s;">
                        <b class="fs-6" style="margin-top:-2px;">Zalo</b>
                    </button>
                    <span class="text-muted small align-self-center fst-italic ms-2"><i class="fas fa-hand-pointer me-1"></i> Nhấp vào Logo mà bạn muốn hiển thị</span>
                </div>
                
                <div class="row g-2">
                    <div class="col-md-6" x-show="activeSocials.facebook" x-transition.opacity>
                        <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-primary text-white border-0" style="width: 45px; justify-content: center;"><i class="fab fa-facebook-f"></i></span>
                            <input type="text" x-model="block.content.socials.facebook" class="form-control border-0 bg-light" placeholder="Nhập đường dẫn Facebook...">
                        </div>
                    </div>
                    <div class="col-md-6" x-show="activeSocials.tiktok" x-transition.opacity>
                        <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-dark text-white border-0" style="width: 45px; justify-content: center;"><i class="fab fa-tiktok"></i></span>
                            <input type="text" x-model="block.content.socials.tiktok" class="form-control border-0 bg-light" placeholder="Nhập đường dẫn TikTok...">
                        </div>
                    </div>
                    <div class="col-md-6" x-show="activeSocials.youtube" x-transition.opacity>
                        <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-danger text-white border-0" style="width: 45px; justify-content: center;"><i class="fab fa-youtube"></i></span>
                            <input type="text" x-model="block.content.socials.youtube" class="form-control border-0 bg-light" placeholder="Nhập đường dẫn YouTube...">
                        </div>
                    </div>
                    <div class="col-md-6" x-show="activeSocials.zalo" x-transition.opacity>
                        <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-info text-white border-0" style="width: 45px; justify-content: center;"><b>Zalo</b></span>
                            <input type="text" x-model="block.content.socials.zalo" class="form-control border-0 bg-light" placeholder="Nhập đường dẫn Zalo...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Addresses --}}
            <div class="col-12 mt-4" x-data="{
                activePickerIdx: null,
                mapPicker: null,
                markerPicker: null,
                mapLoaded: false,
                isGeocoding: false,
                isLocating: false,
                
                initMapScript() {
                    if (typeof window.L === 'undefined') {
                        const link = document.createElement('link');
                        link.rel = 'stylesheet';
                        link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                        document.head.appendChild(link);
                        
                        const script = document.createElement('script');
                        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                        script.onload = () => { this.mapLoaded = true; };
                        document.head.appendChild(script);
                    } else {
                        this.mapLoaded = true;
                    }
                },
                
                async geocode(lat, lng, idx) {
                    if(!lat || !lng) return;
                    this.isGeocoding = true;
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                        if(!response.ok) throw new Error('API Error');
                        const data = await response.json();
                        if (data && data.display_name && this.block && this.block.content.addresses[idx]) {
                            this.block.content.addresses[idx].address = data.display_name;
                        }
                    } catch (error) {
                        console.error('Lỗi lấy địa chỉ', error);
                    } finally {
                        this.isGeocoding = false;
                    }
                },
                
                locateUser(idx) {
                    if (!navigator.geolocation) {
                        alert('Trình duyệt của bạn không hỗ trợ định vị GPS.');
                        return;
                    }
                    this.isLocating = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.isLocating = false;
                            const lat = position.coords.latitude.toFixed(6);
                            const lng = position.coords.longitude.toFixed(6);
                            
                            if (this.block && this.block.content.addresses[idx]) {
                                this.block.content.addresses[idx].lat = lat;
                                this.block.content.addresses[idx].lng = lng;
                                
                                if (this.activePickerIdx === idx && this.mapPicker) {
                                    this.mapPicker.setView([lat, lng], 16);
                                    if(this.markerPicker) this.markerPicker.setLatLng([lat, lng]);
                                }
                                
                                this.geocode(lat, lng, idx);
                            }
                        },
                        (error) => {
                            this.isLocating = false;
                            let msg = 'Không thể lấy vị trí hiện tại.';
                            if(error.code == 1) msg = 'Vui lòng cho phép quyền truy cập Vị trí trong trình duyệt để sử dụng tính năng này.';
                            alert(msg);
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                },
                
                activatePicker(idx, addr) {
                    if (this.activePickerIdx === idx) {
                        this.activePickerIdx = null; // Ấn lại để ẩn
                        return;
                    }
                    this.activePickerIdx = idx;
                    
                    setTimeout(() => {
                        const mapWrapper = document.getElementById('mapWrapper_' + idx);
                        const mapContainer = document.getElementById('adminMapContainer');
                        if (!mapWrapper || !mapContainer) return;
                        
                        if (mapContainer.parentElement !== mapWrapper) {
                            mapWrapper.appendChild(mapContainer);
                        }
                        
                        if (!this.mapLoaded) {
                            setTimeout(() => this.activatePicker(idx, addr), 200);
                            return;
                        }
                        
                        if (!this.mapPicker) {
                            this.mapPicker = window.L.map('adminMapContainer').setView([21.028511, 105.804817], 13);
                            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.mapPicker);
                            this.markerPicker = window.L.marker([21.028511, 105.804817], {draggable: true}).addTo(this.mapPicker);
                            
                            this.mapPicker.on('click', (e) => {
                                if (this.activePickerIdx !== null && this.block && this.block.content.addresses[this.activePickerIdx]) {
                                    const lat = parseFloat(e.latlng.lat).toFixed(6);
                                    const lng = parseFloat(e.latlng.lng).toFixed(6);
                                    this.block.content.addresses[this.activePickerIdx].lat = lat;
                                    this.block.content.addresses[this.activePickerIdx].lng = lng;
                                    this.markerPicker.setLatLng([lat, lng]);
                                    this.geocode(lat, lng, this.activePickerIdx);
                                }
                            });
                            this.markerPicker.on('dragend', (e) => {
                                if (this.activePickerIdx !== null && this.block && this.block.content.addresses[this.activePickerIdx]) {
                                    const pos = this.markerPicker.getLatLng();
                                    const lat = parseFloat(pos.lat).toFixed(6);
                                    const lng = parseFloat(pos.lng).toFixed(6);
                                    this.block.content.addresses[this.activePickerIdx].lat = lat;
                                    this.block.content.addresses[this.activePickerIdx].lng = lng;
                                    this.geocode(lat, lng, this.activePickerIdx);
                                }
                            });
                        }
                        
                        this.mapPicker.invalidateSize();
                        const lat = parseFloat(addr.lat) || 21.028511;
                        const lng = parseFloat(addr.lng) || 105.804817;
                        this.mapPicker.setView([lat, lng], 15);
                        this.markerPicker.setLatLng([lat, lng]);
                        
                    }, 100);
                }
            }" x-init="initMapScript()">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <label class="form-label m-0 fw-bold text-dark text-uppercase" style="font-size: 0.7rem;">
                        Danh sách Địa chỉ & Tọa độ Bản đồ
                    </label>
                    <button @click="block.content.addresses.push({address: '', lat: '', lng: ''})"
                            class="btn btn-sm btn-primary rounded-pill py-1 px-3 fw-bold"
                            style="font-size: 0.7rem;">
                        <i class="fas fa-plus me-1"></i> Thêm Địa chỉ
                    </button>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <template x-for="(addr, idx) in block.content.addresses" :key="idx">
                        <div class="bg-light p-3 rounded-4 border position-relative" :class="activePickerIdx === idx ? 'border-primary border-2 shadow-sm bg-white' : ''">
                            <button @click="block.content.addresses.splice(idx, 1); if(activePickerIdx===idx) activePickerIdx=null;"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle"
                                    style="width: 24px; height: 24px; padding: 0; line-height: 24px; transform: translate(30%, -30%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <i class="fas fa-times fs-6"></i>
                            </button>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Văn bản địa chỉ</label>
                                    <input type="text" x-model="addr.address" class="form-control form-control-sm bg-white border-0 py-2" placeholder="Nhập địa chỉ, VD: 34 Võ Thúc Đồng...">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Vĩ độ (Latitude)</label>
                                    <input type="number" step="any" x-model="addr.lat" class="form-control form-control-sm bg-white border-0 py-2" placeholder="VD: 18.6508">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1" style="font-size: 0.65rem;">Kinh độ (Longitude)</label>
                                    <input type="number" step="any" x-model="addr.lng" class="form-control form-control-sm bg-white border-0 py-2" placeholder="VD: 105.6983">
                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="small fw-bold" :class="activePickerIdx === idx ? 'text-primary' : 'text-muted'">
                                    <span x-show="isLocating && activePickerIdx === idx" class="text-success"><i class="fas fa-spinner fa-spin me-1"></i>Đang định vị GPS...</span>
                                    <span x-show="isGeocoding && activePickerIdx === idx" class="text-warning"><i class="fas fa-spinner fa-spin me-1"></i>Đang lấy địa chỉ văn bản...</span>
                                    <span x-show="!isLocating && !isGeocoding && activePickerIdx === idx"><i class="fas fa-mouse-pointer fs-6 me-1"></i>Nhấp vào bản đồ ở dưới để ghim toạ độ...</span>
                                </span>
                                <div class="d-flex gap-2">
                                    <button type="button" @click="locateUser(idx)" class="btn btn-sm py-1 px-3 rounded-pill fw-bold btn-outline-success" :disabled="isLocating">
                                        <i class="fas fa-location-arrow me-1"></i> <span x-text="isLocating ? 'Đang định vị' : 'Vị trí của tôi'"></span>
                                    </button>
                                    <button type="button" @click="activatePicker(idx, addr)" class="btn btn-sm py-1 px-3 rounded-pill fw-bold" :class="activePickerIdx === idx ? 'btn-danger shadow-sm' : 'btn-outline-primary'">
                                        <i class="fas" :class="activePickerIdx === idx ? 'fa-times' : 'fa-crosshairs'"></i> 
                                        <span x-text="activePickerIdx === idx ? 'Đóng Bản Đồ' : 'Lấy toạ độ từ bản đồ'"></span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Nơi chứa bản đồ riêng cho dòng này -->
                            <div :id="'mapWrapper_' + idx" class="mt-3 overflow-hidden rounded-4 border position-relative" style="height: 350px;" x-show="activePickerIdx === idx"></div>
                        </div>
                    </template>
                </div>
                
                <!-- Bản đồ gốc (ẩn) để chèn qua lại -->
                <div style="display: none;">
                    <div id="adminMapContainer" class="w-100 h-100" style="min-height: 350px; z-index: 1;"></div>
                </div>
            </div>
        </div>
    </div>
</template>
