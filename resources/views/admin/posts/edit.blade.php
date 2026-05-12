@extends('layouts.admin')

@section('title', 'Thiết kế: ' . $post->title)

@section('styles')
    <style>
        .builder-block {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
        }

        .builder-block:hover {
            border-color: #4e73df;
            background-color: #f8f9fc;
        }

        .block-tools {
            position: absolute;
            top: -15px;
            right: 15px;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .builder-block:hover .block-tools {
            opacity: 1;
        }

        .ghost {
            opacity: 0.5;
            background: #c8ebfb;
        }

        .draggable-handle {
            cursor: move;
        }

        .block-preview {
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div x-data="postBuilder()" x-init="init()">
        @include('admin.posts.partials.edit.header')

        <div class="row">
            @include('admin.posts.partials.edit.sidebar')
            @include('admin.posts.partials.edit.editor')
        </div>

        @include('admin.posts.partials.edit.seo_modal')
        @include('admin.posts.partials.edit.edit_block_modal')
        @include('admin.posts.partials.edit.copy_block_modal')

        <x-admin.media-picker-modal id="mediaPickerModal" />
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/3lj129fowr521a21ymimv9qyjwxyzezc86feaj8brb3fetc0/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        // Sửa lỗi không click/nhập được liệu ở Model TinyMCE khi dùng trong Bootstrap Modal
        document.addEventListener('focusin', (e) => {
            if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !==
                null) {
                e.stopImmediatePropagation();
            }
        });

        function postBuilder() {
            return {
                blocks: {!! $post->blocks->map(function ($b) {
                        return [
                            'id' => $b->id,
                            'uid' => $b->id ?: uniqid('new_'),
                            'type' => $b->type,
                            'content' => $b->content ?: (object) [],
                        ];
                    })->toJson() !!},

                saving: false,
                activePickerTarget: null,
                editingBlock: null,

                init() {
                    const el = document.getElementById('blocks-container');
                    Sortable.create(el, {
                        handle: '.draggable-handle',
                        animation: 200,
                        ghostClass: 'ghost',
                        dataIdAttr: 'data-uid',
                        onEnd: (evt) => {
                            // Get items with data-uid in the new order from DOM
                            const uids = Array.from(el.querySelectorAll('.builder-block')).map(item => item
                                .getAttribute('data-uid'));

                            // Rebuild the array based on the new physical order
                            const newOrderedBlocks = uids.map(uid => {
                                return this.blocks.find(b => String(b.uid) === String(uid));
                            }).filter(b => b !== undefined);

                            // To force Alpine.js to sync the DOM correctly with these indices,
                            // we clear and then set the array in the next tick.
                            this.blocks = [];
                            this.$nextTick(() => {
                                this.blocks = newOrderedBlocks;
                            });
                        }
                    });

                    // Lắng nghe sự kiện từ Media Picker
                    window.addEventListener('on-post-builder-media-selected', (e) => {
                        if (!this.activePickerTarget) return;

                        const {
                            index,
                            field,
                            append
                        } = this.activePickerTarget;
                        this.activePickerTarget = null; // Reset ngay lập tức để tránh double action

                        const setDeepValue = (obj, path, value) => {
                            let parts = path.split('.');
                            let current = obj;
                            for (let i = 0; i < parts.length - 1; i++) {
                                current = current[parts[i]];
                            }
                            if (append) {
                                current[parts[parts.length - 1]] = current[parts[parts.length - 1]] ?
                                    current[parts[parts.length - 1]] + '\n' + value :
                                    value;
                            } else {
                                current[parts[parts.length - 1]] = value;
                            }
                        };

                        if (index === 'post') {
                            const input = document.getElementById('post_thumbnail_input');
                            if (input) input.value = e.detail.url;
                        } else if (index === 'modal') {
                            if (field === 'tiny_editor') {
                                tinymce.get('editor-modal').insertContent(
                                    `<img loading="lazy" src="${e.detail.url}" alt="image" style="max-width:100%; height:auto;">`
                                );
                            } else {
                                setDeepValue(this.editingBlock.content, field, e.detail.url);
                            }
                        } else {
                            if (field === 'tiny_editor') {
                                tinymce.get(`editor-${index}`).insertContent(
                                    `<img loading="lazy" src="${e.detail.url}" alt="image" style="max-width:100%; height:auto;">`
                                );
                            } else {
                                const block = this.blocks[index];
                                if (block) {
                                    setDeepValue(block.content, field, e.detail.url);
                                }
                            }
                        }
                    });

                    // Initialize Editors for existing text blocks delay
                    setTimeout(() => {
                        this.blocks.forEach((block, index) => {
                            if (block.type === 'text' || block.type === 'product_detail') this.initEditor(index);
                        });
                    }, 500);
                },

                initEditor(index) {
                    if (typeof tinymce === 'undefined') return;

                    const selector = index === 'modal' ? '#editor-modal' : `#editor-${index}`;
                    const tinymceId = index === 'modal' ? 'editor-modal' : `editor-${index}`;

                    // Destroy existing instance if moving/re-rendering
                    if (tinymce.get(tinymceId)) {
                        tinymce.get(tinymceId).remove();
                    }

                    tinymce.init({
                        selector: selector,
                        height: 450,
                        menubar: true,
                        relative_urls: false,
                        remove_script_host: false,
                        plugins: [
                            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount',
                            'emoticons', 'accordion', 'lists'
                        ],
                        toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                            'bold italic underline strikethrough forecolor backcolor | alignleft aligncenter ' +
                            'alignright alignjustify customMediaPicker | bullist numlist outdent indent | ' +
                            'image media link | table charmap emoticons | ' +
                            'removeformat fullscreen code | help',
                        font_family_formats: 'Montserrat=montserrat,sans-serif; Inter=inter,sans-serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Verdana=verdana,geneva; Georgia=georgia,times new roman,times,serif;',
                        content_css: [
                            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700;800&display=swap'
                        ],
                        content_style: 'body { font-family:Inter,Montserrat,Helvetica,Arial,sans-serif; font-size:14px }',
                        setup: (editor) => {
                            // Add custom button for media picker
                            editor.ui.registry.addButton('customMediaPicker', {
                                icon: 'image',
                                tooltip: 'Chọn ảnh từ thư viện',
                                onAction: () => {
                                    // Set target for tinyMCE
                                    this.activePickerTarget = {
                                        index: index,
                                        field: 'tiny_editor', // Special field for tinyMCE
                                        append: false
                                    };
                                    window.dispatchEvent(new CustomEvent('open-media-picker', {
                                        detail: {
                                            eventName: 'on-post-builder-media-selected'
                                        }
                                    }));
                                }
                            });

                            editor.on('Change KeyUp', (e) => {
                                // Update Alpine model on change
                                if (index === 'modal') {
                                    this.editingBlock.content.body = editor.getContent();
                                } else {
                                    this.blocks[index].content.body = editor.getContent();
                                }
                            });
                        }
                    });
                },

                initItemEditor(uid, fieldPath, itemObj) {
                    if (typeof tinymce === 'undefined') return;
                    const selector = `#editor-item-${uid}`;
                    if (tinymce.get(`editor-item-${uid}`)) {
                        tinymce.get(`editor-item-${uid}`).remove();
                    }

                    tinymce.init({
                        selector: selector,
                        height: 200,
                        menubar: false,
                        relative_urls: false,
                        remove_script_host: false,
                        plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'code', 'wordcount', 'emoticons'],
                        toolbar: 'undo redo | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link image emoticons | code',
                        setup: (editor) => {
                            editor.on('Change KeyUp', (e) => {
                                itemObj.content = editor.getContent();
                            });
                        }
                    });
                },

                editBlock(index) {
                    const targetBlock = this.blocks[index];

                    // Khởi tạo block để chỉnh sửa
                    this.editingBlock = {
                        index: index,
                        type: targetBlock.type,
                        content: JSON.parse(JSON.stringify(targetBlock.content || {}))
                    };

                    // Đảm bảo các mảng dữ liệu luôn tồn tại để tránh lỗi .push()
                    if (this.editingBlock.type === 'header' && !this.editingBlock.content.buttons) {
                        this.editingBlock.content.buttons = [];
                    }
                    if (this.editingBlock.type === 'pricing' && !this.editingBlock.content.plans) {
                        this.editingBlock.content.plans = [];
                    }
                    if (this.editingBlock.type === 'accordion' && !this.editingBlock.content.items) {
                        this.editingBlock.content.items = [];
                    }
                    if (this.editingBlock.type === 'banner' && !this.editingBlock.content.items) {
                        this.editingBlock.content.items = [];
                    }
                    if (this.editingBlock.type === 'office_map' && !this.editingBlock.content.addresses) {
                        this.editingBlock.content.addresses = [];
                    }
                    if (this.editingBlock.type === 'office_map' && !this.editingBlock.content.socials) {
                        this.editingBlock.content.socials = {
                            facebook: '',
                            tiktok: '',
                            youtube: '',
                            zalo: ''
                        };
                    }
                    if (this.editingBlock.type === 'product_category_grid') {
                        // Initialize banner_images array if missing
                        if (!this.editingBlock.content.banner_images) {
                            this.editingBlock.content.banner_images = [];
                        }
                        // Migrate legacy single banner_image string to array format
                        if (this.editingBlock.content.banner_image && this.editingBlock.content.banner_images.length === 0) {
                            this.editingBlock.content.banner_images.push({ url: this.editingBlock.content.banner_image });
                            delete this.editingBlock.content.banner_image;
                        }
                    }

                    const modal = new bootstrap.Modal(document.getElementById('editBlockModal'));
                    modal.show();

                    // Initialize tinymce if text block
                    if (this.editingBlock.type === 'text' || this.editingBlock.type === 'product_detail') {
                        setTimeout(() => this.initEditor('modal'), 150);
                    }
                },

                updateBlockContent() {
                    const idx = this.editingBlock.index;
                    this.blocks[idx].content = JSON.parse(JSON.stringify(this.editingBlock.content));
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editBlockModal'));
                    modal.hide();

                    // Cleanup TinyMCE if text block
                    if ((this.editingBlock.type === 'text' || this.editingBlock.type === 'product_detail') && typeof tinymce !== 'undefined') {
                        tinymce.get('editor-modal').remove();
                    }
                    this.editingBlock = null;
                },

                getBlockIcon(type) {
                    const icons = {
                        'header': 'fas fa-heading',
                        'footer': 'fas fa-shoe-prints',
                        'hero_content': 'fas fa-id-card',
                        'text': 'fas fa-align-left',
                        'image': 'fas fa-image',
                        'video': 'fas fa-video',
                        'grid': 'fas fa-columns',
                        'slider': 'fas fa-images',
                        'cta': 'fas fa-mouse-pointer',
                        'accordion': 'fas fa-list-ul',
                        'pricing': 'fas fa-tags',
                        'testimonial': 'fas fa-quote-left',
                        'spacer': 'fas fa-arrows-alt-v',
                        'divider': 'fas fa-minus',
                        'banner': 'fas fa-flag',
                        'contact_form': 'fas fa-envelope-open-text',
                        'product_category_grid': 'fas fa-th-list',
                        'post_grid': 'fas fa-border-all',
                        'text_grid': 'fas fa-th',
                        'office_map': 'fas fa-map-marked-alt',
                        'product_detail': 'fas fa-box-open',
                        'product_description': 'fas fa-file-alt',
                        'coupons': 'fas fa-gift',
                        'product_reviews': 'fas fa-star-half-alt',
                        'registration': 'fas fa-file-signature'
                    };
                    return icons[type] || 'fas fa-cube';
                },

                getBlockName(type) {
                    const names = {
                        'header': 'Header Điều hướng',
                        'footer': 'Footer Chân trang',
                        'hero_content': 'Hero / Giới thiệu',
                        'text': 'Văn bản / Tiêu đề',
                        'image': 'Hình ảnh',
                        'video': 'Video',
                        'grid': 'Bố cục (Grid)',
                        'slider': 'Trình chiếu (Slider)',
                        'cta': 'Nút kêu gọi (CTA)',
                        'accordion': 'FAQ / Accordion',
                        'pricing': 'Bảng giá',
                        'testimonial': 'Khách hàng nói gì?',
                        'spacer': 'Khoảng cách',
                        'divider': 'Khoảng cách / Đường kẻ',
                        'banner': 'Banner (Slider/Video)',
                        'contact_form': 'Mẫu liên hệ',
                        'product_category_grid': 'Danh mục sản phẩm (Banner dọc)',
                        'post_grid': 'DS Bài viết / Danh mục',
                        'text_grid': 'Tin tức dạng lưới',
                        'office_map': 'Hệ thống văn phòng (Bản đồ)',
                        'contact_info_bar': 'Thanh liên hệ (Thông tin)',
                        'product_detail': 'Chi tiết sản phẩm',
                        'product_description': 'Mô tả chi tiết SP',
                        'coupons': 'Mã giảm giá (Coupons)',
                        'product_reviews': 'Đánh giá & Hỏi đáp',
                        'registration': 'Mẫu Đăng ký'
                    };
                    return names[type] || type;
                },

                openMediaPicker(index, field, append = false) {
                    this.activePickerTarget = {
                        index,
                        field,
                        append
                    };
                    // Gọi sự kiện mở thẻ Media Picker Modal Component
                    window.dispatchEvent(new CustomEvent('open-media-picker', {
                        detail: {
                            eventName: 'on-post-builder-media-selected'
                        }
                    }));
                },

                addBlock(type) {
                    let defaultContent = {};
                    switch (type) {
                        case 'header':
                            defaultContent = {
                                logo: '',
                                logo_link: '/',
                                buttons: [],
                                bg_color: '#ffffff',
                                text_color: '#000000',
                                font_family: 'inherit',
                                // E-commerce features
                                show_search: false,
                                search_placeholder: 'Tìm kiếm mọi thứ ở đây...',
                                show_account: false,
                                account_link: '/account',
                                show_cart: false,
                                cart_link: '/cart',
                                show_wishlist: false,
                                wishlist_link: '/wishlist',
                                header_height: 80
                            };
                            break;
                        case 'footer':
                            defaultContent = {
                                logo_url: '',
                                about_text: '',
                                copyright: '© 2024 Vinayuuki. All rights reserved.',
                                columns: [],
                                socials: [],
                                bg_color: '#2D3748',
                                text_color: '#ffffff'
                            };
                            break;
                        case 'hero_content':
                            defaultContent = {
                                title: 'VỀ CHÚNG TÔI',
                                body: 'Mô tả chi tiết nội dung giới thiệu...',
                                image: '',
                                image_style: 'rounded-4',
                                reverse_layout: false,
                                btn_label: 'Tìm hiểu thêm',
                                btn_link: '#',
                                btn_icon: '',
                                btn_bg_color: '#004a80',
                                btn_text_color: '#ffffff',
                                bg_color: '#ffffff',
                                text_color: '#000000'
                            };
                            break;
                        case 'text':
                            defaultContent = {
                                heading: '',
                                body: '',
                                show_toc: false,
                                toc_position: 'top',
                                is_article_layout: false,
                                related_category_id: ''
                            };
                            break;
                        case 'image':
                            defaultContent = {
                                url: '',
                                alt: ''
                            };
                            break;
                        case 'video':
                            defaultContent = {
                                url: ''
                            };
                            break;
                        case 'grid':
                            defaultContent = {
                                columns: 2
                            };
                            break;
                        case 'slider':
                            defaultContent = {
                                urls: ''
                            };
                            break;
                        case 'cta':
                            defaultContent = {
                                label: 'Click ngay',
                                link: '#'
                            };
                            break;
                        case 'accordion':
                            defaultContent = {
                                items: [{
                                    title: 'Câu hỏi 1?',
                                    content: 'Trả lời...'
                                }]
                            };
                            break;
                        case 'pricing':
                            defaultContent = {
                                plans: [{
                                    name: 'Cơ bản',
                                    price: '0đ',
                                    features: 'Tính năng 1\nTính năng 2',
                                    button_label: 'Chọn gói'
                                }]
                            };
                            break;
                        case 'testimonial':
                            defaultContent = {
                                quote: '',
                                author: '',
                                avatar: ''
                            };
                            break;
                        case 'spacer':
                            defaultContent = {
                                height: 40
                            };
                            break;
                        case 'divider':
                            defaultContent = {
                                style: 'solid',
                                thickness: 1,
                                color: '#e2e8f0'
                            };
                            break;
                        case 'product':
                            defaultContent = {
                                category_id: '',
                                items_limit: 8,
                                items_per_row: 4,
                                show_price: true,
                                show_stock: true,
                                show_sold: true,
                                btn_text: 'Xem chi tiết'
                            };
                            break;
                        case 'product_description':
                            defaultContent = {
                                product_id: ''
                            };
                            break;
                        case 'product_detail':
                            defaultContent = {
                                product_id: '',
                                video_url: '',
                                show_rating: true,
                                show_sku: true,
                                body: '<h4>LÊN ĐỜI LỐP MỚI, NHẬN KÈM QUÀ SIÊU HỜI</h4><p>1. Bộ checklist kiểm tra xe trước chuyến đi: 300K</p><p>2. Mẹo chăm xe hơi cho người bận rộn: 500K</p><strong>TỔNG TRỊ GIÁ: 1.200.000 ĐỒNG</strong>'
                            };
                            break;
                        case 'banner':
                            defaultContent = {
                                items: [{
                                    image: '',
                                    text: '',
                                    link_text: '',
                                    link_url: ''
                                }],
                                right_items: [
                                    { image: '', link: '' },
                                    { image: '', link: '' }
                                ],
                                layout: 'slider',
                                height: '400px'
                            };
                            break;
                        case 'contact_form':
                            defaultContent = {
                                title: 'LIÊN HỆ VỚI CHÚNG TÔI',
                                description: 'Chúng tôi sẽ phản hồi sớm nhất có thể.',
                                submit_label: 'Gửi yêu cầu ngay',
                                image: '',
                                bg_color: '#ffffff',
                                text_color: '#000000'
                            };
                            break;
                        case 'product_category_grid':
                            defaultContent = {
                                title: 'LỐP XE CAO CẤP',
                                view_all_text: 'Xem tất cả',
                                view_all_link: '#',
                                category_id: '',
                                banner_image: '',
                                items_limit: 8,
                                items_per_row: 4,
                                btn_bg_color: '#00e5ff',
                                btn_text_color: '#ffffff',
                                btn_border_radius: 50
                            };
                            break;
                        case 'post_grid':
                            defaultContent = {
                                category_id: '',
                                items_per_page: 9,
                                text_color: '#004a80'
                            };
                            break;
                        case 'text_grid':
                            defaultContent = {
                                title: 'TIN TỨC MỚI NHẤT',
                                category_id: '',
                                items_limit: 6,
                                columns: 3,
                                show_date: true,
                                show_summary: true,
                                text_color: '#004a80'
                            };
                            break;
                        case 'office_map':
                            defaultContent = {
                                title: '',
                                addresses: [{
                                    address: '',
                                    lat: '',
                                    lng: ''
                                }],
                                phone: '',
                                website: '',
                                email: '',
                                socials: {
                                    facebook: '',
                                    tiktok: '',
                                    youtube: '',
                                    zalo: ''
                                }
                            };
                            break;
                        case 'contact_info_bar':
                            defaultContent = {
                                items: [
                                    {
                                        uid: 'item_' + Date.now() + '_1',
                                        icon_image: '',
                                        content: "<b>Tư vấn chuyên sâu:</b>\nHotline:\n<span class='text-danger'>02383545886</span>",
                                        width: 0
                                    },
                                    {
                                        uid: 'item_' + Date.now() + '_2',
                                        icon_image: '',
                                        content: "<b>Hòm thư liên hệ:</b>\nEmail:\n<span class='text-danger'>linkcoins@gmail.com</span>",
                                        width: 0
                                    },
                                    {
                                        uid: 'item_' + Date.now() + '_3',
                                        icon_image: '',
                                        content: "<b>Địa chỉ liên hệ:</b>\nMiền Nam: Nhà máy Sailun: Lô 37-41, D11, KCN Phước Đông, Gò Dầu, Tây Ninh\nMiền Nam: Nhà máy Yokohama: số 17, Đ10, KCN VSIP, TP. Thuận An, Bình Dương",
                                        width: 0
                                    }
                                ],
                                btn_text: 'Cộng tác viên',
                                btn_link: '#',
                                btn_bg_color: '#00ffff',
                                btn_text_color: '#000000',
                                btn_border_radius: 0
                            };
                            break;
                        case 'coupons':
                            defaultContent = {
                                title: 'ƯU ĐÃI LIÊN QUAN',
                                coupon_ids: []
                            };
                            break;
                        case 'product_reviews':
                            defaultContent = {
                                product_id: '',
                                accent_color: '#C92127',
                                title: 'ĐÁNH GIÁ SẢN PHẨM',
                                qa_title: 'Hỏi và đáp',
                                qa_info: 'Xin mời để lại câu hỏi, bên mình sẽ trả lời lại trong 1h, các câu hỏi sau 22h - 8h sẽ được trả lời vào sáng hôm sau'
                            };
                            break;
                        case 'registration':
                            defaultContent = {
                                title: 'ĐĂNG KÝ TÀI KHOẢN',
                                subtitle: 'Tham gia cùng cộng đồng Lốp Ô Tô Bình Minh để nhận ưu đãi đặc quyền và quản lý dịch vụ chuyên nghiệp.',
                                button_label: 'Tạo tài khoản ngay',
                                image: '',
                                reverse_layout: false,
                                accent_color: '#004a80',
                                title_color: '#0f172a',
                                btn_text_color: '#ffffff',
                                redirect_to: ''
                            };
                            break;
                    }
                    this.blocks.push({
                        id: null,
                        uid: 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                        type: type,
                        content: defaultContent
                    });

                    // If text block, init editor after DOM update
                    if (type === 'text' || type === 'product_detail') {
                        const newIndex = this.blocks.length - 1;
                        setTimeout(() => this.initEditor(newIndex), 150);
                    }

                    // Scroll to bottom
                    setTimeout(() => {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: 'smooth'
                        });
                    }, 100);
                },

                duplicateBlock(index) {
                    const blockToCopy = this.blocks[index];
                    const newBlock = {
                        id: null,
                        uid: 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000),
                        type: blockToCopy.type,
                        content: JSON.parse(JSON.stringify(blockToCopy.content || {}))
                    };

                    // Chèn block mới ngay bên dưới block gốc
                    this.blocks.splice(index + 1, 0, newBlock);

                    // Nếu là block văn bản, khởi tạo lại TinyMCE
                    if (newBlock.type === 'text' || newBlock.type === 'product_detail') {
                        setTimeout(() => this.initEditor(index + 1), 150);
                    }
                },

                removeBlock(index) {
                    Swal.fire({
                        title: 'Xoá khối này?',
                        text: 'Dữ liệu trong khối nội dung này sẽ bị mất.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xoá'
                    }).then(result => {
                        if (result.isConfirmed) {
                            if ((this.blocks[index].type === 'text' || this.blocks[index].type === 'product_detail') && typeof tinymce !== 'undefined') {
                                tinymce.remove(`#editor-${index}`);
                            }
                            this.blocks.splice(index, 1);
                        }
                    });
                },

                async save() {
                    this.saving = true;
                    try {
                        const response = await fetch('{{ route('admin.posts.save-blocks', $post->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                blocks: this.blocks
                            })
                        });

                        const result = await response.json();
                        if (result.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Đã lưu thiết kế thành công!'
                            });
                        }
                    } catch (error) {
                        console.error(error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Có lỗi xảy ra khi lưu thiết kế.'
                        });
                    } finally {
                        this.saving = false;
                    }
                },

                copyColor(color) {
                    if (!color) return;
                    navigator.clipboard.writeText(color).then(() => {
                        Toast.fire({ icon: 'success', title: 'Đã copy màu: ' + color });
                    });
                },

                async pasteColor(obj, field) {
                    try {
                        const text = await navigator.clipboard.readText();
                        if (/^#[0-9A-F]{6}$/i.test(text) || /^#[0-9A-F]{3}$/i.test(text)) {
                            obj[field] = text;
                            Toast.fire({ icon: 'success', title: 'Đã dán màu: ' + text });
                        } else {
                            Toast.fire({ icon: 'error', title: 'Mã màu không hợp lệ!' });
                        }
                    } catch (err) {
                        Toast.fire({ icon: 'error', title: 'Không thể đọc clipboard!' });
                    }
                }
            }
        }
    </script>
@endsection
