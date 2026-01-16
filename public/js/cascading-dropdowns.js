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
            // If no company select (non-superadmin), unlock branch and department by default
            this.unlockDropdown('branch');
            this.unlockDropdown('department');
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
            const branches = Array.isArray(data) ? data : (data.data || Object.entries(data || {}).map(([id, name]) => ({ id, name })));
            
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
        
        fetch(`/api/companies/${companyId}/departments`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const departments = Array.isArray(data) ? data : Object.entries(data || {}).map(([id, name]) => ({ id, name }));
            
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
