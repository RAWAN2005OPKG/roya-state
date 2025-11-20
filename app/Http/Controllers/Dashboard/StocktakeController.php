<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Stocktake;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocktakeController extends Controller
{
    public function index()
    {
        $stocktakes = Stocktake::with('warehouse')->latest()->get();
        return view('dashboard.stocktakes.index', compact('stocktakes'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('is_active', true)->pluck('name', 'id');
        $products = Product::select('id', 'name', 'quantity')->get();
        // توليد رقم مرجعي تلقائي
        $latestId = Stocktake::latest('id')->first()?->id ?? 0;
        $referenceNo = 'JRD-' . date('Y') . '-' . str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

        return view('dashboard.stocktakes.create', compact('warehouses', 'products', 'referenceNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string|unique:stocktakes,reference_no',
            'warehouse_id' => 'required|exists:warehouses,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.actual_quantity' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $stocktake = Stocktake::create($request->only('reference_no', 'warehouse_id', 'date', 'status'));

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                $stocktake->items()->create([
                    'product_id' => $product->id,
                    'system_quantity' => $product->quantity,
                    'actual_quantity' => $itemData['actual_quantity'],
                    'difference' => $itemData['actual_quantity'] - $product->quantity,
                ]);
            }
        });

        return redirect()->route('dashboard.stocktakes.index')->with('success', 'تم حفظ الجرد بنجاح.');
    }
}
