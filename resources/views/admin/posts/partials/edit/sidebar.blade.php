{{-- Sidebar: Add Blocks --}}
<div class="col-lg-3" x-data="{ search: '' }">
    <div class="tech-card sticky-top" style="top: 20px;">
        <div class="tech-header bg-gradient-primary text-white p-3">
            <h6 class="mb-2 fw-bold"><i class="fas fa-th-large me-2"></i>Thành phần (Blocks)</h6>
        </div>
        <div class="card-body p-3">
            <div class="position-relative mb-3">
                <input type="text" x-model="search" class="form-control form-control-sm border-0"
                    placeholder="Tìm kiếm block...">
                <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted"
                    style="font-size: 13px;"></i>
            </div>
            <div class="d-grid gap-2 custom-scrollbar p-1" style="max-height: 50vh; overflow-y: auto;">
                <button @click="addBlock('text_grid')"
                    x-show="'text grid tin tức dạng bài viết lưới block'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-th text-info me-2" style="width: 16px;"></i> Tin tức dạng lưới
                </button>
                <button @click="addBlock('contact_info_bar')"
                    x-show="'contact info bar liên hệ hotline điện thoại địa chỉ button'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-id-card text-primary me-2" style="width: 16px;"></i> Thanh liên hệ
                </button>
                <button @click="addBlock('header')" x-show="'header điều hướng nav'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-heading text-dark me-2" style="width: 16px;"></i> Header Điều hướng
                </button>
                <button @click="addBlock('footer')" x-show="'footer chân trang'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-shoe-prints text-muted me-2" style="width: 16px;"></i> Footer
                </button>
                <button @click="addBlock('hero_content')" x-show="'hero giới thiệu'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-id-card text-success me-2" style="width: 16px;"></i> Hero / Giới thiệu
                </button>
                <button @click="addBlock('text')" x-show="'text văn bản tiêu đề'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-align-left text-primary me-2" style="width: 16px;"></i> Văn bản / Tiêu đề
                </button>
                <button @click="addBlock('image')" x-show="'image hình ảnh'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-image text-success me-2" style="width: 16px;"></i> Hình ảnh
                </button>
                <button @click="addBlock('video')" x-show="'video youtube vimeo'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-video text-danger me-2" style="width: 16px;"></i> Video (Youtube/Vimeo)
                </button>
                <button @click="addBlock('grid')" x-show="'grid bố cục cột collumn'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-columns text-info me-2" style="width: 16px;"></i> Bố cục (Grid)
                </button>
                <button @click="addBlock('product_detail')"
                    x-show="'product detail chi tiết sản phẩm'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-box-open text-primary me-2" style="width: 16px;"></i> Chi tiết Sản phẩm
                </button>
                <button @click="addBlock('product_description')"
                    x-show="'product description mô tả chi tiết sản phẩm'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-file-alt text-info me-2" style="width: 16px;"></i> Mô tả chi tiết SP
                </button>
                <button @click="addBlock('product_category_grid')"
                    x-show="'product category danh mục sản phẩm banner dọc grid'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-th-list text-success me-2" style="width: 16px;"></i> Danh mục SP (Banner)
                </button>
                <button @click="addBlock('post_grid')"
                    x-show="'post grid danh sách bài viết danh mục tin tức'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-border-all text-primary me-2" style="width: 16px;"></i> Danh sách Bài viết
                </button>
                <button @click="addBlock('coupons')"
                    x-show="'coupons mã giảm giá khuyến mãi discount'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-gift text-danger me-2" style="width: 16px;"></i> Mã giảm giá (Coupons)
                </button>
                <button @click="addBlock('product_reviews')"
                    x-show="'product reviews đánh giá hỏi đáp feedback comment rating stars'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-star-half-alt text-warning me-2" style="width: 16px;"></i> Đánh giá & Hỏi đáp
                </button>

                <button @click="addBlock('accordion')"
                    x-show="'faq accordion hỏi đáp collapse'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-list-ul text-secondary me-2" style="width: 16px;"></i> FAQ / Accordion
                </button>
                <button @click="addBlock('pricing')" x-show="'pricing bảng giá'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-tags text-success me-2" style="width: 16px;"></i> Bảng giá
                </button>
                <button @click="addBlock('banner')" x-show="'banner slider video'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-flag text-danger me-2" style="width: 16px;"></i> Banner (Slider/Video)
                </button>
                <button @click="addBlock('testimonial')"
                    x-show="'testimonial khách hàng bình luận phản hồi'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-quote-left text-warning me-2" style="width: 16px;"></i> Khách hàng nói gì?
                </button>
                <button @click="addBlock('slider')" x-show="'slider trình chiếu ảnh'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-images text-warning me-2" style="width: 16px;"></i> Trình chiếu (Slider)
                </button>
                <button @click="addBlock('cta')" x-show="'cta nút kêu gọi action link'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-mouse-pointer text-danger me-2" style="width: 16px;"></i> Nút kêu gọi (CTA)
                </button>
                <button @click="addBlock('contact_form')"
                    x-show="'contact form mẫu liên hệ'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-envelope-open-text text-primary me-2" style="width: 16px;"></i> Mẫu liên hệ
                </button>
                <button @click="addBlock('registration')"
                    x-show="'registration registration form mẫu đăng ký dịch vụ nhận tin'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-file-signature text-danger me-2" style="width: 16px;"></i> Mẫu Đăng ký
                </button>
                <button @click="addBlock('office_map')"
                    x-show="'office map bản đồ hệ thống văn phòng địa chỉ'.includes(search.toLowerCase())"
                    class="btn btn-outline-light text-dark border p-2 text-start rounded-3 hover-shadow small fw-semibold">
                    <i class="fas fa-map-marked-alt text-success me-2" style="width: 16px;"></i> Hệ thống văn phòng
                </button>



                {{-- Spacer & Divider --}}
                <div class="row g-2 mt-0"
                    x-show="'spacer divider khoảng cách đường kẻ'.includes(search.toLowerCase())">
                    <div class="col-6">
                        <button @click="addBlock('spacer')"
                            x-show="'spacer khoảng cách margin padding'.includes(search.toLowerCase())"
                            class="btn btn-outline-light text-dark border w-100 p-2 text-center rounded-3 hover-shadow small h-100 text-truncate fw-semibold">
                            <i class="fas fa-arrows-alt-v text-muted me-1"></i> KC
                        </button>
                    </div>
                    <div class="col-6">
                        <button @click="addBlock('divider')"
                            x-show="'divider đường kẻ line'.includes(search.toLowerCase())"
                            class="btn btn-outline-light text-dark border w-100 p-2 text-center rounded-3 hover-shadow small h-100 text-truncate fw-semibold">
                            <i class="fas fa-minus text-muted me-1"></i> Line
                        </button>
                    </div>
                </div>

            </div> {{-- End d-grid --}}

            <button onclick="openCopyBlockModal()"
                class="btn btn-primary bg-opacity-10 text-white border-primary border-opacity-25 w-100 p-2 rounded-3 hover-shadow mt-3 small fw-bold">
                <i class="fas fa-clone me-1"></i> Sao chép từ Web mẫu
            </button>

            <div class="alert alert-info mt-3 mb-0 small border-0 bg-info bg-opacity-10 text-info py-2 px-3">
                <i class="fas fa-info-circle me-1"></i> Kéo thả bên cạnh để sắp xếp lại
            </div>
        </div> {{-- End card-body --}}
    </div> {{-- End tech-card --}}

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</div> {{-- End col-lg-3 --}}
