{{-- resources/views/dashboard/financial_accounts/partials/_bank_transaction_form.blade.php --}}
<form action="{{ route('dashboard.bank-transactions.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>الحساب البنكي <span class="text-danger">*</span></label>
            <select name="bank_account_id" class="form-control form-control-select2" required data-placeholder="اختر الحساب الذي ستتم عليه الحركة">
                <option label="Label"></option>
                @foreach($bankAccounts as $account)
                    <option value="{{ $account->id }}">{{ $account->account_name }} ({{ $account->bank->name ?? 'N/A' }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <div class="col-lg-6"><label>تاريخ الحركة <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
            <div class="col-lg-6"><label>نوع الحركة <span class="text-danger">*</span></label><select name="type" class="form-control" required><option value="deposit">إيداع</option><option value="withdrawal">سحب</option></select></div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6"><label>المبلغ <span class="text-danger">*</span></label><input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required></div>
            <div class="col-lg-6"><label>العملة <span class="text-danger">*</span></label><select name="currency" class="form-control" required><option value="ILS">شيكل</option><option value="USD">دولار</option><option value="JOD">دينار</option></select></div>
        </div>
        <hr>
        <h5 class="text-muted">تفاصيل إضافية (اختياري)</h5>
        <div class="form-group row">
            <div class="col-lg-6"><label>البنك المرسل/المستقبل</label><select name="payer_bank_name" class="form-control form-control-select2" data-placeholder="اختر البنك"><option label="Label"></option>@foreach($banks as $bank)<option value="{{ $bank->name }}">{{ $bank->name }}</option>@endforeach</select></div>
            <div class="col-lg-6"><label>رقم الحوالة/المرجع</label><input type="text" name="transfer_number" class="form-control"></div>
        </div>
        <div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary font-weight-bold">حفظ الحركة</button>
    </div>
</form>
