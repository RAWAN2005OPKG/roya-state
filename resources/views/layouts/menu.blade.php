      <!--begin::Header-->
      <div id="kt_header" class="header header-fixed">
        <!--begin::Container-->
       <div class="container-fluid d-flex align-items-stretch justify-content-between">
                                <!--begin::Header Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <!--begin::Header Menu-->

                <!--end::Header Menu-->
                </div>
            <!--end::Header Menu Wrapper-->
            <!--begin::Topbar-->
        <div class="topbar">


        <!--begin::Languages-->
        <div class="dropdown">
        <!--begin::Toggle-->
        <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
        <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
        <img class="h-20px w-20px rounded-sm" src="{{asset('dashboard/assets/media/svg/flags/226-united-states.svg')}}" alt="" />
        </div>
        </div>
        <!--end::Toggle-->
        <!--begin::Dropdown-->
        <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
        <!--begin::Nav-->
        <ul class="navi navi-hover py-4">
        <!--begin::Item-->
        <li class="navi-item">
        <a href="#" class="navi-link">
        <span class="symbol symbol-20 mr-3">
        <img src="{{asset('dashboard/assets/media/svg/flags/226-united-states.svg')}}" alt="" />
        </span>
        <span class="navi-text">English</span>
        </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="navi-item active">
        <a href="#" class="navi-link">
        <span class="symbol symbol-20 mr-3">
        <img src="{{asset('dashboard/assets/media/svg/flags/128-spain.svg')}}" alt="" />
        </span>
        <span class="navi-text">Arabic</span>
                                                    </a>
                                                </li>

                                            </ul>
                                            <!--end::Nav-->
                                        </div>
                                        <!--end::Dropdown-->
                                    </div>
                                    <!--end::Languages-->
                                    <!--begin::User-->
    <div class="topbar-item">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">Sean</span>
        <span class="symbol symbol-35 symbol-light-success">
        <span class="symbol-label font-size-h5 font-weight-bold">S</span>
        </span>
        </div>
    </div>
    <!-- Logout Button -->
    <div class="topbar-item ml-3">
        <form action="{{ url('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-light-danger">تسجيل الخروج</button>
        </form>
    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Topbar-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Header-->


    <div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
        <div class="brand flex-column-auto" id="kt_brand">

            <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                <span class="svg-icon svg-icon-xl">
                    <!-- SVG Icon for toggle -->
                </span>
            </button>
        </div>
        <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
            <div id="kt_aside_menu" class="aside-menu my-4 scroll ps ps--active-y" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
                <ul class="menu-nav">
      <li class="menu-item {{ request()->routeIs('dashboard.home') ? 'menu-item-active' : '' }}" aria-haspopup="true">
        <a href="{{ route('dashboard.home') }}" class="menu-link">
            <span class="svg-icon menu-icon"><i class="fas fa-tachometer-alt"></i></span>
            <span class="menu-text">لوحة التحكم</span>
        </a>
    </li>

    <li class="menu-section">
        <h4 class="menu-text">الإدارة</h4>
        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
    </li>

{{-- 5. قائمة المشاريع --}}
<li class="menu-item menu-item-submenu {{ request()->is('dashboard/projects*') || request()->is('dashboard/reportproject*') ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon"><i class="fas fa-project-diagram"></i></span>
        <span class="menu-text">المشاريع</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <ul class="menu-subnav">
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ url('dashboard/projects') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">جميع المشاريع</span>
                </a>
            </li>
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ url('dashboard/reportproject') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">تحليل جميع المشاريع</span>
                </a>
            </li>
        </ul>
    </div>
