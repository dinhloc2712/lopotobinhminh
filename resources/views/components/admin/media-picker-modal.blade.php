@props(['id' => 'mediaPickerModal'])

{{-- Media Picker Modal Component --}}
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true"
    x-data="mediaPickerComponent()" x-init="init()">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 94%;">
        <div class="modal-content border-0 shadow-lg rounded-4 bg-light">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 position-relative z-index-1">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="{{ $id }}Label">
                    <i
                        class="fas fa-folder-open text-primary me-2 box-icon bg-primary bg-opacity-10 rounded text-center p-2"></i>
                    Thư viện Media
                </h5>
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-4 me-4" data-bs-dismiss="modal"
                    aria-label="Close" style="font-size: 0.8rem;"></button>
            </div>

            <div class="modal-body p-4 position-relative">
                {{-- Toolbar --}}
                <div
                    class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 bg-white p-3 rounded-4 shadow-sm border">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3" @click="goUp()"
                            :disabled="currentFolder === '/' || currentFolder === ''">
                            <i class="fas fa-level-up-alt me-1"></i> Lên
                        </button>
                        <span class="text-muted small fw-bold"
                            x-text="'/ ' + (currentFolder === '/' || currentFolder === '' ? 'Gốc' : currentFolder)"></span>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        {{-- Upload Form --}}
                        <div class="position-relative">
                            <input type="file" id="media-picker-upload" class="d-none" multiple
                                @change="uploadFiles($event.target.files)">
                            <button type="button" class="btn btn-sm btn-success rounded-pill fw-bold px-3 shadow-sm"
                                @click="document.getElementById('media-picker-upload').click()">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Tải lên
                            </button>
                        </div>
                        <button type="button"
                            class="btn btn-sm btn-warning text-white rounded-pill fw-bold px-3 shadow-sm"
                            @click="createFolder()">
                            <i class="fas fa-folder-plus me-1"></i> Thư mục
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm border-0 rounded-pill px-3 transition-all fw-bold"
                            :class="viewMode === 'list' ? 'bg-primary text-white shadow-sm' : 'text-muted'"
                            @click="viewMode = 'list'" title="Dạng danh sách">
                            <i class="fas fa-list"></i>
                        </button>
                        <button type="button" class="btn btn-sm border-0 rounded-pill px-3 transition-all fw-bold"
                            :class="viewMode === 'grid' ? 'bg-primary text-white shadow-sm' : 'text-muted'"
                            @click="viewMode = 'grid'" title="Dạng lưới">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>

                    <div class="bg-white rounded-pill shadow-sm border" style="min-width: 250px;">
                        <div class="position-relative">
                            <i
                                class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
                            <input type="text" x-model.debounce.500ms="search"
                                class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2 fw-bold text-dark"
                                placeholder="Tìm tệp tin...">
                        </div>
                    </div>
                </div>

                {{-- Grid View --}}
                <div x-show="viewMode === 'grid'" class="row g-3" style="min-height: 300px;">
                    <template x-for="file in files" :key="file.path">
                        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                            <div class="card h-100 border rounded-4 position-relative shadow-sm transition-all hover-shadow-lg cursor-pointer"
                                :class="{ 'border-primary shadow bg-primary bg-opacity-10': selectedFile && selectedFile
                                        .path === file.path, 'bg-white': !selectedFile || selectedFile.path !== file
                                        .path }"
                                @click="selectFile(file)">

                                <div class="card-body p-3 d-flex flex-column align-items-center text-center pb-2">
                                    <div class="mb-3 mt-2">
                                        <template x-if="file.is_dir">
                                            <span
                                                class="rounded-3 d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning shadow-sm"
                                                style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <i class="fas"
                                                    :class="file.name === '.. (Quay lại)' ? 'fa-level-up-alt' : 'fa-folder'"></i>
                                            </span>
                                        </template>
                                        <template x-if="!file.is_dir">
                                            <span
                                                class="rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm"
                                                :class="getFileIcon(file).bg + ' ' + getFileIcon(file).color"
                                                style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                <template x-if="isImage(file)">
                                                    <img loading="lazy" :src="file.url" class="img-fluid rounded-3"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </template>
                                                <template x-if="!isImage(file)">
                                                    <i class="fas" :class="getFileIcon(file).icon"></i>
                                                </template>
                                            </span>
                                        </template>
                                    </div>
                                    <div class="fw-bold text-dark text-truncate w-100 mb-1"
                                        :title="file.document ? file.document.title : file.name"
                                        style="font-size: 0.8rem;"
                                        x-text="file.document ? file.document.title : file.name"></div>

                                    <template x-if="!file.is_dir">
                                        <div class="text-muted small w-100 mt-auto pt-2 border-top text-center"
                                            style="font-size: 0.65rem;" x-text="formatBytes(file.size)"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="files.length === 0 && !loading">
                        <div class="col-12 text-center py-5">
                            <div class="text-muted"><i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i><br>Thư mục
                                trống hoặc không có kết quả</div>
                        </div>
                    </template>
                </div>

                {{-- List View --}}
                <div x-show="viewMode === 'list'" class="bg-white rounded-4 border shadow-sm"
                    style="min-height: 300px; display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 rounded-top-start-4">Tên tệp</th>
                                    <th>Kích thước</th>
                                    <th class="rounded-top-end-4 pe-4">Ngày cập nhật</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="file in files" :key="file.path">
                                    <tr class="cursor-pointer"
                                        :class="{ 'bg-primary bg-opacity-10': selectedFile && selectedFile.path === file.path }"
                                        @click="selectFile(file)">
                                        <td class="ps-4 border-bottom-0">
                                            <div class="d-flex align-items-center">
                                                <template x-if="file.is_dir">
                                                    <span
                                                        class="me-3 rounded d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning"
                                                        style="width: 32px; height: 32px;">
                                                        <i class="fas"
                                                            :class="file.name === '.. (Quay lại)' ? 'fa-level-up-alt' :
                                                                'fa-folder'"></i>
                                                    </span>
                                                </template>
                                                <template x-if="!file.is_dir">
                                                    <span
                                                        class="me-3 rounded d-inline-flex align-items-center justify-content-center"
                                                        :class="getFileIcon(file).bg + ' ' + getFileIcon(file).color"
                                                        style="width: 32px; height: 32px;">
                                                        <template x-if="isImage(file)">
                                                            <img loading="lazy" :src="file.url" class="img-fluid rounded"
                                                                style="width: 100%; height: 100%; object-fit: cover;">
                                                        </template>
                                                        <template x-if="!isImage(file)">
                                                            <i class="fas" :class="getFileIcon(file).icon"></i>
                                                        </template>
                                                    </span>
                                                </template>
                                                <div class="fw-bold text-dark" style="font-size: 0.85rem;"
                                                    x-text="file.document ? file.document.title : file.name"></div>
                                            </div>
                                        </td>
                                        <td class="text-muted font-monospace border-bottom-0"
                                            style="font-size: 0.8rem;">
                                            <span x-show="!file.is_dir" x-text="formatBytes(file.size)"></span>
                                            <span x-show="file.is_dir">—</span>
                                        </td>
                                        <td class="text-muted pe-4 border-bottom-0" style="font-size: 0.8rem;"
                                            x-text="formatDate(file.last_modified)"></td>
                                    </tr>
                                </template>
                                <template x-if="files.length === 0 && !loading">
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted"><i
                                                class="fas fa-folder-open fa-2x mb-2 opacity-50"></i><br>Không có dữ
                                            liệu</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Placeholder --}}
                <div class="mt-4 d-flex justify-content-center" x-show="pagination && pagination.last_page > 1">
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <template x-for="link in pagination.links">
                                <li class="page-item" :class="{ 'active': link.active, 'disabled': link.url === null }">
                                    <button class="page-link" @click="if(link.url) fetchMedia(link.url)"
                                        x-html="link.label"></button>
                                </li>
                            </template>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="modal-footer border-top-0 px-4 pb-4">
                <div class="text-muted small me-auto d-flex align-items-center"
                    x-show="selectedFile && !selectedFile.is_dir" style="display: none;">
                    Đã chọn: <strong class="ms-1 text-dark text-truncate" style="max-width: 250px;"
                        x-text="selectedFile.name"></strong>
                </div>
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm"
                    data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
                    :disabled="!selectedFile || selectedFile.is_dir" @click="confirmSelection()">
                    <i class="fas fa-check me-1"></i> Sử dụng Tệp này
                </button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function mediaPickerComponent() {
                return {
                    files: [],
                    pagination: null,
                    currentFolder: '/',
                    search: '',
                    viewMode: 'grid',
                    loading: false,
                    selectedFile: null,
                    callbackEvent: null,

                    init() {
                        this.$watch('search', () => {
                            this.fetchMedia();
                        });

                        // Allow external scripts to trigger modal open
                        const component = this;
                        window.addEventListener('open-media-picker', function(e) {
                            component.openModal(e);
                        });
                    },

                    openModal(e) {
                        // Read callback event data from dispatched event if passed
                        if (e && e.detail && e.detail.eventName) {
                            this.callbackEvent = e.detail.eventName;
                        } else if (this.$event && this.$event.detail && this.$event.detail.eventName) {
                            this.callbackEvent = this.$event.detail.eventName;
                        } else {
                            this.callbackEvent = 'media-selected'; // default
                        }

                        this.selectedFile = null;
                        const modalEl = document.getElementById('{{ $id }}');
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();

                        if (this.files.length === 0) {
                            this.fetchMedia();
                        }
                    },

                    async fetchMedia(url = null) {
                        this.loading = true;
                        const fetchUrl = url ||
                            `{{ route('admin.media.index') }}?folder=${encodeURIComponent(this.currentFolder)}&search=${encodeURIComponent(this.search)}&per_page=50`;

                        try {
                            const response = await fetch(fetchUrl, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            const data = await response.json();

                            if (data.files && data.files.data) {
                                this.files = data.files.data || [];
                                this.pagination = data.files;
                                this.currentFolder = data.currentFolder || '/';
                            } else if (data.files) {
                                this.files = Array.isArray(data.files) ? data.files : [];
                                this.currentFolder = data.currentFolder || '/';
                                this.pagination = null;
                            } else {
                                this.files = [];
                            }
                        } catch (error) {
                            console.error('Error fetching media:', error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Không thể tải thư viện lưu trữ.'
                            });
                        } finally {
                            this.loading = false;
                        }
                    },

                    selectFile(file) {
                        if (file.is_dir) {
                            this.currentFolder = file.path;
                            this.search = '';
                            this.fetchMedia();
                        } else {
                            this.selectedFile = file;
                        }
                    },

                    goUp() {
                        if (this.currentFolder === '/' || this.currentFolder === '') return;

                        const upItem = this.files.find(f => f.name === '.. (Quay lại)');
                        if (upItem) {
                            this.currentFolder = upItem.path;
                            this.fetchMedia();
                        } else {
                            let parts = this.currentFolder.split('/').filter(p => p.length > 0);
                            parts.pop();
                            this.currentFolder = parts.length > 0 ? parts.join('/') : '/';
                            this.fetchMedia();
                        }
                    },

                    confirmSelection() {
                        if (this.selectedFile && !this.selectedFile.is_dir) {
                            window.dispatchEvent(new CustomEvent(this.callbackEvent, {
                                detail: this.selectedFile
                            }));

                            const modalEl = document.getElementById('{{ $id }}');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                    },

                    async createFolder() {
                        const {
                            value: folderName
                        } = await Swal.fire({
                            title: 'Tạo thư mục mới',
                            input: 'text',
                            inputPlaceholder: 'Tên thư mục',
                            showCancelButton: true,
                            confirmButtonText: 'Tạo',
                            cancelButtonText: 'Hủy'
                        });

                        if (folderName) {
                            this.loading = true;
                            try {
                                const response = await fetch('{{ route('admin.media.create-folder') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({
                                        folder_name: folderName,
                                        current_folder: this.currentFolder
                                    })
                                });

                                const result = await response.json();
                                if (result.success) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: result.message
                                    });
                                    this.fetchMedia();
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: result.message || 'Lỗi khi tạo thư mục'
                                    });
                                }
                            } catch (err) {
                                console.error(err);
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Lỗi kết nối'
                                });
                            } finally {
                                this.loading = false;
                            }
                        }
                    },

                    async uploadFiles(filesList) {
                        if (!filesList || filesList.length === 0) return;

                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('folder', this.currentFolder);
                        formData.append('document_type', 'other');

                        for (let i = 0; i < filesList.length; i++) {
                            formData.append('files[]', filesList[i]);
                        }

                        this.loading = true;

                        try {
                            const response = await fetch('{{ route('admin.media.store') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const result = await response.json();
                            if (response.ok && result.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: result.message
                                });
                                this.fetchMedia();
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: result.message || 'Lỗi khi tải lên'
                                });
                            }
                        } catch (error) {
                            console.error(error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi kết nối'
                            });
                        } finally {
                            this.loading = false;
                            document.getElementById('media-picker-upload').value = '';
                        }
                    },

                    formatBytes(size) {
                        if (size === 0) return '0 B';
                        const i = Math.floor(Math.log(size) / Math.log(1024));
                        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
                    },

                    formatDate(timestamp) {
                        if (!timestamp) return '—';
                        return new Date(timestamp * 1000).toLocaleDateString('vi-VN', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    },

                    isImage(file) {
                        if (!file || file.is_dir) return false;
                        const ext = file.name.split('.').pop().toLowerCase();
                        return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext);
                    },

                    getFileIcon(file) {
                        if (file.is_dir) {
                            return {
                                icon: file.name === '.. (Quay lại)' ? 'fa-level-up-alt' : 'fa-folder',
                                color: 'text-warning',
                                bg: 'bg-warning bg-opacity-10'
                            };
                        }
                        const ext = file.name.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                            return {
                                icon: 'fa-file-image',
                                color: 'text-warning',
                                bg: 'bg-warning bg-opacity-10'
                            };
                        } else if (['pdf'].includes(ext)) {
                            return {
                                icon: 'fa-file-pdf',
                                color: 'text-danger',
                                bg: 'bg-danger bg-opacity-10'
                            };
                        } else if (['doc', 'docx'].includes(ext)) {
                            return {
                                icon: 'fa-file-word',
                                color: 'text-primary',
                                bg: 'bg-primary bg-opacity-10'
                            };
                        } else if (['xls', 'xlsx', 'csv'].includes(ext)) {
                            return {
                                icon: 'fa-file-excel',
                                color: 'text-success',
                                bg: 'bg-success bg-opacity-10'
                            };
                        } else if (['zip', 'rar', '7z'].includes(ext)) {
                            return {
                                icon: 'fa-file-archive',
                                color: 'text-info',
                                bg: 'bg-info bg-opacity-10'
                            };
                        } else if (['mp4', 'mov', 'avi'].includes(ext)) {
                            return {
                                icon: 'fa-file-video',
                                color: 'text-danger',
                                bg: 'bg-danger bg-opacity-10'
                            };
                        }
                        return {
                            icon: 'fa-file-alt',
                            color: 'text-secondary',
                            bg: 'bg-light'
                        };
                    }
                }
            }
        </script>
    @endpush
@endonce
