@php
    $isCompany = request()->is('company/*'); // Check if the user is in the company panel
    $active = function ($routes) {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) return 'active'; // Mark the active route
        }
        return '';
    };

    // Define your main menu items
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'route' => $isCompany ? 'company.dashboard' : 'dashboard',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Visitors',
            'icon' => 'bi-person-lines-fill',
            'route' => $isCompany ? 'company.visitors.index' : 'visitors.index',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Visitor In & Out',
            'icon' => 'bi-door-open',
            'route' => $isCompany ? 'company.visitors.entry.page' : 'visitors.entry.page',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Visitor Approvals',
            'icon' => 'bi-door-open',
            'route' => $isCompany ? 'company.visitors.approvals' : 'visitors.approvals',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Visitor History',
            'icon' => 'bi-clock-history',
            'route' => $isCompany ? 'company.visitors.history' : 'visitors.history',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Departments',
            'icon' => 'bi-building',
            'route' => $isCompany ? 'company.departments.index' : 'departments.index',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Company',
            'icon' => 'bi-buildings-fill',
            'route' => $isCompany ? 'company.companies.index' : 'companies.index',
            'roles' => ['superadmin', 'company']
        ],
        [
            'title' => 'Employees',
            'icon' => 'bi-person-workspace',
            'route' => $isCompany ? 'company.employees.index' : 'employees.index',
            'roles' => ['superadmin', 'company']
        ],
    ];

    // Reports (Only for Superadmin)
    $reportItems = [
        [
            'title' => 'Visitor Reports',
            'route' => $isCompany ? 'company.visitors.report' : 'visitors.report',
        ],
        [
            'title' => 'In/Out Reports',
            'route' => $isCompany ? 'company.visitors.report.inout' : 'visitors.report.inout',
        ],
        [
            'title' => 'Approvals Reports',
            'route' => $isCompany ? 'company.visitors.report.approval' : 'visitors.report.approval',
        ],
        [
            'title' => 'Security Reports',
            'route' => $isCompany ? 'company.visitors.report.security' : 'visitors.report.security',
        ],
    ];

    $reportActive = collect($reportItems)->contains(function ($item) use ($active) {
        return $active($item['route']) === 'active';
    });
@endphp

<!-- Main Sidebar Menu -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ $isCompany ? route('company.dashboard') : route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="bi bi-columns-gap fs-4"></i>
        </div>
        <div class="sidebar-brand-text mx-2">VMS Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    @foreach($menuItems as $item)
        @if(in_array(auth()->user()->role, $item['roles']))
            <li class="nav-item {{ $active($item['route']) }}">
                @if(Route::has($item['route']))
                    <a class="nav-link" href="{{ route($item['route']) }}">
                        <i class="bi {{ $item['icon'] }} me-2"></i>
                        <span>{{ $item['title'] }}</span>
                    </a>
                @endif
            </li>
        @endif
    @endforeach

    <!-- Reports Section for Superadmin -->
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
                    @foreach($reportItems as $report)
                        @if(Route::has($report['route']))
                            <a class="collapse-item {{ $active($report['route']) }}"
                               href="{{ route($report['route']) }}">
                               {{ $report['title'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </li>
    @endif

    <!-- Users (Only for Superadmin) -->
    @if(auth()->user()->role === 'superadmin')
        <li class="nav-item {{ $active($isCompany ? 'company.users.index' : 'users.index') }}">
            @if(Route::has($isCompany ? 'company.users.index' : 'users.index'))
                <a class="nav-link" href="{{ $isCompany ? route('company.users.index') : route('users.index') }}">
                    <i class="bi bi-person-bounding-box me-2"></i>
                    <span>Users</span>
                </a>
            @endif
        </li>
    @endif
</ul>
