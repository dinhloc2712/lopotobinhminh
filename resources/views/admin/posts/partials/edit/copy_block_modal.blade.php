{{-- Modal: Copy Blocks From Another Page --}}
<div id="copyBlockModal" class="modal fade" tabindex="-1" aria-labelledby="copyBlockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="copyBlockModalLabel">
                    <i class="fas fa-copy me-2 text-primary"></i>Sao chép block từ trang khác
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-2">
                {{-- Step 1: Choose source page --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="font-size:0.7rem;">1. Chọn trang nguồn</label>
                    <select id="copyBlock-sourceSelect" class="form-select form-select-sm" onchange="copyBlockLoadBlocks(this.value)">
                        <option value="">-- Chọn trang --</option>
                    </select>
                </div>

                {{-- Step 2: Choose blocks --}}
                <div id="copyBlock-blockList" class="d-none">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="font-size:0.7rem;">2. Chọn block(s) muốn sao chép</label>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="copyBlock-blockCount" class="small text-muted"></span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size:0.7rem;" onclick="copyBlockSelectAll(true)">Chọn tất cả</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size:0.7rem;" onclick="copyBlockSelectAll(false)">Bỏ chọn</button>
                        </div>
                    </div>
                    <div id="copyBlock-items" class="d-flex flex-column gap-2" style="max-height:340px;overflow-y:auto;">
                        {{-- Rendered by JS --}}
                    </div>
                </div>

                {{-- Loading state --}}
                <div id="copyBlock-loading" class="text-center py-4 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 small text-muted">Đang tải...</span>
                </div>

                {{-- Empty state --}}
                <div id="copyBlock-empty" class="text-center py-4 text-muted d-none">
                    <i class="fas fa-inbox fa-2x opacity-25 mb-2"></i>
                    <div class="small">Trang này chưa có block nào.</div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Huỷ</button>
                <button type="button" id="copyBlock-submitBtn"
                    class="btn btn-primary rounded-pill px-4 fw-bold"
                    onclick="copyBlockSubmit()"
                    disabled>
                    <i class="fas fa-copy me-1"></i>Sao chép vào trang này
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var _sourceData  = [];
    var _currentPostId = '{{ $post->id }}';
    var _sourceUrl   = '{{ route("admin.posts.blocks-source") }}';
    var _copyUrl     = '{{ route("admin.posts.copy-blocks", $post) }}';
    var _csrfToken   = '{{ csrf_token() }}';

    // Open modal → load pages
    window.openCopyBlockModal = function () {
        document.getElementById('copyBlock-sourceSelect').value = '';
        document.getElementById('copyBlock-blockList').classList.add('d-none');
        document.getElementById('copyBlock-loading').classList.add('d-none');
        document.getElementById('copyBlock-empty').classList.add('d-none');
        document.getElementById('copyBlock-submitBtn').disabled = true;

        var modal = new bootstrap.Modal(document.getElementById('copyBlockModal'));
        modal.show();

        // Fetch page list if empty
        var select = document.getElementById('copyBlock-sourceSelect');
        if (select.options.length <= 1) {
            fetch(_sourceUrl + '?exclude=' + _currentPostId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(function (data) {
                _sourceData = data;
                data.forEach(function (post) {
                    var opt = document.createElement('option');
                    opt.value = post.id;
                    opt.textContent = post.title + ' (' + post.blocks.length + ' block)';
                    select.appendChild(opt);
                });
            });
        }
    };

    // Load blocks of selected page
    window.copyBlockLoadBlocks = function (postId) {
        var list  = document.getElementById('copyBlock-blockList');
        var empty = document.getElementById('copyBlock-empty');
        var items = document.getElementById('copyBlock-items');
        var count = document.getElementById('copyBlock-blockCount');

        list.classList.add('d-none');
        empty.classList.add('d-none');
        document.getElementById('copyBlock-submitBtn').disabled = true;

        if (!postId) return;

        var post = _sourceData.find(p => p.id == postId);
        if (!post) return;

        if (post.blocks.length === 0) {
            empty.classList.remove('d-none');
            return;
        }

        var blockIcons = {
            header: 'fa-heading', footer: 'fa-shoe-prints', hero_content: 'fa-id-card',
            text: 'fa-align-left', image: 'fa-image', video: 'fa-video',
            grid: 'fa-columns', accordion: 'fa-list-ul', pricing: 'fa-tags',
            banner: 'fa-flag', testimonial: 'fa-quote-left', slider: 'fa-images',
            cta: 'fa-mouse-pointer', marquee: 'fa-exchange-alt', globe: 'fa-globe-asia',
            contact_form: 'fa-envelope-open-text', spacer: 'fa-arrows-alt-v', divider: 'fa-minus',
        };

        items.innerHTML = post.blocks.map(function (block) {
            return '<label class="d-flex align-items-center gap-3 bg-light border rounded-3 p-2 cursor-pointer" style="cursor:pointer;">' +
                '<input type="checkbox" class="form-check-input copy-block-chk" value="' + block.id + '" onchange="copyBlockUpdateBtn()">' +
                '<span class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width:34px;height:34px;flex-shrink:0;">' +
                    '<i class="fas ' + (blockIcons[block.type] || 'fa-cube') + ' text-primary" style="font-size:0.85rem;"></i>' +
                '</span>' +
                '<span class="fw-semibold small">' + block.label + '</span>' +
                '<span class="ms-auto badge bg-light text-secondary border" style="font-size:0.65rem;">Thứ tự ' + (block.order + 1) + '</span>' +
            '</label>';
        }).join('');

        count.textContent = post.blocks.length + ' block';
        list.classList.remove('d-none');
    };

    window.copyBlockSelectAll = function (checked) {
        document.querySelectorAll('.copy-block-chk').forEach(c => c.checked = checked);
        copyBlockUpdateBtn();
    };

    window.copyBlockUpdateBtn = function () {
        var any = document.querySelectorAll('.copy-block-chk:checked').length > 0;
        document.getElementById('copyBlock-submitBtn').disabled = !any;
    };

    window.copyBlockSubmit = function () {
        var checked = Array.from(document.querySelectorAll('.copy-block-chk:checked')).map(c => c.value);
        if (!checked.length) return;

        var btn = document.getElementById('copyBlock-submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang sao chép...';

        fetch(_copyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': _csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ block_ids: checked }),
        })
        .then(r => r.json())
        .then(function (data) {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('copyBlockModal')).hide();
                // Reload page to show new blocks in Alpine editor
                window.location.reload();
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-copy me-1"></i>Sao chép vào trang này';
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        });
    };
})();
</script>
