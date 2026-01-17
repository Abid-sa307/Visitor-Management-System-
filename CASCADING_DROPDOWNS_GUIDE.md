# Cascading Dropdowns Implementation Guide

## Summary
Implemented cascading dropdown behavior where:
- Branch dropdown is LOCKED by default, unlocks when Company is selected
- Department dropdown is LOCKED by default, unlocks when Branch is selected
- Dropdowns use same UI style as Company dropdown

## Files Updated
✅ 1. dashboard.blade.php - COMPLETED
✅ 2. departments.index.blade.php - COMPLETED
✅ 3. public/js/cascading-dropdowns.js - CREATED (reusable component)

## Remaining Files to Update

### Pattern for Single Select Dropdowns (like departments.index)

**HTML Changes:**
```php
<!-- Branch Dropdown - Add disabled and style attributes -->
<select name="branch_id" id="filterBranch" class="form-select" disabled style="opacity: 0.5; cursor: not-allowed;">
    <option value="">All Branches</option>
</select>

<!-- Department Dropdown - Add disabled and style attributes -->
<select name="department_id" id="filterDepartment" class="form-select" disabled style="opacity: 0.5; cursor: not-allowed;">
    <option value="">All Departments</option>
</select>
```

**JavaScript Changes:**
```javascript
// Add unlock logic for pre-selected company
if (companyFilter.value && branchFilter) {
    branchFilter.disabled = false;
    branchFilter.style.opacity = '1';
    branchFilter.style.cursor = 'pointer';
}

// In company change handler, add unlock/lock logic
if (companyId) {
    // ... load branches ...
    branchFilter.disabled = false;
    branchFilter.style.opacity = '1';
    branchFilter.style.cursor = 'pointer';
} else {
    branchFilter.disabled = true;
    branchFilter.style.opacity = '0.5';
    branchFilter.style.cursor = 'not-allowed';
}

// Add branch change handler for department
if (branchFilter) {
    if (branchFilter.value && departmentFilter) {
        departmentFilter.disabled = false;
        departmentFilter.style.opacity = '1';
        departmentFilter.style.cursor = 'pointer';
    }
    
    branchFilter.addEventListener('change', function() {
        if (this.value && companyFilter.value) {
            departmentFilter.disabled = false;
            departmentFilter.style.opacity = '1';
            departmentFilter.style.cursor = 'pointer';
            // Load departments via AJAX
        } else {
            departmentFilter.disabled = true;
            departmentFilter.style.opacity = '0.5';
            departmentFilter.style.cursor = 'not-allowed';
        }
    });
}
```

### Pattern for Multi-Select Dropdowns (like dashboard.blade)

**HTML Changes:**
```php
<!-- Branch Button - Add data-dropdown, disabled, and style -->
<button class="btn btn-outline-secondary w-100 text-start" 
        type="button" 
        data-dropdown="branch"
        onclick="..." 
        disabled 
        style="opacity: 0.5; cursor: not-allowed;">
    <span id="branchText">All Branches</span>
    <i class="fas fa-chevron-down float-end mt-1"></i>
</button>

<!-- Department Button - Add data-dropdown, disabled, and style -->
<button class="btn btn-outline-secondary w-100 text-start" 
        type="button" 
        data-dropdown="department"
        onclick="..." 
        disabled 
        style="opacity: 0.5; cursor: not-allowed;">
    <span id="departmentText">All Departments</span>
    <i class="fas fa-chevron-down float-end mt-1"></i>
</button>
```

**JavaScript - Include Script:**
```html
<script src="{{ asset('js/cascading-dropdowns.js') }}"></script>
```

## Files Requiring Updates

### 3. visit.blade (visitors/visit.blade.php)
- Type: Multi-select dropdowns
- Apply: Multi-select pattern

### 4. security-checks.index
- Type: Single/Multi-select (check file)
- Apply: Appropriate pattern

### 5. security-questions.index
- Type: Single/Multi-select (check file)
- Apply: Appropriate pattern

### 6. approval.blade (approvals/index.blade.php)
- Type: Multi-select dropdowns
- Apply: Multi-select pattern

### 7. entry.blade (visitors/entry.blade.php)
- Type: Multi-select dropdowns
- Apply: Multi-select pattern

### 8. visitor-history (visitors/history.blade.php)
- Type: Multi-select dropdowns
- Apply: Multi-select pattern

### 9. employees.index
- Type: Single/Multi-select (check file)
- Apply: Appropriate pattern

### 10-14. Reports Pages
- Visitor Report (reports/visitors.blade.php)
- In/Out Report (reports/in-out.blade.php)
- Security Checkpoints (reports/security.blade.php)
- Approval Status (reports/approvals.blade.php)
- Hourly Report (reports/hourly.blade.php)
- Type: Single/Multi-select (check each file)
- Apply: Appropriate pattern

## Key Points
1. Branch locked by default, unlocks when company selected
2. Department locked by default, unlocks when branch selected
3. Use opacity: 0.5 and cursor: not-allowed for locked state
4. Use opacity: 1 and cursor: pointer for unlocked state
5. For multi-select, include cascading-dropdowns.js
6. For single-select, add manual unlock/lock logic in existing JS
