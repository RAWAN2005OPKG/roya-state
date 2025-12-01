@extends('layouts.container')
@section('title', 'إدارة الخزائن النقدية')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">الخزائن النقدية
                <span class="d-block text-muted pt-2 font-size-sm">عرض وإدارة جميع الخزائن النقدية</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <button class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#addSafeModal">
                <span class="svg-icon svg-icon-md"><i class="fas fa-plus"></i></span>إضافة خزينة جديدة
            </button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-uppercase">
                        <th>اسم الخزينة</th>
                        <th>الرصيد الافتتاحي</th>
                        <th>الرصيد الحالي</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- هنا تم تصحيح الخطأ --}}
                    @forelse($cashSafes as $safe)
                    <tr>
                        <td>{{ $safe->name }}</td>
                        <td>{{ number_format($safe->initial_balance, 2) }}</td>
                        <td class="font-weight-bold">{{ number_format($safe->balance, 2) }}</td>
                        <td>
                            @if($safe->is_active)
                                <span class="label label-lg font-weight-bold label-light-success label-inline">نشطة</span>
                            @else
                                <span class="label label-lg font-weight-bold label-light-danger label-inline">غير نشطة</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-clean btn-icon" data-toggle="modal" data-target="#editSafeModal-{{ $safe->id }}" title="تعديل"><i class="la la-edit"></i></button>
                            <form action="{{ route('dashboard.cash-safes.destroy', $safe->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذه الخزينة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="la la-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center p-5 text-muted">لا توجد خزائن لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $cashSafes->links() }}</div>
    </div>
</div>

{{-- =================================================== --}}
{{-- Modals Section --}}
{{-- =================================================== --}}

<!-- Add Safe Modal -->
<div class="modal fade" id="addSafeModal" tabindex="-1" role="dialog" aria-labelledby="addSafeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSafeModalLabel">إضافة خزينة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.cash-safes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">اسم الخزينة</label>
                        <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="initial_balance">الرصيد الافتتاحي</label>
                        <input type="number" id="initial_balance" name="initial_balance" class="form-control" value="{{ old('initial_balance', 0) }}" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Safe Modals -->
@foreach ($cashSafes as $safe)
<div class="modal fade" id="editSafeModal-{{ $safe->id }}" tabindex="-1" role="dialog" aria-labelledby="editSafeModalLabel-{{ $safe->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSafeModalLabel-{{ $safe->id }}">تعديل الخزينة: {{ $safe->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.cash-safes.update', $safe->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name-{{ $safe->id }}">اسم الخزينة</label>
                        <input type="text" id="name-{{ $safe->id }}" name="name" class="form-control" value="{{ old('name', $safe->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="is_active-{{ $safe->id }}">الحالة</label>
                        <select id="is_active-{{ $safe->id }}" name="is_active" class="form-control" required>
                            <option value="1" @selected(old('is_active', $safe->is_active) == '1')>نشطة</option>
                            <option value="0" @selected(old('is_active', $safe->is_active) == '0')>غير نشطة</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
