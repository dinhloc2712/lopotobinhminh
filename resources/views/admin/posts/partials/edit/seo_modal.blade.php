{{-- Post Settings & SEO Modal --}}
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-cog me-2 text-primary"></i>Cài đặt bài viết & SEO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <form id="post-settings-form" action="{{ route('admin.posts.update', $post->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- General Info --}}
                        <div class="col-12">
                            <div class="bg-white p-3 rounded-4 shadow-sm border">
                                <h6 class="fw-bold mb-3 text-primary small text-uppercase">Thông tin cơ bản</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Tiêu đề bài
                                            viết</label>
                                        <input type="text" name="title" class="form-control border-0 bg-light"
                                            value="{{ $post->title }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Đường dẫn tự
                                            chọn (Slug)</label>
                                        <input type="text" name="slug" class="form-control border-0 bg-light"
                                            value="{{ $post->slug }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Chuyên
                                            mục</label>
                                        <select name="category_id" class="form-select border-0 bg-light">
                                            <option value="">-- Chọn chuyên mục --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ $post->category_id == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Mô tả ngắn
                                            (Summary)</label>
                                        <textarea name="summary" class="form-control border-0 bg-light" rows="2">{{ $post->summary }}</textarea>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Ảnh đại diện
                                            (Thumbnail)</label>
                                        <div
                                            class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden border">
                                            <button type="button" class="btn btn-white border-end text-primary"
                                                @click="openMediaPicker('post', 'thumbnail')">
                                                <i class="fas fa-image"></i> Chọn ảnh
                                            </button>
                                            <input type="text" name="thumbnail" id="post_thumbnail_input"
                                                class="form-control border-0 bg-white" value="{{ $post->thumbnail }}"
                                                placeholder="URL ảnh...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Trạng
                                            thái</label>
                                        <select name="status" class="form-select border-0 bg-light">
                                            <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Bản
                                                nháp</option>
                                            <option value="published"
                                                {{ $post->status == 'published' ? 'selected' : '' }}>Công khai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SEO Info --}}
                        <div class="col-12">
                            <div class="bg-white p-3 rounded-4 shadow-sm border">
                                <h6 class="fw-bold mb-3 text-success small text-uppercase">Cấu hình SEO</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">SEO Meta
                                            Title</label>
                                        <input type="text" name="meta_title" class="form-control border-0 bg-light"
                                            value="{{ $post->meta_title }}"
                                            placeholder="Mặc định dùng Tiêu đề bài viết">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">SEO Meta
                                            Keywords</label>
                                        <input type="text" name="meta_keywords"
                                            class="form-control border-0 bg-light" value="{{ $post->meta_keywords }}"
                                            placeholder="Từ khóa 1, Từ khóa 2...">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-muted text-uppercase">SEO Meta
                                            Description</label>
                                        <textarea name="meta_description" class="form-control border-0 bg-light" rows="3">{{ $post->meta_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top-0 px-4 pb-4">
                <button type="button" class="btn btn-white rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="post-settings-form"
                    class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-save me-1"></i> Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</div>
