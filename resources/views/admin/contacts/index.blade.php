@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
{{-- Breadcrumb Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 fw-bold">Quản lý Liên hệ</h1>
        <p class="mb-0 text-muted small">Danh sách khách hàng đăng ký thông tin liên hệ</p>
    </div>
</div>

<div class="tech-card h-100">
    <div class="tech-header" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); padding: 20px 25px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0 fw-bold text-white d-flex align-items-center">
                <i class="fas fa-address-book me-2 bg-white bg-opacity-25 rounded-circle p-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"></i>
                Danh sách Liên hệ
            </h6>

            <form method="GET" action="{{ route('admin.contacts.index') }}" class="d-flex align-items-center flex-wrap gap-2">
                {{-- Per Page --}}
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-2 shadow-sm">
                    <small class="text-muted fw-bold me-2 text-uppercase" style="font-size: 0.65rem;">Hiển thị</small>
                    <select name="per_page" class="form-select form-select-sm border-0 bg-transparent fw-bold text-dark py-0 pe-4" style="width: auto; box-shadow: none; cursor: pointer;" onchange="this.form.submit()">
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="bg-white rounded-pill shadow-sm" style="flex: 1; min-width: 200px; max-width: 300px;">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3" style="z-index: 5;"></i>
                        <input type="text" name="search" class="form-control form-select-sm border-0 bg-transparent rounded-pill ps-5 pe-3 py-2" placeholder="Tìm tên, email, sđt..." value="{{ request('search') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-light rounded-pill px-3 fw-bold shadow-sm">Tìm kiếm</button>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <x-admin.table-header key="name" label="Họ tên" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="phone_number" label="Số điện thoại" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <x-admin.table-header key="email" label="Email" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th>Địa chỉ</th>
                        <x-admin.table-header key="created_at" label="Ngày gửi" :sortColumn="$sortColumn" :sortOrder="$sortOrder" />
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td class="ps-4">{{ $contact->id }}</td>
                        <td class="fw-bold text-dark">{{ $contact->name }}</td>
                        <td>{{ $contact->phone_number }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($contact->address, 50) }}</td>
                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline-block" id="delete-form-{{ $contact->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Xóa" onclick="confirmDelete({{ $contact->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-light rounded-circle p-4 mb-3">
                                    <i class="fas fa-address-book fa-3x text-secondary"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Chưa có liên hệ nào</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-top">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Vâng, xóa nó!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection
