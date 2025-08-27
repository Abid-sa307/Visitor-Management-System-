@php
    // Detect if this is a company user
    $isCompany = request()->is('company/*');

    // Helper to highlight active menu
    $active = function ($routes) {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }
        return '';
    };
@endphp

<!-- Sidebar -->
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

    <!-- Dashboard -->
    <li class="nav-item {{ $active($isCompany ? 'company.dashboard' : 'dashboard') }}">
        <a class="nav-link" href="{{ $isCompany ? route('company.dashboard') : route('dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Visitors -->
    <li class="nav-item {{ $active($isCompany ? 'company.visitors.index' : 'visitors.index') }}">
        <a class="nav-link" href="{{ $isCompany ? route('company.visitors.index') : route('visitors.index') }}">
    <i class="bi bi-person-lines-fill me-2"></i>
    <span>Visitors</span>
</a>
    </li>

    <!-- Visitor In & Out -->
    <li class="nav-item {{ $active($isCompany ? 'company.visitors.entry.page' : 'visitors.entry.page') }}">
        <a class="nav-link" href="{{ $isCompany ? route('company.visitors.entry.page') : route('visitors.entry.page') }}">
            <i class="bi bi-door-open me-2"></i>
            <span>Visitor In & Out</span>
        </a>
    </li>

    <!-- Visitor History -->
    <li class="nav-item {{ $active($isCompany ? 'company.visitors.history' : 'visitors.history') }}">
        <a class="nav-link" href="{{ $isCompany ? route('company.visitors.history') : route('visitors.history') }}">
            <i class="bi bi-clock-history me-2"></i>
            <span>Visitor History</span>
        </a>
    </li>

    <!-- Reports -->
    <li class="nav-item {{ $active($isCompany ? 'company.visitors.report' : 'visitors.report') }}">
        <a class="nav-link" href="{{ $isCompany ? route('company.visitors.report') : route('visitors.report') }}">
            <i class="bi bi-graph-up-arrow me-2"></i>
            <span>Visitor Reports</span>
        </a>
    </li>

    @if($isCompany)
        <!-- Company-specific pages -->
        <li class="nav-item {{ $active('company.departments.index') }}">
            <a class="nav-link" href="{{ route('company.departments.index') }}">
                <i class="bi bi-building me-2"></i>
                <span>Departments</span>
            </a>
        </li>

        <li class="nav-item {{ $active('company.employees.index') }}">
            <a class="nav-link" href="{{ route('company.employees.index') }}">
                <i class="bi bi-person-workspace me-2"></i>
                <span>Employees</span>
            </a>
        </li>

        <li class="nav-item {{ $active('company.users.index') }}">
            <a class="nav-link" href="{{ route('company.users.index') }}">
                <i class="bi bi-person-bounding-box me-2"></i>
                <span>Users</span>
            </a>
        </li>
    @else
        <!-- Only for Super Admin -->
        <li class="nav-item {{ $active('visitor-categories.index') }}">
            <a class="nav-link" href="{{ route('visitor-categories.index') }}">
                <i class="bi bi-person-rolodex me-2"></i>
                <span>Visitor Categories</span>
            </a>
        </li>

        <li class="nav-item {{ $active('visitors.approvals') }}">
            <a class="nav-link" href="{{ route('visitors.approvals') }}">
                <i class="bi bi-person-check me-2"></i>
                <span>Visitor Approvals</span>
            </a>
        </li>

        <li class="nav-item {{ $active('companies.index') }}">
            <a class="nav-link" href="{{ route('companies.index') }}">
                <i class="bi bi-buildings-fill me-2"></i>
                <span>Companies</span>
            </a>
        </li>

        <li class="nav-item {{ $active('departments.index') }}">
            <a class="nav-link" href="{{ route('departments.index') }}">
                <i class="bi bi-building me-2"></i>
                <span>Departments</span>
            </a>
        </li>

        <!-- <li class="nav-item {{ $active('employees.index') }}">
            <a class="nav-link" href="{{ route('employees.index') }}">
                <i class="bi bi-person-workspace me-2"></i>
                <span>Employees</span>
            </a>
        </li> -->

        <li class="nav-item {{ $active('users.index') }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="bi bi-person-bounding-box me-2"></i>
                <span>Users</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider">

    <!-- Logout -->
    <li class="nav-item mt-3">
        <form method="POST" action="{{ $isCompany ? route('company.logout') : route('logout') }}">
            @csrf
            <button class="nav-link text-start btn text-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </li>

</ul>
<!-- End Sidebar -->
