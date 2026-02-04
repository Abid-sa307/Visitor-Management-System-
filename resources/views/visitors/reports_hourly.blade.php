@extends('layouts.sb')

@push('styles')
<style>
    .filter-section .form-select {
        min-width: 100%;
    }
    .table th {
        white-space: nowrap;
        font-size: 0.85rem;
    }
    .table td {
        font-size: 0.9rem;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .filter-section .col-md-3 {
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .filter-section .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $exportRoute = 'reports.hourly.export';
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Hourly Visitor Reports </h2>
        <form method="GET" action="{{ route('reports.hourly.export') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export
            </button>
        </form>
    </div>

    {{-- =================== FILTERS CARD =================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" id="hourlyFilterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Date Range</label>
                        @include('components.basic_date_range', ['from' => $from ?? now()->format('Y-m-d'), 'to' => $to ?? now()->format('Y-m-d')])
                    </div>
                    
                    {{-- 2️⃣ Company (superadmin only) --}}
                    @if(auth()->user()->role === 'superadmin')
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Company</label>
                        <select name="company_id" id="company_id" class="form-select form-select-sm">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 3️⃣ Branch --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Branch</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="branchBtn" data-dropdown="branch" onclick="document.getElementById('branchDropdownMenu').style.display = document.getElementById('branchDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(!request('company_id') && auth()->user()->role === 'superadmin') disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                <span id="branchText">All Branches</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="branchDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllBranches" onchange="toggleAllBranches()">
                                    <label class="form-check-label fw-bold" for="selectAllBranches">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="branchOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('branchDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- 4️⃣ Department --}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Department</label>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary w-100 text-start" type="button" id="departmentBtn" data-dropdown="department" onclick="document.getElementById('departmentDropdownMenu').style.display = document.getElementById('departmentDropdownMenu').style.display === 'block' ? 'none' : 'block'" @if(!request('company_id') && auth()->user()->role === 'superadmin') disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                <span id="departmentText">All Departments</span>
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                            <div class="border rounded bg-white position-absolute w-100 p-2" id="departmentDropdownMenu" style="max-height: 200px; overflow-y: auto; display: none; z-index: 1000; top: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllDepartments" onchange="toggleAllDepartments()">
                                    <label class="form-check-label fw-bold" for="selectAllDepartments">Select All</label>
                                </div>
                                <hr class="my-1">
                                <div id="departmentOptions" style="max-height: 120px; overflow-y: auto;"></div>
                                <hr class="my-1">
                                <button type="button" class="btn btn-sm btn-primary w-100" onclick="document.getElementById('departmentDropdownMenu').style.display='none'">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Buttons row --}}
                    <div class="col-12 d-flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('reports.hourly') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

  @if(!empty($series))
    @php
      // Create time slots from 00:00 to 23:00
      $timeSlots = [];
      for ($i = 0; $i < 24; $i++) {
        $start = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        $end = str_pad(($i + 1) % 24, 2, '0', STR_PAD_LEFT) . ':00';
        $timeSlots[] = "$start-$end";
      }
      
      // Get unique branches that have data
      $branches = collect($series)
        ->map(fn($row) => $row['branch_name'] ?? 'Unknown Branch')
        ->unique()
        ->sort()
        ->values();
      
      // Get unique dates for each branch
      $branchDates = [];
      foreach ($branches as $branch) {
        $branchDates[$branch] = collect($series)
          ->filter(fn($row) => ($row['branch_name'] ?? 'Unknown Branch') === $branch)
          ->map(fn($row) => \Carbon\Carbon::parse($row['hour'])->format('Y-m-d'))
          ->unique()
          ->sort()
          ->values();
      }
    @endphp
    
    <div class="table-responsive shadow-sm border rounded">
      <table class="table table-bordered align-middle text-center mb-0">
        <thead class="table-primary">
          <tr>
            <th class="text-center">Branch</th>
            <th class="text-center">Date</th>
            @foreach($timeSlots as $slot)
              <th class="text-center small" style="min-width: 80px;">{{ $slot }}</th>
            @endforeach
            <th class="text-center fw-bold">Total</th>
          </tr>
        </thead>

        <tbody>
          @foreach($branches as $branch)
            @foreach($branchDates[$branch] as $date)
              @php
                $dateTotal = 0;
                $dateFormatted = \Carbon\Carbon::parse($date)->format('d M Y');
              @endphp
              <tr>
                @if($loop->first)
                  <th class="text-center" rowspan="{{ $branchDates[$branch]->count() }}">{{ $branch }}</th>
                @endif
                <th class="text-center">{{ $dateFormatted }}</th>
                @foreach($timeSlots as $index => $slot)
                  @php
                    $startHour = str_pad($index, 2, '0', STR_PAD_LEFT);
                    $endHour = str_pad(($index + 1) % 24, 2, '0', STR_PAD_LEFT);
                    
                    $count = collect($series)
                      ->filter(function($row) use ($branch, $date, $startHour) {
                        $rowDate = date('Y-m-d', strtotime($row['hour']));
                        $rowHour = date('H', strtotime($row['hour']));
                        $rowBranch = $row['branch_name'] ?? 'Unknown Branch';
                        return $rowBranch === $branch && $rowDate === $date && $rowHour === $startHour;
                      })
                      ->sum('count');
                    
                    $dateTotal += $count;
                  @endphp
                  <td class="{{ $count > 0 ? 'bg-light' : '' }}">
                    {{ $count > 0 ? $count : '-' }}
                  </td>
                @endforeach
                <td class="fw-bold bg-light">{{ $dateTotal > 0 ? $dateTotal : '-' }}</td>
              </tr>
            @endforeach
          @endforeach
          
          <!-- Hourly Total Row -->
          <tr class="table-secondary">
            <th class="text-center">Total</th>
            <th class="text-center">All Dates</th>
            @php
              $hourlyTotals = array_fill(0, 24, 0);
              $grandTotal = 0;
              
              foreach ($series as $row) {
                $hour = (int)date('H', strtotime($row['hour']));
                $hourlyTotals[$hour] += $row['count'];
                $grandTotal += $row['count'];
              }
            @endphp
            
            @foreach($hourlyTotals as $total)
              <td class="fw-bold">{{ $total > 0 ? $total : '-' }}</td>
            @endforeach
            <td class="fw-bold bg-light">{{ $grandTotal > 0 ? $grandTotal : '-' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  @else
    <div class="alert alert-light border text-center text-muted">
      No data for selected filters.
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
    // Multi-select functions for branches and departments
    function toggleAllBranches() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBranchText();
        updateSelectAllBranchesState();
        
        // Unlock department dropdown when branches are selected
        const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
        const departmentBtn = document.getElementById('departmentBtn');
        if (departmentBtn) {
            if (anyChecked) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
                loadDepartmentsByBranches();
            } else {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
            }
        }
    }

    function toggleAllDepartments() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateDepartmentText();
        updateSelectAllDepartmentsState();
    }

    function updateSelectAllBranchesState() {
        const selectAll = document.getElementById('selectAllBranches');
        const checkboxes = document.querySelectorAll('.branch-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.branch-checkbox:checked').length;
        }
    }

    function updateSelectAllDepartmentsState() {
        const selectAll = document.getElementById('selectAllDepartments');
        const checkboxes = document.querySelectorAll('.department-checkbox');
        if (checkboxes.length === 0) {
            selectAll.checked = false;
            selectAll.disabled = true;
        } else {
            selectAll.disabled = false;
            selectAll.checked = checkboxes.length === document.querySelectorAll('.department-checkbox:checked').length;
        }
    }

    function updateBranchText() {
        const checkboxes = document.querySelectorAll('.branch-checkbox:checked');
        const text = document.getElementById('branchText');
        if (checkboxes.length === 0) {
            text.textContent = 'All Branches';
        } else if (checkboxes.length === 1) {
            text.textContent = checkboxes[0].nextElementSibling.textContent;
        } else {
            text.textContent = `${checkboxes.length} branches selected`;
        }
        updateSelectAllBranchesState();
        
        // Unlock department dropdown when branches are selected
        const anyChecked = checkboxes.length > 0;
        const departmentBtn = document.getElementById('departmentBtn');
        if (departmentBtn) {
            if (anyChecked) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
                loadDepartmentsByBranches();
            } else {
                departmentBtn.disabled = true;
                departmentBtn.style.opacity = '0.5';
                departmentBtn.style.cursor = 'not-allowed';
            }
        }
    }

    function updateDepartmentText() {
        const checkboxes = document.querySelectorAll('.department-checkbox:checked');
        const text = document.getElementById('departmentText');
        if (checkboxes.length === 0) {
            text.textContent = 'All Departments';
        } else if (checkboxes.length === 1) {
            text.textContent = checkboxes[0].nextElementSibling.textContent;
        } else {
            text.textContent = `${checkboxes.length} departments selected`;
        }
        updateSelectAllDepartmentsState();
    }

