@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
<style>
    :root {
        --primary-color: #007bff; /* أزرق جذاب */
        --light-bg-1: #ffffff; /* خلفية بيضاء */
        --light-bg-2: #f8f9fa; /* خلفية أفتح قليلاً للأقسام */
        --text-color: #212529; /* أسود داكن للنصوص */
        --text-muted: #6c757d; /* رمادي للنصوص الثانوية */
        --border-color: #dee2e6; /* رمادي فاتح للحدود */
        --shadow-color: rgba(0, 0, 0, 0.05);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', 'Arial', sans-serif;
    }

    body {
        background-color: var(--light-bg-1);
        color: var(--text-color);
        display: flex;
    }

    .sidebar {
        width: 260px;
        background-color: var(--light-bg-2);
        padding: 25px;
        height: 100vh;
        position: fixed;
        top: 0;
        right: 0;
        border-left: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .sidebar-header h2 {
        color: var(--text-color);
        font-size: 1.8rem;
    }

    .sidebar-header h2 i {
        color: var(--primary-color);
    }

    .nav-menu a {
        display: flex;
        align-items: center;
        padding: 15px;
        color: var(--text-muted);
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background-color 0.3s, color 0.3s;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        background-color: var(--primary-color);
        color: #fff;
    }

    .nav-menu a i {
        margin-left: 15px;
        font-size: 1.2rem;
        width: 20px;
    }

    .main-content {
        margin-right: 260px;
        width: calc(100% - 260px);
        padding: 40px;
        overflow-y: auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 2.2rem;
        color: var(--text-color);
    }

    .btn-add-project {
        background-color: var(--primary-color);
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .btn-add-project:hover {
        background-color: #0056b3; /* درجة أغمق من الأزرق */
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 25px;
    }

    .project-card {
        background-color: var(--light-bg-1);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px var(--shadow-color);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .project-card-header {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .project-card-header h3 {
        color: var(--primary-color);
        margin: 0 0 5px 0;
    }

    .project-card-header span {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .project-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .metric-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .metric-item .label {
        color: var(--text-muted);
    }

    .metric-item .value {
        font-weight: bold;
        color: var(--text-color);
    }

    .notes-box {
        background: var(--light-bg-2);
        padding: 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #495057;
        white-space: pre-wrap;
        max-height: 90px;
        overflow-y: auto;
        margin-top: 15px;
        border: 1px solid #e9ecef;
    }

    .project-card-footer {
        padding: 15px;
        background-color: var(--light-bg-2);
        border-top: 1px solid var(--border-color);
        text-align: center;
    }

    .btn-delete {
        background: none;
        border: 1px solid #dc3545;
        color: #dc3545;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-delete:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .no-projects {
        text-align: center;
        padding: 50px;
        background-color: var(--light-bg-2);
        border-radius: 12px;
        border: 1px dashed var(--border-color);
    }

    .no-projects i {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    /* --- Mobile Responsive Code --- */
    .menu-toggle {
        display: none;
        /* Hidden on desktop */
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1100;
        background-color: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 8px;
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(100%);
            /* Start off-screen to the right */
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            position: fixed;
        }

        .sidebar.active {
            transform: translateX(0);
            /* Slide in */
        }

        .main-content {
            margin-right: 0;
            /* Full width */
            width: 100%;
            padding: 20px;
        }

        .menu-toggle {
            display: block;
            /* Show on mobile */
        }

        .projects-grid {
            grid-template-columns: 1fr;
            /* Stack cards vertically */
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .page-header h1 {
            font-size: 1.8rem;
        }
    }
</style>
@endsection


@section('content')


<button class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>
<main class="main-content">
    <div class="page-header">
        <h1>قائمة المشاريع</h1>
        <a href="{{ route('dashboard.projects.create') }}" class="btn-add-project"><i class="fas fa-plus"></i> مشروع جديد</a>
    </div>

    {{-- عرض رسالة النجاح بعد الحذف --}}
    @if (session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="projects-grid" id="projectsGrid">

        {{-- نستخدم @forelse للتحقق إذا كانت هناك مشاريع لعرضها أم لا --}}
        @forelse ($projects as $project)
            <div class="project-card">
                <div class="project-card-header">
                    <h3>{{ $project->name }}</h3>
                    {{-- ->format() لتنسيق شكل التاريخ --}}
                    <span>تاريخ البدء: {{ $project->start_date->format('d/m/Y') }}</span>
                </div>
                <div class="project-card-body">
                    <div class="metric-item">
                        <span class="label">حالة المشروع:</span>
                        <span class="value">{{ $project->status }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="label">التكاليف التقديرية:</span>
                        <span class="value">{{ number_format($project->estimated_cost, 2) }} $</span>
                    </div>
                    <div class="metric-item">
                        <span class="label">المصروفات الفعلية:</span>
                        <span class="value">{{ number_format($project->actual_expenses, 2) }} $</span>
                    </div>
                    <div class="metric-item">
                        <span class="label">الإيرادات الفعلية:</span>
                        <span class="value">{{ number_format($project->actual_revenue, 2) }} $</span>
                    </div>
                    <h4><i class="fas fa-clipboard"></i> ملاحظات مالية</h4>
                    {{-- نستخدم ?? لعرض نص بديل إذا كانت الملاحظات فارغة --}}
                    <div class="notes-box">{{ $project->financial_notes ?? 'لا توجد ملاحظات حالياً.' }}</div>
                </div>
                <div class="project-card-footer">
                    {{-- فورم الحذف --}}
                    <form action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المشروع نهائياً؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete"><i class="fas fa-trash"></i> حذف المشروع</button>
                    </form>
                </div>
            </div>
        @empty
            {{-- هذا الجزء سيظهر فقط إذا لم تكن هناك أي مشاريع في قاعدة البيانات --}}
            <div class="no-projects">
                <i class="fas fa-folder-open"></i>
                <h2>لا توجد مشاريع لعرضها</h2>
                <p>ابدأ بإضافة مشروع جديد من الزر في الأعلى.</p>
            </div>
        @endforelse

    </div>
</main>

@endsection

@section('script')

<script>
 
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
</script>

@endsection
