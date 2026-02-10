<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(auth()->check() && auth()->user()->company_id)
        <meta name="company-id" content="{{ auth()->user()->company_id }}">
    @endif
    <title>{{ $title ?? 'Dashboard' }} - VMS</title>

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('sb-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb-admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    @include('layouts.partials.theme')
    @stack('styles')

    <!-- Face API JS -->
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Simple check to see if face-api is loaded
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof faceapi === 'undefined') {
                console.error('Face API not loaded!');
                // Show error in the console
                const errorDiv = document.createElement('div');
                errorDiv.style.position = 'fixed';
                errorDiv.style.top = '10px';
                errorDiv.style.right = '10px';
                errorDiv.style.zIndex = '9999';
                errorDiv.style.padding = '15px';
                errorDiv.style.background = '#dc3545';
                errorDiv.style.color = 'white';
                errorDiv.style.borderRadius = '5px';
                errorDiv.innerHTML = '<strong>Error:</strong> Face detection not working. Please check console for details.';
                document.body.appendChild(errorDiv);
            }
        });
    </script>

</head>

<body id="page-top" class="has-sidebar">
    @php
        /**
         * Normalize & gate page access for the current user
         * (No json_decode on arrays; supports legacy JSON strings too)
         */
        $authUser = auth()->user();

        // Super admins see everything
        $isSuper = $authUser && in_array($authUser->role, ['super_admin', 'superadmin'], true);

        // Normalize master_pages to an array (works for casted arrays and legacy JSON strings)
        $normalizeToArray = function ($value) {
            if (is_array($value))
                return $value;
            if (is_string($value) && $value !== '') {
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            }
            return [];
        };

        // Prefer model accessor if present; else normalize raw column
        $masterPages = $authUser
            ? (method_exists($authUser, 'getMasterPagesListAttribute')
                ? ($authUser->master_pages_list ?? [])
                : $normalizeToArray($authUser->master_pages ?? []))
            : [];

        // Tiny helper for sidebar/topbar/anywhere:
        $can = fn(string $key) => $isSuper || in_array($key, $masterPages, true);
    @endphp

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('partials.sidebar', ['can' => $can, 'isSuper' => $isSuper, 'masterPages' => $masterPages])
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('partials.topbar')

                <!-- Notification Permission Request -->
                <div id="notification-request-banner" class="alert alert-info border-0 rounded-0 mb-0 text-center"
                    style="display: none;">
                    <strong><i class="fas fa-bell"></i> Enable Notifications:</strong>
                    Get real-time alerts for visitor arrivals and approvals.
                    <button id="enable-notifs-btn" class="btn btn-sm btn-primary ml-3">
                        Enable Now
                    </button>
                    <button
                        onclick="document.getElementById('notification-request-banner').style.display='none'; localStorage.setItem('hide_notif_banner', 'true')"
                        class="btn btn-sm btn-link text-muted ml-2">
                        Dismiss
                    </button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const banner = document.getElementById('notification-request-banner');
                        const status = Notification.permission;

                        if (status === 'default' && !localStorage.getItem('hide_notif_banner')) {
                            banner.style.display = 'block';
                            banner.classList.add('alert-info');
                        } else if (status === 'denied') {
                            banner.style.display = 'block';
                            banner.classList.add('alert-danger');
                            banner.innerHTML = `<strong><i class="fas fa-ban"></i> Notifications Blocked</strong>. Please click the Lock icon 🔒 in your address bar to Allow notifications.`;
                        } else {
                            // Granted - Hide banner
                            banner.style.display = 'none';
                        }

                        // Handler for enable button 
                        const btn = document.getElementById('enable-notifs-btn');
                        if (btn) {
                            btn.addEventListener('click', function () {
                                Notification.requestPermission().then(function (result) {
                                    if (result === 'granted') {
                                        // Play test sound to unlock audio engine
                                        if (window.visitorNotifications) {
                                            window.visitorNotifications.playNotificationSound();
                                        }
                                        banner.style.display = 'none';
                                        alert('Notifications enabled! You will now hear alerts.');
                                    } else {
                                        window.location.reload();
                                    }
                                });
                            });
                        }
                    });
                </script>
                <!-- End Notification Permission Request -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{-- Flash error from access middleware (or other redirects) --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Flash success messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- End Page Content -->

            </div>
            <!-- End Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="text-center my-auto">
                        <span>&copy; 2025 Visitor Management System <br> <a href="https://www.nntsoftware.com"
                                target="_blank">(Developed By N&T Software)</a></span>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const sidebarShell = document.getElementById('sidebarShell');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const openTrigger = document.getElementById('sidebarToggle');
            const closeTrigger = document.getElementById('sidebarClose');

            const toggleSidebar = (forceOpen = null) => {
                const shouldOpen = typeof forceOpen === 'boolean'
                    ? forceOpen
                    : !sidebarShell.classList.contains('is-open');

                if (shouldOpen) {
                    sidebarShell.classList.add('is-open');
                    sidebarOverlay.classList.add('show');
                    body.classList.add('sidebar-open');
                } else {
                    sidebarShell.classList.remove('is-open');
                    sidebarOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                }
            };

            openTrigger?.addEventListener('click', () => toggleSidebar(true));
            closeTrigger?.addEventListener('click', () => toggleSidebar(false));
            sidebarOverlay?.addEventListener('click', () => toggleSidebar(false));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    toggleSidebar(false);
                }
            });

            const collapseOnMobile = (callback) => {
                if (window.matchMedia('(max-width: 991.98px)').matches) {
                    callback();
                }
            };

            document.querySelectorAll('[data-sidebar-link]').forEach((link) => {
                link.addEventListener('click', () => collapseOnMobile(() => toggleSidebar(false)));
            });

            document.querySelectorAll('[data-sidebar-toggle]').forEach((toggleBtn) => {
                const targetId = toggleBtn.getAttribute('data-sidebar-toggle');
                const target = document.getElementById(targetId);
                const parentItem = toggleBtn.closest('.sidebar-item');

                toggleBtn.addEventListener('click', () => {
                    const isOpen = parentItem?.classList.toggle('is-open');
                    toggleBtn.classList.toggle('is-active', Boolean(isOpen));
                    target?.classList.toggle('show', Boolean(isOpen));
                });
            });
        });
    </script>

    <!-- Modern Date Range Picker Styles -->
    <style>
        /* Modern Form Select Dropdowns */
        /* Standard Form Select (Reverted) */
        .form-select {
            /* Using Bootstrap defaults */
            cursor: pointer;
        }

        .form-select:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Modern Date Range Picker Button */
        .date-range-picker {
            background: #007bff;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .date-range-picker::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .date-range-picker:hover::before {
            left: 100%;
        }

        .date-range-picker:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .date-range-picker:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.3), 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .date-range-picker i {
            font-size: 1rem;
            opacity: 0.9;
        }

        .date-range-picker i.fa-chevron-down {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .date-range-picker[aria-expanded="true"] i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .date-range-dropdown.show,
        .dropdown-menu.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Modern Section Headers */
        .date-range-dropdown .small.text-muted.fw-semibold {
            color: #007bff !important;
            font-weight: 600 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin-bottom: 12px !important;
        }

        /* Modern Preset Buttons */
        .date-range-dropdown .date-preset {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            color: #495057;
            font-weight: 500;
            padding: 10px 16px;
            transition: all 0.2s ease;
            text-align: left;
            display: block;
            margin-bottom: 4px;
            text-decoration: none;
        }

        #dateRangeDropdown.show {
            display: block !important;
        }

        #dateRangeDropdown .small.text-muted.fw-semibold,
        .date-range-dropdown .small.text-muted.fw-semibold {
            color: #4e73df !important;
            margin-bottom: 10px;
        }

        #dateRangeDropdown .date-preset,
        .date-range-dropdown .date-preset {
            padding: 8px 12px;
            border-radius: 8px;
            color: #4e73df;
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        #dateRangeDropdown .date-preset:hover,
        .date-range-dropdown .date-preset:hover {
            background-color: #f8f9fc;
            border-color: #4e73df;
            color: #2e59d9;
        }

        #dateRangeDropdown .date-preset:active,
        .date-range-dropdown .date-preset:active {
            background-color: #eaecf4;
        }

        #dateRangeDropdown .form-control,
        .date-range-dropdown .form-control {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 8px 12px;
            font-size: 14px;
        }

        #dateRangeDropdown .form-control:focus,
        .date-range-dropdown .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        #dateRangeDropdown .btn-outline-secondary,
        .date-range-dropdown .btn-outline-secondary {
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 14px;
            color: #858796;
            border: 1px solid #d1d3e2;
        }

        #dateRangeDropdown .btn-outline-secondary:hover,
        .date-range-dropdown .btn-outline-secondary:hover {
            background-color: #f8f9fc;
            color: #5a5c69;
        }

        #dateRangeDropdown .btn-primary,
        .date-range-dropdown .btn-primary {
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 14px;
            background-color: #4e73df;
            border: none;
        }

        #dateRangeDropdown .btn-primary:hover,
        .date-range-dropdown .btn-primary:hover {
            background-color: #2e59d9;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35);
        }

        @media (max-width: 768px) {

            #dateRangeDropdown,
            .date-range-dropdown {
                width: 100%;
                min-width: unset;
                left: 0 !important;
                right: 0 !important;
            }
        }
    </style>

    <!-- Dashboard Visual System -->
    <style>
        .dashboard-hero {
            background: var(--brand-primary);
            color: white;
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 28px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 24px;
            box-shadow: 0 12px 30px rgba(0, 123, 255, 0.25);
        }

        .hero-eyebrow {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            opacity: 0.75;
            margin-bottom: 8px;
        }

        .dashboard-hero h1 {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .hero-subtitle {
            opacity: 0.9;
            max-width: 520px;
            margin: 0;
        }

        .hero-metrics {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .metric-chip {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            padding: 16px 18px;
            min-width: 160px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .metric-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            display: block;
            margin-top: 4px;
        }

        .modern-panel {
            background: var(--surface);
            border-radius: 18px;
            border: 1px solid var(--border-color);
            padding: 24px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        }

        .filter-card__header {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 18px;
        }

        .filter-card__icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(0, 123, 255, 0.1);
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .filter-card__body label {
            font-weight: 600;
            color: #1f2937;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            gap: 16px;
            align-items: center;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        }

        .stat-card__icon {
            width: 52px;
            height: 52px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--brand-primary);
            background: rgba(0, 123, 255, 0.12);
        }

        .stat-card__label {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .stat-card__value {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .stat-card__subtext {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .accent-success .stat-card__icon {
            color: #198754;
            background: rgba(25, 135, 84, 0.12);
        }

        .accent-warning .stat-card__icon {
            color: #ffb300;
            background: rgba(255, 179, 0, 0.12);
        }

        .accent-danger .stat-card__icon {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.12);
        }

        .panel-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .panel-eyebrow {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .panel-count {
            font-weight: 600;
            color: var(--brand-primary);
            background: rgba(0, 123, 255, 0.1);
            padding: 6px 14px;
            border-radius: 999px;
        }

        .modern-table table {
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .modern-table thead th {
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 1px;
            color: var(--text-muted);
            border: none;
        }

        .modern-table tbody tr {
            box-shadow: inset 0 0 0 1px var(--border-color);
            border-radius: 14px;
        }

        .modern-table tbody tr td {
            background: var(--surface);
            border-top: none;
            border-bottom: none;
        }

        .modern-table tbody tr td:first-child {
            border-radius: 14px 0 0 14px;
        }

        .modern-table tbody tr td:last-child {
            border-radius: 0 14px 14px 0;
        }

        .avatar-chip {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--surface-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--brand-primary);
        }

        .status-pill {
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: capitalize;
        }

        .status-approved {
            background: rgba(25, 135, 84, 0.15);
            color: #0f5132;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #7a5200;
        }

        .status-rejected {
            background: rgba(220, 53, 69, 0.15);
            color: #842029;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 2.8rem;
            color: var(--border-color);
            margin-bottom: 12px;
        }

        @media (max-width: 992px) {
            .dashboard-hero {
                flex-direction: column;
            }

            .hero-metrics {
                width: 100%;
            }
        }
    </style>

    <!-- SB Admin 2 Scripts -->
    <script src="{{ asset('sb-admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('sb-admin/js/sb-admin-2.min.js') }}"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @include('partials.visitor-notification')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let sidebarOpen = false;

            // Hide sidebar initially on mobile
            if (window.innerWidth <= 991.98) {
                const sidebar = document.querySelector('#accordionSidebar');
                if (sidebar) {
                    sidebar.classList.add('mobile-hidden');
                }
            }

            // Mobile sidebar toggle
            document.addEventListener('click', function (e) {
                if (e.target.closest('#sidebarToggle')) {
                    e.preventDefault();

                    if (window.innerWidth <= 991.98) {
                        const sidebar = document.querySelector('#accordionSidebar');
                        const overlay = document.querySelector('.sidebar-overlay');

                        if (sidebarOpen) {
                            // Hide sidebar
                            sidebar.classList.add('mobile-hidden');
                            overlay.classList.remove('show');
                            sidebarOpen = false;
                        } else {
                            // Show sidebar
                            sidebar.classList.remove('mobile-hidden');
                            overlay.classList.add('show');
                            sidebarOpen = true;
                        }
                    }
                }

                // Close on overlay click
                if (e.target.classList.contains('sidebar-overlay')) {
                    const sidebar = document.querySelector('#accordionSidebar');
                    const overlay = document.querySelector('.sidebar-overlay');
                    sidebar.classList.add('mobile-hidden');
                    overlay.classList.remove('show');
                    sidebarOpen = false;
                }

                // Close sidebar when clicking on sidebar links (but not Reports dropdown)
                const sidebarLink = e.target.closest('#accordionSidebar a[href]');
                if (sidebarLink && !sidebarLink.hasAttribute('data-bs-toggle')) {
                    const sidebar = document.querySelector('#accordionSidebar');
                    const overlay = document.querySelector('.sidebar-overlay');
                    sidebar.classList.add('mobile-hidden');
                    overlay.classList.remove('show');
                    sidebarOpen = false;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const processedPairs = new WeakMap();

            const bindBranchToDepartment = (branchSelect, departmentSelect) => {
                if (!branchSelect || !departmentSelect) {
                    return;
                }

                if (processedPairs.get(branchSelect) === departmentSelect) {
                    return;
                }

                processedPairs.set(branchSelect, departmentSelect);

                const includeEmpty = departmentSelect.dataset.includeEmpty !== 'false';
                const placeholder = departmentSelect.dataset.placeholder || (departmentSelect.options[0]?.text ?? 'Select Department');
                const loadingText = departmentSelect.dataset.loadingText || 'Loading departments...';
                const errorText = departmentSelect.dataset.errorText || 'Unable to load departments';
                const emptyText = departmentSelect.dataset.emptyText || 'Select a branch first';

                const setOptions = (optionsHtml) => {
                    departmentSelect.innerHTML = optionsHtml;
                };

                const setDisabledState = (text, disabled) => {
                    const baseOption = includeEmpty ? `<option value="">${text}</option>` : `<option value="">${text}</option>`;
                    setOptions(baseOption);
                    if (disabled) {
                        departmentSelect.value = '';
                    } else {
                        const selected = departmentSelect.dataset.selected || '';
                        departmentSelect.value = selected;
                        if (departmentSelect.value !== selected) {
                            departmentSelect.value = '';
                        }
                    }
                    departmentSelect.disabled = disabled;
                };

                const populateDepartments = (departments) => {
                    let optionsHtml = '';
                    if (includeEmpty) {
                        optionsHtml += `<option value="">${placeholder}</option>`;
                    }

                    const selectedValue = departmentSelect.dataset.selected || '';

                    departments.forEach(dep => {
                        const value = String(dep.id);
                        const selected = value === selectedValue ? 'selected' : '';
                        optionsHtml += `<option value="${value}" ${selected}>${dep.name}</option>`;
                    });

                    setOptions(optionsHtml);
                    departmentSelect.disabled = departments.length === 0;

                    if (departments.length === 0) {
                        departmentSelect.value = '';
                    } else if (selectedValue) {
                        departmentSelect.value = selectedValue;
                        if (departmentSelect.value !== selectedValue) {
                            departmentSelect.value = '';
                            departmentSelect.dataset.selected = '';
                        }
                    }
                };

                const loadDepartments = (branchId) => {
                    if (!branchId) {
                        setDisabledState(emptyText, true);
                        return;
                    }

                    setDisabledState(loadingText, true);

                    fetch(`/api/branches/${branchId}/departments`, {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const departments = Array.isArray(data)
                                ? data
                                : Object.entries(data).map(([id, name]) => ({ id, name }));

                            if (departments.length === 0) {
                                setDisabledState('No departments available', true);
                                return;
                            }

                            populateDepartments(departments);
                        })
                        .catch(() => {
                            setDisabledState(errorText, true);
                        });
                };

                departmentSelect.dataset.selected = departmentSelect.dataset.selected || departmentSelect.value || '';

                branchSelect.addEventListener('change', function () {
                    departmentSelect.dataset.selected = departmentSelect.value || '';
                    loadDepartments(this.value);
                });

                const initialBranch = branchSelect.value || branchSelect.dataset.selected || '';
                if (initialBranch) {
                    loadDepartments(initialBranch);
                } else {
                    setDisabledState(emptyText, true);
                }
            };

            const initialiseBindings = () => {
                document.querySelectorAll('[data-department-target]').forEach(branchSelect => {
                    const selector = branchSelect.dataset.departmentTarget;
                    const departmentSelect = selector ? document.querySelector(selector) : null;
                    if (departmentSelect) {
                        bindBranchToDepartment(branchSelect, departmentSelect);
                    }
                });

                document.querySelectorAll('select[name="branch_id"]').forEach(branchSelect => {
                    const form = branchSelect.closest('form');
                    const departmentSelect = form ? form.querySelector('select[name="department_id"]') : null;
                    if (departmentSelect) {
                        bindBranchToDepartment(branchSelect, departmentSelect);
                    }
                });

                // Conflicting global handler removed. Specific pages (departments/create, users/_form) handle this.
                // document.querySelectorAll('select[name="company_id"]').forEach(...)
            };

            initialiseBindings();

            const observer = new MutationObserver(mutations => {
                let needsInit = false;
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (!(node instanceof Element)) {
                            return;
                        }

                        if (
                            node.matches?.('[data-department-target]') ||
                            node.matches?.('select[name="branch_id"]') ||
                            node.querySelector?.('[data-department-target]') ||
                            node.querySelector?.('select[name="branch_id"]')
                        ) {
                            needsInit = true;
                        }
                    });
                });

                if (needsInit) {
                    initialiseBindings();
                }
            });

            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>

    @php
        $playNotification = session('play_notification', false);
        $visitorName = session('visitor_name', 'Unknown');
        $notificationMessage = session('notification_message', 'New visitor registered');
    @endphp



    <script>
        // Check if page was reloaded and clear query parameters (filters)
        (function () {
            try {
                if (window.performance) {
                    let isReload = false;
                    // Check deprecated performance.navigation property
                    if (performance.navigation && performance.navigation.type === 1) {
                        isReload = true;
                    }
                    // Check modern PerformanceNavigationTiming API
                    else if (performance.getEntriesByType) {
                        const navEntries = performance.getEntriesByType("navigation");
                        if (navEntries.length > 0 && navEntries[0].type === 'reload') {
                            isReload = true;
                        }
                    }

                    // If it's a reload and there are query parameters, clear them
                    if (isReload && window.location.search) {
                        window.location.href = window.location.pathname;
                    }
                }
            } catch (e) {
                console.error("Error checking navigation type for filter clearing:", e);
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>