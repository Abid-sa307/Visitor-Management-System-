@php
    $companyUser = \Illuminate\Support\Facades\Auth::guard('company')->user();
    $normalizeToArray = function ($value) {
        if (is_array($value)) return $value;
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    };

    $pages = $normalizeToArray($companyUser?->master_pages ?? []);

    $menuItems = [
        ['key' => 'dashboard', 'route' => 'company.dashboard', 'label' => 'Dashboard', 'icon' => 'fas fa-fw fa-home'],

        ['key' => 'visitors', 'route' => 'company.visitors.index', 'label' => 'Visitors', 'icon' => 'fas fa-fw fa-users'],
        ['key' => 'visitor_categories', 'route' => 'visitor-categories.index', 'label' => 'Visitor Categories', 'icon' => 'fas fa-fw fa-tags'],
        ['key' => 'visitor_approvals', 'route' => 'company.approvals.index', 'label' => 'Visitor Approvals', 'icon' => 'fas fa-fw fa-user-check'],
        ['key' => 'visitor_in_out', 'route' => 'company.visitors.inout', 'label' => 'Visitor In & Out', 'icon' => 'fas fa-fw fa-sign-in-alt'],
        ['key' => 'visitor_reports', 'route' => 'company.visitors.report', 'label' => 'Visitor Reports', 'icon' => 'fas fa-fw fa-chart-line'],
        ['key' => 'visitor_history', 'route' => 'company.visitors.history', 'label' => 'Visitor History', 'icon' => 'fas fa-fw fa-history'],

        ['key' => 'companies', 'route' => 'companies.index', 'label' => 'Companies', 'icon' => 'fas fa-fw fa-building'],
        ['key' => 'departments', 'route' => 'departments.index', 'label' => 'Departments', 'icon' => 'fas fa-fw fa-sitemap'],
        ['key' => 'employees', 'route' => 'company.employees.index', 'label' => 'Employees', 'icon' => 'fas fa-fw fa-id-badge'],
        ['key' => 'users', 'route' => 'users.index', 'label' => 'Users', 'icon' => 'fas fa-fw fa-user'],

        ['key' => 'settings', 'route' => 'company.settings', 'label' => 'Settings', 'icon' => 'fas fa-fw fa-cogs'],
    ];
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-text mx-3">VMS</div>
    </a>

    <hr class="sidebar-divider my-0">
  

    @foreach ($menuItems as $item)
        @if(in_array($item['key'], $pages, true) && \Illuminate\Support\Facades\Route::has($item['route']))
            <li class="nav-item">
                <a class="nav-link" href="{{ route($item['route']) }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endif
    @endforeach

    <hr class="sidebar-divider d-none d-md-block">
</ul>
