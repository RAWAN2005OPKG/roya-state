@extends('layouts.container')
@section('title', 'إضافة حركة بنكية')
@section('content')
<div class="card card-custom">
    <div class="card-header"><h3 class="card-title">إضافة حركة بنكية جديدة</h3></div>
    <form class="form" action="{{ route('dashboard.bank-transactions.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('dashboard.bank_transactions.partials._form', ['transaction' => null])
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">حفظ</button>
            <a href="{{ route('dashboard.bank-transactions.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
