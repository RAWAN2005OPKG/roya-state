@extends('layouts.container')
@section('title', 'سلة محذوفات وليد الخالص')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">سلة المحذوفات
                <span class="d-block text-muted pt-2 font-size-sm">الحركات التي تم حذفها</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.waleed-transactions.index') }}" class="btn btn-light-primary font-weight-bolder">
                <i class="fas fa-arrow-left"></i> العودة للسجل
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
                        <th>من (دفع ليد)</th>
                        <th>صرف لمين</th>
                        <th>تاريخ الحذف</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trashedTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->date }}</td>
                            <td>{{ $transaction->paid_by }}</td>
                            <td>{{ $transaction->paid_to }}</td>
                            <td>{{ $transaction->deleted_at->format('Y-m-d H:i') }}</td>
                            <td nowrap="nowrap">
                                {{-- فورم الاسترجاع --}}
                                <form action="{{ route('dashboard.waleed-transactions.restore', $transaction->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="استرجاع"><i class="fas fa-undo"></i></button>
                                </form>
                                {{-- فورم الحذف النهائي --}}
                                <form id="force-delete-form-{{ $transaction->id }}" action="{{ route('dashboard.waleed-transactions.forceDelete', $transaction->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-clean btn-icon" title="حذف نهائي" onclick="confirmForceDelete({{ $transaction->id }})"><i class="fas fa-trash-alt text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center p-5 text-muted">سلة المحذوفات فارغة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmForceDelete(id ) {
        Swal.fire({
            title: 'هل أنت متأكد من الحذف النهائي؟',
            text: "لا يمكن التراجع عن هذا الإجراء إطلاقاً!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذفه نهائياً!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('force-delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
