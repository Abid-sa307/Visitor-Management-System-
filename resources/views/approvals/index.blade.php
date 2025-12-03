@extends('layouts.sb')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Visitor Approvals</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Pending Approvals</h6>
        </div>
        <div class="card-body">
            @if($visitors->isEmpty())
                <div class="alert alert-info">No pending approvals found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Visit Purpose</th>
                                <th>Visit Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitors as $visitor)
                                <tr>
                                    <td>{{ $visitor->name }}</td>
                                    <td>{{ $visitor->email }}</td>
                                    <td>{{ $visitor->phone }}</td>
                                    <td>{{ $visitor->department->name ?? 'N/A' }}</td>
                                    <td>{{ $visitor->purpose }}</td>
                                    <td>{{ $visitor->visit_date ? $visitor->visit_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="d-flex">
                                        <form action="{{ route('company.approvals.approve', $visitor) }}" method="POST" class="mr-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $visitor->id }}" title="Reject">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                        
                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $visitor->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectModalLabel">Reject Visitor Request</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('company.approvals.reject', $visitor) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="rejection_reason">Reason for Rejection</label>
                                                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $visitors->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick date range buttons
    document.querySelectorAll('.quick-range').forEach(button => {
        button.addEventListener('click', function() {
            const range = this.dataset.range;
            const today = new Date();
            let fromDate, toDate;

            switch(range) {
                case 'today':
                    fromDate = toDate = today.toISOString().split('T')[0];
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    fromDate = toDate = yesterday.toISOString().split('T')[0];
                    break;
                case 'this-month':
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    toDate = today.toISOString().split('T')[0];
                    break;
                case 'last-month':
                    const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    fromDate = firstDayLastMonth.toISOString().split('T')[0];
                    toDate = lastDayLastMonth.toISOString().split('T')[0];
                    break;
            }

            document.getElementById('from_date').value = fromDate;
            document.getElementById('to_date').value = toDate;
            document.getElementById('approvalFilterForm').submit();
        });
    });

    // Handle company change to update branches and departments
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');

    if (companySelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Enable/disable branch and department selects based on company selection
            if (companyId) {
                // Fetch branches for the selected company
                fetch(`/api/companies/${companyId}/branches`)
                    .then(response => response.json())
                    .then(data => {
                        updateSelectOptions(branchSelect, data);
                        branchSelect.disabled = false;
                    });

                // Fetch departments for the selected company
                fetch(`/api/companies/${companyId}/departments`)
                    .then(response => response.json())
                    .then(data => {
                        updateSelectOptions(departmentSelect, data);
                        departmentSelect.disabled = false;
                    });
            } else {
                // Reset and disable selects if no company is selected
                updateSelectOptions(branchSelect, {});
                updateSelectOptions(departmentSelect, {});
                branchSelect.disabled = true;
                departmentSelect.disabled = true;
            }
        });
    }

    // Helper function to update select options
    function updateSelectOptions(selectElement, options) {
        // Save current value
        const currentValue = selectElement.value;
        
        // Clear existing options except the first one
        while (selectElement.options.length > 1) {
            selectElement.remove(1);
        }

        // Add new options
        for (const [value, text] of Object.entries(options)) {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = text;
            selectElement.appendChild(option);
        }

        // Restore previous value if it still exists
        if (Array.from(selectElement.options).some(opt => opt.value === currentValue)) {
            selectElement.value = currentValue;
        }
    }

    // Initialize all approval forms
    document.querySelectorAll('form.js-approval-form').forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
});

// Handle form submission for approval/rejection
function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const url = form.action;
    const method = form.method;
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    
    // Disable button and show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('success', data.message || 'Operation completed successfully');
            
            // Reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', error.message || 'An error occurred while processing your request');
    })
    .finally(() => {
        // Re-enable button and restore text
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// Helper function to show alerts
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remove alert after 5 seconds
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        alert.close();
    }, 5000);
}
</script>
@endpush