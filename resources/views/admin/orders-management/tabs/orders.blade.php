@include('admin.orders-management.modal.order-management-modal')
<div class="tech-card h-100">
    <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fa-solid fa-warehouse me-2 bg-white bg-opacity-25 rounded-circle p-2"
                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                {{-- Đơn hàng --}}
            </h6>

            <form method="GET" action="{{ route('admin.orders.index') }}"
                class="d-flex align-items-center flex-wrap gap-2">
                <input type="hidden" name="tab" value="orders">
                {{-- Time filter --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Thời gian</small>
                    <select name="search_time"
                        class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                        style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="all" {{ request('search_time') == 'all' ? 'selected' : '' }}>Tất cả
                        </option>
                        <option value="month" {{ request('search_time') == 'month' ? 'selected' : '' }}>Tháng này
                        </option>
                        <option value="quarter" {{ request('search_time') == 'quarter' ? 'selected' : '' }}>Quý hiện tại
                        </option>
                        <option value="year" {{ request('search_time') == 'year' ? 'selected' : '' }}>Năm nay
                        </option>
                    </select>
                </div>

                {{-- Status filter --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Trạng thái</small>
                    <select name="search_status"
                        class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                        style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="all" {{ request('search_status') == 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="pending" {{ request('search_status') == 'pending' ? 'selected' : '' }}>Đang Xử lý
                        </option>
                        <option value="completed" {{ request('search_status') == 'completed' ? 'selected' : '' }}>Hoàn
                            tất
                        </option>
                        <option value="cancelled" {{ request('search_status') == 'cancelled' ? 'selected' : '' }}>Đã Huỷ
                        </option>
                    </select>
                </div>

                {{-- Per Page --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                    <select name="per_page_order"
                        class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                        style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="20" {{ request('per_page_order') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page_order') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page_order') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3"
                            style="z-index: 5;"></i>
                        <input type="text" name="search_order"
                            class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2"
                            placeholder="Tìm mã đơn, tên học viên..." value="{{ request('search_order') }}">
                    </div>
                </div>
                @can('create_orders')
                    <a href="javascript:void(0)"
                        class="text-white fw-bold px-2 text-decoration-none d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#createOrderModal">
                        <i class="fas fa-plus me-1"></i> Tạo đơn mới
                    </a>
                @endcan
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <x-admin.table-header class="ps-3" key="order_code" label="Mã đơn & Ngày" :sortColumn="$sortColumn"
                            :sortOrder="$sortOrder" />
                        <th>Khách hàng/Học Viên</th>
                        <th>Gói dịch vụ</th>
                        <th>Thanh toán</th>
                        <th>CTV / Hoa hồng</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                        <tr>
                            {{-- Mã đơn & Ngày --}}
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $order->order_code }}</div>
                                <div class="small text-muted">{{ $order->created_at->format('d/m/Y') }}</div>
                            </td>

                            {{-- Khách hàng / Học viên --}}
                            <td>
                                @if ($order->customer)
                                    <div class="fw-bold text-dark">{{ $order->customer->name }}</div>
                                    <div class="small text-muted">{{ $order->customer->phone ?? '—' }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Gói dịch vụ --}}
                            <td>
                                @if ($order->service)
                                    <div class="fw-bold"><i
                                            class="fas fa-tag me-1 text-primary"></i>{{ $order->service->name }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Thanh toán --}}
                            <td>
                                @if ($order->service)
                                    <div class="fw-bold text-dark">{{ number_format($order->service->amount) }}đ</div>
                                    <span class="small text-success fw-bold">Đã nộp:
                                        {{ number_format($order->payments->sum('amount') ?? 0) }}đ</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- CTV / Hoa hồng --}}
                            <td>
                                @if ($order->referrer)
                                    <div class="fw-bold text-dark">{{ $order->referrer->name }}</div>
                                    @if ($order->service && $order->service->commission)
                                        <div class="small text-warning fw-bold"><span class="fst-italic">HH:
                                                {{ number_format($order->service->commission) }}đ</span></div>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Trạng thái --}}
                            <td>
                                @php
                                    $statusMap = [
                                        'pending' => [
                                            'label' => 'Đang xử lý',
                                            'class' => 'bg-info bg-opacity-10 text-info',
                                        ],
                                        'completed' => [
                                            'label' => 'Hoàn tất',
                                            'class' => 'bg-success bg-opacity-10 text-success',
                                        ],
                                        'cancelled' => [
                                            'label' => 'Đã huỷ',
                                            'class' => 'bg-danger bg-opacity-10 text-danger',
                                        ],
                                    ];
                                    $s = $statusMap[$order->status] ?? [
                                        'label' => $order->status ?? 'N/A',
                                        'class' => 'bg-secondary text-white',
                                    ];
                                @endphp
                                <span
                                    class="badge rounded-pill px-3 py-2 {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>

                            {{-- Tác vụ --}}
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                    title="Quản lý đơn hàng"
                                    @click="$dispatch('open-order-detail', { id: {{ $order->id }} })">
                                    Quản lý
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-light rounded-circle p-4 mb-3">
                                        <i class="fas fa-file-alt fa-3x text-secondary opacity-50"></i>
                                    </div>
                                    <h6 class="text-muted fw-bold">Chưa có đơn hàng nào</h6>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach ($orders ?? [] as $order)
    @include('admin.orders-management.modal.order-management-modal', ['order' => $order])
@endforeach

<!-- Modal Thêm đơn hàng Làm -->
<div class="modal fade" id="createOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 px-4 py-4"
                style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                <h5 class="modal-title fw-bold text-white mb-1" id="orderModalTitle"><i
                        class="fas fa-plus me-1"></i>Tạo Đơn Hàng Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="orderForm" action="{{ route('admin.orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="is_new_user" :value="isNewUser">
                    <div class="row g-4 mb-2">
                        <div class="col-md-6 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-muted small text-uppercase mb-0">
                                <i class="fas fa-user me-1"></i> Thông tin khách hàng
                            </h6>

                            <div class="bg-light p-0 d-flex rounded-2 align-items-center shadow-sm border"
                                style="font-size:12px;">
                                <button type="button" @click="isNewUser = 'false'"
                                    :class="isNewUser === 'false' ? 'bg-white shadow-sm text-primary' : 'text-muted'"
                                    class="btn px-2 py-1 border-0 fw-bold">
                                    Từ Kho Lead
                                </button>
                                <button type="button" @click="isNewUser = 'true'"
                                    :class="isNewUser === 'true' ? 'bg-white shadow-sm text-primary' : 'text-muted'"
                                    class="btn px-2 py-1 border-0 fw-bold">
                                    Khách Mới
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted small text-uppercase mb-0">
                                <i class="fas fa-suitcase me-1"></i> Dịch vụ & tài chính
                            </h6>
                        </div>
                    </div>


                    <div class="row g-4">
                        <div class="col-md-6" x-data="{
                            customers: @js($customers ?? []),
                            selectedCustomerId: '',
                            get selectedCustomer() {
                                return this.customers.find(s => s.id == this.selectedCustomerId) || null;
                            }
                        }">
                            <div class="mb-3 bg-primary bg-opacity-10 rounded-3 p-3 border"
                                x-show="isNewUser === 'false'" style="display: none;">
                                <h6 class="fw-bold text-primary mb-3">Chọn Lead tiềm năng</h6>
                                <select name="customer_id" id="select-customer" class="form-select"
                                    @change="loadCustomer($event.target.value)" x-model="selectedCustomerId"
                                    :required="isNewUser === 'false'">
                                    <option value="">-- Chọn khách hàng --</option>
                                    @foreach ($customers ?? [] as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }}
                                            @if ($customer->code)
                                                ({{ $customer->code }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-light-primary rounded-3 p-3 border">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Họ tên học viên <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="new_customer_name" class="form-control"
                                        :disabled="isNewUser === 'false'"
                                        :value="selectedCustomer ? selectedCustomer.name : ''" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Số điện thoại <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="new_customer_phone" class="form-control"
                                        :disabled="isNewUser === 'false'"
                                        :value="selectedCustomer ? selectedCustomer.phone : ''" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="new_customer_email" class="form-control"
                                        :disabled="isNewUser === 'false'"
                                        :value="selectedCustomer ? selectedCustomer.email : ''" required>
                                </div>

                                <div>
                                    <label class="form-label small fw-bold text-muted">Địa chỉ</label>
                                    <textarea class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" x-data="{
                            services: @js($allServices ?? []),
                            selectedServiceId: '',
                            get selectedService() {
                                return this.services.find(s => s.id == this.selectedServiceId) || null;
                            }
                        }">
                            <div class="bg-light-primary rounded-3 p-3 border mb-3">
                                <label class="form-label small fw-bold text-muted">Gói dịch vụ đăng ký <span
                                        class="text-danger">*</span></label>

                                <select name="service_id" class="form-select mb-3" x-model="selectedServiceId"
                                    required>
                                    <option value="">-- Chọn gói dịch vụ --</option>
                                    @foreach ($allServices ?? [] as $service)
                                        <option value="{{ $service->id }}">
                                            {{ $service->name }}
                                            @if ($service->amount)
                                                — {{ number_format($service->amount) }}đ
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">
                                            Giá trị hợp đồng (VNĐ)
                                        </label>
                                        <input type="number" name="contract_amount" class="form-control fw-bold"
                                            placeholder="0"
                                            :value="selectedService ? Math.round(selectedService.amount) : ''">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">
                                            Hoa hồng CTV (VNĐ)
                                        </label>
                                        <input type="number" name="commission_amount"
                                            class="form-control bg-warning bg-opacity-10 text-warning fw-bold"
                                            placeholder="0"
                                            :value="selectedService ? Math.round(selectedService.commission) : ''">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 border">
                                <h6 class="fw-bold text-primary mb-2">
                                    Người giới thiệu / Thụ hưởng
                                </h6>

                                <label class="form-label small text-primary">
                                    Gán cho CTV/Nhân viên
                                </label>

                                <select name="referrer_id" class="form-select">
                                    <option value="">-- Chọn người giới thiệu --</option>
                                    @foreach ($referrers ?? [] as $referrer)
                                        <option value="{{ $referrer->id }}">
                                            {{ $referrer->name }}
                                            @if ($referrer->code)
                                                ({{ $referrer->code }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="fas fa-save me-1"></i> Lưu Đơn Hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
