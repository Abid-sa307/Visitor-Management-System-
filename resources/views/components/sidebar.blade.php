@php
    // ---------- Context ----------
    $authUser = auth()->user();
    $isCompany = request()->is('company/*');

    // Superadmins see everything
    $isSuper = $authUser && in_array($authUser->role, ['super_admin','superadmin'], true);

    // Normalize master_pages to an array (supports casted array or legacy JSON string)
    $normalizeToArray = function ($value) {
        if (is_array($value)) return $value;
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    };
    $masterPages = $authUser
        ? ($authUser->master_pages_list ?? $normalizeToArray($authUser->master_pages ?? []))
        : [];

    // Gate by page key (matches your CheckMasterPageAccess middleware keys)
    $canPage = fn (string $key) => $isSuper || in_array($key, $masterPages, true);

    // UI helpers
    $active = function ($routes) {
        foreach ((array)$routes as $route) {
            if (request()->routeIs($route)) return 'active';
        }
        return '';
    };

    // ------------- Menu Model -------------
    // Add a 'page' key for each item -> the value must match your middleware mapping:
    // dashboard, visitors, visitor_inout, approvals, visitor_history, departments, employees, reports, users
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon'  => 'bi-speedometer2',
            'route' => $isCompany ? 'company.dashboard' : 'dashboard',
            'page'  => 'dashboard',
        ],
        [
            'title' => 'Visitors',
            'icon'  => 'bi-person-lines-fill',
            'route' => $isCompany ? 'company.visitors.index' : 'visitors.index',
            'page'  => 'visitors',
        ],
        [
            'title' => 'Visitor In & Out',
            'icon'  => 'bi-door-open',
            'route' => $isCompany ? 'company.visitors.entry.page' : 'visitors.entry.page',
            'page'  => 'visitor_inout',
        ],
        [
            'title' => 'Visitor Approvals',
            'icon'  => 'bi-check2-circle',
            'route' => $isCompany ? 'company.visitors.approvals' : 'visitors.approvals',
            'page'  => 'approvals',
        ],
        [
            'title' => 'Visitor History',
            'icon'  => 'bi-clock-history',
            'route' => $isCompany ? 'company.visitors.history' : 'visitors.history',
            'page'  => 'visitor_history',
        ],
        [
            'title' => 'Companies',
            'icon'  => 'bi-buildings',
            'route' => 'companies.index',
            'page'  => 'departments',
        ],
        [
            'title' => 'Departments',
            'icon'  => 'bi-building',
            'route' => $isCompany ? 'company.departments.index' : 'departments.index',
            'page'  => 'departments',
        ],
        [
            'title' => 'Employees',
            'icon'  => 'bi-person-workspace',
            'route' => $isCompany ? 'company.employees.index' : 'employees.index',
            'page'  => 'employees',
        ],
    ];

    $reportItems = [
        ['title' => 'Visitor Reports',   'route' => $isCompany ? 'company.visitors.report'          : 'visitors.report',           'page' => 'reports'],
        ['title' => 'In/Out Reports',    'route' => $isCompany ? 'company.visitors.report.inout'    : 'visitors.report.inout',     'page' => 'reports'],
        ['title' => 'Approvals Reports', 'route' => $isCompany ? 'company.visitors.report.approval' : 'visitors.report.approval',  'page' => 'reports'],
        ['title' => 'Security Reports',  'route' => $isCompany ? 'company.visitors.report.security' : 'visitors.report.security',  'page' => 'reports'],
    ];
    $reportActive = collect($reportItems)->contains(fn($i) => request()->routeIs($i['route']));

    // Users (superadmin only, or gate via page key 'users' if you want)
    $usersRoute = $isCompany ? 'company.users.index' : 'users.index';
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ $isCompany ? route('company.dashboard') : route('dashboard') }}">
        <div class="sidebar-brand-icon"><i class="bi bi-columns-gap fs-4"></i></div>
        <div class="sidebar-brand-text mx-2">VMS Panel</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Main items (gated by master_pages) -->
    @foreach($menuItems as $item)
        @if(Route::has($item['route']) && $canPage($item['page']))
            <li class="nav-item {{ $active($item['route']) }}">
                <a class="nav-link" href="{{ route($item['route']) }}">
                    <i class="bi {{ $item['icon'] }} me-2"></i>
                    <span>{{ $item['title'] }}</span>
                </a>
            </li>
        @endif
    @endforeach

    <!-- Reports (group visible only if user has "reports") -->
    @if($canPage('reports'))
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
                        @if(Route::has($report['route'])) {{-- child links share "reports" key --}}
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

    <!-- Users (superadmin bypass; or gate via page 'users' if you prefer) -->
    @if(Route::has($usersRoute) && ($isSuper /* || $canPage('users') */))
        <li class="nav-item {{ $active($usersRoute) }}">
            <a class="nav-link" href="{{ route($usersRoute) }}">
                <i class="bi bi-person-bounding-box me-2"></i>
                <span>Users</span>
            </a>
        </li>
    @endif
</ul>
