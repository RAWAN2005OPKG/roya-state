<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Product;class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // عرض قائمة فواتير الشراء
        $purchases = Purchase::with('supplier')->latest()->paginate(10);
        return view('dashboard.purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function create()
    {
        // جلب قائمة الموردين والمنتجات
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('dashboard.purchase_returns.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id', // إضافة التحقق من المورد
            'return_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id', // تم تغييرها إلى required
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // إنشاء مرجوع المشتريات الرئيسي
            $return = PurchaseReturn::create([
                'supplier_id' => $request->supplier_id,
                'return_number' => 'PR-' . time(), // رقم مرجع افتراضي
                'return_date' => $request->return_date,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // إضافة أصناف المرتجع
            foreach ($request->items as $itemData) {
                // جلب اسم المنتج الفعلي
                $product = Product::find($itemData['product_id']);
                $productName = $product ? $product->name : 'منتج محذوف';

                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'product_id' => $itemData['product_id'],
                    'product_name' => $productName,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total' => $itemData['quantity'] * $itemData['unit_price'],
                ]);
            }

            // هنا يجب إضافة منطق القيد المحاسبي (Journal Entry) وتحديث المخزون
        });

        return redirect()->route('dashboard.purchase-returns.index')->with('success', 'تم إضافة مرجوع المشتريات بنجاح.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        // عرض تفاصيل فاتورة الشراء
        return view('dashboard.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        // عرض نموذج تعديل فاتورة الشراء
        return view('dashboard.purchases.edit', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        // تحديث فاتورة الشراء
        // ... (منطق التحديث) ...
        return redirect()->route('dashboard.purchases.index')->with('success', 'تم تحديث فاتورة الشراء بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        // حذف (Soft Delete) فاتورة الشراء
        $purchase->delete();
        return redirect()->route('dashboard.purchases.index')->with('success', 'تم نقل فاتورة الشراء إلى سلة المحذوفات.');
    }

    // يمكنك إضافة دوال أخرى مثل trash و restore هنا
}
