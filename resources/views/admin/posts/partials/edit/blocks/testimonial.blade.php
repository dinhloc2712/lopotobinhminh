<template x-if="block.type === 'testimonial'">
    <div class="mt-2">
{{-- removed shared_styles --}}
        <div class="bg-light p-3 rounded-4 border">
            {{-- Danh sách đánh giá --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-muted text-uppercase mb-0" style="font-size: 0.65rem;">Danh sách đánh giá</label>
                <button type="button"
                    class="btn btn-sm btn-outline-primary rounded-pill fw-bold"
                    style="font-size: 0.7rem;"
                    @click="if(!block.content.reviews) block.content.reviews = []; block.content.reviews.push({quote: '', author: '', avatar: ''})">
                    <i class="fas fa-plus"></i> Thêm đánh giá
                </button>
            </div>

            <div class="d-flex flex-column gap-2">
                <template x-for="(review, i) in (block.content.reviews || [])">
                    <div class="bg-white p-2 rounded-3 border">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted fw-bold" x-text="'Đánh giá ' + (i + 1)"></span>
                            <button type="button" class="btn btn-sm text-danger p-0"
                                @click="block.content.reviews.splice(i, 1)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <textarea x-model="review.quote" class="form-control form-control-sm border bg-light mb-2" rows="2"
                            placeholder="Lời bình của khách hàng..."></textarea>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <input type="text" x-model="review.author"
                                    class="form-control form-control-sm border bg-light"
                                    placeholder="Tên khách hàng">
                            </div>
                            <div class="col-md-4">
                                <input type="text" x-model="review.subtitle"
                                    class="form-control form-control-sm border bg-light"
                                    placeholder="Chức vụ / Mô tả">
                            </div>
                            <div class="col-md-3">
                                <select x-model="review.stars" class="form-select form-select-sm border bg-light">
                                    <option value="5">⭐⭐⭐⭐⭐ 5 sao</option>
                                    <option value="4">⭐⭐⭐⭐ 4 sao</option>
                                    <option value="3">⭐⭐⭐ 3 sao</option>
                                    <option value="2">⭐⭐ 2 sao</option>
                                    <option value="1">⭐ 1 sao</option>
                                </select>
                            </div>
                        </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-sm rounded-3 overflow-hidden shadow-sm">
                                    <button type="button"
                                        class="btn btn-light border-end text-primary"
                                        @click="openMediaPicker(index, 'reviews.' + i + '.avatar')"
                                        title="Chọn ảnh đại diện">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <input type="text" x-model="review.avatar"
                                        class="form-control form-control-sm border-0 bg-white"
                                        placeholder="URL ảnh đại diện">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