</li>

    {{-- 2. القائمة المالية --}}

    <li class="menu-item menu-item-submenu {{ request()->is('dashboard/accounts*') || request()->is('dashboard/journal-entries*') || request()->is('dashboard/expenses*') || request()->is('dashboard/cash-safes*') || request()->is('dashboard/bank-accounts*') || request()->is('dashboard/fund-transfers*') || request()->is('dashboard/project-transfers*') ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
            <span class="svg-icon menu-icon"><i class="fas fa-wallet"></i></span>
            <span class="menu-text"> الخزينة العامة</span>
            <i class="menu-arrow"></i>
        </a>        <div class="menu-submenu">
        <ul class="menu-subnav">
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('dashboard.payments.create') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">تسجيل قيد يومي</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('dashboard.payments.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">سجل القيود (الدفعات)</span>
                    </a>
                </li>
               <li class="menu-item {{ request()->routeIs('dashboard.cash.*') ? 'menu-item-active' : '' }}">
                   <a href="{{ route('dashboard.cash.index') }}" class="menu-link">
                   <i class="mmenu-bullet menu-bullet-dot"></i>
                   <span class="menu-text">المحفظة النقدية</span>
                 </a>
               </li>


                 <li class="menu-item {{ request()->routeIs('dashboard.financial-accounts.index') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                <a href="{{ route('dashboard.financial-accounts.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">المركز المالي</span>
                </a>
            </li>
                <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.financial.summary') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">الملخص المالي</span>
                </a>
            </li>

                <li class="menu-item {{ request()->routeIs('dashboard.expenses.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.expenses.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">دراسة تحليل المشروع</span>
                    </a>
                </li>

<li class="menu-item {{ request()->is('dashboard/supplier-expenses*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                <a href="{{ route('dashboard.supplier_expenses.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">مصروفات المورّدين</span>
                </a>
            </li>

             <li class="menu-item {{ request()->routeIs('dashboard.banks.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                    <a href="{{ route('dashboard.banks.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">دليل البنوك</span>
                    </a>
                </li> <li class="menu-item {{ request()->routeIs('dashboard.bank-accounts.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.bank-accounts.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">إدارة البنوك</span>
                    </a>
                </li>
             <li class="menu-item" aria-haspopup="true">
    <a href="{{ route('dashboard.bank-transactions.index') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-university"></i></span>
        <span class="menu-text">الحركات البنكية</span>
    </a>
</li>

                <li class="menu-item {{ request()->routeIs('dashboard.checks.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.checks.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">إدارة الشيكات</span>
                    </a>
                </li>
                   <li class="menu-item {{ request()->routeIs('dashboard.fund-transfers.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.fund-transfers.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">تحويل الأموال</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.project-transfers.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.project-transfers.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">تحويل المشاريع</span>
                    </a>
                </li>
           <li class="menu-item {{ request()->routeIs('dashboard.waleed-transactions.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
    <a href="{{ route('dashboard.waleed-transactions.index') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-user-tie"></i></span>
        <span class="menu-text">سجل وليد الخالص</span>
    </a>
</li>
 <li class="menu-item {{ request()->routeIs('dashboard.khaleed-mohamed.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
    <a href="{{ route('dashboard.khaleed-mohamed.index') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-users"></i></span>
        <span class="menu-text">سجل خالد ومحمد</span>
    </a>
</li>
</ul>
        </div>
    </li>
<li class="menu-item menu-item-submenu {{ request()->is('dashboard/purchases*') || request()->is('dashboard/purchase-returns*') ? 'menu-item-open menu-item-here' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon"><i class="fas fa-shopping-cart"></i></span>
        <span class="menu-text">المشتريات</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <i class="menu-arrow"></i>
        <ul class="menu-subnav">
            {{-- رابط فواتير الشراء --}}
            <li class="menu-item {{ request()->is('dashboard/purchases*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                <a href="{{ route('dashboard.purchases.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">فواتير الشراء</span>
                </a>
            </li>

            {{-- رابط مرتجعات المشتريات --}}
            <li class="menu-item {{ request()->is('dashboard/purchase-returns*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                <a href="{{ route('dashboard.purchase-returns.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">مرتجعات المشتريات</span>
                </a>
            </li>
        </ul>
    </div>
</li>

                    <!-- بداية قائمة المبيعات
<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon">
            <i class="fas fa-chart-line"></i>
        </span>
        <span class="menu-text">المبيعات</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <i class="menu-arrow"></i>
        <ul class="menu-subnav">

            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.sales.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">الفواتير</span>
                </a>
            </li>

            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.quotations.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">عروض الأسعار</span>
                </a>
            </li>

            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.sales.create') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">إضافة فاتورة</span>
                </a>
            </li>

            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.collections') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">التحصيل</span>
                </a>
            </li>

            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.sales-returns.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">مردودات المبيعات</span>
                </a>
            </li>

        </ul>
    </div>
</li>
<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon">
            <i class="fas fa-boxes"></i>
        </span>
        <span class="menu-text">المنتجات والمخزون</span>
        <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
        <i class="menu-arrow"></i>
        <ul class="menu-subnav">

            {{-- 1. قائمة المنتجات --}}
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.products.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">قائمة المنتجات</span>
                </a>
            </li>

            {{-- 2. المستودعات --}}
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.warehouses.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">المستودعات</span>
                </a>
            </li>

            {{-- 3. قوائم الأسعار --}}
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.pricelists.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">قوائم الأسعار</span>
                </a>
            </li>

            {{-- 4. إدارة الجرد --}}
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.stocktakes.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">إدارة الجرد</span>
                </a>
            </li>

            {{-- 5. الأذون المخزنية --}}
            <li class="menu-item" aria-haspopup="true">
                <a href="{{ route('dashboard.transfers.index') }}" class="menu-link">
                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                    <span class="menu-text">الأذون المخزنية</span>
                </a>
            </li>

        </ul>
    </div>
</li>-->
              <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
            <i class="menu-icon fas fa-users"></i>
            <span class="menu-text"> دائرة العمل</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <ul class="menu-subnav">
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('dashboard.clients.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">العملاء</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('dashboard.investors.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">المستثمرون</span>
                    </a>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('dashboard.subcontractors.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">المقاولون والموردون</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>{{-- 6. قائمة العقود --}}

<li class="menu-item {{ request()->is('dashboard/contracts*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
    <a href="{{ route('dashboard.contracts.index') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-file-signature"></i></span>
        <span class="menu-text">العقود</span>
    </a>
</li>

{{-- =================================================== --}}
{{-- 8. قائمة المستخدمين --}}
{{-- =================================================== --}}
<li class="menu-item {{ request()->is('dashboard/employees*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
    <a href="{{ url('dashboard/employees') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-users-cog"></i></span>
        <span class="menu-text">المستخدمون</span>
    </a>
</li>


                <li class="menu-item {{ request()->routeIs('dashboard.annual-profit.index') ? 'menu-item-active' : '' }}" aria-haspopup="true">
    <a href="{{ route('dashboard.annual-profit.index') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-chart-line"></i></span>
        <span class="menu-text">الربح السنوي</span>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('dashboard.alerts.index') ? 'menu-item-active' : '' }}" aria-haspopup="true">
    <a href="{{ route('dashboard.alerts.index') }}" class="menu-link">
        <span class="svg-icon menu-icon"><i class="fas fa-bell"></i></span>
        <span class="menu-text">التنبيهات</span>
    </a>
</li>
<li class="menu-item {{ request()->routeIs('dashboard.settings.*') ? 'menu-item-active' : '' }}">
    <a href="{{ route('dashboard.settings.index') }}" class="menu-link">
        <i class="menu-icon fas fa-cogs"></i>
        <span class="menu-text">الإعدادات</span>
    </a>
</li>

<li class="menu-item" aria-haspopup="true">
        {{-- عند الضغط على هذا الرابط، سيتم تنفيذ الفورم المخفي لتسجيل الخروج --}}
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
           class="menu-link">
            <span class="svg-icon menu-icon"><i class="fas fa-sign-out-alt"></i></span>
            <span class="menu-text">تسجيل الخروج</span>
        </a>
    </li>
</ul>

{{-- هذا الفورم المخفي ضروري لعملية تسجيل الخروج --}}
<form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
            </div>
        </div>
    </div>


