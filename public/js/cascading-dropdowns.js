// Cascading Dropdowns Handler
class CascadingDropdowns {
    constructor() {
        this.companySelect = document.getElementById('company_id') || document.getElementById('filterCompany');
        this.branchButton = document.querySelector('[data-dropdown="branch"]');
        this.departmentButton = document.querySelector('[data-dropdown="department"]');
        this.branchMenu = document.getElementById('branchDropdownMenu');
        this.departmentMenu = document.getElementById('departmentDropdownMenu');

        this.init();
    }

    init() {
        if (!this.companySelect) {
            // If no company select (non-superadmin), populate with server-side data
            this.initializeForCompanyUser();
            return;
        }

        // Lock branch and department by default
        this.lockDropdown('branch');
        this.lockDropdown('department');

        // If company is pre-selected, unlock branch
        if (this.companySelect.value) {
            this.unlockDropdown('branch');
            this.loadBranches(this.companySelect.value);
        }

        // Company change handler
        this.companySelect.addEventListener('change', (e) => {
            const companyId = e.target.value;

            if (companyId) {
                this.unlockDropdown('branch');
                this.loadBranches(companyId);
                this.lockDropdown('department');
                this.clearDropdown('department');
            } else {
                this.lockDropdown('branch');
                this.lockDropdown('department');
                this.clearDropdown('branch');
                this.clearDropdown('department');
            }
        });

        // Branch selection handler
        if (this.branchMenu) {
            let departmentsLoaded = false;
            this.branchMenu.addEventListener('change', (e) => {
                if (e.target.classList.contains('branch-checkbox')) {
                    const anyChecked = document.querySelectorAll('.branch-checkbox:checked').length > 0;
                    if (anyChecked && this.companySelect.value) {
                        this.unlockDropdown('department');
                        if (!departmentsLoaded) {
                            this.loadDepartments(this.companySelect.value);
                            departmentsLoaded = true;
                        }
                    } else {
                        this.lockDropdown('department');
                        this.clearDropdown('department');
                        departmentsLoaded = false;
                    }
                }
            });
        }
    }

    initializeForCompanyUser() {
        // Get server-side data from window object (will be set by blade template)
        const branches = window.serverBranches || {};
        const departments = window.serverDepartments || {};

        // Populate branches
        const branchOptions = document.getElementById('branchOptions');
        if (branchOptions && Object.keys(branches).length > 0) {
            this.unlockDropdown('branch');
            branchOptions.innerHTML = '';

            const urlParams = new URLSearchParams(window.location.search);
            const selectedBranches = urlParams.getAll('branch_id[]');

            Object.entries(branches).forEach(([id, name]) => {
                const isChecked = selectedBranches.includes(String(id));
                const div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML = `
                    <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${id}" id="branch_${id}" ${isChecked ? 'checked' : ''} onchange="window.updateBranchText ? window.updateBranchText() : null">
                    <label class="form-check-label" for="branch_${id}">${name}</label>
                `;
                branchOptions.appendChild(div);
            });

            if (window.updateBranchText) window.updateBranchText();

            // Add event listener for branch selection to unlock departments and filter them
            this.branchMenu.addEventListener('change', (e) => {
                if (e.target.classList.contains('branch-checkbox')) {
                    const checkedBranches = Array.from(document.querySelectorAll('.branch-checkbox:checked')).map(cb => cb.value);

                    if (checkedBranches.length > 0) {
                        this.unlockDropdown('department');
                        this.filterDepartmentsByBranches(checkedBranches);
                    } else {
                        this.lockDropdown('department');
                        this.clearDepartmentSelection();
                    }
                }
            });

            // If branches are pre-selected from URL, unlock departments and filter them
            if (selectedBranches.length > 0) {
                this.unlockDropdown('department');
                this.filterDepartmentsByBranches(selectedBranches);
            } else {
                this.lockDropdown('department');
            }
        } else {
            this.lockDropdown('branch');
            this.lockDropdown('department');
        }


        // Store all departments for filtering
        const departmentOptions = document.getElementById('departmentOptions');
        if (departmentOptions && Object.keys(departments).length > 0) {
            this.allDepartments = departments;
        }
    }

    filterDepartmentsByBranches(branchIds) {
        const departmentOptions = document.getElementById('departmentOptions');
        if (!departmentOptions || !this.allDepartments) {
            return;
        }

        const urlParams = new URLSearchParams(window.location.search);
        const selectedDepartments = urlParams.getAll('department_id[]');

        departmentOptions.innerHTML = '';
        let hasMatchingDepartments = false;

        Object.entries(this.allDepartments).forEach(([id, dept]) => {
            // Only show departments that belong to one of the selected branches
            if (branchIds.includes(String(dept.branch_id))) {
                hasMatchingDepartments = true;
                const isChecked = selectedDepartments.includes(String(id));
                const div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML = `
                    <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${id}" id="department_${id}" ${isChecked ? 'checked' : ''} onchange="window.updateDepartmentText ? window.updateDepartmentText() : null">
                    <label class="form-check-label" for="department_${id}">${dept.name}</label>
                `;
                departmentOptions.appendChild(div);
            }
        });

        if (!hasMatchingDepartments) {
            departmentOptions.innerHTML = '<div class="text-muted">No departments for selected branch(es)</div>';
        }

        if (window.updateDepartmentText) window.updateDepartmentText();
    }

