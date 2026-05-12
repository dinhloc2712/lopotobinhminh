{{-- Modal Chỉnh sửa Block --}}
<div class="modal fade" id="editBlockModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-height: 90vh;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" x-show="editingBlock">
            <template x-if="editingBlock">
                <div x-data="{ block: editingBlock, index: 'modal' }" class="d-flex flex-column h-100">
                    <div class="modal-header border-bottom pt-4 px-4 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 45px; height: 45px;">
                                <i :class="getBlockIcon(editingBlock.type)" class="fs-5"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold mb-0"
                                    x-text="'Chỉnh sửa: ' + getBlockName(editingBlock.type)">
                                </h5>
                                <div class="small text-muted"
                                    x-text="'Vị trí số ' + (editingBlock.index + 1) + ' trong trang'">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            @click="editingBlock = null"></button>
                    </div>

                    <div class="modal-body p-4 bg-white" style="overflow-y: auto; max-height: 60vh;">
                        <div class="editor-container">
                            @include('admin.posts.partials.edit.blocks.header')
                            @include('admin.posts.partials.edit.blocks.footer')
                            @include('admin.posts.partials.edit.blocks.hero_content')
                            @include('admin.posts.partials.edit.blocks.text')
                            @include('admin.posts.partials.edit.blocks.image')
                            @include('admin.posts.partials.edit.blocks.video')
                            @include('admin.posts.partials.edit.blocks.cta')
                            @include('admin.posts.partials.edit.blocks.grid')
                            @include('admin.posts.partials.edit.blocks.slider')
                            @include('admin.posts.partials.edit.blocks.accordion')
                            @include('admin.posts.partials.edit.blocks.pricing')
                            @include('admin.posts.partials.edit.blocks.testimonial')
                            @include('admin.posts.partials.edit.blocks.spacer')
                            @include('admin.posts.partials.edit.blocks.divider')
                            @include('admin.posts.partials.edit.blocks.contact_form')
                            @include('admin.posts.partials.edit.blocks.registration')
                            @include('admin.posts.partials.edit.blocks.banner')
                             @include('admin.posts.partials.edit.blocks.product_category_grid')
                            @include('admin.posts.partials.edit.blocks.post_grid')
                            @include('admin.posts.partials.edit.blocks.text_grid')
                            @include('admin.posts.partials.edit.blocks.contact_info_bar')
                            @include('admin.posts.partials.edit.blocks.office_map')
                            @include('admin.posts.partials.edit.blocks.product_detail')
                            @include('admin.posts.partials.edit.blocks.product_description')
                            @include('admin.posts.partials.edit.blocks.coupons')
                            @include('admin.posts.partials.edit.blocks.product_reviews')
                        </div>
                    </div>

                    <div class="modal-footer border-top px-4 py-3 bg-light">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal" @click="editingBlock = null">Hủy bỏ</button>
                        <button type="button" @click="updateBlockContent()"
                            class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-check me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
