<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Expense;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /*
       عرض قائمة بكل المشاريع (وظيفة index)
     */
    public function index()
    {
        $projects = Project::latest()->get(); // جلب كل المشاريع من قاعدة البيانات

        // حساب المصروفات الفعلية والإيرادات لكل مشروع
        foreach ($projects as $project) {
            $project->actual_expenses = Expense::where('project_id', $project->id)->sum('amount');
            // هنا يمكن إضافة منطق لحساب الإيرادات الفعلية للمشروع إذا كان هناك نموذج للإيرادات
            // حاليا، نفترض أن الإيرادات صفر أو يمكن جلبها من مكان آخر
            $project->actual_revenue = 0; // Placeholder, replace with actual revenue calculation if available
        }

        return view('dashboard.project.index', ['projects' => $projects]); // إرسال البيانات إلى الواجهة
    }


      # عرض فورم إضافة مشروع جديد (وظيفة create)

    public function create()
    {
        # وظيفتها فقط عرض ملف الواجهة الخاص بالإضافة
        return view('dashboard.add-project');
    }


     # تخزين بيانات المشروع الجديد (وظيفة store)

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name'    => ['required', 'string', 'max:255'],
            'due_date'        => ['nullable', 'date'],
            'owner_name'      => ['required', 'string', 'max:255'],
            'owner_phone'     => ['required', 'string', 'max:50'],
            'owner_id'        => ['required', 'string', 'max:50'],
            'project_title'   => ['required', 'string', 'max:255'],
            'currency'        => ['nullable', 'string', 'max:10'],
            'apartment_price' => ['nullable', 'string'],
            'down_payment'    => ['nullable', 'string'],
            'project_status'  => ['nullable', 'string', 'max:50'],
            'payment_method'  => ['nullable', 'string', 'max:50'],
            'project_media'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:20480'], // 20MB
            'land_cost'       => ['nullable', 'numeric'],
            'excavation_cost' => ['nullable', 'numeric'],
            'engineers_cost'  => ['nullable', 'numeric'],
            'licensing_cost'  => ['nullable', 'numeric'],
            'materials_cost'  => ['nullable', 'numeric'],
            'finishing_cost'  => ['nullable', 'numeric'],
            'total_budget'    => ['nullable', 'numeric'],
        ]);

        $num = fn($val) => $val === null ? null : (float) str_replace(',', '', $val);

    #معالجة رفع الملف
        $mediaPath = null;
        if ($request->hasFile('project_media')) {
            $mediaPath = $request->file('project_media')->store('projects', 'public');
        }

        # تجميع البيانات للحفظ
        $data = [
            'name'             => $request->input('project_name'),
            'start_date'       => $request->input('due_date'),
            'owner_name'       => $request->input('owner_name'),
            'owner_phone'      => $request->input('owner_phone'),
            'owner_id'         => $request->input('owner_id'),
            'project_title'    => $request->input('project_title'),
            'currency'         => $request->input('currency'),
            'apartment_price'  => $num($request->input('apartment_price')),
            'down_payment'     => $num($request->input('down_payment')),
            'project_status'   => $request->input('project_status'),
            'payment_method'   => $request->input('payment_method'),
            'cash_receiver'       => $request->input('cash_receiver'),
            'cash_receiver_other' => $request->input('cash_receiver_other'),
            'cash_receiver_job'   => $request->input('cash_receiver_job'),
            'sender_bank'       => $request->input('sender_bank'),
            'sender_bank_other' => $request->input('sender_bank_other'),
            'sender_branch'     => $request->input('sender_branch'),
            'receiver_bank'     => $request->input('receiver_bank'),
            'receiver_bank_other'=> $request->input('receiver_bank_other'),
            'receiver_branch'   => $request->input('receiver_branch'),
            'transaction_id'    => $request->input('transaction_id'),
            'check_number'      => $request->input('check_number'),
            'check_owner'       => $request->input('check_owner'),
            'check_holder'      => $request->input('check_holder'),
            'check_due_date'    => $request->input('check_due_date'),
            'check_receive_date'=> $request->input('check_receive_date'),
            'land_cost'         => $num($request->input('land_cost')),
            'excavation_cost'   => $num($request->input('excavation_cost')),
            'engineers_cost'    => $num($request->input('engineers_cost')),
            'licensing_cost'    => $num($request->input('licensing_cost')),
            'materials_cost'    => $num($request->input('materials_cost')),
            'finishing_cost'    => $num($request->input('finishing_cost')),
            'total_budget'      => $num($request->input('total_budget')),
        ];

        if ($mediaPath) {
            $data['project_media'] = $mediaPath;
        }

        # إذا لم يرسل total_budget، احسبه من التكاليف
        if (!$data['total_budget']) {
            $data['total_budget'] = collect([
                $data['land_cost'],
                $data['excavation_cost'],
                $data['engineers_cost'],
                $data['licensing_cost'],
                $data['materials_cost'],
                $data['finishing_cost'],
            ])->filter()->sum();
        }

        Project::create($data);

        return redirect()->route('dashboard.projects')->with('success', 'تم إضافة المشروع بنجاح!');
    }


     # حذف مشروع (وظيفة destroy)

    public function destroy(Project $project)
    {
        $project->delete(); # حذف المشروع من قاعدة البيانات

        # بعد الحذف، قم بإعادة توجيه المستخدم إلى صفحة قائمة المشاريع
        return redirect()->route('dashboard.projects')->with('success', 'تم حذف المشروع بنجاح!');
    }
}

