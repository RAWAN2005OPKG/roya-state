@extends('layouts.container')
@section('title', 'تحويل الأموال بين الحسابات')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-exchange-alt"></i> تحويل الأموال بين الحسابات</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- نموذج التحويل --}}
    <div class="form-container">
        <h2 class="container-title">عملية تحويل جديدة</h2>
        <form action="{{ route('dashboard.fund-transfers.store') }}" method="POST" class="form-grid">
            @csrf
            <div class="form-group">
                <label for="date">تاريخ التحويل</label>
                <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label for="amount">المبلغ</label>
                <input type="number" id="amount" name="amount" step="0.01" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" name="currency" required>
                    <option value="SAR">ريال سعودي</option>
                    <option value="USD">دولار أمريكي</option>
                </select>
            </div>
            <div class="form-group">
                <label for="from_account">من حساب</label>
                <select id="from_account" name="from_account" required>
                    <option value="">-- اختر الحساب المصدر --</option>
                    <optgroup label="الخزائن النقدية">
                        @foreach($cashSafes as $safe)
                            <option value="cash-{{ $safe->id }}">{{ $safe->name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="الحسابات البنكية">
                        @foreach($bankAccounts as $account)
                            <option value="bank-{{ $account->id }}">{{ $account->name }} ({{ $account->bank_name }})</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label for="to_account">إلى حساب</label>
                <select id="to_account" name="to_account" required>
                    <option value="">-- اختر الحساب الهدف --</option>
                    <optgroup label="الخزائن النقدية">
                        @foreach($cashSafes as $safe)
                            <option value="cash-{{ $safe->id }}">{{ $safe->name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="الحسابات البنكية">
                        @foreach($bankAccounts as $account)
                            <option value="bank-{{ $account->id }}">{{ $account->name }} ({{ $account->bank_name }})</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="notes">ملاحظات</label>
                <textarea id="notes" name="notes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn-submit">تنفيذ التحويل</button>
        </form>
    </div>

    {{-- جدول التحويلات السابقة --}}
    <div class="table-container">
        <h2 class="container-title">سجل التحويلات</h2>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المبلغ</th>
                        <th>من</th>
                        <th>إلى</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr>
                            <td>{{ $transfer->date }}</td>
                            <td>{{ number_format($transfer->amount, 2) }} {{ $transfer->currency }}</td>
                            <td>{{ $transfer->from_type == 'cash' ? 'خزينة' : 'بنك' }} #{{$transfer->from_id}}</td>
                            <td>{{ $transfer->to_type == 'cash' ? 'خزينة' : 'بنك' }} #{{$transfer->to_id}}</td>
                            <td>{{ $transfer->notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">لا توجد عمليات تحويل سابقة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
