@extends('layouts.sb')

@section('content')
<div class="container-fluid px-4">
    <div class="page-heading mb-4">
        <div>
            <div class="page-heading__eyebrow">Integrity</div>
            <h1 class="page-heading__title">
                @if(isset($branch) && $branch)
                    {{ $branch->name }} QR Access
                @else
                    {{ $company->name }} QR Access
                @endif
            </h1>
            <div class="page-heading__meta">
                Generate, download, and share the live check-in link for this location with zero friction.
            </div>
        </div>
        <div class="page-heading__actions">
            <a href="{{ route('qr-management.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
            @php
                $routeName = isset($branch) && $branch ? 'companies.branches.qr.download' : 'companies.qr.download';
                $routeParams = isset($branch) && $branch ? ['company' => $company, 'branch' => $branch] : $company;
            @endphp
            <a href="{{ route($routeName, $routeParams) }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-download me-2"></i> Download QR
            </a>
            @php
                $publicRoute = isset($branch) && $branch 
                    ? route('companies.branches.public.qr', ['company' => $company, 'branch' => $branch])
                    : route('companies.public.qr', $company);
            @endphp
            <button class="btn btn-success btn-lg shadow-sm" 
                    onclick="copyToClipboard('{{ $publicRoute }}')"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Copy public link to clipboard">
                <i class="fas fa-link me-2"></i> Copy Public Link
            </button>
            <a href="{{ $publicRoute }}" 
               class="btn btn-outline-primary btn-lg shadow-sm"
               target="_blank"
               data-bs-toggle="tooltip"
               data-bs-placement="top"
               title="Open public QR code page in new tab">
                <i class="fas fa-external-link-alt me-2"></i> View Public
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                {!! $qrCode !!}
            </div>
            
            <h4 class="mb-3">Scan this QR code to check-in visitors</h4>
            
            <div class="input-group mb-4" style="max-width: 500px; margin: 0 auto;">
                <input type="text" class="form-control" value="{{ $url }}" id="qrUrl" readonly>
                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                    <i class="fas fa-copy me-1"></i> Copy Link
                </button>
            </div>
            
            <div class="text-muted small">
                <p class="mb-1">Place this QR code at your {{ isset($branch) && $branch ? 'branch' : 'company' }} entrance for visitors to scan</p>
                <p class="mb-0">Visitors will be directed to: <code>{{ parse_url($url, PHP_URL_PATH) }}</code></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(event) {
    const copyText = document.getElementById("qrUrl");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    
    // Show tooltip or alert
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
    const button = event.target;
    setTimeout(() => {
        button.innerHTML = originalText;
    }, 2000);
}
</script>
@endpush
@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success tooltip
        const tooltip = bootstrap.Tooltip.getInstance(event.target);
        if (tooltip) {
            const originalTitle = event.target.getAttribute('data-bs-original-title');
            event.target.setAttribute('data-bs-original-title', 'Copied to clipboard!');
            tooltip.show();
            
            // Reset tooltip after 2 seconds
            setTimeout(() => {
                event.target.setAttribute('data-bs-original-title', originalTitle);
                tooltip.hide();
            }, 2000);
        }
    }).catch(function(error) {
        console.error('Error copying to clipboard: ', error);
        alert('Failed to copy to clipboard. Please copy the URL manually.');
    });
}
</script>
@endpush
@endsection