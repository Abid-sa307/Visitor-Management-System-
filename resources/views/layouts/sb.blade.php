<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - VMS</title>

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('sb-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb-admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')

    <style>
    /* Make sidebar sticky and scrollable */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1030;
        width: 250px; /* keep in sync with content margin-left */
    }

    /* Shift content wrapper to the right */
    #content-wrapper {
        margin-left: 250px; /* width of the sidebar */
        min-height: 100vh;
    }

    /* Prevent content from overlapping under topbar */
    #content {
        padding-top: 1rem;
    }

    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            height: auto;
            margin-bottom: 1rem;
            width: 100%;
        }

        #content-wrapper {
            margin-left: 0;
        }
    }
    </style>
</head>

<body id="page-top">
@php
    /**
     * Normalize & gate page access for the current user
     * (No json_decode on arrays; supports legacy JSON strings too)
     */
    $authUser = auth()->user();

    // Super admins see everything
    $isSuper = $authUser && in_array($authUser->role, ['super_admin','superadmin'], true);

    // Normalize master_pages to an array (works for casted arrays and legacy JSON strings)
    $normalizeToArray = function ($value) {
        if (is_array($value)) return $value;
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
    $can = fn (string $key) => $isSuper || in_array($key, $masterPages, true);
@endphp

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
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{-- Flash error from access middleware (or other redirects) --}}
                    @if(session('error'))
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          {{ session('error') }}
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <span>&copy; {{ date('Y') }} Visitor Management System</span>
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

    <!-- SB Admin 2 Scripts -->
    <script src="{{ asset('sb-admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sb-admin/js/sb-admin-2.min.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const invalidInputs = document.querySelectorAll('form .is-invalid');
        if (invalidInputs.length > 0) {
            const first = invalidInputs[0];
            if (first.scrollIntoView) {
                first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            const parent = first.closest('.form-group, .mb-3');
            if (parent) {
                parent.classList.add('was-validated');
            }
        }
    });
    </script>

    @stack('scripts')
</body>
</html>
