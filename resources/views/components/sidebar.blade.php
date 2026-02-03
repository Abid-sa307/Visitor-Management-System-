@php
    use Illuminate\Support\Str;

    $isCompanyGuard = \Illuminate\Support\Facades\Auth::guard('company')->check();
    $authUser = $isCompanyGuard ? \Illuminate\Support\Facades\Auth::guard('company')->user() : auth()->user();
    $isCompany = $isCompanyGuard;

    $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

    $normalizeToArray = function ($value) {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    };

    $masterPages = $authUser
        ? ($authUser->master_pages_list ?? $normalizeToArray($authUser->master_pages ?? []))
        : [];

    $canPage = function (string $key) use ($isSuper, $masterPages) {
        if ($isSuper) {
            return true;
        }

        return in_array($key, $masterPages, true);
    };

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
            'route' => 'departments.index',
            'page' => 'departments',
        ],
        [
            'title' => 'Visitors',
            'icon' => 'bi-person-lines-fill',
            'route' => $isCompany ? 'company.visitors.index' : 'visitors.index',
            'page' => 'visitors',
            'dropdown' => [
                [
                    'title' => 'All Visitors',
                    'route' => $isCompany ? 'company.visitors.index' : 'visitors.index',
                    'icon' => 'fas fa-users',
                ],
                [
                    'title' => 'Visit Details',
                    'route' => $isCompany ? 'company.visits.index' : 'visits.index',
                    'icon' => 'fas fa-calendar-check',
                ],
            ],
        ],
        [
            'title' => 'Security',
            'icon' => 'bi-shield-check',
            'route' => $isCompany ? 'company.security-checks.index' : 'security-checks.index',
            'page' => 'security_checks',
            'dropdown' => [
                [
                    'title' => 'Check In & Check Out',
                    'route' => $isCompany ? 'company.security-checks.index' : 'security-checks.index',
                    'icon' => 'fas fa-shield-alt',
                ],
                [
                    'title' => 'Security Questions',
                    'route' => $isCompany ? 'company.security-questions.index' : 'security-questions.index',
                    'icon' => 'fas fa-question-circle',
                ],
            ],
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
        [
            'title' => 'Employees',
            'icon' => 'bi-person-workspace',
            'route' => $isCompany ? 'company.employees.index' : 'employees.index',
            'page' => 'employees',
        ],
    ];

    $reportItems = collect([
        [
            'title' => 'Visitor Report',
            'icon' => 'fas fa-users',
            'route' => $isCompany ? 'company.reports.visitors' : 'reports.visitors',
        ],
        [
            'title' => 'In/Out Report',
            'icon' => 'fas fa-door-open',
            'route' => $isCompany ? 'company.reports.visits' : 'reports.inout',
        ],
        [
            'title' => 'Security Checkpoints',
            'icon' => 'fas fa-shield-alt',
            'route' => $isCompany ? 'company.reports.security' : 'reports.security',
            'flag' => 'security',
        ],
        [
            'title' => 'Approval Status',
            'icon' => 'fas fa-check-circle',
            'route' => $isCompany ? 'company.reports.approval' : 'reports.approval',
            'flag' => 'approval',
        ],
        [
            'title' => 'Hourly Report',
            'icon' => 'fas fa-clock',
            'route' => $isCompany ? 'company.reports.hourly' : 'reports.hourly',
        ],
    ]);

    $company = $authUser->company ?? null;
    $showApprovalReport = !$company || !$company->auto_approved;
    $showSecurityReport = !$company || $company->security_check_service_type !== 'none';

    $reportItems = $reportItems->filter(function ($item) use ($showApprovalReport, $showSecurityReport) {
        if (($item['flag'] ?? null) === 'security') {
            return $showSecurityReport;
        }
        if (($item['flag'] ?? null) === 'approval') {
            return $showApprovalReport;
        }
        return true;
    });

    $reportActive = $reportItems->contains(function ($item) {
        return Route::has($item['route']) && request()->routeIs($item['route']);
    });

    $usersRoute = $isCompany ? 'company.users.index' : 'users.index';
@endphp

