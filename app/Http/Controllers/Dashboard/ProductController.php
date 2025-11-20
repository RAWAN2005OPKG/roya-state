<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * عرض قائمة بجميع المنتجات (التي لم يتم حذفها).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب أحدث المنتجات من قاعدة البيانات
        $products = Product::latest()->get();

        // عرض الواجهة مع تمرير بيانات المنتجات إليها
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * عرض فورم إنشاء منتج جديد.
     * (في حالتنا، نستخدم نافذة منبثقة في صفحة index، لذلك هذه الدالة غير مستخدمة حالياً)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // يمكن إنشاء صفحة منفصلة للإضافة إذا أردت ذلك مستقبلاً
        return view('dashboard.products.create');
    }

    /**
     * تخزين منتج جديد تم إرساله من الفورم في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'category' => 'nullable|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|string|max:255',
        ]);

        // إنشاء سجل جديد في جدول المنتجات
        Product::create($request->all());

        // إعادة التوجيه إلى صفحة قائمة المنتجات مع رسالة نجاح
        return redirect()->route('dashboard.products.index')->with('success', 'تمت إضافة المنتج بنجاح.');
    }

    /**
     * عرض منتج محدد.
     * (غير مستخدمة حالياً ولكنها جزء من الـ resource controller)
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * عرض صفحة تعديل منتج محدد.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        // عرض واجهة التعديل مع تمرير بيانات المنتج الحالي إليها
        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * تحديث بيانات منتج محدد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        // التحقق من صحة البيانات (مع تجاهل SKU الحالي للمنتج عند التحقق من التفرد)
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'category' => 'nullable|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|string|max:255',
        ]);

        // تحديث بيانات المنتج بالبيانات الجديدة
        $product->update($request->all());

        // إعادة التوجيه إلى صفحة قائمة المنتجات مع رسالة نجاح
        return redirect()->route('dashboard.products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    /**
     * حذف منتج (نقله إلى سلة المحذوفات - Soft Delete).
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // تنفيذ الحذف المؤقت
        $product->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('dashboard.products.index')->with('success', 'تم نقل المنتج إلى سلة المحذوفات.');
    }

    /**
     * عرض جميع المنتجات الموجودة في سلة المحذوفات.
     *
     * @return \Illuminate\View\View
     */
    public function trash()
    {
        // جلب المنتجات التي تم حذفها حذفاً مؤقتاً فقط
        $trashedProducts = Product::onlyTrashed()->latest()->get();

        // عرض واجهة سلة المحذوفات
        return view('dashboard.products.trash', compact('trashedProducts'));
    }

    /**
     * استعادة منتج محدد من سلة المحذوفات.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        // البحث عن المنتج في سلة المحذوفات فقط ثم استعادته
        Product::onlyTrashed()->findOrFail($id)->restore();

        // إعادة التوجيه إلى سلة المحذوفات مع رسالة نجاح
        return redirect()->route('dashboard.products.trash.index')->with('success', 'تم استعادة المنتج بنجاح.');
    }

    /**
     * حذف منتج بشكل نهائي من قاعدة البيانات.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id)
    {
        // البحث عن المنتج في سلة المحذوفات فقط ثم حذفه نهائياً
        Product::onlyTrashed()->findOrFail($id)->forceDelete();

        // إعادة التوجيه إلى سلة المحذوفات مع رسالة نجاح
        return redirect()->route('dashboard.products.trash.index')->with('success', 'تم حذف المنتج نهائياً.');
    }
}
