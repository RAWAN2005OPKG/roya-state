@extends('layouts.container')
@section('title', 'سجل وليد الخالص')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">سجل حركات وليد الخالص
                <span class="d-block text-muted pt-2 font-size-sm">عرض كل الحركات المالية المسجلة</span>
            </h3>
        </div>
        <div class="card-toolbar">
          <a href="{{ route('dashboard.waleed-transactions.trash') }}" class="btn btn-danger font-weight-bolder mr-2">
        <i class="fas fa-trash"></i> سلة المحذوفات
    </a>  <a href="{{ route('dashboard.waleed-transactions.create') }}" class="btn btn-primary font-weight-bolder">
                <i class="fas fa-plus"></i> إضافة حركة جديدة
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-head-custom table-hover">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>قيمة الدفعة (شيكل)</th>
                        <th>قيمة الدفعة (دولار)</th>
                        <th>من (دفع ليد)</th>
                        <th>صرف لمين</th>
                        <th>بيانات المصاريف</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->date }}</td>
                            <td>{{ $transaction->amount_shekel ? number_format($transaction->amount_shekel, 2) : '-' }}</td>
                            <td>{{ $transaction->amount_dollar ? number_format($transaction->amount_dollar, 2) : '-' }}</td>
                            <td>{{ $transaction->paid_by }}</td>
                            <td>{{ $transaction->paid_to }}</td>
                            <td>{{ $transaction->expense_details }}</td>
                            <td>{{ $transaction->notes }}</td>
                            {{-- >>== أزرار التحكم الجديدة ==<< --}}
                            <td nowrap="nowrap">
                                <a href="{{ route('dashboard.waleed-transactions.edit', $transaction->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="delete-form-{{ $transaction->id }}" action="{{ route('dashboard.waleed-transactions.destroy', $transaction->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-clean btn-icon" title="حذف" onclick="confirmDelete({{ $transaction->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center p-5 text-muted">لا توجد حركات لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
=<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id ) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F64E60',
            cancelButtonColor: '#3699FF',
            confirmButtonText: 'نعم، احذفها!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
