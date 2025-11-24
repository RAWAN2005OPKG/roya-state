@extends('layouts.container')
@section('title', 'إضافة قيد يومية يدوي')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">إضافة قيد يومية يدوي</h3>
    </div>
    <div class="card-body" x-data="journalEntryForm()">
        <form action="{{ route('dashboard.journal-entries.store') }}" method="POST">
            @csrf
            <div class="row mb-4">
                <div class="col-md-4">
                    <label>التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-8">
                    <label>البيان / الوصف</label>
                    <input type="text" name="description" class="form-control" placeholder="وصف المعاملة" required>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>الحساب</th>
                        <th width="20%">مدين</th>
                        <th width="20%">دائن</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr>
                            <td>
                                <select :name="`items[${index}][account_id]`" class="form-control" required>
                                    <option value="">اختر أو اكتب اسم الحساب</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" :name="`items[${index}][debit]`" class="form-control" x-model.number="item.debit" @input="updateTotals" placeholder="0.00" step="0.01"></td>
                            <td><input type="number" :name="`items[${index}][credit]`" class="form-control" x-model.number="item.credit" @input="updateTotals" placeholder="0.00" step="0.01"></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" @click="removeItem(index)" x-show="items.length > 2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <button type="button" class="btn btn-light-primary" @click="addItem">
                                <i class="fas fa-plus"></i> إضافة سطر
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">الإجمالي</th>
                        <td><input type="text" class="form-control" :value="totalDebit.toFixed(2)" readonly></td>
                        <td><input type="text" class="form-control" :value="totalCredit.toFixed(2)" readonly></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-right">الفرق</th>
                        <td colspan="2"><input type="text" class="form-control" :value="Math.abs(totalDebit - totalCredit).toFixed(2)" readonly :class="{ 'is-invalid': totalDebit !== totalCredit }"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary" :disabled="totalDebit !== totalCredit || totalDebit === 0">حفظ القيد</button>
                <a href="{{ route('dashboard.journal-entries.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- تأكد من تضمين Alpine.js في القالب الرئيسي --}}
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script>
    function journalEntryForm( ) {
        return {
            items: [
                { account_id: '', debit: 0, credit: 0 },
                { account_id: '', debit: 0, credit: 0 }
            ],
            totalDebit: 0,
            totalCredit: 0,
            addItem() {
                this.items.push({ account_id: '', debit: 0, credit: 0 });
            },
            removeItem(index) {
                this.items.splice(index, 1);
                this.updateTotals();
            },
            updateTotals() {
                this.totalDebit = this.items.reduce((sum, item) => sum + (parseFloat(item.debit) || 0), 0);
                this.totalCredit = this.items.reduce((sum, item) => sum + (parseFloat(item.credit) || 0), 0);
            }
        }
    }
</script>
@endpush
