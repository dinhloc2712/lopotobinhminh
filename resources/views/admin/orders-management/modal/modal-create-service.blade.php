{{-- Create service modal --}}
<div class="modal fade" id="createServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 px-4 py-4"
                style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); ">
                <div>
                    <h5 id='service-modal-title' class="modal-title fw-bold text-white mb-1"><i
                            class="fas fa-plus-circle me-2"></i>Tạo Gói Dịch Vụ
                        Mới</h5>
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.service.store') }}" method="POST" enctype="multipart/form-data"
                    id="create-service-form" x-data="serviceForm()"
                    @fill-form.window="initFormUpdateModal($event.detail)">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Tên gói dịch vụ <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" x-model="name" class="form-control" required
                            placeholder="VD: Du học Nhật Bản kỳ tháng 4...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Danh mục <span
                                class="text-danger">*</span></label>
                        <select name="category" class="form-select" x-model="category" required>
                            <option value="studyAbroad">Du học (Study Abroad)</option>
                            <option value="job">Xuất khẩu lao động (Job)</option>
                            <option value="language">Đào tạo ngôn ngữ (Language)</option>
                            <option value="tourism">Du lịch (Tourism)</option>
                        </select>
                    </div>
                    <div class="mb-3 g-3 row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Giá niêm yết (VNĐ) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" x-model="amount" required
                                    @input="formatInputAmount($event, 'amount')" placeholder="Vd: 50,000,000">
                                <span class="input-group-text fw-bold text-muted">VNĐ</span>
                            </div>
                            <input type="hidden" name="amount" :value="rawAmount">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Hoa hồng CTV <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" x-model="commission" required
                                    @input="formatInputAmount($event, 'commission')" placeholder="Vd: 50,000,000">
                                <span class="input-group-text fw-bold text-muted">VNĐ</span>
                            </div>
                            <input type="hidden" name="commission" :value="rawCommission">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Thời lượng / Lộ trình</label>
                        <input type="text" name="duration" x-model="duration" class="form-control"
                            placeholder="VD: 6 tháng, 3 năm...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Mô tả chi tiết </label>
                        <textarea name="description" x-model="description" class="form-control" rows="3"
                            placeholder="Quyền lợi, yêu cầu..."></textarea>
                    </div>
                    {{-- btn --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" id="service-modal-submit-btn"
                            class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">Lưu gói
                            mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function serviceForm() {
        return {
            name: '',
            category: 'studyAbroad',
            amount: '',
            rawAmount: '',
            commission: '',
            rawCommission: '',
            duration: '',
            description: '',

            init() {
                const modalEl = document.getElementById('createServiceModal');
                if (modalEl) {
                    modalEl.addEventListener('hidden.bs.modal', () => {
                        this.resetForm();
                    });
                }
            },

            formatInputAmount(e, field) {
                let val = e.target.value.replace(/\D/g, '');
                let fieldName = 'raw' + field.charAt(0).toUpperCase() + field.slice(1);
                this[fieldName] = val;

                if (val) {
                    let formatted = Number(val).toLocaleString('vi-VN');
                    this[field] = formatted;
                } else {
                    this[field] = '';
                    e.target.value = '';
                }
            },

            initFormUpdateModal(data) {
                // fill data
                this.name = data.name;
                this.category = data.category;
                this.rawAmount = data.amount;
                this.rawCommission = data.commission;
                this.duration = data.duration;
                this.description = data.description;

                this.amount = Number(data.amount).toLocaleString('vi-VN');
                this.commission = Number(data.commission).toLocaleString('vi-VN');

                //update title and button modal
                const title = document.getElementById('service-modal-title');
                title.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Cấu hình gói dịch vụ';
                const submitBtn = document.getElementById('service-modal-submit-btn');
                submitBtn.innerHTML = 'Lưu cấu hình'

                //update form action and method
                const form = document.getElementById('create-service-form');
                form.action = `/admin/service/${data.id}`;

                const methodInput = document.getElementById('form-method');
                if (methodInput) {
                    methodInput.value = 'PUT';
                } else {
                    console.error("Không tìm thấy thẻ #form-method!");
                }
            },

            resetForm() {
                this.name = '';
                this.category = 'studyAbroad';
                this.amount = '';
                this.rawAmount = '';
                this.commission = '';
                this.rawCommission = '';
                this.duration = '';
                this.description = '';

                //reset title and button
                const title = document.getElementById('service-modal-title');
                title.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Tạo Gói Dịch Vụ Mới';
                const submitBtn = document.getElementById('service-modal-submit-btn');
                submitBtn.innerHTML = 'Lưu gói mới'

                //update form action and method
                const form = document.getElementById('create-service-form');
                form.action = `/admin/service`;

                const methodInput = document.getElementById('form-method');
                if (methodInput) {
                    methodInput.value = 'POST';
                }
            },
        }
    }
</script>
