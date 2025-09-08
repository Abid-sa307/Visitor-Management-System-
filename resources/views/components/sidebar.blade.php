{{-- resources/views/components/sidebar.blade.php --}}
@php
    $isCompany = request()->is('company/*');

    $active = function ($routes) {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) return 'active';
        }
        return '';
    };

    $reportActive = request()->routeIs([
        $isCompany ? 'company.visitors.report'          : 'visitors.report',
        $isCompany ? 'company.visitors.report.inout'    : 'visitors.report.inout',
        $isCompany ? 'company.visitors.report.approval' : 'visitors.report.approval',
        $isCompany ? 'company.visitors.report.security' : 'visitors.report.security',
    ]);
@endphp

<style>
/* ---------- Sidebar dropdown: force transparent look ---------- */
/* SB Admin sets a background on .collapse-inner; override with higher specificity */
.sidebar.sidebar-dark .nav-item .collapse .collapse-inner {
    background: transparent !important;
    box-shadow: none !important;
    border: 0 !important;
    padding: .25rem 0 !important;
}

/* Remove any default fill on items; keep white text */
.sidebar.sidebar-dark .nav-item .collapse .collapse-inner .collapse-item {
    background: transparent !important;
    color: #f8f9fa !important;
    padding: .5rem 1rem !important;
    border-radius: .35rem !important;
    display: block;
    transition: background-color .18s ease, color .18s ease !important;
}

/* Hover/active subtle tint only */
.sidebar.sidebar-dark .nav-item .collapse .collapse-inner .collapse-item:hover,
.sidebar.sidebar-dark .nav-item .collapse .collapse-inner .collapse-item.active {
    background: rgba(255,255,255,.08) !important;
    color: #ffffff !important;
}

/* Chevron rotation */
.sidebar .nav-link .chev { transition: transform .2s ease; }
.sidebar .nav-link[aria-expanded="true"] .chev { transform: rotate(180deg); }
</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="{{ $isCompany ? route('company.dashboard') : route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="bi bi-columns-gap fs-4"></i>
        </div>
        <div class="sidebar-brand-text mx-2">VMS Panel</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard (Always visible) -->
    <li class="nav-item {{ $active($isCompany ? 'company.dashboard' : 'dashboard') }}">
        <a class="nav-link" href="{{ $isCompany ? route('company.dashboard') : route('dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Check if the user is a Company user -->
    @if(auth()->user()->role === 'company')
        <!-- Only Show Dashboard for Company Users -->
        <!-- No other menu items will be shown for company users -->
    @else
        <!-- Show these items only for superadmin -->
        <!-- Visitors (Only show for superadmins and company users) -->
        <li class="nav-item {{ $active($isCompany ? 'company.visitors.index' : 'visitors.index') }}">
            <a class="nav-link" href="{{ $isCompany ? route('company.visitors.index') : route('visitors.index') }}">
                <i class="bi bi-person-lines-fill me-2"></i>
                <span>Visitors</span>
            </a>
        </li>

        <!-- Visitor In & Out (Only show for superadmins and company users) -->
        <li class="nav-item {{ $active($isCompany ? 'company.visitors.entry.page' : 'visitors.entry.page') }}">
            <a class="nav-link" href="{{ $isCompany ? route('company.visitors.entry.page') : route('visitors.entry.page') }}">
                <i class="bi bi-door-open me-2"></i>
                <span>Visitor In & Out</span>
            </a>
        </li>

        <!-- Visitor History (Only show for superadmins and company users) -->
        <li class="nav-item {{ $active($isCompany ? 'company.visitors.history' : 'visitors.history') }}">
            <a class="nav-link" href="{{ $isCompany ? route('company.visitors.history') : route('visitors.history') }}">
                <i class="bi bi-clock-history me-2"></i>
                <span>Visitor History</span>
            </a>
        </li>

        <!-- Reports Dropdown (Only show for superadmins) -->
        @if(auth()->user()->role === 'superadmin')
            <li class="nav-item {{ $reportActive ? 'active' : '' }}">
                <a class="nav-link {{ $reportActive ? '' : 'collapsed' }}"
                   href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#collapseReports"
                   aria-expanded="{{ $reportActive ? 'true' : 'false' }}"
                   aria-controls="collapseReports">
                    <i class="bi bi-bar-chart-line me-2"></i>
                    <span>Reports</span>
                    <span class="ms-auto chev"><i class="bi bi-chevron-down"></i></span>
                </a>

                <div id="collapseReports" class="collapse {{ $reportActive ? 'show' : '' }}" data-bs-parent="#accordionSidebar">
                    <div class="collapse-inner px-2">
                        <a class="collapse-item {{ $active($isCompany ? 'company.visitors.report' : 'visitors.report') }}"
                           href="{{ $isCompany ? route('company.visitors.report') : route('visitors.report') }}">
                            Visitor Reports
                        </a>
                        <a class="collapse-item {{ $active($isCompany ? 'company.visitors.report.inout' : 'visitors.report.inout') }}"
                           href="{{ $isCompany ? route('company.visitors.report.inout') : route('visitors.report.inout') }}">
                            In/Out Reports
                        </a>
                        <a class="collapse-item {{ $active($isCompany ? 'company.visitors.report.approval' : 'visitors.report.approval') }}"
                           href="{{ $isCompany ? route('company.visitors.report.approval') : route('visitors.report.approval') }}">
                            Approvals Reports
                        </a>
                        <a class="collapse-item {{ $active($isCompany ? 'company.visitors.report.security' : 'visitors.report.security') }}"
                           href="{{ $isCompany ? route('company.visitors.report.security') : route('visitors.report.security') }}">
                            Security Reports
                        </a>
                    </div>
                </div>
            </li>
        @endif

        <!-- Departments (Only show for superadmins and company users) -->
        <li class="nav-item {{ $active($isCompany ? 'company.departments.index' : 'departments.index') }}">
            <a class="nav-link" href="{{ $isCompany ? route('company.departments.index') : route('departments.index') }}">
                <i class="bi bi-building me-2"></i>
                <span>Departments</span>
            </a>
        </li>

        <li class="nav-item {{ $active($isCompany ? 'company.companies.index' : 'companies.index') }}">
            <a class="nav-link" href="{{ $isCompany ? route('company.companies.index') : route('companies.index') }}">
                <i class="bi bi-building me-2"></i>
                <span>Company</span>
            </a>
        </li>


        <!-- Employees (Only show for superadmins and company users) -->
        <li class="nav-item {{ $active($isCompany ? 'company.employees.index' : 'employees.index') }}">
            <a class="nav-link" href="{{ $isCompany ? route('company.employees.index') : route('employees.index') }}">
                <i class="bi bi-person-workspace me-2"></i>
                <span>Employees</span>
            </a>
        </li>

        <!-- Users (Only show for superadmins) -->
        @if(auth()->user()->role === 'superadmin')
            <li class="nav-item {{ $active($isCompany ? 'company.users.index' : 'users.index') }}">
                <a class="nav-link" href="{{ $isCompany ? route('company.users.index') : route('users.index') }}">
                    <i class="bi bi-person-bounding-box me-2"></i>
                    <span>Users</span>
                </a>
            </li>
        @endif
    @endif

    <!-- Logout -->
    <hr class="sidebar-divider">
    <li class="nav-item mt-3">
        <form method="POST" action="{{ $isCompany ? route('company.logout') : route('logout') }}">
            @csrf
            <button class="nav-link text-start btn text-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </li>
</ul>
