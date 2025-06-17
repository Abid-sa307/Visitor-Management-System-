

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- End of Sidebar -->
    <!-- Sidebar Toggler -->
    <li class="nav-item text-center d-none d-md-inline mt-3">
        <button class="rounded-circle border-0" id="sidebarToggle">
            
        </button>
    </li> 
    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="bi bi-columns-gap fs-4"></i>
        </div>
        <div class="sidebar-brand-text mx-2">VMS Panel</div>
    </a>
    
    <hr class="sidebar-divider my-0">
    
    @php
        $active = fn($route) => request()->routeIs($route) ? 'active' : '';
    @endphp

<li class="nav-item {{ $active('dashboard') }}">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>
        <span>Dashboard</span>
    </a>
</li>

<!-- Add other nav items similarly... -->
<li class="nav-item {{ $active('visitors.index') }}">
    <a class="nav-link" href="{{ route('visitors.index') }}">
        <i class="bi bi-person-lines-fill me-2"></i>
        <span>Visitors</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('visitors.approvals') }}">
        <i class="bi bi-person-check me-2"></i>
        <span>Visitor Approvals</span>
    </a>
</li>


<li class="nav-item {{ $active('visitors.history') }}">
    <a class="nav-link" href="{{ route('visitors.history') }}">
        <i class="bi bi-clock-history me-2"></i>
        <span>Visitor History</span>
    </a>
</li>

<li class="nav-item {{ $active('users.index') }}">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class="bi bi-person-bounding-box me-2"></i>
        <span>Users</span>
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
    
    <li class="nav-item {{ $active('visitor-categories.index') }}">
        <a class="nav-link" href="{{ route('visitor-categories.index') }}">
            <i class="bi bi-person-rolodex me-2"></i>
            <span>Visitor Categories</span>
        </a>
    </li>
    
    <li class="nav-item {{ $active('employees.index') }}">
        <a class="nav-link" href="{{ route('employees.index') }}">
            <i class="bi bi-person-workspace me-2"></i>
            <span>Employees</span>
        </a>
    </li>
    
    <li class="nav-item {{ $active('visitors.entry.page') }}">
        <a class="nav-link" href="{{ route('visitors.entry.page') }}">
            <i class="bi bi-door-open me-2"></i>
            <span>Visitor In & Out</span>
        </a>
    </li>
    
    <li class="nav-item {{ $active('visitors.report') }}">
        <a class="nav-link" href="{{ route('visitors.report') }}">
            <i class="bi bi-graph-up-arrow me-2"></i>
            <span>Visitor Reports</span>
        </a>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-gear me-2"></i>
            <span>Settings</span>
        </a>
    </li>
    
    <!-- Logout -->
    
    <li class="nav-item mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="nav-link text-start btn text-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </li>
    
    <hr class="sidebar-divider d-none d-md-block">

    
</ul>
<!-- Sidebar Toggler (needs to be outside the <ul>) -->
<script src="{{ asset('sb-admin/js/sb-admin-2.min.js') }}"></script>
<style>
    #sidebarToggleCenter i {
        transition: transform 0.3s ease;
    }
</style>



