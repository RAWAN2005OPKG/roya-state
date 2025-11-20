<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceListController extends Controller
{
    public function index(Request $request)
    {
        $query = PriceList::withCount('products');
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        $priceLists = $query->latest()->get();
        return view('dashboard.pricelists.index', compact('priceLists'));
    }

    public function create()
    {
        $products = Product::select('id', 'name', 'sale_price')->get();
        return view('dashboard.pricelists.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:price_lists,name',
            'type' => 'required|in:percentage,fixed',
            'value' => 'nullable|required_if:type,percentage|numeric',
            'products' => 'nullable|required_if:type,fixed|array',
            'products.*.id' => 'required_with:products|exists:products,id',
            'products.*.price' => 'required_with:products|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $priceList = PriceList::create($request->only('name', 'type', 'value', 'is_active'));

            if ($request->type == 'fixed' && $request->has('products')) {
                foreach ($request->products as $productData) {
                    $priceList->products()->attach($productData['id'], ['fixed_price' => $productData['price']]);
                }
            }
        });

        return redirect()->route('dashboard.pricelists.index')->with('success', 'تم إنشاء قائمة الأسعار بنجاح.');
    }

    public function destroy(PriceList $priceList)
    {
        $priceList->delete();
        return redirect()->route('dashboard.pricelists.index')->with('success', 'تم حذف قائمة الأسعار.');
    }
}