document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const branchBtn = document.getElementById('branchBtn');
    const departmentBtn = document.getElementById('departmentBtn');
    const branchOptions = document.getElementById('branchOptions');
    const departmentOptions = document.getElementById('departmentOptions');
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#branchBtn') && !e.target.closest('#branchDropdownMenu')) {
            document.getElementById('branchDropdownMenu').style.display = 'none';
        }
        if (!e.target.closest('#departmentBtn') && !e.target.closest('#departmentDropdownMenu')) {
            document.getElementById('departmentDropdownMenu').style.display = 'none';
        }
    });
    
    // Initialize branches
    function initBranches() {
        branchOptions.innerHTML = '';
        const selectedBranches = @json(request('branch_id', []));
        
        // Add branches from server data if available
        @if(isset($branches) && count($branches) > 0)
            @foreach($branches as $id => $name)
                const div = document.createElement('div');
                div.className = 'form-check';
                const isChecked = selectedBranches.includes('{{ $id }}');
                div.innerHTML = `
                    <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="{{ $id }}" id="branch_{{ $id }}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="branch_{{ $id }}">{{ $name }}</label>
                `;
                branchOptions.appendChild(div);
            @endforeach
        @endif
        
        updateBranchText();
        updateSelectAllBranchesState();
        
        // Enable branch button if branches are available
        @if(isset($branches) && count($branches) > 0)
            if (branchBtn) {
                branchBtn.disabled = false;
                branchBtn.style.opacity = '1';
                branchBtn.style.cursor = 'pointer';
            }
        @endif
    }
    
    // Initialize departments
    function initDepartments() {
        departmentOptions.innerHTML = '';
        const selectedDepartments = @json(request('department_id', []));
        
        // Add departments from server data if available
        @if(isset($departments) && count($departments) > 0)
            @foreach($departments as $id => $name)
                const div = document.createElement('div');
                div.className = 'form-check';
                const isChecked = selectedDepartments.includes('{{ $id }}');
                div.innerHTML = `
                    <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="{{ $id }}" id="department_{{ $id }}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="department_{{ $id }}">{{ $name }}</label>
                `;
                departmentOptions.appendChild(div);
            @endforeach
        @endif
        
        updateDepartmentText();
        updateSelectAllDepartmentsState();
        
        // Enable department button if departments are available
        @if(isset($departments) && count($departments) > 0)
            if (departmentBtn) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
            }
        @endif
    }
    
    // Load branches by company
    function loadBranchesByCompany(companyId) {
        if (!companyId) return;
        
        fetch(`/api/companies/${companyId}/branches`)
            .then(response => response.json())
            .then(branches => {
                branchOptions.innerHTML = '';
                const selectedBranches = @json(request('branch_id', []));
                
                branches.forEach(branch => {
                    const div = document.createElement('div');
                    div.className = 'form-check';
                    const isChecked = selectedBranches.includes(branch.id.toString());
                    div.innerHTML = `
                        <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" onchange="updateBranchText()" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="branch_${branch.id}">${branch.name}</label>
                    `;
                    branchOptions.appendChild(div);
                });
                
                updateBranchText();
                updateSelectAllBranchesState();
                
                if (branchBtn) {
                    branchBtn.disabled = false;
                    branchBtn.style.opacity = '1';
                    branchBtn.style.cursor = 'pointer';
                }
            })
            .catch(error => console.error('Error loading branches:', error));
    }
    
    // Load departments by selected branches
    function loadDepartmentsByBranches() {
        const selectedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);
        
        departmentOptions.innerHTML = '';
        departmentBtn.disabled = true;
        departmentBtn.style.opacity = '0.5';
        departmentBtn.style.cursor = 'not-allowed';
        document.getElementById('departmentText').textContent = 'All Departments';
        
        if (selectedBranches.length === 0) return;

        Promise.all(selectedBranches.map(branchId => 
            fetch(`/api/branches/${branchId}/departments`).then(r => r.json())
        )).then(results => {
            const deptMap = [];
            results.forEach(depts => {
                depts.forEach(dept => {
                    if (!deptMap.find(d => d.id == dept.id)) {
                        deptMap.push(dept);
                    }
                });
            });
            
            const selectedDepartments = @json(request('department_id', []));
            deptMap.forEach(dept => {
                const div = document.createElement('div');
                div.className = 'form-check';
                const isChecked = selectedDepartments.includes(dept.id.toString());
                div.innerHTML = `
                    <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" onchange="updateDepartmentText()" ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
                `;
                departmentOptions.appendChild(div);
            });
            
            updateDepartmentText();
            updateSelectAllDepartmentsState();
            
            if (departmentBtn) {
                departmentBtn.disabled = false;
                departmentBtn.style.opacity = '1';
                departmentBtn.style.cursor = 'pointer';
            }
        }).catch(error => console.error('Error loading departments:', error));
    }
    
    // Initialize on load
    initBranches();
    initDepartments();
    
    // Handle company change
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Reset dropdowns
            branchOptions.innerHTML = '';
            departmentOptions.innerHTML = '';
            branchBtn.disabled = true;
            branchBtn.style.opacity = '0.5';
            branchBtn.style.cursor = 'not-allowed';
            departmentBtn.disabled = true;
            departmentBtn.style.opacity = '0.5';
            departmentBtn.style.cursor = 'not-allowed';
            document.getElementById('branchText').textContent = 'All Branches';
            document.getElementById('departmentText').textContent = 'All Departments';
            
            if (companyId) {
                loadBranchesByCompany(companyId);
            }
        });
    }
    
    // Make functions global
    window.toggleAllBranches = toggleAllBranches;
    window.toggleAllDepartments = toggleAllDepartments;
    window.updateBranchText = updateBranchText;
    window.updateDepartmentText = updateDepartmentText;
    window.updateSelectAllBranchesState = updateSelectAllBranchesState;
    window.updateSelectAllDepartmentsState = updateSelectAllDepartmentsState;
    window.loadDepartmentsByBranches = loadDepartmentsByBranches;
});
</script>
@endpush
