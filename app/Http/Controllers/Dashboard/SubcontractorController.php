<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Subcontractor; 
use Illuminate\Http\Request;

class SubcontractorController extends Controller
{
    /**
     * عرض قائمة المقاولين والموردين.
     */
    public function index()
    {
        $subcontractors = Subcontractor::orderBy('id', 'desc')->get();
        return view('dashboard.subcontractors.index', compact('subcontractors'));
    }

    /**
     * عرض نموذج إنشاء مقاول/مورد جديد.
     */
    public function create()
    {
        return view('dashboard.subcontractors.create');
    }

    /**
     * حفظ مقاول/مورد جديد.
     */
    public function store(Request $request)
    {
        // منطق التحقق والحفظ هنا
        // ...
        return redirect()->route('dashboard.subcontractors.index')->with('success', 'تم إضافة المقاول/المورد بنجاح.');
    }
    
}
