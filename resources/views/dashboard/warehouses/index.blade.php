@extends('layouts.container')
@section('title', 'المستودعات')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-warehouse"></i> المستودعات</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.warehouses.trash.index') }}" class="btn btn-danger"><i class="fas fa-trash"></i> سلة المحذوفات</a>
            <button id="addWarehouseBtn" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مستودع جديد</button>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">إجمالي المستودعات</div><div class="value">{{ $totalCount }}</div></div>
        <div class="kpi-card"><div class="label">المستودعات النشطة</div><div class="value">{{ $activeCount }}</div></div>
        <div class="kpi-card"><div class="label">المستودعات غير النشطة</div><div class="value">{{ $inactiveCount }}</div></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="table-container">
        {{-- إضافة فورم البحث --}}
        <div class="table-controls">
            <form action="{{ route('dashboard.warehouses.index') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="ابحث بالاسم أو الموقع..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>اسم المستودع</th>
                        <th>الموقع / العنوان</th>
                        <th>الحالة</th>
                        <th class="no-print">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses as $warehouse)
                        <tr>
                            <td><strong>{{ $warehouse->name }}</strong></td>
                            <td>{{ $warehouse->location ?? '-' }}</td>
                            <td>
                                @if($warehouse->is_active)
                                    <span class="badge badge-success">نشط</span>
                                @else
                                    <span class="badge badge-danger">غير نشط</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                <button class="edit-btn" data-id="{{ $warehouse->id }}" data-name="{{ $warehouse->name }}" data-location="{{ $warehouse->location ?? '' }}" data-is_active="{{ $warehouse->is_active }}" title="تعديل"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('dashboard.warehouses.destroy', $warehouse->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟ سيتم نقل المستودع إلى سلة المحذوفات.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" title="حذف"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center" style="padding: 2rem;">
                            @if(request('search'))
                                لا توجد نتائج للبحث عن: "{{ request('search') }}"
                            @else
                                لم يتم إضافة أي مستودعات بعد.
                            @endif
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

@include('dashboard.warehouses.partials.modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('warehouseModal');
    const addBtn = document.getElementById('addWarehouseBtn');
    const closeBtns = document.querySelectorAll('.close-btn');
    const form = document.getElementById('warehouseForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');
    const nameInput = document.getElementById('name');
    const locationInput = document.getElementById('location');
    const activeSelect = document.getElementById('is_active');

    // دالة لفتح نافذة الإضافة
    function openAddModal() {
        form.reset();
        form.action = '{{ route('dashboard.warehouses.store') }}';
        formMethod.value = 'POST';
        modalTitle.textContent = 'إضافة مستودع جديد';
        activeSelect.value = '1'; // القيمة الافتراضية "نشط"
        modal.style.display = "block";
        nameInput.focus();
    }

    // دالة لفتح نافذة التعديل
    function openEditModal(button) {
        const id = button.dataset.id;
        form.action = `/dashboard/warehouses/${id}`;
        formMethod.value = 'PUT';
        modalTitle.textContent = 'تعديل المستودع';

        nameInput.value = button.dataset.name;
        locationInput.value = button.dataset.location;
        activeSelect.value = button.dataset.is_active;

        modal.style.display = "block";
        nameInput.focus();
    }

    // ربط الأحداث
    addBtn.addEventListener('click', openAddModal);

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => openEditModal(button));
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => modal.style.display = "none");
    });

    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});
</script>
@endpush
@endsection
