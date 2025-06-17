<style>
    .sidebar .nav-link:hover {
        background:rgb(208, 215, 231);
        border-radius: 6px;
        text-decoration: none;
        transform: translateX(3px);
        transition: all 0.2s ease-in-out;
    }
</style>

<div class="sidebar bg-light p-4 vh-100 border-end" style="width: 240px;">
    <h5 class="mb-4 pb-2 border-bottom d-flex align-items-center text-primary">
        <i class="bi bi-columns-gap me-2 fs-4"></i> Panel
    </h5>

    <ul class="nav flex-column mt-3">
        <li class="nav-item mb-2">
            <a href="{{ route('dashboard') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('visitors.index') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-2"></i> Visitors
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('visitors.history') }}" class="nav-link text-dark">
                <i class="bi bi-clock-history me-2"></i> Visitor History
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('users.index') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi bi-person-bounding-box me-2"></i> Users
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('companies.index') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi bi-buildings-fill me-2"></i> Companies
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('departments.index') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi bi-building me-2"></i> Departments
            </a>
        </li>
        <li>
            <a href="{{ route('visitor-categories.index') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi bi-person-rolodex me-2"></i> Visitor Categories
            </a>
        </li>
        <li>
            <a href="{{ route('employees.index') }}" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi bi-person-rolodex me-2"></i> Employees
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('visitors.entry.page') }}" class="nav-link text-dark">
                <i class="bi bi-clock-history me-2"></i> Visitor In and Out
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('visitors.report') }}" class="nav-link text-dark">
                <i class="bi bi-graph-up-arrow me-2"></i> Visitor Reports
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-dark d-flex align-items-center">
                <i class="bi bi-gear me-2"></i> Settings
            </a>
        </li>
        <li class="nav-item mt-4">
            <a href="#" class="nav-link text-danger d-flex align-items-center">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>