<aside class="sidebar-shell" id="sidebarShell">
    <div class="sidebar-shell__inner">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-3">
                <div class="sidebar-brand__icon">
                    <i class="bi bi-columns-gap"></i>
                </div>
                <div>
                    <p class="sidebar-footer__eyebrow mb-1 text-white-50">Visitor OS</p>
                    <p class="sidebar-brand__title mb-0">VMS Command</p>
                </div>
            </div>
            <button id="sidebarClose" class="btn btn-outline-light btn-sm d-lg-none" type="button" aria-label="Close sidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-scroll">
            <div class="sidebar-section">
                <p class="sidebar-section__label">Navigation</p>
                <ul class="sidebar-section__list">
                    @foreach($menuItems as $item)
                        @php
                            $superOnly = $item['super_only'] ?? false;
                            $isDropdown = isset($item['dropdown']);
                            $canSee = ($superOnly ? $isSuper : $canPage($item['page']))
                                && (Route::has($item['route']) || $item['title'] === 'Visitor Approvals' || $isDropdown);

                            if (!$canSee) {
                                continue;
                            }

                            $dropdownActive = $isDropdown
                                ? collect($item['dropdown'])->contains(fn ($d) => Route::has($d['route']) && request()->routeIs($d['route']))
                                : false;
                            $linkIsActive = !$isDropdown && Route::has($item['route']) && request()->routeIs($item['route']);
                            $subnavId = $isDropdown ? 'sidebar-subnav-' . Str::slug($item['title']) : null;
                        @endphp

                        <li class="sidebar-item {{ $isDropdown ? 'has-children' : '' }} {{ $dropdownActive ? 'is-open' : '' }}">
                            @if($isDropdown)
                                <button class="sidebar-link {{ $dropdownActive ? 'is-active' : '' }}" type="button" data-sidebar-toggle="{{ $subnavId }}">
                                    <span class="sidebar-link__icon">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>
                                    <span>{{ $item['title'] }}</span>
                                    <i class="bi bi-chevron-down sidebar-link__chevron"></i>
                                </button>
                                <div class="sidebar-subnav {{ $dropdownActive ? 'show' : '' }}" id="{{ $subnavId }}">
                                    @foreach($item['dropdown'] as $subItem)
                                        @if(Route::has($subItem['route']))
                                            <a class="sidebar-sublink {{ request()->routeIs($subItem['route']) ? 'is-active' : '' }}" href="{{ route($subItem['route']) }}">
                                                <i class="{{ $subItem['icon'] }} me-2"></i>{{ $subItem['title'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                @php
                                    $resolvedRoute = Route::has($item['route'])
                                        ? route($item['route'])
                                        : ($item['title'] === 'Visitor Approvals' ? url('/visitor-approvals') : '#');
                                @endphp
                                <a class="sidebar-link {{ $linkIsActive ? 'is-active' : '' }}" href="{{ $resolvedRoute }}" data-sidebar-link>
                                    <span class="sidebar-link__icon">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>
                                    <span>{{ $item['title'] }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-section">
                <p class="sidebar-section__label">Workflows</p>
                <ul class="sidebar-section__list">
                    @if($isSuper || $canPage('qr_scanner'))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('qr-management*') ? 'is-active' : '' }}" href="{{ url('/qr-management') }}" data-sidebar-link>
                                <span class="sidebar-link__icon"><i class="bi bi-qr-code-scan"></i></span>
                                <span>QR Codes</span>
                            </a>
                        </li>
                    @endif

                    @if($isSuper || $canPage('visitor_categories'))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeIs('visitor-categories.*') ? 'is-active' : '' }}" href="{{ route('visitor-categories.index') }}" data-sidebar-link>
                                <span class="sidebar-link__icon"><i class="fas fa-tags"></i></span>
                                <span>Visitor Categories</span>
                            </a>
                        </li>
                    @endif

                    @if(Route::has($usersRoute) && ($isSuper || $canPage('users')))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeIs($usersRoute) ? 'is-active' : '' }}" href="{{ route($usersRoute) }}" data-sidebar-link>
                                <span class="sidebar-link__icon"><i class="bi bi-person-bounding-box"></i></span>
                                <span>Users</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            @if(($isSuper || $canPage('reports')) && $reportItems->isNotEmpty())
                <div class="sidebar-section">
                    <p class="sidebar-section__label">Insights & Reports</p>
                    <ul class="sidebar-section__list">
                        @php
                            $reportsSubnavId = 'sidebar-subnav-reports';
                        @endphp
                        <li class="sidebar-item has-children {{ $reportActive ? 'is-open' : '' }}">
                            <button class="sidebar-link {{ $reportActive ? 'is-active' : '' }}" type="button" data-sidebar-toggle="{{ $reportsSubnavId }}">
                                <span class="sidebar-link__icon"><i class="bi bi-clipboard-data"></i></span>
                                <span>Reports Hub</span>
                                <i class="bi bi-chevron-down sidebar-link__chevron"></i>
                            </button>
                            <div class="sidebar-subnav {{ $reportActive ? 'show' : '' }}" id="{{ $reportsSubnavId }}">
                                @foreach($reportItems as $report)
                                    @if(Route::has($report['route']))
                                        <a class="sidebar-sublink {{ request()->routeIs($report['route']) ? 'is-active' : '' }}" href="{{ route($report['route']) }}">
                                            <i class="{{ $report['icon'] }} me-2"></i>{{ $report['title'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

        <div class="sidebar-footer">
            <p class="sidebar-footer__eyebrow mb-2">Signed In</p>
            <p class="sidebar-footer__text mb-1">{{ $authUser->name ?? 'Guest User' }}</p>
            <p class="sidebar-footer__text small text-white-50 mb-0">
                {{ $isCompany ? 'Company Workspace' : ($authUser->role ?? 'Team Member') }}
            </p>
        </div>
    </div>
</aside>
