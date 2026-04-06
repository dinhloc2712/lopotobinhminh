<template x-if="block.type === 'header'">
    <div class="mt-2">
        <div class="row g-3">
            {{-- Shared Block Styling --}}
            @include('admin.posts.partials.edit.blocks.shared_styles')

            {{-- Tham số Logo --}}
            <div class="col-12 pb-3 mb-1 border-bottom">
                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Logo hiển thị</label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="small text-muted mb-1">Ảnh Logo</label>
                        <div class="input-group input-group-sm rounded-3 shadow-sm border bg-white">
                            <button type="button" class="btn btn-light border-end fw-bold text-primary" @click="openMediaPicker(index, 'logo')" title="Chọn từ thư viện">
                                <i class="fas fa-image"></i>
                            </button>
                            <input type="text" x-model="block.content.logo" class="form-control border-0" placeholder="Đường dẫn ảnh Logo...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted mb-1">Chữ Logo (Nếu không có ảnh)</label>
                        <input type="text" x-model="block.content.logo_text" class="form-control form-control-sm border bg-white" placeholder="VD: MyBrand">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted mb-1">Đường dẫn Logo</label>
                        <input type="text" x-model="block.content.logo_link" class="form-control form-control-sm border bg-white" placeholder="/">
                    </div>
                    <div class="col-md-1">
                        <label class="small text-muted mb-1">C.Cao(px)</label>
                        <input type="number" x-model="block.content.header_height" class="form-control form-control-sm border bg-white" placeholder="80">
                    </div>
                </div>
            </div>

            {{-- E-commerce Features --}}
            <div class="col-12 pb-3 mb-1 border-bottom">
                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Tính năng E-commerce (Như ảnh mẫu)</label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-light p-2 rounded-3 border">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" x-model="block.content.show_search" id="show_search">
                                <label class="form-check-label small fw-bold" for="show_search">Hiển thị Thanh tìm kiếm (Center)</label>
                            </div>
                            <template x-if="block.content.show_search">
                                <div>
                                    <label class="small text-muted mb-1">Gợi ý tìm kiếm (Placeholder)</label>
                                    <input type="text" x-model="block.content.search_placeholder" class="form-control form-control-sm border bg-white" placeholder="Tìm kiếm mọi thứ ở đây...">
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-2 rounded-3 border h-100">
                            <label class="small fw-bold mb-2">Icon hành động (Right)</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" x-model="block.content.show_account" id="show_account">
                                    <label class="form-check-label small" for="show_account">Tài khoản</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" x-model="block.content.show_cart" id="show_cart">
                                    <label class="form-check-label small" for="show_cart">Giỏ hàng</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" x-model="block.content.show_wishlist" id="show_wishlist">
                                    <label class="form-check-label small" for="show_wishlist">Yêu thích</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row g-2">
                            <template x-if="block.content.show_account">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Link Tài khoản</label>
                                    <input type="text" x-model="block.content.account_link" class="form-control form-control-sm border bg-white" placeholder="/account">
                                </div>
                            </template>
                            <template x-if="block.content.show_cart">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Link Giỏ hàng</label>
                                    <input type="text" x-model="block.content.cart_link" class="form-control form-control-sm border bg-white" placeholder="/cart">
                                </div>
                            </template>
                            <template x-if="block.content.show_wishlist">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Link Yêu thích</label>
                                    <input type="text" x-model="block.content.wishlist_link" class="form-control form-control-sm border bg-white" placeholder="/wishlist">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-0">Nút điều hướng
                        (Navigation)</label>
                    <button
                        @click="block.content.buttons.push({label: 'Nút mới', link: '#', icon: '', bg_color: '#4e73df', text_color: '#ffffff', border_radius: 20, image_url: ''})"
                        class="btn btn-sm btn-outline-primary rounded-pill fw-bold" style="font-size: 0.7rem;">
                        <i class="fas fa-plus me-1"></i> Thêm nút
                    </button>
                </div>

                <div class="d-flex flex-column gap-3">
                    <template x-for="(btn, i) in block.content.buttons">
                        <div class="bg-light p-3 rounded-4 border position-relative">
                            <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
                                <button @click="block.content.buttons.splice(i + 1, 0, JSON.parse(JSON.stringify(btn)))"
                                    class="btn btn-sm text-primary p-1" title="Nhân bản">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button @click="block.content.buttons.splice(i, 1)" class="btn btn-sm text-danger p-1"
                                    title="Xóa">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Nhãn nút</label>
                                    <input type="text" x-model="btn.label"
                                        class="form-control form-control-sm border-0 bg-white"
                                        placeholder="Ví dụ: Trang chủ">
                                </div>
                                <div class="col-md-5">
                                    <label class="small text-muted mb-1">Đường dẫn (Link)</label>
                                    <input type="text" x-model="btn.link"
                                        class="form-control form-control-sm border-0 bg-white"
                                        placeholder="https://...">
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Icon (FA class)</label>
                                    <input type="text" x-model="btn.icon"
                                        class="form-control form-control-sm border-0 bg-white"
                                        placeholder="fas fa-home">
                                </div>

                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Màu nền nút</label>
                                    <div class="d-flex gap-2">
                                        <input type="color" x-model="btn.bg_color"
                                            class="form-control-color border-0 p-0 bg-transparent"
                                            style="width: 30px; height: 30px;">
                                        <input type="text" x-model="btn.bg_color"
                                            class="form-control form-control-sm border-0 bg-white small"
                                            style="font-size: 0.7rem;">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Màu chữ nút</label>
                                    <div class="d-flex gap-2">
                                        <input type="color" x-model="btn.text_color"
                                            class="form-control-color border-0 p-0 bg-transparent"
                                            style="width: 30px; height: 30px;">
                                        <input type="text" x-model="btn.text_color"
                                            class="form-control form-control-sm border-0 bg-white small"
                                            style="font-size: 0.7rem;">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Bo góc (px)</label>
                                    <input type="number" x-model="btn.border_radius"
                                        class="form-control form-control-sm border-0 bg-white">
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Ảnh trong nút</label>
                                    <div class="input-group input-group-sm rounded-3 overflow-hidden shadow-sm">
                                        <button type="button" class="btn btn-white border-end text-primary p-1"
                                            @click="openMediaPicker(index, 'buttons.' + i + '.image_url')">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <input type="text" x-model="btn.image_url"
                                            class="form-control border-0 bg-white small" placeholder="URL ảnh..."
                                            style="font-size: 0.7rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
