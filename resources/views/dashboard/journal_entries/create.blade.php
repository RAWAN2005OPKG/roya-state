@extends('layouts.container')
@section('title', 'إنشاء قيد يومية جديد')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> إنشاء قيد يومية جديد</h1>
    </div>

    <form action="{{ route('dashboard.journal-entries.store') }}" method="POST" class="form-container">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger" style="grid-column: 1 / -1;">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="form-grid" style="grid-template-columns: 1fr 3fr; align-items: start;">
            <div class="form-group"><label for="date">التاريخ</label><input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required></div>
            <div class="form-group"><label for="description">البيان</label><input type="text" id="description" name="description" value="{{ old('description') }}" placeholder="اكتب وصفاً للقيد" required></div>
        </div>

        <h3 class="container-title mt-4">بنود القيد</h3>
        <div class="table-wrapper">
            <table class="data-table" id="journal-items-table">
                <thead>
                    <tr>
                        <th>الحساب</th>
                        <th width="20%">مدين</th>
                        <th width="20%">دائن</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- سيتم إضافة الصفوف هنا عبر JavaScript --}}
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right"><strong>الإجمالي</strong></td>
                        <td><strong id="total-debit">0.00</strong></td>
                        <td><strong id="total-credit">0.00</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button type="button" id="add-row" class="btn btn-secondary mt-3"><i class="fas fa-plus"></i> إضافة سطر</button>
        <button type="submit" class="btn-submit">حفظ القيد</button>
    </form>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#journal-items-table tbody');
    const addRowBtn = document.getElementById('add-row');
    let rowIndex = 0;

    function createRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="items[${rowIndex}][account_id]" class="form-control" required>
                    <option value="">-- اختر الحساب --</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${rowIndex}][debit]" class="form-control debit-input" step="0.01" placeholder="0.00"></td>
            <td><input type="number" name="items[${rowIndex}][credit]" class="form-control credit-input" step="0.01" placeholder="0.00"></td>
            <td><button type="button" class="btn-icon text-danger remove-row"><i class="fas fa-trash"></i></button></td>
        `;
        tableBody.appendChild(row);
        rowIndex++;
    }

    function updateTotals() {
        let totalDebit = 0;
        let totalCredit = 0;
        document.querySelectorAll('.debit-input').forEach(input => totalDebit += parseFloat(input.value) || 0);
        document.querySelectorAll('.credit-input').forEach(input => totalCredit += parseFloat(input.value) || 0);

        document.getElementById('total-debit').textContent = totalDebit.toFixed(2);
        document.getElementById('total-credit').textContent = totalCredit.toFixed(2);
    }

    addRowBtn.addEventListener('click', createRow);

    tableBody.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    tableBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('debit-input') || e.target.classList.contains('credit-input')) {
            updateTotals();
        }
    });

    // إنشاء صفين مبدئيين
    createRow();
    createRow();
});
</script>
@endpush
