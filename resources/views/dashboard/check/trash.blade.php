@extends('layouts.container')
@section('title', 'سلة مهملات الشيكات')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">سلة مهملات الشيكات</h3>
        <div class="card-toolbar"><a href="{{ route('dashboard.checks.index') }}" class="btn btn-secondary">العودة للقائمة</a></div>
    </div>
    <div class="card-body">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>رقم الشيك</th><th>الطرف</th><th>المبلغ</th><th>تاريخ الحذف</th><th>إجراءات</th></tr></thead>
                <tbody>
                    @forelse($trashedChecks as $check)
                    <tr>
                        <td>{{ $check->check_number }}</td>
                        <td>{{ $check->party_name }}</td>
                        <td class="font-weight-bold">{{ number_format($check->amount, 2) }} {{ $check->currency }}</td>
                        <td>{{ $check->deleted_at->format('Y-m-d H:i A') }}</td>
                        <td>
                            <form action="{{ route('dashboard.checks.restore', $check->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">استعادة</button>
                            </form>
                            <form action="{{ route('dashboard.checks.force-delete', $check->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">حذف نهائي</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center p-5">سلة المهملات فارغة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $trashedChecks->links() }}</div>
    </div>
</div>
@endsection
