<div x-data="orderDetail()" @open-order-detail.window="openModal($event.detail.id)">
    <!-- Modal Chi tiết đơn hàng -->
    <div class="modal fade" id="orderDetailModal">
        <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 1200px;">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden" style="background-color: #f8f9fa;">
                <div class="modal-header border-bottom-0 px-4 py-3 text-white" style="background-color: #0f172a;">
                    <div>
                        <h5 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 35px; height: 35px;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            Chi tiết Đơn hàng: <span x-text="order.order_code || 'Đang tải...'"></span>
                        </h5>
                        <div class="ms-5 small text-light" style="opacity: 0.8;">
                            Học viên: <span x-text="order.customer?.name || '---'"></span> &bull;
                            Ngày tạo: <span x-text="formatDate(order.created_at)"></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body p-4 text-center">
                                    <h6 class="fw-bold text-muted small text-uppercase mb-4 text-start">THÔNG TIN HỒ SƠ
                                    </h6>
                                    <div class="bg-primary text-white rounded-4 d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                                        {{ substr($order->customer->name ?? 'H', 0, 1) }}
                                    </div>
                                    <h5 class="fw-bold mb-1"><span x-text="order.customer?.name || '---'"></span></h5>
                                    <div class="text-primary fw-bold mb-4">SĐT:
                                        <span x-text="order.customer?.phone || '---'"></span>
                                    </div>

                                    <div class="text-start">
                                        <div class="d-flex align-items-center mb-2 text-muted small">
                                            <i class="far fa-envelope me-2" style="width: 16px;"></i>
                                            <span x-text="order.customer?.email || '---'"></span>
                                        </div>
                                        <div class="d-flex align-items-start text-muted small">
                                            <i class="fas fa-map-marker-alt me-2 mt-1" style="width: 16px;"></i>
                                            <span x-text="order.customer?.street_address || '---'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 rounded-4"
                                style="background: linear-gradient(135deg, #5b50d6 0%, #463bbf 100%); color: white;">
                                <div class="card-body p-4">
                                    <h6 class="small text-uppercase mb-2 text-white-50 fw-bold">CỘNG TÁC VIÊN NGUỒN</h6>
                                    <h5 class="fw-bold mb-1" x-text="order.referrer?.name || '---'"></h5>
                                    <h4 class="fw-bold mb-0" x-text="formatMoney(order.service?.commission)">
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm rounded-4 text-center h-100 p-3">
                                        <h6 class="text-muted small text-uppercase fw-bold mb-2">HỢP ĐỒNG</h6>
                                        <h4 class="fw-bold mb-0 text-dark" x-text="formatMoney(meta.total_contract)">
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm rounded-4 text-center h-100 p-3"
                                        style="background-color: #e8f7ed;">
                                        <h6 class="text-success small text-uppercase fw-bold mb-2">ĐÃ NỘP</h6>
                                        <h4 class="fw-bold mb-0 text-success" x-text="formatMoney(meta.total_paid)">
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm rounded-4 text-center h-100 p-3"
                                        style="background-color: #fcebeb;">
                                        <h6 class="text-danger small text-uppercase fw-bold mb-2">DƯ NỢ</h6>
                                        <h4 class="fw-bold mb-0 text-danger" x-text="formatMoney(meta.balance)">
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div
                                        class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold text-muted small text-uppercase mb-0">
                                            <i class="fas fa-history me-2"></i>LỊCH SỬ THANH TOÁN
                                        </h6>
                                        @can('create_payment_receipt')
                                            <button
                                                class="btn btn-sm text-white rounded-pill px-3 fw-bold shadow-sm bg-primary"
                                                @click="openReceiptModal(order.id)">Thu
                                                phí</button>
                                        @endcan
                                    </div>
                                    <div class="card-body p-4 pt-3">
                                        @can('view_payment_receipt')
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead>
                                                        <tr class="text-muted small text-uppercase"
                                                            style="border-bottom: 1px solid #eee;">
                                                            <th class="ps-0 pb-3 font-weight-normal">Ngày</th>
                                                            <th class="pb-3 font-weight-normal">Số tiền</th>
                                                            <th class="pb-3 text-center font-weight-normal">Loại
                                                            </th>
                                                            <th class="text-end pe-0 pb-3 font-weight-normal">Tác vụ
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="payment in order.payments" :key="payment.id">
                                                            <tr style="border-bottom: 1px solid #f5f5f5;">
                                                                <td class="ps-0 py-3 text-muted small"
                                                                    x-text="formatDate(payment.created_at)"></td>
                                                                <td class="py-3 fw-bold text-dark"
                                                                    x-text="formatMoney(payment.amount)"></td>
                                                                <td class="py-3 text-center">
                                                                    <span
                                                                        class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-normal"
                                                                        x-text="formatPaymentMethod(payment.payment_method)"></span>
                                                                </td>
                                                                <td class="text-end pe-0 py-3">
                                                                    @if (auth()->user() && auth()->user()->can('update_payment_receipt'))
                                                                        <button @click="openEditReceiptModal(payment)"
                                                                            class="btn btn-sm btn-link text-muted p-1"><i
                                                                                class="far fa-edit"></i></button>
                                                                    @else
                                                                        <button
                                                                            class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                                            style="width: 32px; height: 32px;" disabled
                                                                            title="Mặc định hệ thống">
                                                                            <i class="fas fa-lock"
                                                                                style="font-size: 0.8rem;"></i>
                                                                        </button>
                                                                    @endif
                                                                    <button class="btn btn-sm btn-link text-muted p-1"><i
                                                                            class="fas fa-print"></i></button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div
                                    class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-muted small text-uppercase mb-0">
                                        <i class="fas fa-paperclip me-2"></i>HỒ SƠ ĐÍNH KÈM
                                    </h6>
                                    <button class="btn btn-sm btn-light text-primary rounded-circle"
                                        style="width: 30px; height: 30px; display:flex; align-items:center; justify-content:center;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="card-body p-4 pt-4">
                                    <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 border"
                                        style="border-color: #f0f0f0 !important;">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">Hợp đồng du
                                                học có
                                                dấu đỏ</div>
                                            <div class="small text-muted">Tải lên: 2024-05-10</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center p-3 bg-light rounded-3 border"
                                        style="border-color: #f0f0f0 !important;">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">CMND Mặt
                                                trước</div>
                                            <div class="small text-muted">Tải lên: 2024-05-10</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 py-3 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold"
                        data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn text-white rounded-pill px-4 fw-bold"
                        style="background-color: #0f172a;">
                        <i class="fas fa-print me-2"></i> In hồ sơ đơn hàng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tạo Phiếu Thu Mới -->
    <div class="modal fade" id="createReceiptModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-bottom-0 px-4 py-3 pb-3" style="background-color: #059669;">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center mb-0"
                        style="font-size: 1.15rem;">
                        <i class="fas fa-wallet me-2"></i> Tạo Phiếu Thu Mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white opacity-100" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form @submit.prevent="saveReceipt()">
                        <div class="bg-success
                        bg-opacity-10 rounded-3 p-3 mb-4 d-flex justify-content-between align-items-center"
                            style="background-color: #f0fdf4 !important;">
                            <div>
                                <div class="small fw-bold text-success text-uppercase mb-1"
                                    style="font-size: 0.75rem; letter-spacing: 0.5px; color: #059669 !important;">
                                    Dư nợ
                                    hiện tại</div>
                                <h3 class="fw-bold text-success mb-0"
                                    style="font-size: 1.5rem; color: #059669 !important;"
                                    x-text="formatMoney(meta.balance)">
                                </h3>
                            </div>
                            <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center text-success fs-3"
                                style="width: 48px; height: 48px; color: #059669 !important;">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                    style="font-size: 0.75rem; color: #64748b !important;">Ngày thu</label>
                                <input x-model="payment.payment_date" type="date"
                                    class="form-control rounded-3 py-2 px-3 fw-medium text-dark"
                                    value="{{ date('Y-m-d') }}" style="border: 1px solid #e2e8f0;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                    style="font-size: 0.75rem; color: #64748b !important;">Hình thức</label>
                                <select x-model="payment.paymentMethod"
                                    class="form-select rounded-3 py-2 px-3 fw-medium text-dark"
                                    style="border: 1px solid #e2e8f0;">
                                    <option value="bank_transfer" selected>Chuyển khoản</option>
                                    <option value="cash">Tiền mặt</option>
                                    <option value="installment">Trả góp</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                style="font-size: 0.75rem; color: #64748b !important;">Số tiền thu (VNĐ) <span
                                    style="color: #64748b;">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" x-model="payment.amount" required
                                    @input="formatInputAmount($event)" placeholder="Vd: 50,000,000">
                                <span class="input-group-text fw-bold text-muted">VNĐ</span>
                            </div>
                            <input type="hidden" name="paymentAmount" :value="payment.rawAmount">
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                style="font-size: 0.75rem; color: #64748b !important;">Ghi chú</label>
                            <textarea x-model="payment.note" class="form-control rounded-3 p-3 text-dark fw-medium" rows="3"
                                placeholder="Nội dung thu" style="border: 1px solid #e2e8f0; resize: none;"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <button type="button" class="btn fw-bold text-secondary px-4 py-2"
                                data-bs-dismiss="modal"
                                style="background: transparent; border: none; font-size: 0.95rem; color: #64748b !important;">Hủy</button>
                            <button type="submit"
                                class="btn text-white fw-bold rounded-2 px-4 py-2 shadow-sm d-flex align-items-center"
                                style="background-color: #059669; font-size: 0.95rem;">
                                <i class="far fa-save me-2"></i> Lưu Phiếu Thu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Phiếu Thu -->
    <div class="modal fade" id="editReceiptModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-bottom-0 px-4 py-3 pb-3" style="background-color: #059669;">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center mb-0"
                        style="font-size: 1.15rem;">
                        <i class="fas fa-edit me-2"></i> Chỉnh Sửa Phiếu Thu
                    </h5>
                    <button type="button" class="btn-close btn-close-white opacity-100" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form @submit.prevent="updateReceipt()">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                    style="font-size: 0.75rem; color: #64748b !important;">Ngày thu</label>
                                <input x-model="editPayment.payment_date" type="date"
                                    class="form-control rounded-3 py-2 px-3 fw-medium text-dark"
                                    style="border: 1px solid #e2e8f0;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                    style="font-size: 0.75rem; color: #64748b !important;">Hình thức</label>
                                <select x-model="editPayment.paymentMethod"
                                    class="form-select rounded-3 py-2 px-3 fw-medium text-dark"
                                    style="border: 1px solid #e2e8f0;">
                                    <option value="bank_transfer">Chuyển khoản</option>
                                    <option value="cash">Tiền mặt</option>
                                    <option value="installment">Trả góp</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                style="font-size: 0.75rem; color: #64748b !important;">Số tiền thu (VNĐ) <span
                                    style="color: #64748b;">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" x-model="editPayment.amount" required
                                    @input="formatEditInputAmount($event)" placeholder="Vd: 50,000,000">
                                <span class="input-group-text fw-bold text-muted">VNĐ</span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-secondary text-uppercase mb-2"
                                style="font-size: 0.75rem; color: #64748b !important;">Ghi chú</label>
                            <textarea x-model="editPayment.note" class="form-control rounded-3 p-3 text-dark fw-medium" rows="3"
                                placeholder="Nội dung thu" style="border: 1px solid #e2e8f0; resize: none;"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <button type="button" class="btn fw-bold text-secondary px-4 py-2"
                                data-bs-dismiss="modal"
                                style="background: transparent; border: none; font-size: 0.95rem; color: #64748b !important;">Hủy</button>
                            <button type="submit"
                                class="btn text-white fw-bold rounded-2 px-4 py-2 shadow-sm d-flex align-items-center"
                                style="background-color: #059669; font-size: 0.95rem;">
                                <i class="far fa-save me-2"></i> Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function orderDetail() {
        return {
            order: {},
            meta: {},
            payment: {
                amount: "",
                rawAmount: "",
                payment_date: new Date().toISOString().slice(0, 10),
                paymentMethod: "cash",
                note: ""
            },
            editPayment: {
                id: null,
                amount: "",
                rawAmount: "",
                payment_date: "",
                paymentMethod: "",
                note: ""
            },
            async openModal(id) {
                this.isLoading = true;
                this.order = {};

                try {
                    const response = await fetch(`/admin/orders/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const result = await response.json();

                    this.order = result.data;
                    this.meta = result.meta;

                    const modalElement = document.getElementById('orderDetailModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.show();

                } catch (error) {
                    console.error("Lỗi khi tải đơn hàng:", error);
                } finally {
                    this.isLoading = false;
                }
            },

            openReceiptModal(orderId) {
                let fillAmount = this.meta.balance || 0;
                this.payment.rawAmount = fillAmount;
                this.payment.amount = Number(fillAmount).toLocaleString('vi-VN');

                const receiptModalElement = document.getElementById('createReceiptModal');
                const modal = bootstrap.Modal.getOrCreateInstance(receiptModalElement);

                modal.show();

                setTimeout(() => {
                    receiptModalElement.style.zIndex = "1060";
                    document.querySelectorAll('.modal-backdrop').forEach((backdrop, index) => {
                        if (index > 0) backdrop.style.zIndex = "1055";
                    });
                }, 0);
            },
            openEditReceiptModal(payment) {
                this.editPayment.id = payment.id;
                this.editPayment.rawAmount = payment.amount;
                this.editPayment.amount = Number(payment.amount).toLocaleString('vi-VN');
                this.editPayment.payment_date = payment.payment_date ? payment.payment_date.split(' ')[0].split('T')[
                    0] : '';
                this.editPayment.paymentMethod = payment.payment_method;
                this.editPayment.note = payment.note || "";

                const editReceiptModalElement = document.getElementById('editReceiptModal');
                const modal = bootstrap.Modal.getOrCreateInstance(editReceiptModalElement);
                modal.show();

                setTimeout(() => {
                    editReceiptModalElement.style.zIndex = "1060";
                    document.querySelectorAll('.modal-backdrop').forEach((backdrop, index) => {
                        if (index > 0) backdrop.style.zIndex = "1055";
                    });
                }, 0);
            },

            async saveReceipt() {
                try {
                    const response = await fetch('/admin/payment-receipts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: this.order.id,
                            amount: this.payment.rawAmount,
                            payment_method: this.payment.paymentMethod,
                            payment_date: this.payment.payment_date,
                            note: this.payment.note
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        this.order.payments.unshift(result.payment);
                        const newAmount = Number(result.payment.amount);
                        this.meta.total_paid = Number(this.meta.total_paid) + newAmount;
                        this.meta.balance = Number(this.meta.balance) - newAmount;

                        if (result.status_updated) {
                            this.order.status = result.new_status || 'completed';
                            setTimeout(() => {
                                window.location.reload();
                            }, 700);
                        }

                        const modalElement = document.getElementById('createReceiptModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) modalInstance.hide();
                        //reset form 
                        this.payment = {
                            amount: "",
                            rawAmount: "",
                            payment_date: new Date().toISOString().slice(0, 10),
                            paymentMethod: "bank_transfer",
                            note: ""
                        };
                    } else {
                        console.log("Lỗi: " + result.message);
                    }
                } catch (error) {
                    console.error("Lỗi kết nối API:", error);
                }
            },

            formatEditInputAmount(e) {
                let val = e.target.value.replace(/\D/g, '');
                this.editPayment.rawAmount = val;
                if (val) {
                    this.editPayment.amount = Number(val).toLocaleString('vi-VN');
                } else {
                    this.editPayment.amount = '';
                }
            },

            async updateReceipt() {
                try {
                    const response = await fetch(`/admin/payment-receipts/${this.editPayment.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            amount: this.editPayment.rawAmount,
                            payment_method: this.editPayment.paymentMethod,
                            payment_date: this.editPayment.payment_date,
                            note: this.editPayment.note
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        const index = this.order.payments.findIndex(p => p.id === this.editPayment.id);
                        if (index !== -1) {
                            this.order.payments[index] = result.payment;
                        }

                        const diff = result.amount_diff || 0;
                        this.meta.total_paid = Number(this.meta.total_paid) + diff;
                        this.meta.balance = Number(this.meta.balance) - diff;

                        if (result.status_updated) {
                            this.order.status = result.new_status || 'completed';
                            setTimeout(() => {
                                window.location.reload();
                            }, 700);
                        }

                        const modalElement = document.getElementById('editReceiptModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) modalInstance.hide();
                    } else {
                        console.log("Lỗi: " + result.message);
                    }
                } catch (error) {
                    console.error("Lỗi kết nối API:", error);
                }
            },

            formatPaymentMethod(method) {
                const methods = {
                    'bank_transfer': 'Chuyển khoản',
                    'cash': 'Tiền mặt',
                    'installment': 'Trả góp'
                };
                return methods[method] || method;
            },

            formatDate(dateString) {
                if (!dateString) return '---';
                return new Date(dateString).toLocaleDateString('vi-VN');
            },

            formatMoney(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount || 0);
            },

            formatInputAmount(e) {
                let val = e.target.value.replace(/\D/g, '');
                this.payment.rawAmount = val;

                // Add commas
                if (val) {
                    this.payment.amount = Number(val).toLocaleString('vi-VN');
                } else {
                    this.payment.amount = '';
                }
            },
        }
    }
</script>
