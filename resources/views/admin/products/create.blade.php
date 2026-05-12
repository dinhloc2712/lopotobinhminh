@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Thêm sản phẩm mới</h1>
        <p class="text-muted small mb-0">Nhập đầy đủ thông tin để tạo sản phẩm.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-tech-outline">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="tech-card mb-4">
                <div class="tech-header">
                    <i class="fas fa-box-open me-2"></i> Thông tin cơ bản
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên sản phẩm..." required value="{{ old('name') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase text-muted">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control tinymce-editor" rows="8" placeholder="Nhập mô tả sản phẩm...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="tech-card mb-4">
                <div class="tech-header">
                    <i class="fas fa-images me-2"></i> Hình ảnh
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Ảnh đại diện (Thumbnail)</label>
                        <input type="file" name="thumbnail" id="thumbnail-input" accept="image/*" style="display: none;">
                        
                        <div class="input-group">
                            <input type="text" id="thumbnail-fake-input" class="form-control" readonly style="cursor: pointer; background-color: #fff;" 
                                value="Chưa có tệp nào được chọn" 
                                onclick="document.getElementById('thumbnail-input').click()">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('thumbnail-input').click()">
                                <i class="fas fa-folder-open me-1"></i>Chọn ảnh
                            </button>
                        </div>
                        <div id="thumbnail-preview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Link Video Youtube sản phẩm</label>
                        <textarea name="video_urls" id="video-urls-input" class="form-control" rows="3" placeholder="https://www.youtube.com/watch?v=...&#10;Có thể nhập nhiều link, mỗi link 1 dòng">{{ old('video_urls') }}</textarea>
                        <div id="youtube-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase text-muted">Thư viện ảnh (Images)</label>
                        <input type="file" name="images[]" id="images-input" class="form-control" accept="image/*" multiple>
                        <div class="form-text small">Có thể chọn nhiều ảnh cùng lúc.</div>
                        <div id="images-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="tech-card mb-4">
                <div class="tech-header">
                    <i class="fas fa-cog me-2"></i> Cấu hình
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Bộ sưu tập</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Không có --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Giá bán (Gốc)</label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control" placeholder="0" min="0" value="{{ old('price') }}">
                            <span class="input-group-text">₫</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Giá khuyến mãi</label>
                        <div class="input-group">
                            <input type="number" name="sale_price" class="form-control" placeholder="0" min="0" value="{{ old('sale_price') }}">
                            <span class="input-group-text">₫</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Tồn kho</label>
                        <input type="number" name="stock" class="form-control" placeholder="0" min="0" value="{{ old('stock', 0) }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang bán</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill">
                        <i class="fas fa-save me-1"></i> Lưu sản phẩm
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    (function(){
        const dt = new DataTransfer();
        const input = document.getElementById('images-input');
        const preview = document.getElementById('images-preview');
        
        let draggedIndex = null;

        input.addEventListener('change', function(e) {
            for(let i = 0; i < this.files.length; i++){
                let exists = false;
                for(let j = 0; j < dt.files.length; j++) {
                    if(dt.files[j].name === this.files[i].name && dt.files[j].size === this.files[i].size) {
                        exists = true; break;
                    }
                }
                if(!exists) {
                    dt.items.add(this.files[i]);
                }
            }
            this.files = dt.files;
            renderPreview();
        });

        function renderPreview() {
            preview.innerHTML = '';
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                if (file.type.startsWith('image/')) {
                    const col = document.createElement('div');
                    col.className = 'position-relative mt-2 me-2';
                    col.style.width = '80px';
                    col.style.height = '80px';
                    col.style.cursor = 'grab';
                    col.setAttribute('draggable', 'true');
                    
                    col.addEventListener('dragstart', function(evt) {
                        draggedIndex = i;
                        setTimeout(() => col.style.opacity = '0.5', 0);
                    });

                    col.addEventListener('dragend', function(evt) {
                        col.style.opacity = '1';
                    });
                    
                    col.addEventListener('dragover', function(evt) {
                        evt.preventDefault();
                        col.style.border = '2px dashed #00d2ff';
                    });

                    col.addEventListener('dragleave', function(evt) {
                        col.style.border = '';
                    });
                    
                    col.addEventListener('drop', function(evt) {
                        evt.preventDefault();
                        col.style.border = '';
                        if (draggedIndex !== null && draggedIndex !== i) {
                            const filesArray = Array.from(dt.files);
                            const draggedFile = filesArray.splice(draggedIndex, 1)[0];
                            filesArray.splice(i, 0, draggedFile);
                            
                            dt.items.clear();
                            for (let f of filesArray) dt.items.add(f);
                            input.files = dt.files;
                            renderPreview();
                        }
                    });

                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'rounded shadow-sm border border-secondary border-1 w-100 h-100';
                    img.style.objectFit = 'cover';
                    img.setAttribute('draggable', 'false');

                    img.onload = function() {
                        URL.revokeObjectURL(img.src);
                    }

                    const removeBtn = document.createElement('span');
                    removeBtn.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm';
                    removeBtn.style.cursor = 'pointer';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.onclick = function() {
                        const newDt = new DataTransfer();
                        for(let j = 0; j < input.files.length; j++) {
                            if(j !== i) newDt.items.add(input.files[j]);
                        }
                        dt.items.clear();
                        for(let j = 0; j < newDt.files.length; j++) dt.items.add(newDt.files[j]);
                        input.files = dt.files;
                        renderPreview();
                    };

                    col.appendChild(img);
                    col.appendChild(removeBtn);
                    preview.appendChild(col);
                }
            }
        }

        const videoInput = document.getElementById('video-urls-input');
        const ytPreview = document.getElementById('youtube-preview');

        function renderYoutubePreview() {
            if (!ytPreview || !videoInput) return;
            ytPreview.innerHTML = '';
            const lines = videoInput.value.split('\n');
            lines.forEach(line => {
                const url = line.trim();
                if (!url) return;
                const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
                if (match && match[1]) {
                    const col = document.createElement('div');
                    col.className = 'position-relative mt-2 me-2';
                    col.style.width = '120px';
                    col.style.height = '80px';
                    
                    const img = document.createElement('img');
                    img.src = 'https://img.youtube.com/vi/' + match[1] + '/hqdefault.jpg';
                    img.className = 'rounded shadow-sm border border-secondary border-1 w-100 h-100';
                    img.style.objectFit = 'cover';
                    
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-play-circle position-absolute top-50 start-50 translate-middle text-white fs-4';
                    icon.style.textShadow = '0 0 5px rgba(0,0,0,0.8)';
                    
                    col.appendChild(img);
                    col.appendChild(icon);
                    ytPreview.appendChild(col);
                }
            });
        }
        
        if (videoInput) {
            videoInput.addEventListener('input', renderYoutubePreview);
            renderYoutubePreview();
        }

        const thumbInput = document.getElementById('thumbnail-input');
        const thumbPreview = document.getElementById('thumbnail-preview');
        const fakeInput = document.getElementById('thumbnail-fake-input');
        if (thumbInput) {
            thumbInput.addEventListener('change', function() {
                if (thumbPreview) thumbPreview.innerHTML = '';
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    if (fakeInput) fakeInput.value = file.name;
                    
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = 'rounded shadow-sm border border-secondary border-1 mt-2';
                        img.style.width = '120px';
                        img.style.height = '120px';
                        img.style.objectFit = 'cover';
                        img.onload = () => URL.revokeObjectURL(img.src);
                        
                        // Delete button for thumbnail preview
                        const wrapper = document.createElement('div');
                        wrapper.className = 'position-relative d-inline-block';
                        
                        const removeBtn = document.createElement('span');
                        removeBtn.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm mt-2';
                        removeBtn.style.cursor = 'pointer';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.onclick = function() {
                            thumbInput.value = '';
                            thumbPreview.innerHTML = '';
                            if (fakeInput) fakeInput.value = 'Chưa có tệp nào được chọn';
                        };
                        
                        wrapper.appendChild(img);
                        wrapper.appendChild(removeBtn);
                        thumbPreview.appendChild(wrapper);
                    }
                } else {
                    if (fakeInput) fakeInput.value = 'Chưa có tệp nào được chọn';
                }
            });
        }
    })();
</script>
@endsection
