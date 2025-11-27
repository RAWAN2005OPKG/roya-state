@extends('layouts.container')
@section('title', 'الخزائن')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">إدارة الخزائن</h1>
            <p class="mb-0 text-muted">إضافة وتعديل ومتابعة أرصدة الخزائن في النظام.</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('dashboard.cash-safes.trash.index') }}" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-trash-alt fa-sm mr-1"></i> سلة المحذوفات
            </a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addSafeModal">
                <i class="fas fa-plus fa-sm mr-1"></i> إضافة خزينة جديدة
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الخزائن</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSafes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الخزائن النشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSafes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي الأرصدة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($safes->sum('balance'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card: Filters and Table -->
    <div class="card card-custom shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الخزائن</h5>
            <form action="{{ route('dashboard.cash-safes.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">اسم الخزينة</th>
                            <th scope="col">الرصيد الحالي</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">تاريخ الإنشاء</th>
                            <th scope="col" class="text-center">تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($safes as $safe)
                            <tr>
                                <td>{{ $safe->id }}</td>
                                <td>{{ $safe->name }}</td>
                                <td class="font-weight-bold">{{ number_format($safe->balance, 2) }}</td>
                                <td>
                                    @if($safe->is_active)
                                        <span class="badge badge-pill badge-success">نشطة</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">غير نشطة</span>
                                    @endif
                                </td>
                                <td>{{ $safe->created_at->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-icon btn-light-primary" data-toggle="modal" data-target="#editSafeModal-{{ $safe->id }}" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('dashboard.cash-safes.destroy', $safe->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذه الخزينة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Edit Safe Modal -->
                            @include('dashboard.cash_safes.partials.edit_modal')
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                                    <h4>لا توجد خزائن للعرض حالياً.</h4>
                                    <p>يمكنك البدء بإضافة خزينة جديدة من الزر أعلاه.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($safes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $safes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Safe Modal -->
@include('dashboard.cash_safes.partials.add_modal')

@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                // إذا كانت هناك أخطاء عند إضافة خزينة جديدة، افتح نافذة الإضافة
                @if(old('initial_balance'))
                    $('#addSafeModal').modal('show');
                @endif

                // إذا كانت هناك أخطاء عند تحديث خزينة، افتح نافذة التعديل الخاصة بها
                @if($errors->has('name') && !$errors->has('initial_balance'))
                    var errorModalId = '#editSafeModal-{{ substr($errors->getBag("default")->keys()[0], 5) }}';
                    if($(errorModalId).length) {
                       $(errorModalId).modal('show');
                    }
                @endif
            });
        </script>
    @endif
@endpush
