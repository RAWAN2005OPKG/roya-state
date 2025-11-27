@extends('layouts.container')
@section('title', 'سلة محذوفات الخزائن')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">سلة محذوفات الخزائن</h1>
            <p class="mb-0 text-muted">الخزائن التي تم حذفها مؤقتاً.</p>
        </div>
        <a href="{{ route('dashboard.cash-safes.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-right"></i> العودة إلى الخزائن
        </a>
    </div>

    <!-- Table -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الخزينة</th>
                            <th>تاريخ الحذف</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trashedSafes as $safe)
                            <tr>
                                <td>{{ $safe->id }}</td>
                                <td>{{ $safe->name }}</td>
                                <td>{{ $safe->deleted_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <form action="{{ route('dashboard.cash-safes.restore', $safe->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-success" title="استعادة">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.cash-safes.force-delete', $safe->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذه الخزينة نهائياً؟ لا يمكن التراجع عن هذا الإجراء.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف نهائي">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <h4>سلة المحذوفات فارغة.</h4>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($trashedSafes->hasPages())
                <div class="mt-4">{{ $trashedSafes->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