    clearDepartmentSelection() {
        const departmentOptions = document.getElementById('departmentOptions');
        if (departmentOptions) {
            departmentOptions.innerHTML = '';
        }
        const departmentText = document.getElementById('departmentText');
        if (departmentText) {
            departmentText.textContent = 'All Departments';
        }
    }

    lockDropdown(type) {
        const button = type === 'branch' ? this.branchButton : this.departmentButton;
        if (button) {
            button.disabled = true;
            button.style.opacity = '0.5';
            button.style.cursor = 'not-allowed';
        }
    }

    unlockDropdown(type) {
        const button = type === 'branch' ? this.branchButton : this.departmentButton;
        if (button) {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
        }
    }

    clearDropdown(type) {
        const options = document.getElementById(type === 'branch' ? 'branchOptions' : 'departmentOptions');
        const text = document.getElementById(type === 'branch' ? 'branchText' : 'departmentText');

        if (options) options.innerHTML = '';
        if (text) text.textContent = type === 'branch' ? 'All Branches' : 'All Departments';
    }

    loadBranches(companyId) {
        const branchOptions = document.getElementById('branchOptions');
        if (!branchOptions) return;

        const urlParams = new URLSearchParams(window.location.search);
        const selectedBranches = urlParams.getAll('branch_id[]');

        branchOptions.innerHTML = '<div class="text-muted">Loading...</div>';

        fetch(`/api/companies/${companyId}/branches`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.json())
            .then(data => {
                let branches = [];
                if (Array.isArray(data)) {
                    branches = data;
                } else if (data.data && Array.isArray(data.data)) {
                    branches = data.data;
                } else {
                    branches = Object.entries(data || {}).map(([key, val]) => {
                        if (typeof val === 'object' && val !== null) return { id: key, ...val };
                        return { id: key, name: val };
                    });
                }

                branchOptions.innerHTML = '';
                if (branches.length > 0) {
                    branches.forEach(branch => {
                        const isChecked = selectedBranches.includes(String(branch.id));
                        const div = document.createElement('div');
                        div.className = 'form-check';
                        div.innerHTML = `
                        <input class="form-check-input branch-checkbox" type="checkbox" name="branch_id[]" value="${branch.id}" id="branch_${branch.id}" ${isChecked ? 'checked' : ''} onchange="window.updateBranchText ? window.updateBranchText() : null">
                        <label class="form-check-label" for="branch_${branch.id}">${branch.name || branch.branch_name}</label>
                    `;
                        branchOptions.appendChild(div);
                    });
                    if (window.updateBranchText) window.updateBranchText();
                    if (selectedBranches.length > 0) {
                        this.unlockDropdown('department');
                        this.loadDepartments(companyId);
                    }
                } else {
                    branchOptions.innerHTML = '<div class="text-muted">No branches available</div>';
                }
            })
            .catch(() => {
                branchOptions.innerHTML = '<div class="text-muted">Error loading branches</div>';
            });
    }

    loadDepartments(companyId) {
        const departmentOptions = document.getElementById('departmentOptions');
        if (!departmentOptions) return;

        const urlParams = new URLSearchParams(window.location.search);
        const selectedDepartments = urlParams.getAll('department_id[]');

        departmentOptions.innerHTML = '<div class="text-muted">Loading...</div>';

        // Build URL with branch IDs
        let url = `/api/companies/${companyId}/departments`;
        const branchCheckboxes = document.querySelectorAll('.branch-checkbox:checked');
        if (branchCheckboxes.length > 0) {
            const params = new URLSearchParams();
            branchCheckboxes.forEach(cb => params.append('branch_id[]', cb.value));
            url += `?${params.toString()}`;
        }

        fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.json())
            .then(data => {
                let departments = [];
                if (Array.isArray(data)) {
                    departments = data;
                } else if (data.data && Array.isArray(data.data)) {
                    departments = data.data;
                } else {
                    departments = Object.entries(data || {}).map(([key, val]) => {
                        if (typeof val === 'object' && val !== null) return { id: key, ...val };
                        return { id: key, name: val };
                    });
                }

                departmentOptions.innerHTML = '';
                if (departments.length > 0) {
                    departments.forEach(dept => {
                        const isChecked = selectedDepartments.includes(String(dept.id));
                        const div = document.createElement('div');
                        div.className = 'form-check';
                        div.innerHTML = `
                        <input class="form-check-input department-checkbox" type="checkbox" name="department_id[]" value="${dept.id}" id="department_${dept.id}" ${isChecked ? 'checked' : ''} onchange="window.updateDepartmentText ? window.updateDepartmentText() : null">
                        <label class="form-check-label" for="department_${dept.id}">${dept.name}</label>
                    `;
                        departmentOptions.appendChild(div);
                    });
                    if (window.updateDepartmentText) window.updateDepartmentText();
                } else {
                    departmentOptions.innerHTML = '<div class="text-muted">No departments available</div>';
                }
            })
            .catch(() => {
                departmentOptions.innerHTML = '<div class="text-muted">Error loading departments</div>';
            });
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new CascadingDropdowns();
});
