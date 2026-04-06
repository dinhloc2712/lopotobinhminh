<template x-if="block.type === 'footer'">
    <div class="mt-2 text-start">
        <div class="row g-3">
            {{-- Shared Block Styling --}}
            @include('admin.posts.partials.edit.blocks.shared_styles')

            {{-- Cấu hình chính của Footer --}}
            <div class="col-12">
                <div class="bg-light p-3 rounded-4 border mb-3">
                    <h6 class="fw-bold text-dark small text-uppercase mb-3">Thông tin chính</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Logo URL</label>
                            <div class="input-group input-group-sm rounded-3 overflow-hidden shadow-sm">
                                <button type="button" class="btn btn-white border-end text-primary p-1" @click="openMediaPicker(index, 'logo_url')">
                                    <i class="fas fa-image"></i>
                                </button>
                                <input type="text" x-model="block.content.logo_url" class="form-control border-0 bg-white" placeholder="URL logo...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Dòng Copyright</label>
                            <input type="text" x-model="block.content.copyright" class="form-control form-control-sm border-0 bg-white shadow-sm" placeholder="© 2024 Vinayuuki. All rights reserved.">
                        </div>
                        <div class="col-12">
                            <label class="small text-muted mb-1">Giới thiệu ngắn (About)</label>
                            <textarea x-model="block.content.about_text" class="form-control form-control-sm border-0 bg-white shadow-sm" rows="2" placeholder="Nhập một vài dòng giới thiệu về công ty..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Cột liên kết --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-0">Các cột liên kết</label>
                    <button @click="block.content.columns = block.content.columns || []; block.content.columns.push({title: 'Danh mục', links: [{label: 'Trang chủ', url: '#'}]})"
                        class="btn btn-sm btn-outline-primary rounded-pill fw-bold" style="font-size: 0.7rem;">
                        <i class="fas fa-plus me-1"></i> Thêm cột
                    </button>
                </div>

                <div class="row g-3 mb-4">
                    <template x-for="(col, i) in block.content.columns">
                        <div class="col-md-6">
                            <div class="bg-white p-3 rounded-4 border shadow-sm position-relative">
                                <button @click="block.content.columns.splice(i, 1)" class="btn btn-sm text-danger position-absolute top-0 end-0 p-2">
                                    <i class="fas fa-times"></i>
                                </button>
                                
                                <div class="mb-3">
                                    <label class="small text-muted mb-1">Tiêu đề cột</label>
                                    <input type="text" x-model="col.title" class="form-control form-control-sm bg-light border-0 fw-bold" placeholder="Tên cột...">
                                </div>

                                <div class="links-list">
                                    <template x-for="(link, j) in col.links">
                                        <div class="d-flex gap-1 mb-2">
                                            <input type="text" x-model="link.label" class="form-control form-control-sm border-0 bg-light" placeholder="Nhãn..." style="flex: 1;">
                                            <input type="text" x-model="link.url" class="form-control form-control-sm border-0 bg-light" placeholder="URL..." style="flex: 2;">
                                            <button @click="col.links.splice(j, 1)" class="btn btn-sm btn-light p-1">
                                                <i class="fas fa-minus text-danger"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="col.links.push({label: 'Liên kết mới', url: '#'})" class="btn btn-sm btn-white border w-100 mt-1" style="font-size: 0.65rem;">
                                        <i class="fas fa-plus me-1"></i> Thêm link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Mạng xã hội --}}
                <h6 class="fw-bold text-dark small text-uppercase mb-2 mt-4">Mạng xã hội (Social)</h6>
                <div class="bg-light p-3 rounded-4 border">
                    <div class="row g-2">
                        <template x-for="(soc, k) in (block.content.socials || [])">
                            <div class="col-md-4">
                                <div class="bg-white p-2 rounded-3 border d-flex gap-1">
                                    <input type="text" x-model="soc.icon" class="form-control form-control-sm border-0" placeholder="fab fa-facebook" style="width: 100px;">
                                    <input type="text" x-model="soc.url" class="form-control form-control-sm border-0" placeholder="Link...">
                                    <button @click="block.content.socials.splice(k, 1)" class="btn btn-sm btn-light text-danger p-1">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <div class="col-12 mt-2">
                            <button @click="block.content.socials = block.content.socials || []; block.content.socials.push({icon: 'fab fa-facebook', url: '#'})" class="btn btn-sm btn-outline-primary border-dashed w-100">
                                <i class="fas fa-plus me-1"></i> Thêm mạng xã hội
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
