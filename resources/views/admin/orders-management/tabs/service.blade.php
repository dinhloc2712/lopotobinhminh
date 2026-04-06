@include('admin.orders-management.modal.modal-create-service')

@php
    function formatMoney($amount)
    {
        return number_format($amount, 0, ',', '.') . 'đ';
    }

    function mapCategoryName($key)
    {
        $mapping = [
            'studyAbroad' => 'Du học',
            'job' => 'XKLĐ',
            'language' => 'Ngôn ngữ',
            'tourism' => 'Du lịch',
        ];
        $name = $mapping[$key] ?? $key;

        return mb_strtoupper($name, 'UTF-8');
    }
@endphp

<div class="tech-card h-100">
    <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fa-solid fa-box-open me-2 bg-white bg-opacity-25 rounded-circle p-2"
                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                Danh sách Gói dịch vụ
            </h6>

            <form method="GET" action="{{ url('admin/orders') }}" class="d-flex align-items-center flex-wrap gap-2">
                <input type="hidden" name="tab" value="service">

                {{-- Category filter --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Danh mục</small>
                    <select name="category"
                        class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                        style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="" {{ request('category') == '' ? 'selected' : '' }}>Tất cả</option>
                        <option value="studyAbroad" {{ request('category') == 'studyAbroad' ? 'selected' : '' }}>Du học
                        </option>
                        <option value="job" {{ request('category') == 'job' ? 'selected' : '' }}>XKLD</option>
                        <option value="language" {{ request('category') == 'language' ? 'selected' : '' }}>Ngôn ngữ
                        </option>
                        <option value="tourism" {{ request('category') == 'tourism' ? 'selected' : '' }}>Du lịch
                        </option>
                    </select>
                </div>

                {{-- per page --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                    <select name="per_page"
                        class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4"
                        style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3"
                            style="z-index: 5;"></i>
                        <input type="text" name="search"
                            class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2"
                            placeholder="Tìm tên gói dịch vụ..." value="{{ request('search') }}">
                    </div>
                </div>
                @can('create_services')
                    <a href="javascript:void(0)"
                        class="text-white fw-bold px-2 text-decoration-none d-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#createServiceModal">
                        <i class="fas fa-plus me-1"></i> Thêm mới
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
                        <th class="ps-4" style="width: 50px;">ID</th>
                        <th>Danh mục</th>
                        <th>Tên gói</th>
                        <th>Giá gói</th>
                        <th>Hoa hồng</th>
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($services) && $services->count() > 0)
                        @foreach ($services as $service)
                            <tr x-data="serviceCard({{ Js::from($service) }})">
                                <td class="ps-4 fw-bold text-dark">{{ $service->id }}</td>
                                <td>
                                    <span
                                        class="badge badge-tech text-primary bg-primary bg-opacity-10 py-2 px-3 rounded-pill"
                                        style="font-weight: 600; font-size: 0.75rem;">
                                        <?php echo mapCategoryName($service['category']); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-dark" style="color: #1a3a8a;">{{ $service->name }}</td>
                                <td class="fw-bold">{{ formatMoney($service['amount']) }}</td>
                                <td class="fw-bold text-warning">{{ formatMoney($service['commission']) }}</td>
                                <td class="text-end pe-4">
                                    @if (auth()->user() && auth()->user()->can('delete_services'))
                                        <button @click="editService"
                                            class="btn btn-sm btn-outline-info rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                                            style="width: 32px; height: 32px;" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @else
                                        <button
                                            class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" disabled title="Mặc định hệ thống">
                                            <i class="fas fa-lock" style="font-size: 0.8rem;"></i>
                                        </button>
                                    @endif
                                    @if (auth()->user() && auth()->user()->can('delete_services'))
                                        <button @click="deleteService"
                                            class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <button
                                            class="btn btn-sm btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" disabled title="Mặc định hệ thống">
                                            <i class="fas fa-lock" style="font-size: 0.8rem;"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-light rounded-circle p-4 mb-3">
                                        <i class="fas fa-box-open fa-3x text-secondary"></i>
                                    </div>
                                    <h6 class="text-muted fw-bold">Không tìm thấy gói dịch vụ nào</h6>
                                    <p class="text-muted small mb-0">Thử thay đổi bộ lọc hoặc thêm gói mới.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ method_exists($services ?? null, 'links') ? $services->links() : '' }}
        </div>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('serviceCard', (serviceCardData) => ({
                item: serviceCardData,

                deleteService() {
                    Swal.fire({
                        title: `Xóa dịch vụ "${this.item.name}"?`,
                        html: `<p class="text-muted mb-0">Toàn bộ dữ liệu của dịch vụ này sẽ bị xoá<br><strong class="text-danger">Hành động không thể hoàn tác!</strong></p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i>Xóa quy trình',
                        cancelButtonText: 'Hủy',
                        reverseButtons: true,
                        preConfirm: () => {
                            return fetch(
                                    `/admin/service/${this.item.id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content'),
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json'
                                        }
                                    })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Không thể xóa dịch vụ này');
                                    }
                                    return response.json();
                                })
                                .catch(error => {
                                    Swal.showValidationMessage(`Lỗi: ${error.message}`);
                                });
                        },
                        allowOutsideClick: () => !Swal.isLoading(),
                    }).then((result) => {
                        if (result.isConfirmed && result.value.status === 'success') {
                            Swal.fire({
                                title: 'Đã xóa!',
                                text: result.value.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                },

                editService() {
                    this.$dispatch('fill-form', this.item);
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                        'createServiceModal'));
                    modal.show();
                },
            }))
        })
    </script>
@endsection
