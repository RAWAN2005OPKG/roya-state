@extends('layouts.container')
@section('title', 'تعديل فاتورة شراء: ' . $invoice->invoice_number)

@section('content')
<main class="main-content">
    <div class="card card-custom" style="max-width: 900px; margin: auto;">
        <div class="card-header"><h3 class="card-title">تعديل فاتورة شراء</h3></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <form action="{{ route('dashboard.purchases.invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- ⬅️ مهم جداً لتحديد طريقة التحديث --}}

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="invoice_number">رقم الفاتورة *</label>
                        <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="supplier_id">اسم المورد *</label>
                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                            <option value="">-- اختر المورد --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $invoice->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="invoice_date">تاريخ الفاتورة *</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="due_date">تاريخ الاستحقاق (اختياري)</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <hr>
                <h4>الأصناف</h4>
                <div id="items-container">
                    @foreach(old('items', $invoice->items) as $index => $item)
                    <div class="row item-row mb-3">
                        <div class="col-md-4"><label>المنتج</label><select name="items[{{ $index }}][product_id]" class="form-control product-select"><option value="">اختر منتج</option></select></div>
                        <div class="col-md-2"><label>الكمية</label><input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity-input" value="{{ old('items.' . $index . '.quantity', $item->quantity ?? 1) }}" min="1"></div>
                        <div class="col-md-3"><label>سعر الوحدة</label><input type="number" name="items[{{ $index }}][unit_price]" class="form-control price-input" value="{{ old('items.' . $index . '.unit_price', $item->unit_price ?? 0.00) }}" step="0.01"></div>
                        <div class="col-md-2"><label>الإجمالي</label><input type="text" class="form-control total-output" value="{{ number_format(($item->quantity ?? 0) * ($item->unit_price ?? 0), 2) }}" readonly></div>
                        <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button></div>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-item" class="btn btn-sm btn-secondary mb-3"><i class="fas fa-plus"></i> إضافة بند</button>

                <hr>
                <h4>الدفع</h4>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>الإجمالي الكلي للفاتورة:</label>
                        <input type="text" id="grand-total" class="form-control" value="{{ number_format($invoice->total_amount, 2) }}" readonly>
                        <input type="hidden" name="total_amount" id="hidden-total-amount" value="{{ $invoice->total_amount }}">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="paid_amount">المبلغ المدفوع *</label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount', $invoice->paid_amount) }}" step="0.01" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>المتبقي:</label>
                        <input type="text" id="remaining-amount-output" class="form-control" value="{{ number_format($invoice->remaining_amount, 2) }}" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary me-2">تحديث</button>
                    <a href="{{ route('dashboard.purchases.invoices.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    // هذا كود JavaScript لتشغيل منطق حساب الإجماليات بشكل ديناميكي
    document.addEventListener('DOMContentLoaded', function () {
        const itemsContainer = document.getElementById('items-container');
        const addItemButton = document.getElementById('add-item');
        const paidAmountInput = document.getElementById('paid_amount');
        const grandTotalOutput = document.getElementById('grand-total');
        const hiddenTotalAmount = document.getElementById('hidden-total-amount');
        const remainingAmountOutput = document.getElementById('remaining-amount-output');
        let itemIndex = 1;

        // دالة لحساب إجمالي البند
        function calculateItemTotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = quantity * price;
            row.querySelector('.total-output').value = total.toFixed(2);
            return total;
        }

        // دالة لحساب الإجمالي الكلي
        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                grandTotal += calculateItemTotal(row);
            });
            grandTotalOutput.value = grandTotal.toFixed(2);
            hiddenTotalAmount.value = grandTotal.toFixed(2);
            calculateRemaining();
        }

        // دالة لحساب المبلغ المتبقي
        function calculateRemaining() {
            const grandTotal = parseFloat(hiddenTotalAmount.value) || 0;
            const paidAmount = parseFloat(paidAmountInput.value) || 0;
            const remaining = grandTotal - paidAmount;
            remainingAmountOutput.value = remaining.toFixed(2);
        }

        // إضافة مستمعي الأحداث للبند الجديد
        function attachListeners(row) {
            row.querySelector('.quantity-input').addEventListener('input', calculateGrandTotal);
            row.querySelector('.price-input').addEventListener('input', calculateGrandTotal);
            row.querySelector('.remove-item').addEventListener('click', function() {
                row.remove();
                calculateGrandTotal();
            });
        }

        // إضافة بند جديد
        addItemButton.addEventListener('click', function () {
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'item-row', 'mb-3');
            newRow.innerHTML = `
                <div class="col-md-4"><select name="items[${itemIndex}][product_id]" class="form-control product-select"><option value="">اختر منتج</option></select></div>
                <div class="col-md-2"><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" value="1" min="1"></div>
                <div class="col-md-3"><input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" value="0.00" step="0.01"></div>
                <div class="col-md-2"><input type="text" class="form-control total-output" value="0.00" readonly></div>
                <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button></div>
            `;
            itemsContainer.appendChild(newRow);
            attachListeners(newRow);
            itemIndex++;
        });

        // مستمعي الأحداث للمدخلات الموجودة عند التحميل
        document.querySelectorAll('.item-row').forEach(attachListeners);
        paidAmountInput.addEventListener('input', calculateRemaining);

        // الحساب الأولي
        calculateGrandTotal();
    });
</script>
@endsection
