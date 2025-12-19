@php
    // ---------- Context ----------
    $isCompanyGuard = \Illuminate\Support\Facades\Auth::guard('company')->check();
    $authUser = $isCompanyGuard ? \Illuminate\Support\Facades\Auth::guard('company')->user() : auth()->user();
    $isCompany = $isCompanyGuard;

    // Superadmins see everything
    $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

    // Normalize master_pages to an array (supports casted array or legacy JSON string)
    $normalizeToArray = function ($value) {
        if (is_array($value))
            return $value;
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    };
    $masterPages = $authUser
        ? ($authUser->master_pages_list ?? $normalizeToArray($authUser->master_pages ?? []))
        : [];

    // Gate by page key: superadmins see all; company users must be permitted via master_pages
    $canPage = function (string $key) use ($isSuper, $masterPages) {
        // Super admins can see everything
        if ($isSuper)
            return true;

        // For company users, check if the page is in their master_pages
        return in_array($key, $masterPages, true);
    };

    // UI helpers
    $active = function ($routes) {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route))
                return 'active';
        }
        return '';
    };

    // ------------- Menu Model -------------
    // Add a 'page' key for each item -> the value must match your middleware mapping:
    // dashboard, visitors, visitor_inout, approvals, visitor_history, departments, employees, reports, users
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'route' => $isCompany ? 'company.dashboard' : 'dashboard',
            'page' => 'dashboard',
        ],

        [
            'title' => 'Companies',
            'icon' => 'bi-buildings',
            'route' => 'companies.index',
            'page' => 'companies',
            'super_only' => true,
        ],


        [
            'title' => 'Departments',
            'icon' => 'bi-building',
            'route' => $isCompany ? 'company.departments.index' : 'departments.index',
            'page' => 'departments',
        ],

        [
            'title' => 'Visitors',
            'icon' => 'bi-person-lines-fill',
            'route' => $isCompany ? 'company.visitors.index' : 'visitors.index',
            'page' => 'visitors',
        ],

        [
            'title' => 'Security Checks',
            'icon' => 'bi-shield-check',
            'route' => $isCompany ? 'company.security-checks.index' : 'security-checks.index',
            'page' => 'security_checks',
        ],

        [
            'title' => 'Visitor Approvals',
            'icon' => 'bi-check2-circle',
            'route' => $isCompany ? 'company.approvals.index' : 'visitors.approvals',
            'page' => 'approvals',
        ],
        [
            'title' => 'Visitor In & Out',
            'icon' => 'bi-door-open',
            'route' => $isCompany ? 'company.visitors.entry.page' : 'visitors.entry.page',
            'page' => 'visitor_inout',
        ],

        [
            'title' => 'Visitor History',
            'icon' => 'bi-clock-history',
            'route' => $isCompany ? 'company.visitors.history' : 'visitors.history',
            'page' => 'visitor_history',
        ],
        [
            'title' => 'QR Scanner',
            'icon' => 'bi-qr-code-scan',
            'route' => $isCompany ? 'company.qr.scanner' : 'qr.scanner',
            'page' => 'qr_scanner',
        ],

        // Companies (superadmin only; never visible to company users)

        [
            'title' => 'Employees',
            'icon' => 'bi-person-workspace',
            'route' => $isCompany ? 'company.employees.index' : 'employees.index',
            'page' => 'employees',
        ],
    ];

    $reportItems = [
        ['title' => '👥 Visitor Report', 'route' => $isCompany ? 'company.reports.visitors' : 'reports.visitors', 'page' => 'reports'],
        ['title' => '🚪 In/Out Report', 'route' => $isCompany ? 'company.reports.visits' : 'reports.inout', 'page' => 'reports'],
        ['title' => '🛡️ Security Checkpoints', 'route' => $isCompany ? 'company.reports.security' : 'reports.security', 'page' => 'reports'],
        ['title' => '✅ Approval Status', 'route' => $isCompany ? 'company.reports.approval' : 'reports.approval', 'page' => 'reports'],
        ['title' => '⏰ Hourly Report', 'route' => $isCompany ? 'company.reports.hourly' : 'reports.hourly', 'page' => 'reports']
    ];
    $reportActive = collect($reportItems)->contains(fn($i) => request()->routeIs($i['route']));

    // Users (superadmin only, or gate via page key 'users' if you want)
    $usersRoute = $isCompany ? 'company.users.index' : 'users.index';
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="{{ $isCompany ? route('company.dashboard') : route('dashboard') }}">
        <div class="sidebar-brand-icon"><i class="bi bi-columns-gap fs-4"></i></div>
        <div class="sidebar-brand-text mx-2">VMS Panel</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Main items (gated by master_pages) -->
    @foreach($menuItems as $item)
        @php $superOnly = $item['super_only'] ?? false; @endphp
        @if(($superOnly ? $isSuper : true) && Route::has($item['route']) && $canPage($item['page']))
            <li class="nav-item {{ $active($item['route']) }}">
                <a class="nav-link" href="{{ route($item['route']) }}">
                    <i class="bi {{ $item['icon'] }} me-2"></i>
                    <span>{{ $item['title'] }}</span>
                </a>
            </li>
        @endif
    @endforeach

    <!-- QR Codes Section -->
    @if($isSuper || $canPage('qr_codes'))
        <li class="nav-item {{ $active(['qr-management.*']) }}">
            <a class="nav-link" href="{{ url('/qr-management') }}">
                <i class="bi bi-qr-code-scan me-2"></i>
                <span>QR Codes</span>
            </a>
        </li>
    @endif

    @if($isSuper || $canPage('visitor_categories'))
        <li class="nav-item {{ $active('visitor-categories.*') }}">
            <a class="nav-link" href="{{ route('visitor-categories.index') }}">
                <i class="fas fa-tags me-2"></i>
                <span>Visitor Categories</span>
            </a>
        </li>
    @endif

    <!-- Users (visible only if user has 'users' permission; superadmin always) -->
    @if(Route::has($usersRoute) && ($isSuper || $canPage('users')))
        <li class="nav-item {{ $active($usersRoute) }}">
            <a class="nav-link" href="{{ route($usersRoute) }}">
                <i class="bi bi-person-bounding-box me-2"></i>
                <span>Users</span>
            </a>
        </li>
    @endif

    <!-- Check Reports -->
    <li class="nav-item {{ $reportActive ? 'active' : '' }}">
        <a class="nav-link {{ $reportActive ? '' : 'collapsed' }}"
           href="#" 
           data-toggle="collapse"
           data-target="#collapseCheckReports"
           aria-expanded="{{ $reportActive ? 'true' : 'false' }}"
           aria-controls="collapseCheckReports">
            <i class="bi bi-clipboard-data me-2"></i>
            <span>Check Reports</span>
        </a>

        <div id="collapseCheckReports" class="collapse {{ $reportActive ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ $isCompany ? route('company.reports.visitors') : route('reports.visitors') }}">
                    <i class="fas fa-users me-2"></i>Visitor Report
                </a>
                <a class="collapse-item" href="{{ $isCompany ? route('company.reports.visits') : route('reports.inout') }}">
                    <i class="fas fa-door-open me-2"></i>In/Out Report
                </a>
                <a class="collapse-item" href="{{ $isCompany ? route('company.reports.security') : route('reports.security') }}">
                    <i class="fas fa-shield-alt me-2"></i>Security Checkpoints
                </a>
                <a class="collapse-item" href="{{ $isCompany ? route('company.reports.approval') : route('reports.approval') }}">
                    <i class="fas fa-check-circle me-2"></i>Approval Status
                </a>
                <a class="collapse-item" href="{{ $isCompany ? route('company.reports.hourly') : route('reports.hourly') }}">
                    <i class="fas fa-clock me-2"></i>Hourly Report
                </a>
            </div>
        </div>
    </li>




    <hr class="sidebar-divider d-none d-md-block">

</ul>

<style>
    /* Check Reports dropdown styling */
    /*  */
    
    #accordionSidebar #collapseCheckReports .collapse-inner .collapse-item {
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 0.75rem 1rem;
        display: block;
        text-decoration: none;
        border-radius: 6px;
        margin: 2px 4px;
        transition: all 0.2s ease;
    }

    #accordionSidebar #collapseCheckReports .collapse-inner .collapse-item:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.1));
        color: #fff !important;
        transform: translateX(3px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    /* Hide the SB Admin footer */
    .sidebar .text-center {
        display: none !important;
    }

    /* More specific selector if needed */
    #accordionSidebar>.text-center:last-child {
        display: none !important;
    }

    /* Target the specific text if needed */
    #accordionSidebar>div:contains('SB Admin v7.0.7') {
        display: none !important;
    }

    /* Mobile styles handled by parent layout */
    #accordionSidebar {
         
        height: 100vh;
        overflow-y: auto;
    }
@media (max-width: 768px) {
  #accordionSidebar {
    width: 135px !important;
  }
  
  .nav-link span:not(.chev) {
    display: none;
  }
}



    
</style>

