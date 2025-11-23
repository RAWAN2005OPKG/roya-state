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
    <span class="menu-text">لوحة التحكم</span>
</a>


    </li>

    <li class="menu-section">
        <h4 class="menu-text">الإدارة</h4>
        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
    </li>

    {{-- =================================================== --}}
    {{-- 2. القائمة المالية --}}
    {{-- =================================================== --}}
    <li class="menu-item menu-item-submenu {{ request()->is('dashboard/accounts*') || request()->is('dashboard/journal-entries*') || request()->is('dashboard/expenses*') || request()->is('dashboard/cash-safes*') || request()->is('dashboard/bank-accounts*') || request()->is('dashboard/fund-transfers*') || request()->is('dashboard/project-transfers*') ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
        <a href="javascript:;" class="menu-link menu-toggle">
            <span class="svg-icon menu-icon"><i class="fas fa-wallet"></i></span>
            <span class="menu-text">الإدارة المالية</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="menu-submenu">
            <ul class="menu-subnav">
                <li class="menu-item {{ request()->routeIs('dashboard.accounts.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.accounts.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">دليل الحسابات</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.journal-entries.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.journal-entries.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">قيود اليومية</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.expenses.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.expenses.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">المصروفات</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.cash-safes.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.cash-safes.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">الخزينة العامة</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('dashboard.bank-accounts.*') ? 'menu-item-active' : '' }}">
                    <a href="{{ route('dashboard.bank-accounts.index') }}" class="menu-link">
                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                        <span class="menu-text">الحسابات البنكية</span>
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
            </ul>
        </div>
    </li>

                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{url('dashboard.investors') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">المستثمرون</span>
                        </a>
                    </li>

                    <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">المشاريع</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <ul class="menu-subnav">

                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{ url('dashboard.projects') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text">جميع المشاريع</span>
                                    </a>
                                </li>
                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{url('dashboard.reportproject.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text">تحليل جميع المشاريع</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{url('dashboard.client-payments') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">العقود</span>
                        </a>
                    </li>
                    <li class="menu-item" aria-haspopup="true">
                    <a href="{{url('dashboard.customers') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">العملاء</span>
                        </a>
                    </li>
                     <li class="menu-item" aria-haspopup="true">
                        <a href="{{ route('dashboard.subcontractors.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon"><i class="fas fa-hard-hat"></i></span> {{-- يمكنك تغيير الأيقونة --}}
                            <span class="menu-text">المقاولون والموردون</span>
                        </a>
                    </li>
                    <!-- بداية قائمة المبيعات -->
<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:;" class="menu-link menu-toggle">
        <span class="svg-icon menu-icon">
            {{-- يمكنك استخدام أيقونة من Metronic أو FontAwesome --}}
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
<!-- نهاية قائمة المبيعات -->

<!-- بداية قائمة المنتجات والمخزون -->
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
</li>

                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{url('dashboard.employees') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">المستخدمون</span>
                        </a>
                    </li>

                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{url('dashboard.daily') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">التقرير اليومي</span>
                        </a>
                    </li>
                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{url('dashboard.years') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">الربح السنوي</span>
                        </a>
                    </li>

                    <li class="menu-item" aria-haspopup="true">
                        <a href="{{url('dashboard.alerts') }}" class="menu-link">
                            <span class="svg-icon menu-icon"></span>
                            <span class="menu-text">التنبيهات</span>
                        </a>
                    </li>

                    <li class="menu-section">
                    <h4 class="menu-text">الحساب</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="menu-link">
                        <span class="svg-icon menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="menu-text">تسجيل الخروج</span>
                    </a>
                </li>
                </ul>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
