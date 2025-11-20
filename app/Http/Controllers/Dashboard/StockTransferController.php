<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse'])->latest()->get();
        return view('dashboard.transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('is_active', true)->pluck('name', 'id');
        $products = Product::pluck('name', 'id');
        return view('dashboard.transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $latestId = StockTransfer::latest('id')->first()?->id ?? 0;
            $refNo = 'TR-' . date('Y') . '-' . str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);

            $transfer = StockTransfer::create($request->only('from_warehouse_id', 'to_warehouse_id', 'date') + ['reference_no' => $refNo]);

            foreach ($request->items as $itemData) {
                $transfer->items()->create($itemData);
                // ملاحظة: منطق تحديث كمية المنتج في المستودعات يجب إضافته هنا
                // Product::find($itemData['product_id'])->decrement('quantity', $itemData['quantity']);
            }
        });

        return redirect()->route('dashboard.transfers.index')->with('success', 'تم إنشاء إذن النقل بنجاح.');
    }
}
